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
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                   placeholder="Advanced Web Development">
                        </div>

                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-hashtag mr-2 text-blue-400"></i>Course Code Id
                            </label>
                            <input type="text" name="course_code_id" required 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                   placeholder="adv-web-dev">
                        </div>
                    </div>

                    <!-- Course Logo Upload -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image mr-2 text-blue-400"></i>Course Logo
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col w-full h-32 border-4 border-dashed hover:border-gray-300 hover:bg-gray-50 transition-all rounded-xl cursor-pointer">
                                <div class="flex flex-col items-center justify-center pt-7">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-500">Drag & drop or click to upload</p>
                                </div>
                                <input type="file" name="logo" class="opacity-0">
                            </label>
                        </div>
                    </div>

                    <!-- Duration & Learners -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-clock mr-2 text-blue-400"></i>Course Duration
                            </label>
                            <input type="text" name="duration" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                   placeholder="300 hours / 3 months">
                        </div>

                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-users mr-2 text-blue-400"></i>Placed Learners
                            </label>
                            <input type="number" name="placed_learner" required 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                   placeholder="Enter number">
                        </div>
                    </div>

                    <!-- Slug & Rating -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-link mr-2 text-blue-400"></i>Course Slug
                            </label>
                            <input type="text" name="slug" required 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                   placeholder="advance-web-development-course">
                        </div>

                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-star mr-2 text-blue-400"></i>Course Rating : Example - 4.8 (17K+ students)
                            </label>
                            <div class="relative">
                                <input type="text" name="rating" step="0.1" 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="4.8 (17K+ students)">
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-blue-400"></i>Course Price
                        </label>
                        <div class="relative">
                            <input type="text" name="price" 
                                   class="w-full pl-8 pr-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                   placeholder="Rs .10,999">
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