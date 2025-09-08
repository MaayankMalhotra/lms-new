<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ResumeController extends Controller
{
    public function index()
    {
        return view('resume.index');
    }

    public function store(Request $request)
    {
        // 1. Save to DB
        $leadId = DB::table('maayank_malhotra_leads_through_resume')->insertGetId([
            'name'    => $request->input('name'),
            'email'   => $request->input('email'),
            'phone'   => $request->input('phone'),
            'message' => $request->input('message'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Send Email to Admin (You)
        Mail::raw("New Lead from Resume Page\n\nName: {$request->name}\nEmail: {$request->email}\nPhone: {$request->phone}\nMessage: {$request->message}", function ($message) {
            $message->to('maayankmalhotra095@gmail.com')
                    ->subject('🚀 New Lead from Resume Page');
        });

        // 3. Send Confirmation Email to User
        Mail::raw("Hi {$request->name},\n\nThanks for contacting me! I will get back to you shortly.\n\n- Maayank Malhotra", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('✅ Thanks for contacting Maayank Malhotra');
        });

        return back()->with('success', 'Thanks for reaching out! Confirmation email has been sent.');
    }
}
