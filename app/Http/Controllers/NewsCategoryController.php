<?php

namespace App\Http\Controllers;

use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsCategoryController extends Controller
{
    public function index()
    {
        $categories = NewsCategory::latest()->paginate(10);
        return view('admin.news-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.news-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:news_categories,name',
        ]);

        NewsCategory::create($request->all());

        return redirect()->route('admin.news-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(NewsCategory $category)
    {
        return view('admin.news-categories.edit', compact('category'));
    }

    public function update(Request $request, NewsCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:news_categories,name,' . $category->id,
        ]);

        $category->update($request->all());

        return redirect()->route('admin.news-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(NewsCategory $category)
    {
        if ($category->news()->count() > 0) {
            return redirect()->route('admin.news-categories.index')->with('error', 'Cannot delete category with associated news.');
        }

        $category->delete();
        return redirect()->route('admin.news-categories.index')->with('success', 'Category deleted successfully.');
    }
}