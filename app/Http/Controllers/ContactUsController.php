<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function index(){
        return view('website.contact_us');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_type' => 'required|in:student,employee',
            'contact_number' => 'required|string|max:20',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'graduation_year' => 'nullable|string|max:10',
            'department' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);

        ContactUs::create($validated);

        return redirect()->back()->with('success', 'Your message has been submitted successfully!');
    }
    // Show all contact inquiries
    public function contactindex(Request $request)
{
    $query = ContactUs::query();
    if ($request->filled('user_type')) {
        $query->where('user_type', $request->user_type);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    $contacts = $query->latest()
        ->paginate(10)
        ->appends($request->only(['user_type','status', 'date']));

    return view('admin.contactus.index', compact('contacts'));
}



    public function resolve($id)
{
    // Fetch the contact entry
    $contact = ContactUs::find($id);

    if (!$contact) {
        return redirect()->route('admin.contactus.index')->with('error', 'Contact not found.');
    }

    // Update the status to "resolved" (1)
    $contact->status = 1;
    $contact->save();

    return redirect()->route('admin.contactus.index')->with('success', 'Contact marked as resolved.');
}

}
