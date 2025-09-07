<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.testimonial.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonial.create');
    }

    public function store(Request $request)
{
    // dd($request->all());
    $request->validate([
        'name' => 'required|string|max:255',
        'department' => 'nullable|string|max:255',
        'position' => 'required|string|max:255',
        'company' => 'nullable|string|max:255',
        'rating' => 'required|integer|min:1|max:5',
        'image_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $data = $request->only(['name', 'department', 'position', 'company', 'rating']);

    // Handle file upload if provided
    if ($request->hasFile('image_url')) {
        $imagePath = $request->file('image_url')->store('uploads/testimonials', 'public');
        $data['image_url'] = 'storage/' . $imagePath;
    }

    Testimonial::create($data);

    return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created successfully.');
}


    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonial.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
{
    $request->validate([
        'name' => 'required|string',
        'department' => 'required|string',
        'position' => 'required|string',
        'company' => 'required|string',
        'rating' => 'required|integer|min:1|max:5',
        'image_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Optional file validation
    ]);

    // Update the testimonial data
    $testimonial->update([
        'name' => $request->name,
        'department' => $request->department,
        'position' => $request->position,
        'company' => $request->company,
        'rating' => $request->rating,
    ]);

    // Handle the image upload if it exists
    if ($request->hasFile('image_url')) {
        $imagePath = $request->file('image_url')->store('testimonials', 'public');
        $testimonial->update(['image_url' => $imagePath]);
    }

    return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated successfully.');
}

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted successfully.');
    }
}
