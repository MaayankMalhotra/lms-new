@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-10">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-10 flex items-center">
            <i class="fas fa-star text-yellow-500 mr-3"></i> Career Highlight Details
        </h1>

        @forelse($highlight as $item)
            <div class="bg-white shadow-md rounded-lg p-8 mb-10 border border-gray-200">
                <!-- Heading Line -->
                <div class="mb-4">
                    <h2 class="text-xl font-semibold text-gray-700">Heading Line:</h2>
                    <p class="text-gray-600">{{ $item->heading_line }}</p>
                </div>

                <!-- Heading Highlight -->
                <div class="mb-4">
                    <h2 class="text-xl font-semibold text-gray-700">Heading Highlight:</h2>
                    <p class="text-blue-600 font-bold">{{ $item->heading_highlight }}</p>
                </div>

                <!-- CTA Text -->
                @if($item->cta_text)
                <div class="mb-4">
                    <h2 class="text-xl font-semibold text-gray-700">CTA Text:</h2>
                    <p class="text-green-600">{{ $item->cta_text }}</p>
                </div>
                @endif

                <!-- Career Stats -->
                <div class="mt-6">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Career Stats:</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($item->stats as $stat)
                            <div class="p-4 border border-gray-200 rounded-lg bg-gray-50 shadow-sm">
                                <p><strong class="text-blue-500">Icon:</strong> {{ $stat->icon }}</p>
                                <p><strong class="text-gray-700">Value:</strong> {{ $stat->value }}</p>
                                <p><strong class="text-gray-500">Label:</strong> {{ $stat->label }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 col-span-2">No stats available for this highlight.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <p class="text-red-500">No Career Highlight data found.</p>
        @endforelse
        <!-- Delete All Button -->
<form action="{{ route('admin.career_highlight.deleteAll') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete ALL career highlights and stats?');" class="mb-6">
    @csrf
    @method('DELETE')
    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 shadow">
        <i class="fas fa-trash-alt mr-2"></i>Delete All Career Highlights & Stats
    </button>
</form>
    </div>
</div>
@endsection
