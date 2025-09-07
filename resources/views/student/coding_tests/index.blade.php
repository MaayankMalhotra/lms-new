@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-gray-50 to-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-code mr-2 text-blue-500"></i>Coding Questions
                </h1>
                <p class="text-gray-500 mt-2">Manage all coding questions in the system</p>
            </div>
            <a href="{{ route('admin.coding_questions.create') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-all">
                <i class="fas fa-plus-circle mr-2"></i>Add New Question
            </a>
        </div>

        <!-- Coding Questions Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Title</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Description</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Solutions</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($codingQuestions as $question)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $question->title }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700">{{ Str::limit($question->description, 100) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700">
                                <ul class="list-disc pl-5 blur-sm hover:blur-none transition-all duration-300">
                                    @foreach($question->solutions as $solution)
                                        <li>{{ $solution }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-4">
                                <!-- Solve Button -->
                                <a href="{{ url('student/coding-tests/' . $question->id) }}"
                                   class="text-purple-500 hover:text-purple-600">
                                    <i class="fas fa-play mr-1"></i>Solve
                                </a>
                                <!-- View Submissions -->
                                <a href="{{ route('admin.coding_questions.show_submissions', $question->id) }}"
                                   class="text-green-500 hover:text-green-600">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($codingQuestions->isEmpty())
            <div class="p-12 text-center text-gray-500">
                <i class="fas fa-inbox text-4xl mb-4"></i>
                <p class="text-lg">No coding questions found. Start by adding a new question!</p>
            </div>
            @endif
        </div>

      
    </div>
</div>
@endsection