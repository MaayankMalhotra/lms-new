<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Website')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="manifest" href="/manifest.json">   
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  
<!-- Add Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
  
    <!-- Alpine Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

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
</head>
<body>
    {{-- Include the header --}}
    @include('website.partials.header')

    <main class="pt-10">
        @yield('content')
    </main>

    {{-- Include the footer --}}
    @include('website.partials.footer')

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
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
        toast.className = 'fixed bottom-6 right-6 z-[9999] hidden rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-xl';
        document.body.appendChild(toast);

        function showToast(message, isError = false) {
            toast.textContent = message;
            toast.style.backgroundColor = isError ? '#dc2626' : '#0f172a';
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 2600);
        }

        function updateCartCount(count) {
            document.querySelectorAll('[data-cart-count]').forEach((el) => el.textContent = count);
        }

        async function addToCart(formData, options = {}) {
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
            showToast(options.successMessage || 'Product added to cart.');

            if (typeof fbq !== 'undefined') {
                fbq('track', 'AddToCart', {
                    value: payload.summary.subtotal,
                    currency: payload.summary.currency,
                });
            }

            return payload.summary;
        }

        document.addEventListener('submit', (event) => {
            const form = event.target.closest('form[data-add-to-cart]');
            if (!form) {
                return;
            }

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
                    await addToCart(formData, { successMessage: 'Redirecting to checkout...' });
                    window.location.href = routes.checkout;
                } catch (error) {
                    showToast(error.message, true);
                }
            });
        });

        async function updateCartItem(id, quantity) {
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
        }

        async function removeCartItem(id) {
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
        }

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
