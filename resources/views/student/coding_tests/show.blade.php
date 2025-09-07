@extends('admin.layouts.app')

@section('title', 'Solve Coding Question')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <!-- Header -->
        <h1 class="text-4xl font-bold text-gray-800 tracking-tight mb-8">
            {{ $codingQuestion->title }}
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
            <h2 class="text-2xl font-semibold text-indigo-600 mb-3">Description</h2>
            <p class="text-gray-600 mb-2">{{ $codingQuestion->description }}</p>
            <p class="text-gray-600 mb-2">
                <span class="font-medium">Possible Solutions:</span> {{ count($codingQuestion->solutions) }}
            </p>
        </div>

        <!-- Submission Form -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-indigo-600 mb-3">Submit Your Solution</h2>
            <form action="{{ route('student.coding_tests.submit', $codingQuestion->id) }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Solution Input -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-code mr-2 text-blue-400"></i>Your Solution
                        </label>
                        <textarea name="submitted_solution" required
                                  class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                  rows="5" placeholder="Enter your solution here">{{ old('submitted_solution') }}</textarea>
                        @error('submitted_solution')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8">
                        <button type="submit"
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200">
                            Submit Solution
                        </button>
                    </div>
                </div>
            </form>

            <!-- Feedback -->
            @if($submission)
                <div class="mt-6 p-4 rounded-lg {{ $submission->is_correct ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    <p class="font-semibold">
                        @if($submission->is_correct)
                            <i class="fas fa-check-circle mr-2"></i>Congratulations! Your solution is correct.
                        @else
                            <i class="fas fa-times-circle mr-2"></i>Sorry, your solution is incorrect. Please try again.
                        @endif
                    </p>
                    <p class="mt-2">Your submitted solution: <code>{{ $submission->submitted_solution }}</code></p>
                </div>
            @endif
        </div>
    </div>
@endsection