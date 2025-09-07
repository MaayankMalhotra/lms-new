@extends('admin.layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-r from-gray-50 to-gray-100">
    <div class="mx-10 ">
        <div class="p-8">
            <!-- Form Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-book-open mr-2 text-blue-500"></i>Create New Career Highlight
                </h1>
                <p class="text-gray-500">Fill in the details to add a new career highlight to the platform</p>
            </div>

            <form action="{{ route('admin.career_highlight.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Career Highlight Info Section -->
                <div class="space-y-6">
                    <!-- Heading Line & Heading Highlight -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-hashtag mr-2 text-blue-400"></i>Heading Line
                            </label>
                            <input type="text" name="heading_line" required
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                   placeholder="Heading Line">
                        </div>

                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-hashtag mr-2 text-blue-400"></i>Heading Highlight
                            </label>
                            <input type="text" name="heading_highlight" required
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                   placeholder="Heading Highlight">
                        </div>
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-hashtag mr-2 text-blue-400"></i>Cta Text
                            </label>
                            <input type="text" name="cta_text" required
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                   placeholder="Cta Text">
                        </div>
                    </div>

                    <!-- Dynamic Icon, Value, and Label -->
                    <div class="dynamic-fields">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 icon-value-label-row">
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-cogs mr-2 text-blue-400"></i>Icon
                                </label>
                                <input type="text" name="icons[]" required
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="Icon">
                            </div>

                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-dollar-sign mr-2 text-blue-400"></i>Value
                                </label>
                                <input type="text" name="values[]" required
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="Value">
                            </div>

                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-tag mr-2 text-blue-400"></i>Label
                                </label>
                                <input type="text" name="labels[]" required
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                       placeholder="Label">
                            </div>
                        </div>
                    </div>

                    <!-- Add More Icon, Value, Label Button -->
                    <div class="flex justify-end mt-4">
                        <button type="button" id="addMoreFields"
                                class="bg-blue-500 text-white py-2 px-4 rounded-md shadow-md hover:bg-blue-600 transition-all">
                            Add More
                        </button>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="mt-8">
                    <button type="submit"
                            class="w-80 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-4 px-6 rounded-xl transition-all transform hover:scale-[1.01] shadow-lg">
                        <i class="fas fa-plus-circle mr-2"></i>Create Career Highlight
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('addMoreFields').addEventListener('click', function() {
        // Create a new row for Icon, Value, Label
        const newRow = document.createElement('div');
        newRow.classList.add('grid', 'grid-cols-1', 'md:grid-cols-3', 'gap-6', 'icon-value-label-row');
        
        newRow.innerHTML = `
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-cogs mr-2 text-blue-400"></i>Icon
                </label>
                <input type="text" name="icons[]" required
                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                       placeholder="Icon">
            </div>

            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-dollar-sign mr-2 text-blue-400"></i>Value
                </label>
                <input type="text" name="values[]" required
                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                       placeholder="Value">
            </div>

            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag mr-2 text-blue-400"></i>Label
                </label>
                <input type="text" name="labels[]" required
                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                       placeholder="Label">
            </div>
        `;

        // Append the new row to the dynamic-fields container
        document.querySelector('.dynamic-fields').appendChild(newRow);
    });
</script>
@endsection




