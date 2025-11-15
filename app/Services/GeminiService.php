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

    public function reply(string $userMessage, string $fallback, array $products = []): string
    {
        if (! $this->available()) {
            return $fallback;
        }

        try {
            $prompt = $this->buildPrompt($userMessage, $fallback, $products);

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

    protected function buildPrompt(string $userMessage, string $fallback, array $products): string
    {
        $productContext = collect($products)->map(function ($product) {
            return "{$product['name']} - ₹{$product['price']} ({$product['category']})";
        })->implode("\n");

        return <<<PROMPT
You are Aromea AI, a calm conversational ecommerce concierge for perfumes, motion sneakers, and ritual kits.
Keep answers under 3 short sentences, maintain a friendly Amazon-style tone, and NEVER invent products.
Base your answer on this suggested reply: "{$fallback}".
If products are provided, mention that you've curated them without listing every detail; encourage the user to tap the cards.
If the customer asks for refunds, direct them to the refund portal and explain the steps briefly.
User said: "{$userMessage}".
Products available:
{$productContext}
PROMPT;
    }
}
