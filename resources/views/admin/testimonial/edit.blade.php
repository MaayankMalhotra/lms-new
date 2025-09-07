@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-gray-50 to-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8">
        <div class="mb-6 border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-edit text-yellow-500 mr-2"></i>Edit Testimonial
            </h2>
            <p class="text-gray-500 mt-1">Update the details for this testimonial.</p>
        </div>

        <form method="POST" action="{{ route('admin.testimonials.update', $testimonial->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $testimonial->name) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-green-400 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Department</label>
                    <input type="text" name="department" value="{{ old('department', $testimonial->department) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-green-400 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Position</label>
                    <input type="text" name="position" value="{{ old('position', $testimonial->position) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-green-400 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Company</label>
                    <input type="text" name="company" value="{{ old('company', $testimonial->company) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-green-400 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Rating (1 to 5)</label>
                    <input type="number" name="rating" min="1" max="5" value="{{ old('rating', $testimonial->rating) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-green-400 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Photo (optional)</label>
                    <input type="file" name="image_url"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-green-50 file:text-green-700 hover:file:bg-green-100" />
                    @if($testimonial->image_url)
                        <div class="mt-4">
                            <img src="{{ asset($testimonial->image_url) }}" class="w-32 h-32 object-cover rounded-lg" />
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('admin.testimonials.index') }}"
                   class="mr-4 bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2 rounded-lg transition-all">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg transition-all">
                    Update Testimonial
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
