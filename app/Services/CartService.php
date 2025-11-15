<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class CartService
{
    public function __construct(private Request $request)
    {
    }

    public function sessionId(): string
    {
        if (! $this->request->session()->has('cart_session_id')) {
            $this->request->session()->put('cart_session_id', Str::uuid()->toString());
        }

        return $this->request->session()->get('cart_session_id');
    }

    public function items(): Collection
    {
        return CartItem::with('product')
            ->where('session_id', $this->sessionId())
            ->get();
    }

    public function add(Product $product, int $quantity = 1, array $options = []): CartItem
    {
        $quantity = max(1, $quantity);

        $cartItem = CartItem::firstOrNew([
            'session_id' => $this->sessionId(),
            'product_id' => $product->id,
        ]);

        $cartItem->fill([
            'user_id' => optional($this->request->user())->id,
            'unit_price' => $product->price,
            'options' => $options ?: null,
        ]);

        $cartItem->quantity = $cartItem->exists
            ? $cartItem->quantity + $quantity
            : $quantity;

        $cartItem->save();

        return $cartItem->load('product');
    }

    public function updateQuantity(int $cartItemId, int $quantity): CartItem
    {
        $quantity = max(1, $quantity);

        /** @var CartItem $cartItem */
        $cartItem = CartItem::where('session_id', $this->sessionId())
            ->whereKey($cartItemId)
            ->firstOrFail();

        $cartItem->update(['quantity' => $quantity]);

        return $cartItem->load('product');
    }

    public function removeItem(int $cartItemId): void
    {
        CartItem::where('session_id', $this->sessionId())
            ->whereKey($cartItemId)
            ->delete();
    }

    public function clear(): void
    {
        CartItem::where('session_id', $this->sessionId())->delete();
    }

    public function summary(): array
    {
        if (! Schema::hasTable('cart_items')) {
            return [
                'items' => collect(),
                'count' => 0,
                'subtotal' => 0,
                'tax' => 0,
                'shipping' => 0,
                'total' => 0,
                'currency' => config('commerce.currency', 'INR'),
            ];
        }

        $items = $this->items();
        $subtotal = $items->sum(fn (CartItem $item) => $item->line_total);
        $taxRate = (float) config('commerce.tax_rate', 0);
        $tax = round($subtotal * $taxRate, 2);
        $shipping = $subtotal > 0 ? (float) config('commerce.shipping_flat_rate', 0) : 0;
        $total = $subtotal + $tax + $shipping;

        return [
            'items' => $items,
            'count' => $items->sum('quantity'),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
            'currency' => config('commerce.currency', 'INR'),
        ];
    }

    public function attachUser(?Authenticatable $user): void
    {
        if (! $user) {
            return;
        }

        CartItem::where('session_id', $this->sessionId())
            ->whereNull('user_id')
            ->update(['user_id' => $user->getAuthIdentifier()]);
    }
}
