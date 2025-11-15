@extends('shop.admin.layout')

@section('title', 'Manage Products')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-900">Products</h1>
        <a href="{{ route('admin.shop.products.create') }}" class="rounded-md bg-[#ffd814] px-4 py-2 text-sm font-semibold text-[#111]">
            Add product
        </a>
    </div>

    <div class="mt-6 overflow-x-auto rounded-lg bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Slug</th>
                    <th class="px-4 py-3 text-left">Price</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Categories</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-gray-700">
                @foreach($products as $product)
                    <tr>
                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $product->slug }}</td>
                        <td class="px-4 py-3 font-semibold">₹{{ number_format($product->price, 0) }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-{{ $product->status === 'published' ? 'emerald' : 'amber' }}-100 px-3 py-1 text-xs font-semibold text-{{ $product->status === 'published' ? 'emerald' : 'amber' }}-800">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            {{ $product->categories->pluck('name')->join(', ') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex gap-2">
                                <a href="{{ route('admin.shop.products.edit', $product) }}" class="text-sm font-semibold text-[#007185]">Edit</a>
                                <form method="POST" action="{{ route('admin.shop.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-semibold text-red-500">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
@endsection
