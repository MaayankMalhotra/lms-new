<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductAdminRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProductAdminController extends Controller
{
    public function index(): View
    {
        $products = Product::with('categories')->latest()->paginate(15);

        return view('shop.admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('shop.admin.products.create', [
            'product' => new Product(),
            'categories' => $categories,
        ]);
    }

    public function store(ProductAdminRequest $request): RedirectResponse
    {
        $product = Product::create($this->payload($request));
        $product->categories()->sync($request->input('categories', []));

        return redirect()->route('admin.shop.products.index')
            ->with('status', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();
        $product->load('categories');

        return view('shop.admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductAdminRequest $request, Product $product): RedirectResponse
    {
        $product->update($this->payload($request));
        $product->categories()->sync($request->input('categories', []));

        return redirect()->route('admin.shop.products.index')
            ->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.shop.products.index')
            ->with('status', 'Product deleted.');
    }

    protected function payload(ProductAdminRequest $request): array
    {
        $data = $request->validated();

        $data['is_featured'] = $request->boolean('is_featured');
        $data['specifications'] = $request->filled('specifications')
            ? json_decode($request->input('specifications'), true) ?: null
            : null;

        if ($request->hasFile('hero_image_file')) {
            $path = $request->file('hero_image_file')->store('products', 'public');
            $data['hero_image'] = 'storage/' . $path;
        }

        unset($data['hero_image_file']);

        return $data;
    }
}
