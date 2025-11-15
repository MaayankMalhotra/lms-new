<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aromea Market')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amazon+Ember:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-p0IffAtyT62C1zBcY9VgAorAFz2uVH54camzato3tr1cGukdxbYlR5c+3F4iAfDdc0AGJi/7luWASxM49P4b0A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body { font-family: 'Amazon Ember', 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .shadow-amazon { box-shadow: 0 10px 30px rgba(0,0,0,0.35); }
    </style>
    @if(config('commerce.meta_pixel_id'))
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ config('commerce.meta_pixel_id') }}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ config('commerce.meta_pixel_id') }}&ev=PageView&noscript=1" />
        </noscript>
    @endif
    @stack('head')
</head>
<body class="bg-[#eaeded]">
    @include('shop.partials.header')

    <main class="pt-28 lg:pt-32">
        @yield('content')
    </main>

    @include('shop.partials.footer')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const routes = {
                cartStore: '{{ route('cart.store') }}',
                cartUpdate: (id) => '{{ url('/cart') }}/' + id,
                cartDestroy: (id) => '{{ url('/cart') }}/' + id,
                checkout: '{{ route('checkout.show') }}',
            };

            const toast = document.createElement('div');
            toast.className = 'fixed bottom-6 right-6 z-50 hidden rounded-full bg-[#131921] px-5 py-3 text-sm font-semibold text-white shadow-lg transition';
            document.body.appendChild(toast);

            const showToast = (message, isError = false) => {
                toast.textContent = message;
                toast.classList.toggle('bg-red-600', isError);
                toast.classList.remove('hidden');
                setTimeout(() => toast.classList.add('hidden'), 2800);
            };

            const updateCartCount = (count) => {
                document.querySelectorAll('[data-cart-count]').forEach((el) => el.textContent = count);
            };

            const addToCart = async (formData, options = {}) => {
                const response = await fetch(routes.cartStore, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });

                let payload;
                try {
                    payload = await response.json();
                } catch (error) {
                    throw new Error('Unable to add to cart.');
                }

                if (!response.ok) {
                    throw new Error(payload.message || 'Unable to add to cart.');
                }

                updateCartCount(payload.summary.count);
                document.dispatchEvent(new CustomEvent('cart:updated', { detail: payload.summary }));
                showToast(options.successMessage || 'Added to cart');

                if (typeof fbq !== 'undefined') {
                    fbq('track', 'AddToCart', {
                        value: payload.summary.subtotal,
                        currency: payload.summary.currency,
                    });
                }

                return payload.summary;
            };

            document.addEventListener('submit', (event) => {
                const form = event.target.closest('form[data-add-to-cart]');
                if (!form) return;
                event.preventDefault();
                addToCart(new FormData(form)).catch((error) => showToast(error.message, true));
            });

            document.querySelectorAll('[data-buy-now]').forEach((button) => {
                button.addEventListener('click', async () => {
                    const form = button.closest('[data-product-detail-form]');
                    const quantity = form?.querySelector('input[name="quantity"]')?.value || 1;
                    const formData = new FormData();
                    formData.append('product_id', button.dataset.productId);
                    formData.append('quantity', quantity);

                    try {
                        await addToCart(formData, { successMessage: 'Taking you to checkout...' });
                        window.location.href = routes.checkout;
                    } catch (error) {
                        showToast(error.message, true);
                    }
                });
            });

            const updateCartItem = async (id, quantity) => {
                const response = await fetch(routes.cartUpdate(id), {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ quantity }),
                });
                let payload;
                try {
                    payload = await response.json();
                } catch (error) {
                    throw new Error('Unable to update cart.');
                }
                if (!response.ok) {
                    throw new Error(payload.message || 'Unable to update cart.');
                }
                updateCartCount(payload.summary.count);
                window.location.reload();
            };

            const removeCartItem = async (id) => {
                const response = await fetch(routes.cartDestroy(id), {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                let payload;
                try {
                    payload = await response.json();
                } catch (error) {
                    throw new Error('Unable to remove item.');
                }
                if (!response.ok) {
                    throw new Error(payload.message || 'Unable to remove item.');
                }
                updateCartCount(payload.summary.count);
                window.location.reload();
            };

            document.querySelectorAll('[data-cart-item]').forEach((container) => {
                const itemId = container.dataset.cartItem;
                const quantityInput = container.querySelector('[data-quantity-input]');

                container.querySelectorAll('[data-quantity-btn]').forEach((button) => {
                    button.addEventListener('click', () => {
                        const delta = parseInt(button.dataset.quantityBtn, 10);
                        const currentValue = parseInt(quantityInput.value, 10) || 1;
                        const nextValue = Math.max(1, currentValue + delta);
                        updateCartItem(itemId, nextValue).catch((error) => showToast(error.message, true));
                    });
                });

                quantityInput?.addEventListener('change', () => {
                    const nextValue = Math.max(1, parseInt(quantityInput.value, 10) || 1);
                    updateCartItem(itemId, nextValue).catch((error) => showToast(error.message, true));
                });

                container.querySelector('[data-remove-item]')?.addEventListener('click', () => {
                    removeCartItem(itemId).catch((error) => showToast(error.message, true));
                });
            });
        });
    </script>

    @stack('scripts')
    @stack('meta_pixel_events')
</body>
</html>
