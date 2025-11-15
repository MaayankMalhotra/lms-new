@extends('admin.layouts.app')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="min-h-screen bg-gradient-to-r from-gray-50 to-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-code mr-2 text-blue-500"></i>Edit Coding Question
            </h1>
            <p class="text-gray-500 mt-2">Update the coding question and its solutions</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <form action="{{ route('admin.coding_questions.update', $codingQuestion->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Course -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-graduation-cap mr-2 text-blue-400"></i>Course
                    </label>
                    <select name="course_id" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white">
                        <option value="">Select a course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}"
                                {{ old('course_id', $codingQuestion->course_id) == $course->id ? 'selected' : '' }}>
                                {{ $course->name ?? 'Unnamed course' }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @if($courses->isEmpty())
                        <p class="text-sm text-gray-500 mt-1">No courses are available yet.</p>
                    @endif
                </div>

                <div class="space-y-6">
                    <!-- Title -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heading mr-2 text-blue-400"></i>Question Title
                        </label>
                        <input type="text" name="title" required
                               class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                               value="{{ old('title', $codingQuestion->title) }}">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-blue-400"></i>Description
                        </label>
                        <textarea name="description" required
                                  class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                  rows="5">{{ old('description', $codingQuestion->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Solutions -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-code mr-2 text-blue-400"></i>Solutions
                        </label>
                        <div id="solutions-container">
                            @foreach($codingQuestion->solutions as $solution)
                            <div class="solution-field flex items-center space-x-2 mb-2">
                                <input type="text" name="solutions[]" required
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       value="{{ $solution }}">
                                <button type="button" class="remove-solution text-red-500 hover:text-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-solution"
                                class="mt-2 text-blue-500 hover:text-blue-600">
                            <i class="fas fa-plus-circle mr-1"></i>Add Another Solution
                        </button>
                        @error('solutions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @error('solutions.*')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8">
                        <button type="submit"
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-4 px-6 rounded-xl transition-all">
                            Update Coding Question
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// document.getElementById('add-solution').addEventListener('click', function () {
//     const container = document.getElementById('solutions-container');
//     const solutionField = document.createElement('div');
//     solutionField.className = 'solution-field flex items-center space-x-2 mb-2';
//     solutionField.innerHTML = `
//         <input type="text" name="solutions[]" required
//                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
//                placeholder="Enter solution">
//         <button type="button" class="remove-solution text-red-500 hover:text-red-600">
//             <i class="fas fa-trash"></i>
//         </button>
//     `;
//     container.appendChild(solutionField);
// });

// document.addEventListener('click', function (e) {
//     if (e.target.classList.contains('remove-solution')) {
//         const solutionFields = document.querySelectorAll('.solution-field');
//         if (solutionFields.length > 1) { // Ensure at least one solution field remains
//             e.target.closest('.solution-field').remove();
//         }
//     }
// });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add new solution field
        document.getElementById('add-solution').addEventListener('click', function () {
            const container = document.getElementById('solutions-container');
            const solutionField = document.createElement('div');
            solutionField.className = 'solution-field flex items-center space-x-2 mb-2';
            solutionField.innerHTML = `
                <input type="text" name="solutions[]" required
                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                       placeholder="Enter solution">
                <button type="button" class="remove-solution text-red-500 hover:text-red-600">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(solutionField);
        });
    
        // Event delegation for remove buttons (works for both existing and dynamically added buttons)
        document.getElementById('solutions-container').addEventListener('click', function(e) {
            if (e.target.closest('.remove-solution')) {
                const solutionField = e.target.closest('.solution-field');
                const solutionFields = document.querySelectorAll('.solution-field');
                
                // Ensure at least one solution field remains
                if (solutionFields.length <= 1) {
                    alert('At least one solution is required!');
                    return;
                }
    
                const solutionValue = solutionField.querySelector('input').value;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const questionId = '{{ $codingQuestion->id }}';
    
                console.log(solutionField, solutionValue, questionId);
    
                // If the input is empty, just remove it client-side (for dynamically added empty fields)
                if (!solutionValue.trim()) {
                    solutionField.remove();
                    return;
                }
    
                // Otherwise, make DELETE request to server
                if (confirm('Are you sure you want to delete this solution?')) {
                    const url = `/coding-questions/delete-solution?question_id=${encodeURIComponent(questionId)}&solution=${encodeURIComponent(solutionValue)}`;
                    
                    fetch(url, {
                        method: 'get', // Changed back to DELETE as per your route
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        solutionField.remove();
                        alert('Solution deleted successfully');
                    })
                    .catch(error => {
                        alert('Error deleting solution: ' + error.message);
                    });
                }
            }
        });
    });
    </script>
@endsection
