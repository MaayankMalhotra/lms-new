@extends('admin.layouts.app')

@section('title', 'My Quiz Sets')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <h1 class="text-4xl font-bold text-gray-800 tracking-tight mb-8">
            My Quiz Sets
        </h1>

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

        @if($quizSets->isEmpty())
            <div class="text-center text-gray-600 text-lg py-10">
                Aapke batch mein abhi koi quiz set nahi hai!
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($quizSets as $set)
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                        <h2 class="text-2xl font-semibold text-indigo-600 mb-3">
                            {{ $set->title }}
                        </h2>
                        <p class="text-gray-600 mb-2">
                            <span class="font-medium">Total Quizzes:</span> {{ $set->total_quizzes }}
                        </p>
                        <p class="text-gray-600 mb-2">
                            <span class="font-medium">Course:</span> {{ $set->batch->course->name ?? 'N/A' }}
                        </p>
                        <p class="text-gray-600 mb-4">
                            <span class="font-medium">Batch:</span> {{ $set->batch->start_date ?? 'N/A' }}
                        </p>
                        @if($student->studentQuizSetAttempts->where('quiz_set_id', $set->id)->first())
                            <p class="text-green-600 font-semibold">
                                Your Score: {{ $student->studentQuizSetAttempts->where('quiz_set_id', $set->id)->first()->score }} / {{ $set->total_quizzes }}
                            </p>
                        @else
                            <a href="{{ route('student.quiz_sets.take', $set->id) }}"
                               class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-all duration-200">
                                Take Quiz
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection