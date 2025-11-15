@extends('shop.layouts.app')

@section('title', 'Request a refund')

@section('content')
    <div class="mx-auto max-w-3xl px-4">
        <div class="rounded-3xl bg-white p-8 shadow-sm">
            <h1 class="text-2xl font-semibold text-slate-900">Need to request a refund?</h1>
            <p class="mt-2 text-sm text-gray-600">
                Enter your order number and the email you used at checkout. Our team will review and respond within 24 hours.
            </p>

            @if(session('status'))
                <div class="mt-4 rounded-md bg-emerald-100 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mt-4 rounded-md bg-red-100 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('refunds.store') }}" class="mt-6 space-y-4">
                @csrf
                <label class="block text-sm font-semibold text-slate-700">
                    Order number
                    <input type="text" name="order_number" value="{{ old('order_number') }}" class="mt-2 w-full rounded-md border border-gray-300 px-4 py-2" placeholder="TC-20241114-ABCD" required>
                </label>
                <label class="block text-sm font-semibold text-slate-700">
                    Email used for the order
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-2 w-full rounded-md border border-gray-300 px-4 py-2" required>
                </label>
                <label class="block text-sm font-semibold text-slate-700">
                    Refund reason
                    <input type="text" name="reason" value="{{ old('reason') }}" class="mt-2 w-full rounded-md border border-gray-300 px-4 py-2" placeholder="e.g. Product damaged" required>
                </label>
                <label class="block text-sm font-semibold text-slate-700">
                    Additional details (optional)
                    <textarea name="message" rows="4" class="mt-2 w-full rounded-md border border-gray-300 px-4 py-2" placeholder="Share anything that helps us resolve this quickly.">{{ old('message') }}</textarea>
                </label>
                <button type="submit" class="w-full rounded-full bg-[#ffd814] px-4 py-3 text-sm font-semibold text-[#111]">Submit refund request</button>
            </form>

            <p class="mt-6 text-xs text-gray-500">
                By submitting, you agree to our refund policy. Approved refunds are processed manually via Razorpay or the original method of payment.
            </p>
        </div>
    </div>
@endsection
