<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $news = News::query()
            ->with(['category', 'user'])
            ->when($search, fn($query) => $query->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%"))
            ->when($category, fn($query) => $query->whereHas('category', fn($q) => $q->where('name', $category)))
            ->latest()
            ->paginate(9);

        $recentNews = News::with(['category', 'user'])->latest()->take(3)->get();
        $categories = NewsCategory::pluck('name')->toArray();

        return view('website.news.index', compact('news', 'recentNews', 'categories'));
    }

    public function show($slug)
    {
        $news = News::with(['category', 'user'])->where('slug', $slug)->firstOrFail();
        return view('website.news.show', compact('news'));
    }

    public function showImage(News $news)
    {
        if (!$news->image) {
            abort(404);
        }

        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $news->image));
        return response($image)->header('Content-Type', 'image/jpeg');
    }

    public function adminIndex()
    {
        $news = News::with(['category', 'user'])->latest()->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        $categories = NewsCategory::all();
        if ($categories->isEmpty()) {
            return redirect()->route('admin.news-categories.create')
                ->with('warning', 'Please create at least one news category before creating a news article.');
        }
        return view('admin.news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required|exists:news_categories,id',
            'published_at' => 'required|date',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('image')) {
            $image = base64_encode(file_get_contents($request->file('image')->path()));
            $data['image'] = 'data:image/' . $request->file('image')->extension() . ';base64,' . $image;
        }

        News::create($data);

        return redirect()->route('admin.news.index')->with('success', 'News created successfully.');
    }

    public function edit(News $news)
    {
        $categories = NewsCategory::all();
        if ($categories->isEmpty()) {
            return redirect()->route('admin.news-categories.create')
                ->with('warning', 'Please create at least one news category before editing a news article.');
        }
        return view('admin.news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required|exists:news_categories,id',
            'published_at' => 'required|date',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = base64_encode(file_get_contents($request->file('image')->path()));
            $data['image'] = 'data:image/' . $request->file('image')->extension() . ';base64,' . $image;
        }

        $news->update($data);

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully.');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully.');
    }
}