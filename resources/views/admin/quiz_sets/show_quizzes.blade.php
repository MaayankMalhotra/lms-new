@extends('admin.layouts.app')

@section('title', 'Quizzes in Set')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <!-- Heading -->
        <h1 class="text-4xl font-bold text-gray-800 tracking-tight mb-4">
            Quizzes in "{{ $quizSet->title }}"
        </h1>
        <!-- Batch and Course Info -->
        <p class="text-gray-600 text-lg mb-8">
            <span class="font-semibold">Course:</span> {{ $quizSet->batch->course->name ?? 'N/A' }} |
            <span class="font-semibold">Batch:</span> {{ $quizSet->batch->start_date ?? 'N/A' }}
        </p>

        <!-- Quiz List -->
        @if($quizSet->quizzes->isEmpty())
            <div class="text-center text-gray-600 text-lg py-10">
                Is set mein abhi koi quiz nahi hai, abhi add karein!
                <a href="{{ route('admin.quiz_sets.add_quizzes', $quizSet->id) }}" 
                   class="block text-indigo-600 hover:text-indigo-800 mt-2">
                    Add Quizzes Now
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($quizSet->quizzes as $quiz)
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <!-- Question -->
                        <h2 class="text-2xl font-semibold text-indigo-600 mb-4">
                            {{ $quiz->question }}
                        </h2>
                        <!-- Options -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <p class="text-gray-600 {{ $quiz->correct_option == 1 ? 'font-bold text-green-600' : '' }}">
                                1. {{ $quiz->option_1 }} {{ $quiz->correct_option == 1 ? '(Correct)' : '' }}
                            </p>
                            <p class="text-gray-600 {{ $quiz->correct_option == 2 ? 'font-bold text-green-600' : '' }}">
                                2. {{ $quiz->option_2 }} {{ $quiz->correct_option == 2 ? '(Correct)' : '' }}
                            </p>
                            <p class="text-gray-600 {{ $quiz->correct_option == 3 ? 'font-bold text-green-600' : '' }}">
                                3. {{ $quiz->option_3 }} {{ $quiz->correct_option == 3 ? '(Correct)' : '' }}
                            </p>
                            <p class="text-gray-600 {{ $quiz->correct_option == 4 ? 'font-bold text-green-600' : '' }}">
                                4. {{ $quiz->option_4 }} {{ $quiz->correct_option == 4 ? '(Correct)' : '' }}
                            </p>
                        </div>
                        <!-- Actions -->
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" 
                               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-all duration-200">
                                Edit Quiz
                            </a>
                            <form action="{{ route('admin.quizzes.delete', $quiz->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-all duration-200"
                                        onclick="return confirm('Are you sure you want to delete this quiz?')">
                                    Delete Quiz
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Back Link -->
        <a href="{{ route('admin.quiz_sets') }}" 
           class="block text-indigo-600 hover:text-indigo-800 mt-6 text-center">
            Back to Quiz Sets
        </a>
    </div>
@endsection