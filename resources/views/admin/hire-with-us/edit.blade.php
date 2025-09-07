@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-gray-50 to-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center mb-6">
            <i class="fas fa-edit text-yellow-500 mr-2"></i>Edit Job Role
        </h2>

        <form action="{{ route('admin.job-roles.update', $jobRole->id) }}" method="POST" id="jobRoleForm" onsubmit="return handleFormSubmit()">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $jobRole->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Technologies</label>
                <div id="technologies-container">
                    @php
                        $technologies = old('tech_name') ? collect(old('tech_name'))->map(function ($name, $i) use ($request) {
                            return ['name' => $name, 'image_url' => old("tech_url.$i")];
                        }) : $jobRole->technologies;
                    @endphp

                    @foreach($technologies as $index => $tech)
                        <div class="technology-entry flex space-x-4 mb-2" data-index="{{ $index }}">
                            <div class="flex-1">
                                <label for="tech_name_{{ $index }}" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="tech_name[]" id="tech_name_{{ $index }}" value="{{ $tech['name'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="flex-1">
                                <label for="tech_url_{{ $index }}" class="block text-sm font-medium text-gray-700">Image URL</label>
                                <input type="url" name="tech_url[]" id="tech_url_{{ $index }}" value="{{ $tech['image_url'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <button type="button" class="remove-tech-btn text-red-500 mt-7 hover:text-red-700">Remove</button>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-tech-btn" class="mt-2 text-blue-500 hover:text-blue-700 focus:outline-none">+ Add Technology</button>
                <input type="hidden" name="technologies" id="technologies" value="{{ old('technologies', json_encode($jobRole->technologies)) }}">
                @error('technologies')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                <p class="text-gray-500 text-xs mt-1">Data will be stored as: [{"name": "HTML", "image_url": "https://..."}, ...]</p>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.job-roles.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">Cancel</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Update Job Role</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        document.addEventListener('DOMContentLoaded', function () {
            let techIndex = document.querySelectorAll('.technology-entry').length - 1;

            document.getElementById('add-tech-btn').addEventListener('click', function () {
                techIndex++;
                const container = document.getElementById('technologies-container');
                const newEntry = document.createElement('div');
                newEntry.className = 'technology-entry flex space-x-4 mb-2';
                newEntry.setAttribute('data-index', techIndex);
                newEntry.innerHTML = `
                    <div class="flex-1">
                        <label for="tech_name_${techIndex}" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="tech_name[]" id="tech_name_${techIndex}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex-1">
                        <label for="tech_url_${techIndex}" class="block text-sm font-medium text-gray-700">Image URL</label>
                        <input type="url" name="tech_url[]" id="tech_url_${techIndex}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <button type="button" class="remove-tech-btn text-red-500 mt-7 hover:text-red-700">Remove</button>
                `;
                container.appendChild(newEntry);
                updateRemoveButtons();
            });

            document.getElementById('technologies-container').addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-tech-btn')) {
                    e.target.closest('.technology-entry').remove();
                    updateRemoveButtons();
                }
            });

            function updateRemoveButtons() {
                const entries = document.querySelectorAll('.technology-entry');
                document.querySelectorAll('.remove-tech-btn').forEach(btn => {
                    btn.style.display = entries.length > 1 ? 'block' : 'none';
                });
            }

            window.handleFormSubmit = function () {
                const techNames = document.querySelectorAll('input[name="tech_name[]"]');
                const techUrls = document.querySelectorAll('input[name="tech_url[]"]');
                const technologies = [];

                techNames.forEach((nameInput, index) => {
                    const name = nameInput.value.trim();
                    const url = techUrls[index]?.value.trim();
                    if (name && url) {
                        technologies.push({ name: name, image_url: url });
                    }
                });

                if (technologies.length === 0) {
                    alert('Please add at least one valid technology.');
                    return false;
                }

                document.getElementById('technologies').value = JSON.stringify(technologies);
                return true;
            };

            updateRemoveButtons();
        });
    })();
</script>
@endsection
