@extends('shop.layouts.app')

@section('title', 'Checkout | Aromea Market')

@section('content')
    <div class="mx-auto max-w-7xl px-4 space-y-10">
        <p class="text-xs uppercase tracking-[0.3em] text-gray-500">Aromea / Checkout</p>

        <div class="grid gap-8 lg:grid-cols-[2fr,1fr]">
            <form id="checkout-form" class="rounded-3xl bg-white p-8 shadow-sm space-y-8" data-checkout-form>
                @csrf
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Contact</p>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <input type="text" name="first_name" placeholder="First name" class="rounded-2xl border border-gray-200 px-4 py-3" required>
                        <input type="text" name="last_name" placeholder="Last name" class="rounded-2xl border border-gray-200 px-4 py-3">
                        <input type="email" name="email" placeholder="Email" class="rounded-2xl border border-gray-200 px-4 py-3" required>
                        <input type="tel" name="phone" placeholder="Phone" class="rounded-2xl border border-gray-200 px-4 py-3" required>
                    </div>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Shipping</p>
                    <div class="mt-4 grid gap-4">
                        <input type="text" name="address_line1" placeholder="Address line 1" class="rounded-2xl border border-gray-200 px-4 py-3" required>
                        <input type="text" name="address_line2" placeholder="Address line 2" class="rounded-2xl border border-gray-200 px-4 py-3">
                        <div class="grid gap-4 md:grid-cols-3">
                            <input type="text" name="city" placeholder="City" class="rounded-2xl border border-gray-200 px-4 py-3" required>
                            <input type="text" name="state" placeholder="State" class="rounded-2xl border border-gray-200 px-4 py-3" required>
                            <input type="text" name="postal_code" placeholder="PIN" class="rounded-2xl border border-gray-200 px-4 py-3" required>
                        </div>
                        <input type="text" name="country" value="India" class="rounded-2xl border border-gray-200 px-4 py-3" required>
                        <textarea name="notes" rows="3" placeholder="Delivery instructions" class="rounded-2xl border border-gray-200 px-4 py-3"></textarea>
                    </div>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Payment</p>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <label class="flex items-center gap-3 rounded-2xl border border-gray-200 px-4 py-3">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <span class="text-sm font-semibold text-slate-900">Cash on delivery</span>
                        </label>
                        <label class="flex items-center gap-3 rounded-2xl border border-gray-200 px-4 py-3">
                            <input type="radio" name="payment_method" value="razorpay">
                            <span class="text-sm font-semibold text-slate-900">Razorpay (UPI/cards)</span>
                        </label>
                    </div>
                </div>

                <label class="flex items-start gap-3 text-sm text-gray-600">
                    <input type="checkbox" name="agree_to_terms" required>
                    <span>I agree to receive transactional updates and accept the terms.</span>
                </label>

                <button type="submit" data-checkout-submit class="w-full rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white">
                    Place order
                </button>
                <p class="text-xs text-gray-500" data-checkout-feedback></p>
            </form>

            <aside class="space-y-4 rounded-3xl bg-white p-8 shadow-sm">
                <h2 class="text-xl font-semibold text-slate-900">Order summary</h2>
                <div class="space-y-4 max-h-[420px] overflow-y-auto pr-1">
                    @foreach($summary['items'] as $item)
                        <div class="flex items-center gap-3">
                            <img src="{{ $item->product?->hero_image_url ?? asset('images/catalog/placeholder.jpg') }}" alt="{{ $item->product?->name }}" class="h-16 w-16 rounded-2xl object-cover">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-slate-900">{{ $item->product?->name }}</p>
                                <p class="text-xs text-gray-500">Qty {{ $item->quantity }}</p>
                            </div>
                            <p class="text-sm font-semibold text-slate-900">₹{{ number_format($item->line_total, 0) }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between"><span>Subtotal</span><span>₹{{ number_format($summary['subtotal'], 0) }}</span></div>
                    <div class="flex justify-between"><span>Tax</span><span>₹{{ number_format($summary['tax'], 0) }}</span></div>
                    <div class="flex justify-between"><span>Shipping</span><span>₹{{ number_format($summary['shipping'], 0) }}</span></div>
                    <div class="flex justify-between border-t border-dashed pt-3 text-base font-semibold text-slate-900">
                        <span>Total</span>
                        <span>₹{{ number_format($summary['total'], 0) }}</span>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        const checkoutForm = document.querySelector('[data-checkout-form]');
        const submitButton = document.querySelector('[data-checkout-submit]');
        const feedbackEl = document.querySelector('[data-checkout-feedback]');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        async function handleCheckout(event) {
            event.preventDefault();
            feedbackEl.textContent = '';
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';

            const formData = new FormData(checkoutForm);

            try {
                const response = await fetch('{{ route('checkout.store') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const payload = await response.json();
                if (!response.ok) {
                    throw new Error(payload.message || 'Unable to place order.');
                }

                if (payload.razorpay) {
                    launchRazorpay(payload);
                } else {
                    window.location.href = payload.redirect_url;
                }
            } catch (error) {
                feedbackEl.textContent = error.message;
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Place order';
            }
        }

        function launchRazorpay(payload) {
            const options = {
                key: payload.razorpay.key,
                amount: payload.razorpay.amount,
                currency: payload.razorpay.currency,
                name: payload.razorpay.name,
                order_id: payload.razorpay.order_id,
                prefill: payload.razorpay.prefill,
                handler: async function (response) {
                    try {
                        const verifyResponse = await fetch('{{ route('checkout.verify') }}', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({
                                order_number: payload.order_number,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature,
                            })
                        });

                        const verifyPayload = await verifyResponse.json();
                        if (!verifyResponse.ok) {
                            throw new Error(verifyPayload.message || 'Payment verification failed.');
                        }

                        window.location.href = verifyPayload.redirect_url;
                    } catch (error) {
                        feedbackEl.textContent = error.message;
                    }
                },
                theme: {
                    color: '#111827'
                }
            };
            new Razorpay(options).open();
        }

        checkoutForm?.addEventListener('submit', handleCheckout);
    </script>
@endpush

@push('meta_pixel_events')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof fbq !== 'undefined') {
                fbq('track', 'InitiateCheckout', {
                    value: {{ $summary['total'] }},
                    currency: '{{ $summary['currency'] }}'
                });
            }
        });
    </script>
@endpush
