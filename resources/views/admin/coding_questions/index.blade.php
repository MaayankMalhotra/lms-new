@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-center p-8" 
     style="background-image: url('{{ asset('images/dashboard-bg.jpg') }}');">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white drop-shadow-md">
                    <i class="fas fa-code mr-2 text-blue-400"></i>Coding Questions
                </h1>
                <p class="text-gray-200 mt-2">Manage all coding questions in the system</p>
            </div>
            <a href="{{ route('admin.coding_questions.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-lg transition-all">
                <i class="fas fa-plus-circle mr-2"></i>Add New Question
            </a>
        </div>

        <!-- Cards Grid -->
        @if($codingQuestions->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($codingQuestions as $question)
                    <div class="bg-white/90 backdrop-blur-md shadow-lg rounded-xl p-6 hover:shadow-2xl transition-all duration-300">
                        <!-- Title -->
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">
                            {{ $question->title }}
                        </h2>

                        <!-- Course -->
                        <p class="text-sm text-blue-500 font-medium mb-2">
                            Course: {{ optional($question->course)->name ?? 'Unassigned' }}
                        </p>

                        <!-- Description -->
                        <p class="text-sm text-gray-600 mb-3">
                            {{ Str::limit($question->description, 120) }}
                        </p>

                        <!-- Solutions -->
                        @if($question->solutions && count($question->solutions) > 0)
                            <div class="mb-3">
                                <p class="text-sm font-semibold text-gray-700">Solutions:</p>
                                <ul class="list-disc pl-5 text-sm text-gray-600">
                                    @foreach($question->solutions as $solution)
                                        <li>{{ $solution }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex justify-between items-center mt-4 pt-4 border-t">
                            <a href="{{ route('admin.coding_questions.show_submissions', $question->id) }}"
                               class="text-green-500 hover:text-green-600" title="View Submissions">
                                <i class="fas fa-eye text-lg"></i>
                            </a>
                            <a href="{{ route('admin.coding_questions.edit', $question->id) }}"
                               class="text-blue-500 hover:text-blue-600" title="Edit">
                                <i class="fas fa-edit text-lg"></i>
                            </a>
                            <form action="{{ route('admin.coding_questions.destroy', $question->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-600" 
                                        onclick="return confirm('Are you sure you want to delete this question?')"
                                        title="Delete">
                                    <i class="fas fa-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $codingQuestions->links() }}
            </div>
        @else
            <div class="p-12 text-center text-white">
                <i class="fas fa-inbox text-4xl mb-4"></i>
                <p class="text-lg">No coding questions found. Start by adding a new question!</p>
            </div>
        @endif
    </div>
</div>
@endsection
