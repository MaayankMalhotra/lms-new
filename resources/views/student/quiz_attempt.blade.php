@extends('admin.layouts.app')

@section('title', 'Quiz Attempt')

@section('content')
<style>
    .quiz-card {
        transition: transform 0.2s;
    }
    .quiz-card:hover {
        transform: translateY(-5px);
    }
    .option-badge {
        display: inline-block;
        padding: 8px 12px;
        margin: 4px;
        border-radius: 20px;
        font-size: 0.9rem;
    }
    .option-correct {
        background-color: #28a745;
        color: white;
    }
    .option-incorrect {
        background-color: #dc3545;
        color: white;
    }
    .option-neutral {
        background-color: #e9ecef;
        color: #333;
    }
</style>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">Quiz Attempt: {{ $attempt->quizSet->title }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-4" role="alert">
                            <strong>Tera Score:</strong> {{ $attempt->score }} / {{ $attempt->quizSet->total_quizzes }}
                        </div>

                        @foreach ($attempt->quizSet->quizzes as $index => $quiz)
                            <div class="card quiz-card mb-4 border-0 shadow">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0 font-weight-bold">Sawaal {{ $index + 1 }}: {{ $quiz->question }}</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $studentAnswer = $attempt->answers->where('quiz_id', $quiz->id)->first();
                                        $isCorrect = $studentAnswer && $studentAnswer->student_answer == $quiz->correct_option;
                                    @endphp

                                    <p class="mb-2">
                                        <strong>Tera Jawab:</strong> 
                                        @if ($studentAnswer)
                                            <span class="badge {{ $isCorrect ? 'badge-success' : 'badge-danger' }} p-2">
                                                {{ $quiz->{'option_' . $studentAnswer->student_answer} }}
                                                ({{ $isCorrect ? 'Sahi' : 'Galat' }})
                                            </span>
                                        @else
                                            <span class="badge badge-warning p-2">Tune iska jawab nahi diya</span>
                                        @endif
                                    </p>
                                    <p class="mb-4">
                                        <strong>Sahi Jawab:</strong> 
                                        <span class="badge badge-success p-2">{{ $quiz->{'option_' . $quiz->correct_option} }}</span>
                                    </p>

                                    <div class="mt-3">
                                        <p class="font-weight-bold mb-2">Options:</p>
                                        <div>
                                            @for ($i = 1; $i <= 4; $i++)
                                                <span class="option-badge {{ $quiz->correct_option == $i ? 'option-correct' : ($studentAnswer && $studentAnswer->student_answer == $i && !$isCorrect ? 'option-incorrect' : 'option-neutral') }}">
                                                    {{ $quiz->{'option_' . $i} }}
                                                    @if ($studentAnswer && $studentAnswer->student_answer == $i)
                                                        <small>(Tera choice)</small>
                                                    @endif
                                                </span>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <a href="{{ route('student.quiz_sets') }}" class="btn btn-primary btn-lg mt-4">
                            Wapas Quiz Sets Pe Jao
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection