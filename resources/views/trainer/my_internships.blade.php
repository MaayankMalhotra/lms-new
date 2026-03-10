@extends('admin.layouts.app')

@section('content')
<div class="px-3 py-4">
    <h2 class="text-2xl font-semibold text-gray-800 mb-5">Internship Enrolled</h2>

    @if($internships->isEmpty())
        <div class="bg-white shadow rounded-xl p-6 text-gray-600">
            No internships are assigned to you yet.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($internships as $internship)
                <div class="bg-white shadow-md rounded-2xl overflow-hidden">
                    @if(!empty($internship->logo))
                        @php
                            $logoUrl = \Illuminate\Support\Str::startsWith($internship->logo, ['http://', 'https://'])
                                ? $internship->logo
                                : asset($internship->logo);
                        @endphp
                        <img
                            src="{{ $logoUrl }}"
                            alt="{{ $internship->name }}"
                            class="w-full h-48 object-cover"
                        >
                    @else
                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-500">
                            No Image
                        </div>
                    @endif

                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $internship->name ?? 'Unnamed Internship' }}
                        </h3>
                        <p class="text-sm text-gray-600 mt-2">
                            Duration: {{ $internship->duration ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
