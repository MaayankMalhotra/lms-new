@extends('admin.layouts.app')

@section('content')
    <h1>Create Available Slots</h1>
    <form method="POST" action="{{ route('teacher.slots') }}">
        @csrf
        <input type="datetime-local" name="start_time" required>
        <input type="number" name="duration_minutes" placeholder="Duration (min)" required>
        <button type="submit">Create Slot</button>
    </form>

    <h2>Your Slots</h2>
    <ul>
        @foreach($slots as $slot)
            <li>{{ $slot->start_time }} ({{ $slot->duration_minutes }} min) - {{ $slot->is_booked ? 'Booked' : 'Available' }}</li>
        @endforeach
    </ul>
@endsection