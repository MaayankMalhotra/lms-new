@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-gray-50 to-gray-100">
    <div class="mx-10 ">
        <div class=" p-8">
            <!-- Form Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-book-open mr-2 text-blue-500"></i>Create New Course
                </h1>
                <p class="text-gray-500">Fill in the details to add a new course to the platform</p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200">
                    <div class="font-semibold mb-2">Please fix the errors below:</div>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.course.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Course Basic Info Section -->
                <div class="space-y-6">
                    <!-- Course Name & Code -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-book mr-2 text-blue-400"></i>Course Name
                            </label>
                            <input type="text" name="name" required 
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-3 rounded-lg border {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }} focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                   placeholder="Advanced Web Development">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-hashtag mr-2 text-blue-400"></i>Course Code Id
                            </label>
                            <input type="text" name="course_code_id" required 
                                   value="{{ old('course_code_id') }}"
                                   class="w-full px-4 py-3 rounded-lg border {{ $errors->has('course_code_id') ? 'border-red-400' : 'border-gray-200' }} focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                   placeholder="adv-web-dev">
                            @error('course_code_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Course Logo Upload -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image mr-2 text-blue-400"></i>Course Logo
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col w-full h-32 border-4 border-dashed hover:border-gray-300 hover:bg-gray-50 transition-all rounded-xl cursor-pointer {{ $errors->has('logo') ? 'border-red-400' : '' }}">
                                <div class="flex flex-col items-center justify-center pt-7">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-500">Drag & drop or click to upload</p>
                                </div>
                                <input type="file" name="logo" accept="image/*" class="opacity-0">
                            </label>
                        </div>
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Duration & Learners -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-clock mr-2 text-blue-400"></i>Course Duration
                            </label>
                            <input type="text" name="duration" required
                                   value="{{ old('duration') }}"
                                   class="w-full px-4 py-3 rounded-lg border {{ $errors->has('duration') ? 'border-red-400' : 'border-gray-200' }} focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                   placeholder="300 hours / 3 months">
                            @error('duration')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-users mr-2 text-blue-400"></i>Placed Learners
                            </label>
                            <input type="number" name="placed_learner" required min="0" step="1"
                                   value="{{ old('placed_learner') }}"
                                   class="w-full px-4 py-3 rounded-lg border {{ $errors->has('placed_learner') ? 'border-red-400' : 'border-gray-200' }} focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                   placeholder="Enter number">
                            @error('placed_learner')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Slug & Rating -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-link mr-2 text-blue-400"></i>Course Slug
                            </label>
                            <input type="text" name="slug" required 
                                   value="{{ old('slug') }}"
                                   class="w-full px-4 py-3 rounded-lg border {{ $errors->has('slug') ? 'border-red-400' : 'border-gray-200' }} focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                   placeholder="advance-web-development-course">
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-star mr-2 text-blue-400"></i>Course Rating : Example - 4.8 (17K+ students)
                            </label>
                            <div class="relative">
                                <input type="text" name="rating" required
                                       value="{{ old('rating') }}"
                                       class="w-full px-4 py-3 rounded-lg border {{ $errors->has('rating') ? 'border-red-400' : 'border-gray-200' }} focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="4.8 (17K+ students)">
                                @error('rating')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-blue-400"></i>Course Price
                        </label>
                        <div class="relative">
                            <input type="number" name="price" required min="0" step="0.01"
                                   value="{{ old('price') }}"
                                   class="w-full pl-8 pr-4 py-3 rounded-lg border {{ $errors->has('price') ? 'border-red-400' : 'border-gray-200' }} focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                   placeholder="10999">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8">
                    <button type="submit" 
                            class="w-80 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-4 px-6 rounded-xl transition-all transform hover:scale-[1.01] shadow-lg">
                        <i class="fas fa-plus-circle mr-2"></i>Create Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
