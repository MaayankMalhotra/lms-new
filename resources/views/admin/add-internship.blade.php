@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-gray-50 to-gray-100">
    <div class="mx-4 sm:mx-10">
        <div class="p-6 sm:p-8">
            <!-- Form Header -->
            <div class="mb-8 text-center">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-briefcase mr-2 text-blue-500"></i>Create New Internship
                </h1>
                <p class="text-gray-500 text-sm sm:text-base">Fill in the details to add a new internship program</p>
            </div>

            <form action="{{ route('admin.internship.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="space-y-6">
                    <!-- Basic Information Section -->
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold mb-4 text-gray-700">
                            <i class="fas fa-info-circle mr-2 text-blue-400"></i>Basic Information
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Internship Name -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-tag mr-2 text-blue-400"></i>Internship Name
                                </label>
                                <input type="text" name="name" required 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="e.g., Software Development Internship"
                                       value="{{ old('name') }}">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Certification Badge -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-certificate mr-2 text-blue-400"></i>Certification Badge
                                </label>
                                <input type="text" name="certified_button" required 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="e.g., AICTE & MSME Certified"
                                       value="{{ old('certified_button') }}">
                                @error('certified_button')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Logo Upload Section -->
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold mb-4 text-gray-700">
                            <i class="fas fa-camera-retro mr-2 text-blue-400"></i>Program Logo
                        </h2>
                        
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Logo (Recommended: 500x500px PNG/JPG)
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col w-full h-32 border-4 border-dashed hover:border-gray-300 hover:bg-gray-50 transition-all rounded-xl cursor-pointer">
                                    <div id="logoPreview" class="flex flex-col items-center justify-center pt-7">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                        <p class="text-sm text-gray-500">Drag & drop or click to upload</p>
                                    </div>
                                    <input type="file" name="logo" class="opacity-0" id="logoInput">
                                </label>
                            </div>
                            @error('logo')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Program Details Section -->
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h2 class="text-xl font-semibold mb-4 text-gray-700">
                            <i class="fas fa-clipboard-list mr-2 text-blue-400"></i>Program Details
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Duration -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-clock mr-2 text-blue-400"></i>Program Duration
                                </label>
                                <input type="text" name="duration" required 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="e.g., 3 Months (300 Hours)"
                                       value="{{ old('duration') }}">
                                @error('duration')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Applicants -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-users mr-2 text-blue-400"></i>Applicant Statistics
                                </label>
                                <input type="text" name="applicant" required 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="e.g., 4.9 (2K+ applicants)"
                                       value="{{ old('applicant') }}">
                                @error('applicant')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Projects -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-project-diagram mr-2 text-blue-400"></i>Number of Projects
                                </label>
                                <input type="number" name="project" required 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="Enter number of projects"
                                       value="{{ old('project') }}">
                                @error('project')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                ₹ Price
                                </label>
                                <input type="number" name="price" required step="0.01" min="0" 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="e.g., 199.99"
                                       value="{{ old('price') }}">
                                @error('price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-center">
                        <button type="submit" 
                                class="w-full sm:w-80 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-4 px-6 rounded-xl transition-all transform hover:scale-[1.01] shadow-lg">
                            <i class="fas fa-plus-circle mr-2"></i>Create Internship Program
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('logoInput').addEventListener('change', function(e) {
    const preview = document.getElementById('logoPreview');
    const file = e.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="h-28 object-contain p-2" alt="Logo preview">`;
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = `
            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
            <p class="text-sm text-gray-500">Drag & drop or click to upload</p>
        `;
    }
});
</script>
@endsection