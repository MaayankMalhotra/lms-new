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
        'name'       => $request->input('name'),
        'email'      => $request->input('email'),
        'phone'      => $request->input('phone'),
        'message'    => $request->input('message'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // ===============================
    // 2. Email to Admin
    // ===============================
    $adminHtml = "
    <div style=\"font-family: Arial, sans-serif; color:#333; background: url('https://www.transparenttextures.com/patterns/cubes.png'); background-size: cover; padding:20px;\">
        <div style=\"background:#ffffffd9; padding:20px; border-radius:8px;\">
            <h2 style='color:#2c3e50;'>🚀 New Lead Received</h2>
            <p>You just received a new enquiry from your resume page.</p>

            <table style='border-collapse: collapse; width:100%; margin-top:10px;'>
                <tr>
                    <td style='padding:8px; border:1px solid #ddd;'><strong>Name</strong></td>
                    <td style='padding:8px; border:1px solid #ddd;'>{$request->name}</td>
                </tr>
                <tr>
                    <td style='padding:8px; border:1px solid #ddd;'><strong>Email</strong></td>
                    <td style='padding:8px; border:1px solid #ddd;'>{$request->email}</td>
                </tr>
                <tr>
                    <td style='padding:8px; border:1px solid #ddd;'><strong>Phone</strong></td>
                    <td style='padding:8px; border:1px solid #ddd;'>{$request->phone}</td>
                </tr>
                <tr>
                    <td style='padding:8px; border:1px solid #ddd; vertical-align:top;'><strong>Message</strong></td>
                    <td style='padding:8px; border:1px solid #ddd;'>{$request->message}</td>
                </tr>
            </table>

            <p style='margin-top:20px; font-size:12px; color:#888;'>
                📌 This lead was submitted on ".now()->format('d M Y, h:i A')."
            </p>
        </div>
    </div>
    ";

    Mail::html($adminHtml, function ($message) {
        $message->to('maayankmalhotra095@gmail.com')
                ->subject('🚀 New Lead from Resume Page');
    });

    // ===============================
    // 3. Confirmation Email to User
    // ===============================
    $userHtml = "
    <div style=\"font-family: Arial, sans-serif; color:#333; background: url('https://www.transparenttextures.com/patterns/diagonal-stripes.png'); background-size: cover; padding:20px;\">
        <div style=\"background:#ffffffd9; padding:20px; border-radius:8px;\">
            <h2 style='color:#27ae60;'>Hi {$request->name}, 👋</h2>
            <p>Thanks for reaching out through my portfolio page.</p>
            <p>I have received your details and will get back to you as soon as possible.</p>

            <div style='margin:20px 0; padding:15px; background:#f4f4f4; border-left:4px solid #27ae60;'>
                <p style='margin:0;'><strong>📋 Your Submitted Details:</strong></p>
                <p style='margin:4px 0;'>✉️ Email: {$request->email}</p>
                <p style='margin:4px 0;'>📞 Phone: {$request->phone}</p>
                <p style='margin:4px 0;'>💬 Message: {$request->message}</p>
            </div>

            <p style='margin-top:20px;'>Best regards,<br><strong>Maayank Malhotra</strong></p>
            <hr>
            <p style='font-size:12px; color:#888;'>This is an automated confirmation email, please do not reply.</p>
        </div>
    </div>
    ";

    Mail::html($userHtml, function ($message) use ($request) {
        $message->to($request->email)
                ->subject('✅ Thanks for contacting Maayank Malhotra');
    });

    return back()->with('success', 'Thanks for reaching out! Confirmation email has been sent.');
}


}
