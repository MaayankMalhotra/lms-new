<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected ?string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->model = config('services.gemini.chat_model', config('services.gemini.model', 'gemini-pro'));
    }

    public function available(): bool
    {
        return ! empty($this->apiKey);
    }

    public function reply(string $userMessage, string $fallback, array $products = [], array $context = []): string
    {
        if (! $this->available()) {
            return $fallback;
        }

        try {
            $prompt = $this->buildPrompt($userMessage, $fallback, $products, $context);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}v1beta/models/{$this->model}:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
            ]);

            if ($response->failed()) {
                Log::warning('Gemini API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return $fallback;
            }

            $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

            return $text ? trim($text) : $fallback;
        } catch (\Throwable $exception) {
            Log::warning('Gemini reply failed', [
                'message' => $exception->getMessage(),
            ]);

            return $fallback;
        }
    }

    public function recommendProducts(string $userMessage, array $catalog, array $context = []): array
    {
        if (! $this->available() || empty($catalog)) {
            return [
                'product_ids' => [],
                'reason' => null,
            ];
        }

        try {
            $lines = collect($catalog)->map(function (array $product) {
                $category = $product['category'] ?? 'Catalog';
                $price = number_format($product['price'] ?? 0);
                $description = $product['description'] ?? '';

                return "ID: {$product['id']} | {$product['name']} ({$category}) - ₹{$price}. Notes: {$description}";
            })->implode("\n");

            $contextLines = collect($context)->filter(function ($value) {
                if (is_array($value)) {
                    return ! empty(array_filter($value));
                }

                return ! is_null($value) && $value !== '';
            })->map(function ($value, $key) {
                $label = ucwords(str_replace('_', ' ', $key));
                $text = is_array($value) ? implode(', ', array_filter($value)) : $value;

                return "{$label}: {$text}";
            })->implode("\n") ?: 'Guest shopper with no history available.';

            $prompt = <<<PROMPT
You are Aromea AI, an expert merchandiser. From the catalog below, select up to 4 products that best answer the shopper's request.
Only pick from the provided IDs. Prefer diversity when unsure.
Return a strict JSON payload with:
{
  "product_ids": [list of numeric IDs you recommend, in ranking order],
  "reason": "short explanation (max 30 words)"
}
If nothing matches, return an empty array and explain why in reason.
If the request is outside perfumes, sneakers, or ritual kits/care, return an empty list and explain that we only carry those categories.

Catalog:
{$lines}

Shopper profile:
{$contextLines}

Shopper request: "{$userMessage}"
PROMPT;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}v1beta/models/{$this->model}:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                ],
            ]);

            if ($response->failed()) {
                Log::warning('Gemini product recommendation failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'product_ids' => [],
                    'reason' => null,
                ];
            }

            $raw = data_get($response->json(), 'candidates.0.content.parts.0.text');
            $decoded = $raw ? json_decode($raw, true) : null;

            if (! is_array($decoded)) {
                return [
                    'product_ids' => [],
                    'reason' => null,
                ];
            }

            $catalogIds = collect($catalog)->pluck('id')->map(fn ($id) => (int) $id)->all();
            $productIds = collect(data_get($decoded, 'product_ids', []))
                ->map(fn ($id) => (int) $id)
                ->filter(fn ($id) => in_array($id, $catalogIds, true))
                ->values()
                ->take(4)
                ->all();

            return [
                'product_ids' => $productIds,
                'reason' => data_get($decoded, 'reason'),
            ];
        } catch (\Throwable $exception) {
            Log::warning('Gemini product recommendation exception', [
                'message' => $exception->getMessage(),
            ]);

            return [
                'product_ids' => [],
                'reason' => null,
            ];
        }
    }

    protected function buildPrompt(string $userMessage, string $fallback, array $products, array $context = []): string
    {
        $productContext = collect($products)->map(function ($product) {
            $category = null;

            if (isset($product['category'])) {
                $category = $product['category'];
            } elseif (isset($product['categories'])) {
                $category = collect($product['categories'])->map(function ($item) {
                    if (is_array($item)) {
                        return $item['name'] ?? null;
                    }

                    return $item;
                })->filter()->join(', ');
            }

            $category ??= 'Catalog';
            $price = number_format($product['price'] ?? 0);

            return "{$product['name']} - ₹{$price} ({$category})";
        })->implode("\n");

        $productContext = $productContext ?: 'No direct product matches supplied.';

        $contextLines = collect($context)
            ->filter(function ($value) {
                if (is_array($value)) {
                    return ! empty(array_filter($value));
                }

                return ! is_null($value) && $value !== '';
            })
            ->map(function ($value, $key) {
                $label = ucwords(str_replace('_', ' ', $key));
                $text = is_array($value) ? implode(', ', array_filter($value)) : $value;

                return "{$label}: {$text}";
            })->implode("\n");

        $contextBlock = $contextLines ?: 'No extra context provided.';

        return <<<PROMPT
You are Aromea AI, a calm conversational ecommerce concierge for perfumes, motion sneakers, and ritual kits.
Keep answers under 3 short sentences, maintain a friendly Amazon-style tone, and NEVER invent products.
Base your answer on this suggested reply: "{$fallback}".
Always open with the intro segment contained in the fallback before elaborating.
Context to consider:
{$contextBlock}
If products are provided, mention that you've curated them without listing every detail; encourage the user to tap the cards.
If the customer asks for refunds, direct them to the refund portal and explain the steps briefly.
User said: "{$userMessage}".
Products available:
{$productContext}
PROMPT;
    }
}
