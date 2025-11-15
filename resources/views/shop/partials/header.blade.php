@php
    $shopCategories = \App\Models\Category::orderBy('sort_order')->get();
    $cartSummary = app(\App\Services\CartService::class)->summary();
@endphp

<header class="fixed inset-x-0 top-0 z-50">
    <div class="bg-[#131921] text-white shadow-amazon">
        <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-2 text-sm">
            <a href="{{ route('shop.index') }}" class="flex items-center gap-1 text-xl font-bold tracking-tight text-white">
                <span class="text-white">amazon</span><span class="text-[#ff9900]">.style</span>
            </a>
            <div class="hidden lg:flex flex-col leading-tight text-xs">
                <span class="text-gray-300">Deliver to</span>
                <span class="font-semibold">India</span>
            </div>
            <form class="flex flex-1 rounded-md bg-white text-black" action="{{ route('shop.search') }}" method="GET">
                <select name="category" class="hidden sm:block rounded-l-md bg-gray-100 px-3 text-sm text-gray-600 focus:outline-none">
                    <option value="">All</option>
                    @foreach($shopCategories as $category)
                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="q" value="{{ request('q') }}"
                    class="flex-1 px-3 py-2 text-sm focus:outline-none"
                    placeholder="Search perfumes, sneakers, care kits...">
                <button type="submit" class="rounded-r-md bg-[#febd69] px-4 text-[#131921]">
                    <i class="fa fa-search"></i>
                </button>
            </form>
            <a href="{{ route('shop.index') }}" class="hidden sm:flex flex-col text-xs uppercase tracking-wide text-gray-200 hover:text-white">
                <span>Hello, Guest</span>
                <span class="text-sm font-semibold">Account & Lists</span>
            </a>
            <a href="{{ route('cart.index') }}" class="flex items-center gap-2 text-white hover:text-[#febd69]">
                <i class="fa fa-shopping-cart text-2xl"></i>
                <div class="text-xs leading-tight">
                    <span class="uppercase tracking-wide">Cart</span>
                    <div class="text-sm font-semibold"><span data-cart-count>{{ $cartSummary['count'] ?? 0 }}</span> items</div>
                </div>
            </a>
        </div>
    </div>
    <div class="bg-[#232f3e] text-sm text-white">
        <div class="mx-auto flex max-w-7xl items-center gap-4 overflow-x-auto px-4 py-2">
            <a href="{{ route('shop.index') }}" class="font-semibold hover:text-[#febd69]">All</a>
            @foreach($shopCategories as $category)
                <a href="{{ route('shop.category', $category->slug) }}" class="whitespace-nowrap hover:text-[#febd69]">
                    {{ $category->name }}
                </a>
            @endforeach
            <a href="{{ route('shop.search', ['q' => 'gift']) }}" class="whitespace-nowrap hover:text-[#febd69]">Gift Studio</a>
            <a href="{{ route('cart.index') }}" class="whitespace-nowrap hover:text-[#febd69]">Today's Deals</a>
        </div>
    </div>
</header>
