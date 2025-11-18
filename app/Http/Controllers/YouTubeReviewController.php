<?php

namespace App\Http\Controllers;

use App\Models\YouTubeReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $thumbnailPath = $request->file('thumbnail')->store('youtube-thumbnails', 'public');

        YouTubeReview::create([
            'title' => $request->title,
            'description' => $request->description,
            'video_id' => $request->video_id,
            // Store the public URL so existing cards keep working
            'thumbnail_url' => Storage::url($thumbnailPath),
        ]);  // Insert new review into the database

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
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $review = YouTubeReview::findOrFail($id);
        $data = $request->only(['title', 'description', 'video_id']);

        // Replace thumbnail only if a new file is uploaded
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('youtube-thumbnails', 'public');
            $data['thumbnail_url'] = Storage::url($thumbnailPath);
        }

        $review->update($data);  // Update review in the database

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
