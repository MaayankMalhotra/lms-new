<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShopAssistantController extends Controller
{
    public function __construct(private CartService $cart, private GeminiService $gemini)
    {
    }

    public function index(Request $request): View
    {
        $shopperContext = $this->buildShopperContext($request);

        $fallback = "Hey there! I'm your personal shopper. Tell me what you're looking for—perfumes, sneakers, ritual kits, gifts—and I'll drop the best options right here.";

        $greeting = $this->gemini->reply(
            "A shopper just opened the conversational commerce experience. Welcome them warmly, reference any context provided, and remind them that you can help with perfumes, sneakers, ritual kits, or gifts.",
            $fallback,
            [],
            array_merge(
                $this->geminiProfileContext($shopperContext),
                [
                    'intent' => 'greeting',
                    'profile_summary' => $this->summarizeShopperContext($shopperContext),
                ]
            )
        );

        return view('shop.chat', [
            'initialGreeting' => $greeting ?: $fallback,
        ]);
    }

    public function chat(Request $request)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $message = Str::lower($data['message']);

        if (Str::contains($message, ['refund', 'return'])) {
            $reply = "No problem. Tap **Start refund** and submit your order number + email. We'll take care of it: " . route('refunds.create');

            return response()->json([
                'message' => $this->gemini->reply($data['message'], $reply),
                'products' => [],
            ]);
        }

        if (Str::contains($message, ['cart', 'bag'])) {
            $summary = $this->cart->summary();

            return response()->json([
                'message' => $summary['count'] > 0
                    ? "You currently have {$summary['count']} item(s) worth ₹" . number_format($summary['total'], 0) . ". Tap **View cart** when you're ready: " . route('cart.index')
                    : "Your cart is empty right now. Ask me for perfumes, sneakers, or kits and I'll add them for you.",
                'products' => [],
            ]);
        }

        if (Str::contains($message, ['checkout', 'pay', 'order'])) {
            return response()->json([
                'message' => "Great! Head to checkout here: " . route('checkout.show') . ". I can add more products if you need them—just tell me what you're shopping for.",
                'products' => [],
            ]);
        }

        if (Str::contains($message, ['help', 'can you do', 'options'])) {
            return response()->json([
                'message' => "I'm your shopping co-pilot. Ask me for **perfumes**, **sneakers**, or **ritual kits** and I'll pull the best drops. You can also say things like \"show gifts\", \"add sneakers\" or \"what's new\".",
                'products' => [],
            ]);
        }

        $catalog = Product::with('categories')->published()->get();
        $shopperContext = $this->buildShopperContext($request);
        $profileSummary = $this->summarizeShopperContext($shopperContext);

        $interest = $this->resolveInterest($message, $shopperContext, $data['message']);

        if ($interest && $interest['type'] === 'unavailable') {
            $fallback = $this->buildUnavailableFallback($interest['value']);

            $finalMessage = $this->gemini->reply(
                $data['message'],
                $fallback,
                [],
                array_merge(
                    $this->geminiProfileContext($shopperContext),
                    [
                        'intent' => 'catalog_unavailable',
                        'requested_item' => $interest['value'],
                    ]
                )
            );

            return response()->json([
                'message' => $finalMessage,
                'products' => [],
            ]);
        }

        $recommendation = $this->gemini->recommendProducts(
            $data['message'],
            $catalog->map(fn (Product $product) => $this->formatProductForGemini($product))->toArray(),
            ['profile' => $profileSummary]
        );

        $recommendedProducts = $this->orderProductsByRecommendation($catalog, $recommendation['product_ids'] ?? []);

        $products = $recommendedProducts;
        $interestBased = false;

        if ($products->isEmpty()) {
            $interest = $interest ?? $this->resolveInterest($message, $shopperContext, $data['message']);

            if ($interest) {
                $products = Product::with('categories')
                    ->published()
                    ->when(
                        $interest['type'] === 'category',
                        fn ($query) => $query->whereHas('categories', fn ($category) => $category->where('slug', $interest['value']))
                    )
                    ->when(
                        $interest['type'] === 'featured',
                        fn ($query) => $query->featured()
                    )
                    ->take(4)
                    ->get();

                $interestBased = $products->isNotEmpty();
            }
        }

        $usingRecommendation = $recommendedProducts->isNotEmpty();
        $hasProducts = $products->isNotEmpty();
        $intro = $this->buildIntroLine($shopperContext);

        if (! $hasProducts) {
            $reply = $intro . ' I currently curate signature perfumes, motion sneakers, and ritual kits/care. Tell me which lane you want to explore and I will line up options.';
        } elseif ($usingRecommendation) {
            $reply = $intro . ' ' . ($recommendation['reason'] ?? 'Pulled these picks for you—tap any card to learn more.');
        } elseif ($interestBased) {
            $collection = $products->first()?->categories->pluck('name')->join(', ');
            $reply = $intro . ' ';

            if (($interest['source'] ?? null) === 'history') {
                $reply .= 'Since you keep coming back for ' . ($collection ?: 'those favorites') . ', I pulled these new drops.';
            } elseif (($interest['source'] ?? null) === 'message') {
                $reply .= 'Here\'s a curated stack that matches what you just asked for.';
            } else {
                $reply .= 'Here are a few featured picks the community is loving right now.';
            }
        } else {
            $collection = $products->first()?->categories->pluck('name')->join(', ');
            $reply = $intro . ' Here are some curated picks' . ($collection ? " from {$collection}" : '') . '.';
        }

        $productContext = $products->map(fn (Product $product) => [
            'name' => $product->name,
            'price' => $product->price,
            'category' => $product->categories->pluck('name')->first(),
        ])->toArray();

        $finalMessage = $this->gemini->reply(
            $data['message'],
            trim($reply),
            $productContext,
            array_merge(
                $this->geminiProfileContext($shopperContext),
                [
                    'reason' => $usingRecommendation ? $recommendation['reason'] : ($interestBased ? 'Following shopper history' : null),
                    'intent' => $hasProducts
                        ? ($usingRecommendation ? 'ai_ranked_recommendations' : 'interest_based_recommendations')
                        : 'clarify_catalog_scope',
                ]
            )
        );

        return response()->json([
            'message' => $finalMessage,
            'products' => $products->map(fn (Product $product) => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->short_description,
                'price' => number_format($product->price, 0),
                'image' => $product->hero_image_url ?? asset('images/catalog/placeholder.jpg'),
                'category' => $product->categories->pluck('name')->first(),
            ]),
        ]);
    }

    protected function formatProductForGemini(Product $product): array
    {
        $description = $product->short_description ?: Str::limit(strip_tags($product->description ?? ''), 120);
        $specs = collect($product->specifications ?? [])
            ->flatten()
            ->filter(fn ($value) => is_string($value))
            ->take(4)
            ->implode(', ');

        return [
            'id' => $product->id,
            'name' => $product->name,
            'category' => $product->categories->pluck('name')->join(', ') ?: 'Catalog',
            'price' => $product->price,
            'description' => trim($description . ($specs ? ' · ' . $specs : '')),
        ];
    }

    protected function orderProductsByRecommendation($catalog, array $ids)
    {
        if (empty($ids)) {
            return collect();
        }

        $positions = array_flip($ids);

        return $catalog->filter(fn (Product $product) => array_key_exists($product->id, $positions))
            ->sortBy(fn (Product $product) => $positions[$product->id])
            ->values();
    }

    protected function detectCategory(string $message): ?string
    {
        $map = [
            'perfume' => 'signature-perfumes',
            'fragrance' => 'signature-perfumes',
            'scent' => 'signature-perfumes',
            'sneaker' => 'motion-sneakers',
            'shoe' => 'motion-sneakers',
            'trainer' => 'motion-sneakers',
            'kit' => 'ritual-kits',
            'care' => 'ritual-kits',
            'gift' => 'ritual-kits',
        ];

        foreach ($map as $keyword => $slug) {
            if (Str::contains($message, $keyword)) {
                return $slug;
            }
        }

        return null;
    }

    protected function buildShopperContext(Request $request): array
    {
        $summary = $this->cart->summary();
        $user = $request->user();

        $firstName = $user ? trim(Str::before($user->name, ' ')) : null;
        if ($user && ! $firstName) {
            $firstName = trim($user->name);
        }

        $context = [
            'name' => $user?->name ?? 'Guest',
            'first_name' => $firstName ?: 'there',
            'segment' => $user ? 'Authenticated shopper' : 'Guest shopper',
            'order_count' => 0,
            'top_categories' => [],
            'top_category_slugs' => [],
            'cart_count' => $summary['count'] ?? 0,
            'average_order_value' => null,
            'last_order_total' => null,
            'last_order_time' => null,
        ];

        if (! $user) {
            return $context;
        }

        $orders = Order::with(['items.product.categories'])
            ->paid()
            ->where('user_id', $user->id)
            ->latest('placed_at')
            ->take(5)
            ->get();

        $context['order_count'] = $orders->count();
        $context['segment'] = $orders->isEmpty() ? 'New shopper' : 'Returning customer';
        $context['average_order_value'] = $orders->avg('grand_total') ?: null;

        if ($latestOrder = $orders->first()) {
            $context['last_order_total'] = $latestOrder->grand_total;
            $timestamp = $latestOrder->placed_at ?? $latestOrder->created_at;
            $context['last_order_time'] = $timestamp ? $timestamp->diffForHumans() : null;
        }

        $tally = [];

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                if (! $item->product) {
                    continue;
                }

                foreach ($item->product->categories as $category) {
                    $slug = $category->slug;

                    if (! isset($tally[$slug])) {
                        $tally[$slug] = [
                            'slug' => $slug,
                            'name' => $category->name,
                            'count' => 0,
                        ];
                    }

                    $tally[$slug]['count']++;
                }
            }
        }

        $sorted = collect($tally)->sortByDesc('count')->values();
        $context['top_categories'] = $sorted->pluck('name')->take(3)->all();
        $context['top_category_slugs'] = $sorted->pluck('slug')->take(3)->all();

        return $context;
    }

    protected function buildIntroLine(array $context): string
    {
        $name = $context['first_name'] ?? 'there';

        if (($context['order_count'] ?? 0) > 0) {
            $top = $context['top_categories'][0] ?? null;
            $time = $context['last_order_time'] ?? null;

            $line = "Welcome back {$name}! I pulled inspo from your past";
            $line .= $top ? " {$top} hauls" : ' orders';
            $line .= $time ? " ({$time})." : '.';

            return $line;
        }

        if (($context['cart_count'] ?? 0) > 0) {
            return "Hey {$name}! I see {$context['cart_count']} item(s) waiting in your cart—let's round it out with perfumes, sneakers, or ritual kits that pair well.";
        }

        return "Hey {$name}! I'm your personal shopper for signature perfumes, motion sneakers, and ritual care.";
    }

    protected function summarizeShopperContext(array $context): string
    {
        $parts = [
            $context['segment'] ?? null,
        ];

        if (($context['order_count'] ?? 0) > 0) {
            $parts[] = "{$context['order_count']} past orders";
        }

        if (! empty($context['top_categories'])) {
            $parts[] = 'Prefers ' . implode(', ', $context['top_categories']);
        }

        if ($context['average_order_value']) {
            $parts[] = 'Average order ₹' . number_format((float) $context['average_order_value'], 0);
        }

        if (($context['cart_count'] ?? 0) > 0) {
            $parts[] = $context['cart_count'] . ' item(s) currently in cart';
        }

        return implode('. ', array_filter($parts));
    }

    protected function geminiProfileContext(array $context): array
    {
        return array_filter([
            'shopper_name' => $context['first_name'] ?? $context['name'] ?? null,
            'shopper_segment' => $context['segment'] ?? null,
            'shopper_order_count' => $context['order_count'] ?? null,
            'shopper_top_categories' => $context['top_categories'] ?? [],
            'shopper_cart_items' => $context['cart_count'] ?? null,
        ], function ($value) {
            if (is_array($value)) {
                return ! empty($value);
            }

            return ! is_null($value) && $value !== '';
        });
    }

    protected function resolveInterest(string $message, array $context, string $rawMessage): ?array
    {
        if ($categorySlug = $this->detectCategory($message)) {
            return [
                'type' => 'category',
                'value' => $categorySlug,
                'source' => 'message',
            ];
        }

        if ($this->wantsProductIdeas($message)) {
            if (! empty($context['top_category_slugs'])) {
                return [
                    'type' => 'category',
                    'value' => $context['top_category_slugs'][0],
                    'source' => 'history',
                ];
            }

            return [
                'type' => 'featured',
                'value' => null,
                'source' => 'featured',
            ];
        }

        if ($this->requestsSpecificProduct($message)) {
            return [
                'type' => 'unavailable',
                'value' => $this->extractRequestedItem($rawMessage),
                'source' => 'outside_catalog',
            ];
        }

        return null;
    }

    protected function wantsProductIdeas(string $message): bool
    {
        $keywords = [
            'recommend',
            'suggest',
            'show me',
            'something',
            'anything',
            'gift',
            'ideas',
            'options',
            'surprise',
            'browse',
            'new drops',
            'what\'s good',
        ];

        return Str::contains($message, $keywords);
    }

    protected function requestsSpecificProduct(string $message): bool
    {
        $verbs = [
            'want',
            'buy',
            'need',
            'looking for',
            'searching for',
            'order',
            'purchase',
            'get ',
            'get a',
        ];

        return Str::contains($message, $verbs);
    }

    protected function extractRequestedItem(string $rawMessage): string
    {
        $clean = preg_replace('/[^a-z0-9\s]/i', ' ', $rawMessage);
        $clean = preg_replace('/\s+/', ' ', $clean);
        $clean = trim($clean);

        $clean = preg_replace('/\b(i\s+)?(want|need|to buy|buy|looking for|searching for|order|get|purchase)\b/i', '', $clean);
        $clean = trim($clean);

        return $clean ? Str::limit($clean, 40) : 'that item';
    }

    protected function buildUnavailableFallback(?string $item): string
    {
        $safe = $item ? Str::title($item) : 'that item';

        return "I double-checked inventory, but we don't carry {$safe}. Right now the shop features signature perfumes, motion sneakers, and ritual kits/care only.";
    }
}
