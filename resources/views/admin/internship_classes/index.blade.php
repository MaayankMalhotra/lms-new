@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Internship Classes</h2>

    @if(session('success'))
        <div class="bg-green-500 text-white p-3 mb-4 rounded">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-3 mb-4 rounded">{{ session('error') }}</div>
    @endif

    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Batch</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Class Date & Time</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Link</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Thumbnail</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Notes</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Assignments</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($internshipClasses as $class)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $class->batch->batch_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($class->class_date_time)->format('d M Y h:i A') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500"><a href="{{ $class->link }}" target="_blank" class="text-blue-500">{{ $class->link }}</a></td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if($class->thumbnail)
                                <img src="{{ asset('storage/' . $class->thumbnail) }}" alt="Thumbnail" class="w-16 h-16 object-cover rounded">
                            @else
                                No Thumbnail
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if($class->notes && count($class->notes) > 0)
                                <div class="space-y-2">
                                    @foreach($class->notes as $note)
                                        <a href="{{ $note['url'] }}" target="_blank" class="block px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">{{ $note['name'] }}</a>
                                    @endforeach
                                </div>
                            @else
                                No Notes
                            @endif
                            <button class="mt-2 text-blue-600 hover:text-blue-900" onclick="openNotesModal({{ $class->id }})">Add Notes</button>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if($class->notes_2 && count($class->notes_2) > 0)
                                <div class="space-y-2">
                                    @foreach($class->notes_2 as $assignment)
                                        <a href="{{ $assignment['url'] }}" target="_blank" class="block px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">{{ $assignment['name'] }}</a>
                                    @endforeach
                                </div>
                            @else
                                No Assignment
                            @endif
                            <button class="mt-2 text-blue-600 hover:text-blue-900" onclick="openAssignmentModal({{ $class->id }})">Add Assignment</button>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('admin.internship.class.edit', $class->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            |
                            <form action="{{ route('admin.internship-classes.destroy', $class->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $internshipClasses->links() }}
    </div>
</div>

<!-- Modal for Add Notes -->
<div id="notesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Add Notes Links</h3>
        <form id="notesForm" method="POST">
            @csrf
            <div id="notesLinksContainer">
                <div class="link-entry mb-4">
                    <label class="block text-sm font-medium text-gray-700">Link 1</label>
                    <input type="text" name="links[0][name]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Name (e.g., Note 1)" required>
                    <input type="url" name="links[0][url]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://example.com/note1" required>
                </div>
            </div>
            <button type="button" onclick="addNotesLink()" class="mb-4 text-blue-600 hover:text-blue-900">Add Another Link</button>
            <div class="flex justify-end">
                <button type="button" onclick="closeNotesModal()" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Add Assignment -->
<div id="assignmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Add Assignment Links</h3>
        <form id="assignmentForm" method="POST">
            @csrf
            <div id="assignmentLinksContainer">
                <div class="link-entry mb-4">
                    <label class="block text-sm font-medium text-gray-700">Link 1</label>
                    <input type="text" name="links[0][name]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Name (e.g., Assignment 1)" required>
                    <input type="url" name="links[0][url]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://example.com/assignment1" required>
                </div>
            </div>
            <button type="button" onclick="addAssignmentLink()" class="mb-4 text-blue-600 hover:text-blue-900">Add Another Link</button>
            <div class="flex justify-end">
                <button type="button" onclick="closeAssignmentModal()" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Modals -->
<script>
    let notesLinkCount = 1;
    let assignmentLinkCount = 1;

    function openNotesModal(classId) {
        const modal = document.getElementById('notesModal');
        const form = document.getElementById('notesForm');
        form.action = `/internship-class/${classId}/add-notes`;
        modal.classList.remove('hidden');
    }

    function closeNotesModal() {
        const modal = document.getElementById('notesModal');
        const container = document.getElementById('notesLinksContainer');
        container.innerHTML = `
            <div class="link-entry mb-4">
                <label class="block text-sm font-medium text-gray-700">Link 1</label>
                <input type="text" name="links[0][name]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Name (e.g., Note 1)" required>
                <input type="url" name="links[0][url]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://example.com/note1" required>
            </div>
        `;
        notesLinkCount = 1;
        modal.classList.add('hidden');
    }

    function addNotesLink() {
        const container = document.getElementById('notesLinksContainer');
        const newLink = document.createElement('div');
        newLink.classList.add('link-entry', 'mb-4');
        newLink.innerHTML = `
            <label class="block text-sm font-medium text-gray-700">Link ${notesLinkCount + 1}</label>
            <input type="text" name="links[${notesLinkCount}][name]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Name (e.g., Note ${notesLinkCount + 1})" required>
            <input type="url" name="links[${notesLinkCount}][url]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://example.com/note${notesLinkCount + 1}" required>
        `;
        container.appendChild(newLink);
        notesLinkCount++;
    }

    function openAssignmentModal(classId) {
        const modal = document.getElementById('assignmentModal');
        const form = document.getElementById('assignmentForm');
        form.action = `/internship-class/${classId}/add-notes-2`;
        modal.classList.remove('hidden');
    }

    function closeAssignmentModal() {
        const modal = document.getElementById('assignmentModal');
        const container = document.getElementById('assignmentLinksContainer');
        container.innerHTML = `
            <div class="link-entry mb-4">
                <label class="block text-sm font-medium text-gray-700">Link 1</label>
                <input type="text" name="links[0][name]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Name (e.g., Assignment 1)" required>
                <input type="url" name="links[0][url]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://example.com/assignment1" required>
            </div>
        `;
        assignmentLinkCount = 1;
        modal.classList.add('hidden');
    }

    function addAssignmentLink() {
        const container = document.getElementById('assignmentLinksContainer');
        const newLink = document.createElement('div');
        newLink.classList.add('link-entry', 'mb-4');
        newLink.innerHTML = `
            <label class="block text-sm font-medium text-gray-700">Link ${assignmentLinkCount + 1}</label>
            <input type="text" name="links[${assignmentLinkCount}][name]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Name (e.g., Assignment ${assignmentLinkCount + 1})" required>
            <input type="url" name="links[${assignmentLinkCount}][url]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://example.com/assignment${assignmentLinkCount + 1}" required>
        `;
        container.appendChild(newLink);
        assignmentLinkCount++;
    }
</script>
@endsection