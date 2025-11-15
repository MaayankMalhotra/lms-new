<footer class="mt-12 bg-[#232f3e] text-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-6 py-10 md:grid-cols-3">
        <div>
            <p class="text-lg font-semibold">Aromea Market</p>
            <p class="mt-3 text-sm text-gray-300">
                Hyper-curated perfumes, sneakers and care drops built for premium D2C experiences.
            </p>
        </div>
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em]">Discover</p>
            <ul class="mt-3 space-y-2 text-sm">
                <li><a href="{{ route('shop.index') }}">Home</a></li>
                <li><a href="{{ route('shop.search', ['q' => 'launch']) }}">New Arrivals</a></li>
                <li><a href="{{ route('shop.search', ['q' => 'gift']) }}">Gift Sets</a></li>
            </ul>
        </div>
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em]">Support</p>
            <ul class="mt-3 space-y-2 text-sm">
                <li><a href="mailto:support@aromea.market">support@aromea.market</a></li>
                <li><a href="{{ route('cart.index') }}">Your Cart</a></li>
                <li><a href="{{ route('checkout.show') }}">Checkout</a></li>
            </ul>
        </div>
    </div>
    <div class="bg-[#131921] text-center text-xs uppercase tracking-[0.3em] py-4">
        &copy; {{ date('Y') }} Aromea Market · Built for rapid Meta ads experimentation
    </div>
</footer>
