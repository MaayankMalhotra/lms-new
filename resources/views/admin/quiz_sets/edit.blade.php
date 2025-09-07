@extends('admin.layouts.app')

@section('title', 'Edit Quiz Set')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <h1 class="text-4xl font-bold text-gray-800 tracking-tight mb-8">
            Edit "{{ $quizSet->title }}"
        </h1>

        <div class="bg-white rounded-xl shadow-lg p-8 max-w-lg mx-auto">
            <form action="{{ route('admin.quiz_sets.update', $quizSet->id) }}" method="POST">
                @csrf
                @method('PUT')
                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-gray-700 font-semibold mb-2">
                        Quiz Set Title
                    </label>
                    <input type="text" name="title" id="title" value="{{ $quizSet->title }}"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" 
                           required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Batch Dropdown -->
                <div class="mb-6">
                    <label for="batch_id" class="block text-gray-700 font-semibold mb-2">
                        Select Batch
                    </label>
                    <select name="batch_id" id="batch_id" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" required>
                        <option value="">-- Select a Batch --</option>
                        @foreach($batchOptions as $id => $label)
                            <option value="{{ $id }}" {{ $quizSet->batch_id == $id ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('batch_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Quizzes -->
                <div class="mb-6">
                    <label for="total_quizzes" class="block text-gray-700 font-semibold mb-2">
                        Total Number of Quizzes
                    </label>
                    <input type="number" name="total_quizzes" id="total_quizzes" value="{{ $quizSet->total_quizzes }}"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" 
                           min="1" required>
                    @error('total_quizzes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-indigo-700 transition-all duration-300 w-full">
                    Update Quiz Set
                </button>
            </form>
        </div>

        <a href="{{ route('admin.quiz_sets') }}" 
           class="block text-indigo-600 hover:text-indigo-800 mt-6 text-center">
            Back to Quiz Sets
        </a>
    </div>
@endsection