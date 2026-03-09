@extends('admin.layouts.app')

@section('title', 'Solve Coding Question')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <!-- Header -->
        <h1 class="text-4xl font-bold text-gray-800 tracking-tight mb-8">
            {{ $codingQuestion->title }}
        </h1>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md">
                {{ session('error') }}
            </div>
        @endif

        <!-- Question Details -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-semibold text-indigo-600 mb-3">Description</h2>
            <p class="text-gray-600 mb-2">{{ $codingQuestion->description }}</p>
            <p class="text-gray-600 mb-2">
                <span class="font-medium">Possible Solutions:</span> {{ count($codingQuestion->solutions) }}
            </p>
            @if($codingQuestion->example_output_1 || $codingQuestion->example_output_2 || $codingQuestion->example_output_3)
                <div class="mt-4">
                    <h3 class="text-lg font-semibold text-indigo-600 mb-2">Example Outputs</h3>
                    <div class="space-y-2 text-gray-700">
                        @if($codingQuestion->example_output_1)
                            <p><span class="font-medium">Output 1:</span> {{ $codingQuestion->example_output_1 }}</p>
                        @endif
                        @if($codingQuestion->example_output_2)
                            <p><span class="font-medium">Output 2:</span> {{ $codingQuestion->example_output_2 }}</p>
                        @endif
                        @if($codingQuestion->example_output_3)
                            <p><span class="font-medium">Output 3:</span> {{ $codingQuestion->example_output_3 }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Code Runner -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold text-indigo-600">Run your code</h2>
                <span id="run-status" class="text-gray-500 text-sm">Idle</span>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Language
                        </label>
                        <select id="run-language"
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white">
                            <option value="Python">Python</option>
                            <option value="C">C</option>
                            <option value="C++">C++</option>
                            <option value="Java">Java</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Code
                        </label>
                        <textarea id="code-editor"
                                  class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all font-mono text-sm"
                                  rows="10"
                                  placeholder="Write your solution here (stdin below if needed)"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Standard Input (optional)
                        </label>
                        <textarea id="stdin"
                                  class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all font-mono text-sm"
                                  rows="4"
                                  placeholder="Input that will be piped to your program"></textarea>
                    </div>

                    <button id="run-code-btn"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                        Run Code
                    </button>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Output
                    </label>
                    <pre id="run-output"
                         class="bg-gray-900 text-green-400 rounded-lg p-4 text-sm overflow-auto whitespace-pre-wrap"
                         style="min-height: 200px;">Output will appear here...</pre>
                </div>
            </div>
        </div>

        <!-- Submission Form -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-indigo-600 mb-3">Submit Your Solution</h2>
            <form action="{{ route('student.coding_tests.submit', $codingQuestion->id) }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Solution Input -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-code mr-2 text-blue-400"></i>Your Solution
                        </label>
                        <textarea name="submitted_solution" required
                                  class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                  rows="5" placeholder="Enter your solution here">{{ old('submitted_solution') }}</textarea>
                        @error('submitted_solution')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8">
                        <button type="submit"
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200">
                            Submit Solution
                        </button>
                    </div>
                </div>
            </form>

            <!-- Feedback -->
            @if($submission)
                <div class="mt-6 p-4 rounded-lg {{ $submission->is_correct ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    <p class="font-semibold">
                        @if($submission->is_correct)
                            <i class="fas fa-check-circle mr-2"></i>Congratulations! Your solution is correct.
                        @else
                            <i class="fas fa-times-circle mr-2"></i>Sorry, your solution is incorrect. Please try again.
                        @endif
                    </p>
                    <p class="mt-2">Your submitted solution: <code>{{ $submission->submitted_solution }}</code></p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const runBtn = document.getElementById('run-code-btn');
    const outputBox = document.getElementById('run-output');
    const statusBox = document.getElementById('run-status');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (runBtn) {
        runBtn.addEventListener('click', async (event) => {
            event.preventDefault();
            runBtn.disabled = true;
            runBtn.classList.add('opacity-50', 'cursor-not-allowed');
            statusBox.textContent = 'Running...';
            statusBox.className = 'text-gray-700 text-sm font-semibold';
            outputBox.textContent = 'Running your code...';

            try {
                const response = await fetch("{{ route('student.coding_tests.run') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        language: document.getElementById('run-language').value,
                        code: document.getElementById('code-editor').value,
                        input: document.getElementById('stdin').value
                    })
                });

                if (!response.ok) {
                    throw new Error('Request failed with status ' + response.status);
                }

                const data = await response.json();
                outputBox.textContent = data.output || 'No output.';

                if (data.is_error) {
                    statusBox.textContent = `Error (${data.stage})`;
                    statusBox.className = 'text-red-600 text-sm font-semibold';
                } else {
                    statusBox.textContent = 'Success';
                    statusBox.className = 'text-green-600 text-sm font-semibold';
                }
            } catch (error) {
                outputBox.textContent = 'Error running code: ' + error.message;
                statusBox.textContent = 'Error';
                statusBox.className = 'text-red-600 text-sm font-semibold';
            } finally {
                runBtn.disabled = false;
                runBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    }
</script>
@endpush
