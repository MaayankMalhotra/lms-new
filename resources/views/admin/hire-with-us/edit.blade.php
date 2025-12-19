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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Job Role</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $jobRole->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror" placeholder="e.g., Frontend Engineer">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
                    <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $jobRole->company_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('company_name') border-red-500 @enderror" placeholder="e.g., Acme Corp">
                    @error('company_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="salary_package" class="block text-sm font-medium text-gray-700">Salary Package</label>
                    <input type="text" name="salary_package" id="salary_package" value="{{ old('salary_package', $jobRole->salary_package) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('salary_package') border-red-500 @enderror" placeholder="e.g., ₹10 - 15 LPA">
                    @error('salary_package')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $jobRole->location) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('location') border-red-500 @enderror" placeholder="e.g., Bengaluru (Hybrid)">
                    @error('location')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="apply_link" class="block text-sm font-medium text-gray-700">Apply Link</label>
                    <input type="url" name="apply_link" id="apply_link" value="{{ old('apply_link', $jobRole->apply_link) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('apply_link') border-red-500 @enderror" placeholder="https://company.com/careers/role">
                    @error('apply_link')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="image_url" class="block text-sm font-medium text-gray-700">Image URL</label>
                    <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $jobRole->image_url) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('image_url') border-red-500 @enderror" placeholder="Hero/brand image for this role">
                    @error('image_url')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="last_date_to_apply" class="block text-sm font-medium text-gray-700">Last Date to Apply</label>
                    <input type="date" name="last_date_to_apply" id="last_date_to_apply" value="{{ old('last_date_to_apply', optional($jobRole->last_date_to_apply)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('last_date_to_apply') border-red-500 @enderror">
                    @error('last_date_to_apply')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4 md:col-span-2">
                    <label for="suggestions" class="block text-sm font-medium text-gray-700">Suggestions</label>
                    <textarea name="suggestions" id="suggestions" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('suggestions') border-red-500 @enderror" placeholder="Any extra notes for candidates">{{ old('suggestions', $jobRole->suggestions) }}</textarea>
                    @error('suggestions')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Technologies</label>
                <div id="technologies-container">
                    @php
                        $technologies = collect(old('tech_name', []))->isNotEmpty()
                            ? collect(old('tech_name'))->map(function ($name, $i) {
                                return ['name' => $name];
                            })
                            : collect($jobRole->technologies ?? []);

                        if ($technologies->isEmpty()) {
                            $technologies = collect([['name' => '']]);
                        }
                    @endphp

                    @foreach($technologies as $index => $tech)
                        <div class="technology-entry flex space-x-4 mb-2" data-index="{{ $index }}">
                            <div class="flex-1">
                                <label for="tech_name_{{ $index }}" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="tech_name[]" id="tech_name_{{ $index }}" value="{{ $tech['name'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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

                <p class="text-gray-500 text-xs mt-1">Logos use a generic image automatically: [{"name": "HTML", "image_url": "https://dummyimage.com/..."}]</p>
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
            let techIndex = Math.max(document.querySelectorAll('.technology-entry').length - 1, 0);
            const defaultTechImageUrl = 'https://dummyimage.com/100x100/edf2f7/1f2937&text=Tech';

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
                const technologies = [];

                techNames.forEach((nameInput, index) => {
                    const name = nameInput.value.trim();
                    if (name) {
                        technologies.push({ name: name, image_url: defaultTechImageUrl });
                    }
                });

                if (technologies.length === 0) {
                    alert('Please add at least one valid technology name.');
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
