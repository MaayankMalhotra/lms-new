@extends('admin.layouts.app')

@section('title', 'Quiz Sets Listing')

@section('content')
    <style>
        body {
            background: url("https://img.freepik.com/free-vector/education-pattern-background-doodle-style_53876-115365.jpg") no-repeat center center fixed;
            background-size: cover;
        }
    </style>

    <div class="container mx-auto px-4 py-10">
        <!-- Heading -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 tracking-tight">
                Your Quiz Sets
            </h1>
            <a href="{{ route('admin.quiz_sets.create') }}" 
               class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-indigo-700 transition-all duration-300">
                Create New Quiz Set
            </a>
        </div>

        <!-- Success/Error Message -->
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

        <!-- Quiz Sets Listing -->
        @if($quizSets->isEmpty())
            <div class="text-center text-gray-600 text-lg py-10">
               There is no quiz set yet, create a new one and get started!
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($quizSets as $set)
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <!-- Clickable Title -->
                        <a href="{{ route('admin.quiz_sets.show_quizzes', $set->id) }}">
                            <h2 class="text-2xl font-semibold text-indigo-600 mb-3 hover:text-indigo-800 transition-colors">
                                {{ $set->title }}
                            </h2>
                        </a>
                        <!-- Details -->
                        <p class="text-gray-600 mb-2">
                            <span class="font-medium">Total Quizzes:</span> {{ $set->total_quizzes }}
                        </p>
                        <p class="text-gray-500 text-sm">
                            <span class="font-medium">Created:</span> {{ $set->created_at->format('d M Y') }}
                        </p>
                        <!-- Actions -->
                        <div class="mt-4 flex space-x-3">
                            <a href="{{ route('admin.quiz_sets.add_quizzes', $set->id) }}"
                               class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-all duration-200">
                                Add Quizzes
                            </a>
                            <a href="{{ route('admin.quiz_sets.edit', $set->id) }}" 
                               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-all duration-200">
                                Edit
                            </a>
                            <form action="{{ route('admin.quiz_sets.delete', $set->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-all duration-200"
                                        onclick="return confirm('Are you sure you want to delete this set?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@section('styles')
    <style>
        .hover\:shadow-xl:hover {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        .transition-all {
            transition: all 0.3s ease-in-out;
        }
    </style>
@endsection
