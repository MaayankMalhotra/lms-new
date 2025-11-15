@extends('shop.admin.layout')

@section('title', 'Refund Requests')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-slate-900">Refund requests</h1>
        <a href="{{ route('refunds.create') }}" class="text-sm font-semibold text-[#007185]" target="_blank">Public refund form</a>
    </div>

    <div class="mt-6 overflow-x-auto rounded-lg bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-4 py-3 text-left">Ticket</th>
                    <th class="px-4 py-3 text-left">Order</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Amount</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Requested</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($refunds as $refund)
                    <tr>
                        <td class="px-4 py-3 font-semibold">#{{ $refund->id }}</td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-900">{{ $refund->order_number }}</p>
                            <p class="text-xs text-gray-500">{{ $refund->reason }}</p>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $refund->email }}</td>
                        <td class="px-4 py-3 font-semibold">₹{{ number_format($refund->amount ?? 0, 0) }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-{{ $refund->status === 'pending' ? 'amber' : ($refund->status === 'refunded' ? 'emerald' : 'gray') }}-100 px-3 py-1 text-xs font-semibold text-{{ $refund->status === 'pending' ? 'amber' : ($refund->status === 'refunded' ? 'emerald' : 'gray') }}-800">
                                {{ ucfirst($refund->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $refund->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.shop.refunds.show', $refund) }}" class="text-sm font-semibold text-[#007185]">Review</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $refunds->links() }}
    </div>
@endsection
