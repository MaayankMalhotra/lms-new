<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Razorpay\Api\Api;
use Throwable;
use RuntimeException;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cart)
    {
    }

    public function show(): View
    {
        $summary = $this->cart->summary();

        if ($summary['count'] === 0) {
            abort(404, 'Cart is empty');
        }

        return view('shop.checkout', compact('summary'));
    }

    public function store(CheckoutRequest $request)
    {
        $summary = $this->cart->summary();

        if ($summary['count'] === 0) {
            return $this->emptyCartResponse($request);
        }

        $order = $this->placeOrder($request, $summary);

        if ($request->payment_method === 'cod') {
            $this->cart->clear();

            return $this->checkoutResponse($request, $order);
        }

        try {
            $razorpayOrder = $this->createRazorpayOrder($order);

            return $this->checkoutResponse($request, $order, [
                'razorpay' => [
                    'order_id' => $razorpayOrder['id'],
                    'amount' => $razorpayOrder['amount'],
                    'currency' => $razorpayOrder['currency'],
                    'key' => config('services.razorpay.key'),
                    'name' => config('app.name', 'ThinkChamp'),
                    'prefill' => [
                        'name' => trim($order->first_name . ' ' . $order->last_name),
                        'email' => $order->email,
                        'contact' => $order->phone,
                    ],
                ],
            ]);
        } catch (Throwable $exception) {
            Log::error('Unable to create Razorpay order', [
                'order' => $order->order_number,
                'message' => $exception->getMessage(),
            ]);

            return $this->checkoutResponse($request, $order, [
                'error' => 'Could not initiate Razorpay payment. Please try COD or contact support.',
            ], 422);
        }
    }

    public function verifyRazorpayPayment(Request $request)
    {
        $data = $request->validate([
            'order_number' => ['required', 'exists:orders,order_number'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        /** @var Order $order */
        $order = Order::where('order_number', $data['order_number'])->firstOrFail();

        if ($order->razorpay_order_id !== $data['razorpay_order_id']) {
            return response()->json([
                'message' => 'Payment verification failed.',
            ], 422);
        }

        try {
            $this->razorpay()->utility->verifyPaymentSignature([
                'razorpay_signature' => $data['razorpay_signature'],
                'razorpay_payment_id' => $data['razorpay_payment_id'],
                'razorpay_order_id' => $data['razorpay_order_id'],
            ]);
        } catch (Throwable $exception) {
            Log::warning('Razorpay signature mismatch', [
                'order' => $order->order_number,
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'message' => 'Payment verification failed.',
            ], 422);
        }

        $order->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'fulfillment_status' => 'unfulfilled',
            'razorpay_payment_id' => $data['razorpay_payment_id'],
            'razorpay_signature' => $data['razorpay_signature'],
            'placed_at' => $order->placed_at ?? now(),
        ]);

        $this->cart->clear();

        return response()->json([
            'message' => 'Payment captured',
            'redirect_url' => route('checkout.thank-you', $order),
        ]);
    }

    public function thankYou(Order $order): View
    {
        $order->load('items');

        return view('shop.thank-you', compact('order'));
    }

    protected function placeOrder(CheckoutRequest $request, array $summary): Order
    {
        return DB::transaction(function () use ($request, $summary) {
            /** @var Order $order */
            $order = Order::create([
                'user_id' => optional($request->user())->id,
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address_line1' => $request->input('address_line1'),
                'address_line2' => $request->input('address_line2'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
                'postal_code' => $request->input('postal_code'),
                'country' => $request->input('country'),
                'notes' => $request->input('notes'),
                'status' => $request->payment_method === 'cod' ? 'confirmed' : 'pending',
                'payment_status' => $request->payment_method === 'cod' ? 'unpaid' : 'awaiting_payment',
                'fulfillment_status' => 'unfulfilled',
                'payment_method' => $request->payment_method,
                'currency' => $summary['currency'],
                'subtotal' => $summary['subtotal'],
                'tax_total' => $summary['tax'],
                'shipping_total' => $summary['shipping'],
                'discount_total' => 0,
                'grand_total' => $summary['total'],
                'placed_at' => now(),
            ]);

            foreach ($summary['items'] as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'sku' => $item->product->sku,
                    'unit_price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'total' => $item->line_total,
                    'options' => $item->options,
                ]);

                if ($item->product && $item->product->inventory > 0) {
                    $item->product->decrement('inventory', min($item->quantity, $item->product->inventory));
                }
            }

            return $order;
        });
    }

    protected function checkoutResponse(Request $request, Order $order, array $extra = [], int $status = 200)
    {
        $payload = array_merge([
            'order_number' => $order->order_number,
            'redirect_url' => route('checkout.thank-you', $order),
        ], $extra);

        $payload['message'] = $payload['message'] ?? ($status >= 400
            ? 'Unable to process order.'
            : 'Order placed successfully.');

        if ($request->wantsJson()) {
            return response()->json($payload, $status);
        }

        if ($status >= 400) {
            return redirect()->back()->withErrors($payload['error'] ?? $payload['message']);
        }

        return redirect($payload['redirect_url'])->with('status', $payload['message']);
    }

    protected function emptyCartResponse(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Your cart is empty.',
            ], 422);
        }

        return redirect()->route('shop.index')->withErrors('Your cart is empty.');
    }

    protected function createRazorpayOrder(Order $order): array
    {
        $api = $this->razorpay();

        $razorpayOrder = $api->order->create([
            'receipt' => $order->order_number,
            'amount' => (int) round($order->grand_total * 100), // amount in paise
            'currency' => $order->currency,
            'payment_capture' => 1,
        ]);

        $order->update(['razorpay_order_id' => $razorpayOrder['id']]);

        return $razorpayOrder->toArray();
    }

    protected function razorpay(): Api
    {
        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');

        if (! $key || ! $secret) {
            throw new RuntimeException('Razorpay credentials are missing from the environment.');
        }

        return new Api($key, $secret);
    }
}
