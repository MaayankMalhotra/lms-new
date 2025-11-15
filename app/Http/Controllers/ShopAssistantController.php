<?php

namespace App\Http\Controllers;

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

    public function index(): View
    {
        return view('shop.chat');
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

        $categorySlug = $this->detectCategory($message);

        $products = Product::with('categories')
            ->published()
            ->when($categorySlug, fn ($query) => $query->whereHas('categories', fn ($category) => $category->where('slug', $categorySlug)))
            ->when(! $categorySlug, fn ($query) => $query->featured())
            ->take(4)
            ->get();

        if ($products->isEmpty()) {
            $products = Product::with('categories')->published()->take(4)->get();
        }

        $reply = $categorySlug
            ? 'Here are curated picks from the **' . $products->first()?->categories->pluck('name')->join(', ') . '** collection.'
            : "Here are some of our best picks today. Ask for perfumes, sneakers, or kits to refine your feed.";

        $finalMessage = $this->gemini->reply($data['message'], $reply, $products->toArray());

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
}
