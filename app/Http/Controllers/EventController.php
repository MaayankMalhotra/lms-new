<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $location = $request->input('location');

        $events = Event::query()
            ->when($search, fn($query) => $query->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%"))
            ->when($category, fn($query) => $query->whereHas('category', fn($q) => $q->where('name', $category)))
            ->when($location, fn($query) => $query->where('location', $location))
            ->latest()
            ->paginate(9);

        $recentEvents = Event::latest()->take(3)->get();

        $categories = EventCategory::pluck('name')->toArray();
        $locations = Event::select('location')->distinct()->pluck('location')->toArray();

        return view('website.events.index', compact('events', 'recentEvents', 'categories', 'locations'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        return view('website.events.show', compact('event'));
    }

    public function enroll(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

   
        EventEnrollment::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'comments' => $request->comments,
        ]);

        return redirect()->route('events.show', $event->slug)->with('success', 'Successfully enrolled in the event!');
    }

    public function adminIndex()
    {
        $events = Event::latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $categories = EventCategory::all();
        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required|exists:event_categories,id',
            'location' => 'required|string',
            'event_date' => 'required|date',
            'event_time' => 'required',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('image')) {
            $image = base64_encode(file_get_contents($request->file('image')->path()));
            $data['image'] = 'data:image/' . $request->file('image')->extension() . ';base64,' . $image;
        }

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        $categories = EventCategory::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required|exists:event_categories,id',
            'location' => 'required|string',
            'event_date' => 'required|date',
            'event_time' => 'required',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = base64_encode(file_get_contents($request->file('image')->path()));
            $data['image'] = 'data:image/' . $request->file('image')->extension() . ';base64,' . $image;
        }

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }

    public function enrollments()
    {
        $enrollments = EventEnrollment::latest()->paginate(10);
        return view('admin.events.enrollments', compact('enrollments'));
    }
}