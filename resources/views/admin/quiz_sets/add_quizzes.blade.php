@extends('admin.layouts.app')

@section('title', 'Add Quizzes to Set')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <!-- Heading -->
        <h1 class="text-4xl font-bold text-gray-800 tracking-tight mb-8">
            Add Quizzes to "{{ $quizSet->title }}"
        </h1>

        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
                {!! session('error') !!}
            </div>
        @endif

        <!-- Bulk Upload Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-semibold text-indigo-600 mb-4">
                Bulk Upload Quizzes
            </h2>
            <form method="POST" action="{{ route('admin.quiz_sets.bulk_upload', $quizSet->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="csv_file" class="block text-gray-700 font-semibold mb-2">
                        Upload CSV File
                    </label>
                    <input type="file" name="csv_file" id="csv_file" 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" 
                           required>
                    <p class="mt-2 text-sm text-gray-500">
                        CSV file should have the following columns: question, option_1, option_2, option_3, option_4, correct_option. 
                        <a href="{{ asset('sample-quizzes.csv') }}" class="text-indigo-600 hover:underline">Download sample CSV</a>
                    </p>
                </div>
                <button type="submit" 
                        class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-indigo-700 transition-all duration-300">
                    Upload Quizzes
                </button>
            </form>
        </div>

        <!-- Manual Add Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-semibold text-indigo-600 mb-4">
                Add Quizzes Manually
            </h2>
            <form action="{{ route('admin.quiz_sets.store_quizzes', $quizSet->id) }}" method="POST">
                @csrf
                @for($i = 0; $i < $quizSet->total_quizzes; $i++)
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">
                            Quiz {{ $i + 1 }}
                        </h3>

                        <!-- Question -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">
                                Question
                            </label>
                            <textarea name="questions[]" 
                                      class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" 
                                      placeholder="Enter your question here" rows="3" required></textarea>
                            @error("questions.$i")
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Options -->
                        @for($j = 0; $j < 4; $j++)
                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Option {{ $j + 1 }}
                                </label>
                                <input type="text" name="options[{{$i}}][]" 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" 
                                       placeholder="Option {{ $j + 1 }}" required>
                                @error("options.$i.$j")
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endfor

                        <!-- Correct Option -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">
                                Correct Option (1-4)
                            </label>
                            <input type="number" name="correct_options[]" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" 
                                   min="1" max="4" placeholder="e.g., 2" required>
                            @error("correct_options.$i")
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endfor

                <!-- Submit Button -->
                <button type="submit" 
                        class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-indigo-700 transition-all duration-300 w-full max-w-lg mx-auto block">
                    Save Quizzes
                </button>
            </form>
        </div>

        <!-- Back Link -->
        <a href="{{ route('admin.quiz_sets') }}" 
           class="block text-indigo-600 hover:text-indigo-800 mt-6 text-center">
            Back to Quiz Sets
        </a>
    </div>
@endsection