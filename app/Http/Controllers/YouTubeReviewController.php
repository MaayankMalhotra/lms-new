<?php

namespace App\Http\Controllers;

use App\Models\YouTubeReview;
use Illuminate\Http\Request;

class YouTubeReviewController extends Controller
{
    // Show all YouTube reviews
    public function index()
    {
        $reviews = YouTubeReview::all();
        return view('admin.youtube_reviews.index', compact('reviews'));  // Return view with all reviews
    }

    // Show form to create a new review
    public function create()
    {
        return view('admin.youtube_reviews.create');  // Show the form for adding a new review
    }

    // Store the newly created review
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_id' => 'required|string|max:255',
            'thumbnail_url' => 'required|url',
        ]);

        YouTubeReview::create($request->all());  // Insert new review into the database

        return redirect()->route('admin.youtubereview.index')->with('success', 'YouTube review added successfully!');
    }

    // Show form to edit an existing review
    public function edit($id)
    {
        $review = YouTubeReview::findOrFail($id);
        return view('admin.youtube_reviews.edit', compact('review'));  // Show edit form
    }

    // Update an existing review
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_id' => 'required|string|max:255',
            'thumbnail_url' => 'required|url',
        ]);

        $review = YouTubeReview::findOrFail($id);
        $review->update($request->all());  // Update review in the database

        return redirect()->route('admin.youtubereview.index')->with('success', 'YouTube review updated successfully!');
    }

    // Delete a review
    public function destroy($id)
    {
        $review = YouTubeReview::findOrFail($id);
        $review->delete();  // Delete the review from the database

        return redirect()->route('admin.youtubereview.index')->with('success', 'YouTube review deleted successfully!');
    }
}
