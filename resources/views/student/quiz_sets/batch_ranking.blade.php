@extends('admin.layouts.app')

@section('content')
<!-- Include Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* Custom Styles */
    .container {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
    }
    .welcome-message {
        font-size: 1.5rem;
        font-weight: bold;
        color: #1a3c34;
    }
    .welcome-subtext {
        font-size: 0.9rem;
        color: #6c757d;
    }
    .back-link {
        color: #007bff;
        font-weight: bold;
        text-decoration: none;
        margin-bottom: 15px;
        display: inline-flex;
        align-items: center;
    }
    .back-link:hover {
        text-decoration: underline;
    }
    .back-link i {
        margin-right: 5px;
    }
    .filter-label {
        font-weight: bold;
        color: #333;
        margin-right: 10px;
    }
    .filter-select {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 5px 10px;
        font-size: 1rem;
        color: #333;
        background-color: #fff;
        outline: none;
        width: 200px;
        margin-right: 20px;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    .table th {
        font-weight: 600;
        padding: 10px;
        text-align: left;
    }
    .table td {
        padding: 10px;
    }
    .percentage-high {
        color: #28a745;
        font-weight: bold;
    }
    .percentage-low {
        color: #dc3545;
        font-weight: bold;
    }
</style>

<div class="container">
   

    <!-- Batch and Quiz Set Filters -->
    <div class="mt-3 mb-3">
        <form method="GET" action="{{ route('student.batch_quiz_ranking') }}" style="display: flex; align-items: center; flex-wrap: wrap; gap: 15px;">
            <!-- Batch Filter -->
            <div style="display: flex; align-items: center;">
                <label for="batch_id" class="filter-label">Select Batch:</label>
                <select name="batch_id" id="batch_id" class="filter-select" onchange="this.form.submit()">
                    <option value="">Select a Batch</option>
                    @foreach($batches as $batchOption)
                        <option value="{{ $batchOption->id }}" {{ $batchId == $batchOption->id ? 'selected' : '' }}>
                            {{ $batchOption->course->name }} - {{ $batchOption->name }} (Started: {{ $batchOption->start_date }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Quiz Set Filter (only show if a batch is selected) -->
            @if($batchId && !$quizSets->isEmpty())
                <div style="display: flex; align-items: center;">
                    <label for="quiz_set_id" class="filter-label">Filter by Quiz Set:</label>
                    <select name="quiz_set_id" id="quiz_set_id" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Quiz Sets</option>
                        @foreach($quizSets as $quizSet)
                            <option value="{{ $quizSet->id }}" {{ $selectedQuizSetId == $quizSet->id ? 'selected' : '' }}>
                                {{ $quizSet->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Show Non-Attempted Students Filter -->
            @if($batchId)
                <div style="display: flex; align-items: center;">
                    <label for="show_non_attempted" class="filter-label">Show only non-attempted students:</label>
                    <input type="checkbox" name="show_non_attempted" id="show_non_attempted" value="1" 
                           {{ $showNonAttempted ? 'checked' : '' }} onchange="this.form.submit()">
                </div>
            @endif
        </form>
    </div>

    <!-- If No Batch Selected -->
    @if(!$batchId)
        <div class="mt-3" style="color: #dc3545;">
            Please select a batch to view quiz rankings.
        </div>
    @else
        <!-- Header -->
        <h1 class="mt-4 mb-3" style="font-size: 1.25rem; font-weight: bold; color: #333;">
            Quiz Rankings for {{ $batch->course->name }} - {{ $batch->name }}
            <span style="font-size: 0.9rem; color: #6c757d;">(Started: {{ $batch->start_date }})</span>
        </h1>

        <!-- Back Link -->
        <a href="{{ route('student.quiz_sets') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Quiz Sets
        </a>

        <!-- Quiz Set Filter Message -->
        @if($quizSets->isEmpty())
            <div class="mt-3" style="color: #dc3545;">
                No quiz sets found for this batch.
            </div>
        @endif

        <!-- Results Table -->
        @if(empty($studentResults))
            <div class="mt-3" style="color: #dc3545;">
                No quiz attempts found for this batch yet.
            </div>
        @else
            <table class="w-full text-left border-collapse table mt-3" id="rankingsTable">
                <thead class="bg-gradient-to-r from-indigo-900 to-purple-800 text-white">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Rank</th>
                        <th class="px-4 py-3 font-semibold">Student Name</th>
                        <th class="px-4 py-3 font-semibold">Quiz Set</th>
                        <th class="px-4 py-3 font-semibold">Score</th>
                        <th class="px-4 py-3 font-semibold">Percentage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($studentResults as $index => $result)
                        <tr class="hover:bg-orange-500 hover:text-white transition duration-200">
                            <td class="px-4 py-3 text-gray-600">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-gray-800">{{ $result->student_name }}</td>
                            <td class="px-4 py-3 text-gray-800">{{ $result->quiz_set_title }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $result->score }} / {{ $result->total_quizzes }}</td>
                            <td class="px-4 py-3 {{ $result->percentage >= 70 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                {{ number_format($result->percentage, 2) }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endif
</div>
@endsection