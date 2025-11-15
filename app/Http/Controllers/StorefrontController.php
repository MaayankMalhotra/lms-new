<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function index(): View
    {
        $featuredCategories = Category::featured()->withCount('products')->get();

        $featuredProducts = Product::with(['images', 'categories'])
            ->featured()
            ->latest('published_at')
            ->take(6)
            ->get();

        $newArrivals = Product::with(['images', 'categories'])
            ->published()
            ->latest('published_at')
            ->take(6)
            ->get();

        return view('shop.index', compact(
            'featuredCategories',
            'featuredProducts',
            'newArrivals'
        ));
    }

    public function category(Category $category): View
    {
        $category->load(['products' => fn ($query) => $query->with(['images', 'categories'])->published()]);

        $filters = [
            'price_min' => request('price_min'),
            'price_max' => request('price_max'),
        ];

        $products = $category->products()
            ->when($filters['price_min'], fn ($q, $min) => $q->where('price', '>=', $min))
            ->when($filters['price_max'], fn ($q, $max) => $q->where('price', '<=', $max))
            ->paginate(12)
            ->withQueryString();

        return view('shop.category', compact('category', 'products'));
    }

    public function product(Product $product): View
    {
        abort_unless($product->status === 'published', 404);

        $product->load('images', 'categories');

        $related = Product::with(['images', 'categories'])
            ->published()
            ->whereHas('categories', function ($query) use ($product) {
                return $query->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('shop.product', compact('product', 'related'));
    }

    public function search(Request $request): View
    {
        $query = $request->input('q');
        $categoryFilter = $request->input('category');

        $products = Product::with(['images', 'categories'])
            ->published()
            ->when($categoryFilter, function ($builder, $slug) {
                $builder->whereHas('categories', fn ($category) => $category->where('slug', $slug));
            })
            ->when($query, function ($builder, $term) {
                $builder->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', "%{$term}%")
                        ->orWhere('short_description', 'like', "%{$term}%");
                });
            })
            ->paginate(12)
            ->withQueryString();

        return view('shop.search', [
            'query' => $query,
            'categoryFilter' => $categoryFilter,
            'products' => $products,
        ]);
    }
}
