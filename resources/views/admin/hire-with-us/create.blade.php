@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-gray-50 to-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center mb-6">
            <i class="fas fa-briefcase text-blue-500 mr-2"></i>Create Job Role
        </h2>

        <form action="{{ route('admin.job-roles.store') }}" method="POST" id="jobRoleForm" onsubmit="return handleFormSubmit()">
            @csrf
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Technologies</label>
                <div id="technologies-container">
                    <div class="technology-entry flex space-x-4 mb-2" data-index="0">
                        <div class="flex-1">
                            <label for="tech_name_0" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="tech_name[]" id="tech_name_0" value="{{ old('tech_name.0') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tech_name.0') border-red-500 @enderror">
                            @error('tech_name.0')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex-1">
                            <label for="tech_url_0" class="block text-sm font-medium text-gray-700">Image URL</label>
                            <input type="url" name="tech_url[]" id="tech_url_0" value="{{ old('tech_url.0') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tech_url.0') border-red-500 @enderror">
                            @error('tech_url.0')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="button" class="remove-tech-btn text-red-500 mt-7 hover:text-red-700" style="display:none;">Remove</button>
                    </div>
                </div>
                <button type="button" id="add-tech-btn" class="mt-2 text-blue-500 hover:text-blue-700 focus:outline-none">+ Add Technology</button>
                <input type="hidden" name="technologies" id="technologies" value="{{ old('technologies') }}">
                @error('technologies')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">Data will be stored as: [{"name": "HTML", "image_url": "https://..."}, {"name": "CSS", "image_url": "https://..."}]</p>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" onclick="window.location.href='{{ route('admin.job-roles.index') }}'" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Create Job Role</button>
            </div>
            
        </form>
    </div>
</div>

<script>
    (function () {     
        document.addEventListener('DOMContentLoaded', function () {
            let techIndex = 0;
            const addTechButton = document.getElementById('add-tech-btn');
            if (!addTechButton) {
                console.error('Error: Add Technology button (#add-tech-btn) not found in DOM');
                return;
            }

            addTechButton.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent any default behavior
                techIndex++;
                console.log('Adding technology field with index:', techIndex);

                const container = document.getElementById('technologies-container');
                if (!container) {
                    console.error('Error: Technologies container (#technologies-container) not found in DOM');
                    return;
                }

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
                console.log('Added new technology entry with index:', techIndex);
            });

            // Remove technology fields
            const technologiesContainer = document.getElementById('technologies-container');
            if (!technologiesContainer) {
                console.error('Error: Technologies container (#technologies-container) not found in DOM');
                return;
            }

            technologiesContainer.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-tech-btn')) {
                    console.log('Removing technology field with index:', e.target.closest('.technology-entry').getAttribute('data-index'));
                    e.target.closest('.technology-entry').remove();
                    updateRemoveButtons();
                }
            });

            // Update visibility of remove buttons
            function updateRemoveButtons() {
                const entries = document.querySelectorAll('.technology-entry');
                const removeButtons = document.querySelectorAll('.remove-tech-btn');
                removeButtons.forEach(button => {
                    button.style.display = entries.length > 1 ? 'block' : 'none';
                });
                console.log('Updated remove buttons, total entries:', entries.length);
            }

            // Handle form submission
            window.handleFormSubmit = function () {
                try {
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
                        console.warn('No valid technologies provided');
                        alert('Please add at least one valid technology with both name and URL.');
                        return false;
                    }

                    const technologiesJson = JSON.stringify(technologies);
                    const technologiesInput = document.getElementById('technologies');
                    if (!technologiesInput) {
                        console.error('Error: Technologies hidden input (#technologies) not found in DOM');
                        alert('Form error: Technologies field not found.');
                        return false;
                    }

                    technologiesInput.value = technologiesJson;
                    console.log('Technologies JSON set:', technologiesJson);
                    return true;
                } catch (error) {
                    console.error('Error in handleFormSubmit:', error);
                    alert('An error occurred while processing technologies. Please try again.');
                    return false;
                }
            };

            // Initialize remove buttons visibility
            updateRemoveButtons();
        });
    })();
</script>
@endsection