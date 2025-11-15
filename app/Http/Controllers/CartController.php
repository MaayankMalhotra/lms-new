<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cart)
    {
    }

    public function index(): View
    {
        $summary = $this->cart->summary();

        return view('shop.cart', compact('summary'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::published()->findOrFail($data['product_id']);

        $this->cart->add($product, $data['quantity'] ?? 1);

        return $this->respond($request, 'Product added to cart.');
    }

    public function update(Request $request, int $cartItemId)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $this->cart->updateQuantity($cartItemId, $data['quantity']);

        return $this->respond($request, 'Cart updated.');
    }

    public function destroy(Request $request, int $cartItemId)
    {
        $this->cart->removeItem($cartItemId);

        return $this->respond($request, 'Item removed from cart.');
    }

    protected function respond(Request $request, string $message)
    {
        $payload = [
            'message' => $message,
            'summary' => $this->cart->summary(),
        ];

        if ($request->wantsJson()) {
            return response()->json($payload);
        }

        return redirect()->route('cart.index')->with('status', $message);
    }
}
