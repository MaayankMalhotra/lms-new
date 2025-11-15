<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RefundAdminController extends Controller
{
    public function index(): View
    {
        $refunds = RefundRequest::with('order')->latest()->paginate(20);

        return view('shop.admin.refunds.index', compact('refunds'));
    }

    public function show(RefundRequest $refund): View
    {
        $refund->load('order');

        return view('shop.admin.refunds.show', compact('refund'));
    }

    public function update(Request $request, RefundRequest $refund): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected,refunded'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $refund->update([
            'status' => $data['status'],
            'admin_notes' => $data['admin_notes'],
            'processed_at' => in_array($data['status'], ['approved', 'refunded', 'rejected'])
                ? now()
                : null,
        ]);

        return redirect()->route('admin.shop.refunds.show', $refund)
            ->with('status', 'Refund updated.');
    }
}
