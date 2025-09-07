@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-comments mr-2 text-green-500"></i>Testimonials
                </h1>
                <p class="text-gray-500 mt-2">Manage testimonials submitted by students or partners</p>
            </div>
            <a href="{{ route('admin.testimonials.create') }}"
               class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition-all">
                <i class="fas fa-plus-circle mr-2"></i>Add New Testimonial
            </a>
        </div>

        <!-- Testimonials Grid -->
        @if($testimonials->isEmpty())
            <div class="p-12 text-center text-gray-500 bg-white rounded-xl shadow-md">
                <i class="fas fa-inbox text-4xl mb-4"></i>
                <p class="text-lg">No testimonials found. Start by adding one!</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($testimonials as $testimonial)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 flex flex-col justify-between">
                        <!-- User Info -->
                        <div class="flex items-center space-x-4 mb-4">
                            @if($testimonial->image_url)
                                <img src="{{ asset($testimonial->image_url) }}" class="w-14 h-14 rounded-full object-cover shadow">
                            @else
                                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center shadow">
                                    <i class="fas fa-user text-gray-400 text-xl"></i>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $testimonial->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $testimonial->position }} @if($testimonial->company) • {{ $testimonial->company }} @endif</p>
                            </div>
                        </div>

                        <!-- Feedback -->
                        <p class="text-gray-700 italic mb-4">"{{ Str::limit($testimonial->department, 120) }}"</p>

                        <!-- Rating -->
                        <div class="flex items-center mb-4">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" 
                               class="text-blue-500 hover:text-blue-600">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" onsubmit="return confirmDelete()">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Pagination -->
        @if($testimonials->hasPages())
            <div class="mt-8">
                {{ $testimonials->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this testimonial?");
    }
</script>
@endsection
