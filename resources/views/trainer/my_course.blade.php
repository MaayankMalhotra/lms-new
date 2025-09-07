@extends('admin.layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($trainer as $batch)
        <div class="bg-white shadow-md rounded-2xl overflow-hidden">
            <img src="{{ asset('storage/' . $batch->course->logo) }}" alt="{{ $batch->course->name }}" class="w-full h-48 object-cover">
            
            <div class="p-4">
                <h2 class="text-xl font-semibold text-gray-800">{{ $batch->course->name }}</h2>
                
                <div class="mt-4 text-sm text-gray-700">
                    <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($batch->start_date)->format('d M, Y') }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($batch->status) }}</p>
                    <p><strong>Duration:</strong> {{ $batch->duration }}</p>
                 
                </div>

            
            </div>
        </div>
    @endforeach
</div>

@endsection