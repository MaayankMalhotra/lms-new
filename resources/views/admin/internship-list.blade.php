@extends('admin.layouts.app')

@section('content')
<style>
    body {
        background: url("https://img.freepik.com/free-vector/education-pattern-background-doodle-style_53876-115365.jpg") no-repeat center center fixed;
        background-size: cover;
    }

    .listing-table thead th {
        font-size: 0.75rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #6b7280;
    }

    .listing-table tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.05);
    }

    .listing-table td {
        vertical-align: middle;
    }

    .action-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        transition: background-color 0.2s, color 0.2s;
    }

    .action-icon.edit {
        background-color: rgba(59, 130, 246, 0.1);
        color: #2563eb;
    }

    .action-icon.edit:hover {
        background-color: rgba(37, 99, 235, 0.2);
        color: #1d4ed8;
    }

    .action-icon.details {
        background-color: rgba(34, 197, 94, 0.1);
        color: #16a34a;
    }

    .action-icon.details:hover {
        background-color: rgba(22, 163, 74, 0.2);
        color: #15803d;
    }

    .action-icon.delete {
        background-color: rgba(239, 68, 68, 0.12);
        color: #dc2626;
    }

    .action-icon.delete:hover {
        background-color: rgba(239, 68, 68, 0.2);
        color: #b91c1c;
    }
</style>

<div class="min-h-screen bg-gray-100/60 p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-10 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-briefcase mr-2 text-blue-600"></i>Internship Programs
                </h1>
                <p class="text-gray-600 mt-1">Manage all internship programs in the system</p>
            </div>
            <a href="{{ route('admin.internship.add') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-md transition-all">
                <i class="fas fa-plus-circle mr-2"></i>Add New Internship
            </a>
        </div>

        <div class="bg-white/95 backdrop-blur rounded-xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full listing-table">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-6 py-3 text-left">Internship</th>
                            <th class="px-6 py-3 text-left">Duration</th>
                            <th class="px-6 py-3 text-left">Projects</th>
                            <th class="px-6 py-3 text-left">Applicants</th>
                            <th class="px-6 py-3 text-left">Certification</th>
                            <th class="px-6 py-3 text-right">Price</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200/70">
                        @forelse($internships as $internship)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden">
                                            @if($internship->logo)
                                                <img src="{{ asset($internship->logo) }}" alt="{{ $internship->name }}" class="object-contain w-full h-full">
                                            @else
                                                <img src="{{ asset('images/default-internship.png') }}" alt="Default Internship Logo" class="object-contain w-full h-full">
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $internship->name }}</p>
                                            <p class="text-xs text-gray-500 mt-1">Last updated {{ $internship->updated_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $internship->duration }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $internship->project }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $internship->applicant }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $internship->certified_button }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-right font-semibold">₹{{ number_format($internship->price, 2) }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <button onclick="openEditModal(`{{ route('admin.internship.edit', $internship->id) }}`)" class="action-icon edit" title="Edit Internship">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        @if($internship->has_details)
                                            <a href="{{ route('course.edit.int', $internship->internship_detail_id) }}" class="action-icon details" title="Edit Internship Details">
                                                <i class="fas fa-pen-to-square"></i>
                                            </a>
                                        @endif

                                        <form action="{{ route('admin.internship.destroy', $internship->id) }}" method="POST" class="inline-flex">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-icon delete" onclick="return confirm('Are you sure you want to delete this internship?')" title="Delete Internship">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <i class="fas fa-inbox text-3xl"></i>
                                        <p>No internships found. Start by adding a new internship program!</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($internships->hasPages())
        <div class="mt-8">
            {{ $internships->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
