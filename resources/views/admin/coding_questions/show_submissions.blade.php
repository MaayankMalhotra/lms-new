@extends('admin.layouts.app')

@section('title', 'Coding Test Submissions')

@section('content')
<div class="container mx-auto px-4 py-10">
    <!-- Header -->
    <h1 class="text-4xl font-bold text-gray-800 tracking-tight mb-8">
        Submissions for "{{ $codingQuestion->title }}"
    </h1>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md">
            {{ session('error') }}
        </div>
    @endif

    <!-- Question Details -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-2xl font-semibold text-indigo-600 mb-3">Question Details</h2>
        <p class="text-gray-600 mb-2">
            <span class="font-medium">Title:</span> {{ $codingQuestion->title }}
        </p>
        <p class="text-gray-600 mb-2">
            <span class="font-medium">Description:</span> {{ $codingQuestion->description }}
        </p>
        <p class="text-gray-600 mb-2">
            <span class="font-medium">Possible Solutions:</span>
            <ul class="list-disc pl-5">
                @foreach($codingQuestion->solutions as $solution)
                    <li>{{ $solution }}</li>
                @endforeach
            </ul>
        </p>
    </div>

    <!-- Submissions Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Student Name</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Submitted Solution</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Correct</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Submitted At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($codingQuestion->submissions as $submission)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $submission->user->name ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-700">
                            <code>{{ $submission->submitted_solution }}</code>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $submission->is_correct ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $submission->is_correct ? 'Correct' : 'Incorrect' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($submission->created_at)->format('F d, Y H:i') }}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($codingQuestion->submissions->isEmpty())
        <div class="p-12 text-center text-gray-500">
            <i class="fas fa-inbox text-4xl mb-4"></i>
            <p class="text-lg">No submissions for this coding question yet!</p>
        </div>
        @endif
    </div>
</div>
@endsection