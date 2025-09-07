@extends('admin.layouts.app')

@section('title', 'Edit Quiz')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <h1 class="text-4xl font-bold text-gray-800 tracking-tight mb-8">
            Edit Quiz in "{{ $quiz->quizSet->title }}"
        </h1>

        <div class="bg-white rounded-xl shadow-lg p-8 max-w-lg mx-auto">
            <form action="{{ route('admin.quizzes.update', $quiz->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Question
                    </label>
                    <textarea name="question" 
                              class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" 
                              rows="3" required>{{ $quiz->question }}</textarea>
                    @error('question')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @for($j = 0; $j < 4; $j++)
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">
                            Option {{ $j + 1 }}
                        </label>
                        <input type="text" name="option_{{ $j + 1 }}" 
                               value="{{ $quiz->{'option_' . ($j + 1)} }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" 
                               required>
                        @error("option_$j")
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endfor

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Correct Option (1-4)
                    </label>
                    <input type="number" name="correct_option" 
                           value="{{ $quiz->correct_option }}"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" 
                           min="1" max="4" required>
                    @error('correct_option')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-indigo-700 transition-all duration-300 w-full">
                    Update Quiz
                </button>
            </form>
        </div>

        <a href="{{ route('admin.quiz_sets.show_quizzes', $quiz->quiz_set_id) }}" 
           class="block text-indigo-600 hover:text-indigo-800 mt-6 text-center">
            Back to Quizzes
        </a>
    </div>
@endsection