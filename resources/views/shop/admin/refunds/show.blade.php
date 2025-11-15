@extends('shop.admin.layout')

@section('title', 'Refund #' . $refund->id)

@section('content')
    <div class="space-y-6">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-gray-500">Refund ticket</p>
                    <h1 class="text-2xl font-semibold text-slate-900">#{{ $refund->id }} · {{ $refund->order_number }}</h1>
                    <p class="text-sm text-gray-500">Customer: {{ $refund->email }}</p>
                </div>
                <form method="POST" action="{{ route('admin.shop.refunds.update', $refund) }}" class="flex flex-col gap-3 md:flex-row md:items-center">
                    @csrf
                    @method('PUT')
                    <select name="status" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                        @foreach(['pending','approved','rejected','refunded'] as $status)
                            <option value="{{ $status }}" @selected($refund->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <textarea name="admin_notes" rows="2" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm" placeholder="Internal notes...">{{ old('admin_notes', $refund->admin_notes) }}</textarea>
                    <button type="submit" class="rounded-md bg-[#ffd814] px-4 py-2 text-sm font-semibold text-[#111]">Update</button>
                </form>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-xs uppercase tracking-[0.3em] text-gray-500">Customer reason</p>
                <p class="mt-2 font-semibold text-slate-900">{{ $refund->reason }}</p>
                <p class="mt-2 text-sm text-gray-600">{{ $refund->customer_message ?? '—' }}</p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-xs uppercase tracking-[0.3em] text-gray-500">Order summary</p>
                <ul class="mt-2 text-sm text-gray-600">
                    <li>Order total: ₹{{ number_format($refund->order->grand_total ?? $refund->amount ?? 0, 0) }}</li>
                    <li>Payment status: {{ ucfirst($refund->order->payment_status ?? 'n/a') }}</li>
                    <li>Placed at: {{ optional($refund->order->placed_at)->format('d M Y H:i') ?? 'n/a' }}</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
