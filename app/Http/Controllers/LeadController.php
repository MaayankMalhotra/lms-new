<?php

namespace App\Http\Controllers;

use App\Models\LeadsStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class LeadController extends Controller
{
    public function store(Request $request)
    {
       // dd($request->all());
        $data = $request->validate([
            'name'  => ['required','string','max:150'],
            'email' => ['nullable','email','max:150'],
            'phone' => ['nullable','string','max:30'],
            // the hidden fields are optional; no validation constraints needed
            'page'        => ['nullable','string','max:255'],
            'utm_source'  => ['nullable','string','max:100'],
            'utm_medium'  => ['nullable','string','max:100'],
            'utm_campaign'=> ['nullable','string','max:150'],
            'utm_term'    => ['nullable','string','max:150'],
            'utm_content' => ['nullable','string','max:150'],
        ]);

        // Server-side meta capture (safer)
        $data['page']       = $data['page'] ?? url()->current();
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = Str::limit((string) $request->userAgent(), 5000, '');

        $lead = LeadsStudent::create($data);

        return response()->json([
            'status'  => 'ok',
            'message' => 'Lead saved successfully',
            'lead_id' => $lead->id,
        ]);
    }
    public function index()
{
    $leads = \App\Models\LeadsStudent::latest()->paginate(15);

    return view('admin.leads.index', compact('leads'));
}



public function sendEmail(Request $request, $id)
{
    $lead = \App\Models\LeadsStudent::findOrFail($id);

    $data = $request->validate([
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);

    Mail::raw(strip_tags($data['message']), function ($mail) use ($lead, $data) {
        $mail->to($lead->email)
             ->subject($data['subject']);
    });

    return response()->json(['status' => 'ok', 'message' => 'Email sent successfully to '.$lead->name]);
}
}
