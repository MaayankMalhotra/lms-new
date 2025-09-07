@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Join Your Interview</h1>

        @if (isset($error))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline"> {{ $error }}</span>
            </div>
        @else
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline"> Redirecting to your meeting...</span>
            </div>
            <meta http-equiv="refresh" content="2;url={{ $booking->meeting_link ?? '#' }}">
        @endif
    </div>
@endsection