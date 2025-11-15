@extends('shop.admin.layout')

@section('title', 'Edit ' . $product->name)

@section('content')
    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold text-slate-900">Edit product</h1>
        <p class="text-sm text-gray-500">Update pricing, inventory, and merchandising details.</p>

        <form method="POST" action="{{ route('admin.shop.products.update', $product) }}" class="mt-6 space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('shop.admin.products.form')
            <div class="flex gap-4">
                <button type="submit" class="rounded-md bg-[#ffd814] px-4 py-2 text-sm font-semibold text-[#111]">Update product</button>
                <a href="{{ route('admin.shop.products.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700">Cancel</a>
            </div>
        </form>
    </div>
@endsection
