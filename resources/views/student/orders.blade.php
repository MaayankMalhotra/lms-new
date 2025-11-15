@extends('admin.layouts.app')

@section('content')
<div class="px-3 space-y-6">
    <div class="bg-white rounded-2xl shadow p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Student Panel</p>
            <h1 class="text-2xl font-semibold text-gray-900 mt-1">My Orders</h1>
            <p class="text-sm text-gray-500">Track every course you have enrolled in along with its schedule.</p>
        </div>
        <div class="text-sm text-gray-600">Total orders
            <span class="ml-2 inline-flex items-center justify-center text-base font-semibold text-gray-900 bg-gray-100 rounded-full px-3 py-1">
                {{ $orders->count() }}
            </span>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl shadow p-10 text-center text-gray-500">
            <i class="fas fa-box-open text-4xl mb-3"></i>
            <p>No orders found. Once you enroll in a course the details will appear here.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-2xl shadow p-6 space-y-4">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Placed on {{ $order->ordered_on }}</p>
                            <h2 class="text-xl font-semibold text-gray-900 mt-1">{{ $order->course_name }}</h2>
                            <p class="text-sm text-gray-600">Batch: {{ $order->batch_name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-gray-900">₹{{ number_format($order->display_amount, 2) }}</p>
                            <p class="text-sm text-gray-500">{{ strtoupper($order->payment_method ?? 'NA') }}</p>
                            <span class="inline-flex mt-2 items-center px-3 py-1 rounded-full text-xs font-semibold {{ $order->payment_status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                Payment {{ ucfirst($order->payment_status ?? 'pending') }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm text-gray-600">
                        <div>
                            <p class="text-xs uppercase text-gray-400">Start date</p>
                            <p class="text-gray-900 font-medium">{{ $order->start_date_formatted ?? 'TBD' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">End date</p>
                            <p class="text-gray-900 font-medium">{{ $order->end_date_formatted ?? 'TBD' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">Duration</p>
                            <p class="text-gray-900 font-medium">{{ $order->duration_label ?? 'Not set' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">Status</p>
                            <p class="text-gray-900 font-medium">{{ ucfirst($order->enrollment_status ?? 'pending') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
