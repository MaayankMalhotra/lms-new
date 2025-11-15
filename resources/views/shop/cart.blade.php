@extends('shop.layouts.app')

@section('title', 'Your Cart | Aromea Market')

@section('content')
    <div class="mx-auto max-w-7xl px-4 space-y-8">
        <p class="text-xs uppercase tracking-[0.3em] text-gray-500">Aromea / Cart</p>

        @if($summary['count'] > 0)
            <div class="grid gap-6 lg:grid-cols-[2fr,1fr]">
                <div class="rounded-2xl bg-white p-4 shadow-sm">
                    @foreach($summary['items'] as $item)
                        <div class="flex flex-col gap-4 border-b border-gray-200 py-4 last:border-none lg:flex-row" data-cart-item="{{ $item->id }}">
                            <img src="{{ $item->product?->hero_image_url ?? asset('images/catalog/placeholder.jpg') }}" alt="{{ $item->product?->name }}" class="h-28 w-28 rounded-lg object-cover">
                            <div class="flex-1 space-y-2">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-lg font-semibold text-slate-900">{{ $item->product?->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $item->product?->short_description }}</p>
                                    </div>
                                    <button type="button" data-remove-item class="text-sm text-gray-400 hover:text-slate-900">Remove</button>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center rounded-full border border-gray-300 px-2 py-1">
                                        <button type="button" data-quantity-btn="-1" class="px-3 text-lg text-slate-500">-</button>
                                        <input type="number" data-quantity-input min="1" value="{{ $item->quantity }}" class="w-12 border-none text-center">
                                        <button type="button" data-quantity-btn="1" class="px-3 text-lg text-slate-500">+</button>
                                    </div>
                                    <p class="text-lg font-semibold text-slate-900">₹{{ number_format($item->line_total, 0) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <aside class="space-y-4 rounded-2xl bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-900">Order summary</h2>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($summary['subtotal'], 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax</span>
                            <span>₹{{ number_format($summary['tax'], 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span>₹{{ number_format($summary['shipping'], 0) }}</span>
                        </div>
                        <div class="flex justify-between border-t border-dashed pt-3 text-base font-semibold text-slate-900">
                            <span>Total</span>
                            <span>₹{{ number_format($summary['total'], 0) }}</span>
                        </div>
                    </div>
                    <a href="{{ route('checkout.show') }}" class="inline-flex w-full items-center justify-center rounded-full bg-slate-900 px-4 py-3 text-sm font-semibold text-white">
                        Proceed to checkout
                    </a>
                </aside>
            </div>
        @else
            <div class="rounded-3xl border border-dashed border-gray-300 bg-white p-16 text-center">
                <p class="text-2xl font-semibold text-slate-900">Your bag is empty.</p>
                <p class="mt-2 text-gray-500">Discover signature perfumes, sneakers, and creator kits.</p>
                <a href="{{ route('shop.index') }}" class="mt-6 inline-flex rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white">Start shopping</a>
            </div>
        @endif
    </div>
@endsection

@push('meta_pixel_events')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof fbq !== 'undefined') {
                fbq('track', 'AddToCart', {
                    value: {{ $summary['subtotal'] }},
                    currency: '{{ $summary['currency'] }}'
                });
            }
        });
    </script>
@endpush
