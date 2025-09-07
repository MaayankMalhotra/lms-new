<?php

namespace App\Http\Controllers;

use App\Models\EventCategory;
use Illuminate\Http\Request;

class EventCategoryController extends Controller
{
    public function index()
    {
        $categories = EventCategory::latest()->paginate(10);
        return view('admin.event-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.event-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:event_categories,name',
        ]);

        EventCategory::create($request->all());

        return redirect()->route('admin.event-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(EventCategory $category)
    {
        return view('admin.event-categories.edit', compact('category'));
    }

    public function update(Request $request, EventCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:event_categories,name,' . $category->id,
        ]);

        $category->update($request->all());

        return redirect()->route('admin.event-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(EventCategory $category)
    {
        if ($category->events()->count() > 0) {
            return redirect()->route('admin.event-categories.index')->with('error', 'Cannot delete category with associated events.');
        }

        $category->delete();
        return redirect()->route('admin.event-categories.index')->with('success', 'Category deleted successfully.');
    }
}