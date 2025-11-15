@extends('shop.layouts.app')

@section('title', 'Order ' . $order->order_number . ' | Aromea Market')

@section('content')
    <div class="mx-auto max-w-5xl px-4 space-y-8">
        <p class="text-xs uppercase tracking-[0.3em] text-gray-500">Aromea / Order / {{ $order->order_number }}</p>

        <section class="rounded-3xl bg-white p-8 shadow-sm space-y-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Order confirmed</p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900">Thanks for shopping with Aromea</h1>
                    <p class="text-gray-500">We emailed the receipt to {{ $order->email }}.</p>
                </div>
                <div class="rounded-full bg-emerald-100 px-5 py-2 text-sm font-semibold text-emerald-800">
                    Status: {{ ucfirst($order->status) }}
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Shipping to</p>
                    <p class="mt-3 text-sm text-gray-600">
                        {{ $order->first_name }} {{ $order->last_name }}<br>
                        {{ $order->address_line1 }}<br>
                        {{ $order->address_line2 }}<br>
                        {{ $order->city }}, {{ $order->state }} {{ $order->postal_code }}<br>
                        {{ $order->country }}
                    </p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Payment</p>
                    <p class="mt-3 text-sm text-gray-600">
                        Method: {{ strtoupper($order->payment_method) }}<br>
                        Amount: ₹{{ number_format($order->grand_total, 0) }}<br>
                        Payment status: {{ ucfirst($order->payment_status) }}
                    </p>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-gray-200">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs uppercase tracking-[0.3em] text-gray-500">
                        <tr>
                            <th class="px-4 py-3">Product</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr class="border-b border-gray-100">
                                <td class="px-4 py-3 text-slate-900">{{ $item->product_name }}</td>
                                <td class="px-4 py-3">{{ $item->quantity }}</td>
                                <td class="px-4 py-3">₹{{ number_format($item->total, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50 text-slate-900 font-semibold">
                            <td class="px-4 py-3">Grand total</td>
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3">₹{{ number_format($order->grand_total, 0) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>

        <div class="rounded-3xl bg-white p-6 text-center text-sm text-gray-600 shadow-sm">
            Need to cancel or return something? <a href="{{ route('refunds.create') }}" class="font-semibold text-[#007185]">Start a refund request</a>.
        </div>
    </div>
@endsection

@push('meta_pixel_events')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof fbq !== 'undefined') {
                fbq('track', 'Purchase', {
                    value: {{ $order->grand_total }},
                    currency: '{{ $order->currency }}'
                });
            }
        });
    </script>
@endpush
