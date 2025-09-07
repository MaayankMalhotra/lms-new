@extends('student.layouts.app')

@section('title', 'Take Quiz')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <h1 class="text-4xl font-bold text-gray-800 tracking-tight mb-8">
            Take Quiz: {{ $quizSet->title }}
        </h1>
        <p class="text-gray-600 text-lg mb-8">
            <span class="font-semibold">Course:</span> {{ $quizSet->batch->course->name ?? 'N/A' }} |
            <span class="font-semibold">Batch:</span> {{ $quizSet->batch->start_date ?? 'N/A' }}
        </p>

        <form action="{{ route('student.quiz_sets.submit', $quizSet->id) }}" method="POST">
            @csrf
            @foreach($quizSet->quizzes as $quiz)
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h2 class="text-2xl font-semibold text-indigo-600 mb-4">
                        {{ $quiz->question }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        @for($j = 1; $j <= 4; $j++)
                            <div>
                                <input type="radio" name="answers[{{ $quiz->id }}]" value="{{ $j }}"
                                       id="option_{{ $quiz->id }}_{{ $j }}" class="form-radio" required>
                                <label for="option_{{ $quiz->id }}_{{ $j }}" class="ml-2">
                                    {{ $quiz->{'option_' . $j} }}
                                </label>
                            </div>
                        @endfor
                    </div>
                    @error("answers.{$quiz->id}")
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
            <button type="submit"
                    class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-indigo-700 transition-all duration-300 w-full max-w-lg mx-auto block">
                Submit Quiz
            </button>
        </form>

        <a href="{{ route('student.quiz_sets') }}"
           class="block text-indigo-600 hover:text-indigo-800 mt-6 text-center">
            Back to Quiz Sets
        </a>
    </div>
@endsection