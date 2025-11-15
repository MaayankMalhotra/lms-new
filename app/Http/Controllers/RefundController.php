<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RefundController extends Controller
{
    public function create(): View
    {
        return view('shop.refunds.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_number' => ['required', 'string'],
            'email' => ['required', 'email'],
            'reason' => ['required', 'string', 'max:500'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $order = Order::where('order_number', $data['order_number'])
            ->where('email', $data['email'])
            ->first();

        if (! $order) {
            return back()->withErrors('We could not find an order matching those details.');
        }

        $refund = RefundRequest::create([
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'email' => $data['email'],
            'amount' => $order->grand_total,
            'status' => 'pending',
            'reason' => $data['reason'],
            'customer_message' => $data['message'],
        ]);

        return redirect()->route('refunds.create')
            ->with('status', 'Refund ticket #' . $refund->id . ' has been submitted. Our team will contact you soon.');
    }
}
