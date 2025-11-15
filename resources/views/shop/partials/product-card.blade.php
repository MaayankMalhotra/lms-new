@props(['product'])

<div class="rounded-lg bg-white shadow-sm ring-1 ring-black/5">
    <a href="{{ route('shop.product', $product->slug) }}" class="block">
        <img src="{{ $product->hero_image_url ?? asset('images/catalog/placeholder.jpg') }}"
            alt="{{ $product->name }}"
            class="h-56 w-full rounded-t-lg object-cover">
    </a>
    <div class="space-y-2 px-4 py-4">
        <p class="text-xs uppercase tracking-wide text-gray-500">
            {{ $product->categories->pluck('name')->first() ?? 'Collection' }}
        </p>
        <a href="{{ route('shop.product', $product->slug) }}"
            class="text-base font-semibold text-slate-900 hover:text-[#e47911]">
            {{ $product->name }}
        </a>
        <p class="text-sm text-gray-600 line-clamp-2">{{ $product->short_description }}</p>
        <div class="flex items-center justify-between pt-2">
            <div>
                <p class="text-xl font-semibold text-slate-900">₹{{ number_format($product->price, 0) }}</p>
                @if($product->compare_at_price)
                    <p class="text-xs text-gray-400 line-through">₹{{ number_format($product->compare_at_price, 0) }}</p>
                @endif
            </div>
            <form method="POST" action="{{ route('cart.store') }}" data-add-to-cart>
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit"
                    class="rounded-full border border-[#131921] px-4 py-1 text-xs font-semibold text-[#131921] hover:bg-[#131921] hover:text-white">
                    Add to cart
                </button>
            </form>
        </div>
    </div>
</div>
