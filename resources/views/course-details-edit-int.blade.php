@extends('admin.layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Simplified and professional styles */
        .admin-panel {
            min-height: 100vh;
            background-color: #f9fafb;
        }
        body {
            background-color: #ffffff !important;
            background-image: none !important;
        }
        .form-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 1.5rem;
            background-color: #ffffff;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }
        .full-width {
            grid-column: 1 / -1;
        }
        .field-container {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        .field-container label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
        }
        .field-container input,
        .field-container textarea,
        .field-container select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            color: #111827;
            background-color: #fff;
            transition: border-color 0.2s;
        }
        .field-container input:focus,
        .field-container textarea:focus,
        .field-container select:focus {
            outline: none;
            border-color: #3b82f6;
        }
        .field-container textarea {
            resize: vertical;
            min-height: 80px;
        }
        .field-container select[multiple] {
            min-height: 100px;
        }
        .error {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        .success {
            color: #10b981;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background-color: #ecfdf5;
            border-radius: 6px;
        }
        .collapsible-section {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        .collapsible-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            background-color: #f9fafb;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
        }
        .collapsible-header:hover {
            background-color: #f3f4f6;
        }
        .collapsible-content {
            padding: 1rem;
            display: none;
        }
        .collapsible-content.active {
            display: block;
        }
        .dynamic-section {
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background-color: #f9fafb;
        }
        .dynamic-field {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        .dynamic-field:last-child {
            margin-bottom: 0;
        }
        .nested-section {
            padding: 0.5rem;
            border: 1px dashed #d1d5db;
            border-radius: 4px;
            margin-top: 0.5rem;
        }
        .button {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.2s;
            cursor: pointer;
        }
        .add-button {
            background-color: #3b82f6;
            color: #ffffff;
        }
        .add-button:hover {
            background-color: #2563eb;
        }
        .remove-button {
            background-color: #ef4444;
            color: #ffffff;
        }
        .remove-button:hover {
            background-color: #dc2626;
        }
        .submit-button {
            background-color: #10b981;
            color: #ffffff;
        }
        .submit-button:hover {
            background-color: #059669;
        }
        .chevron {
            transition: transform 0.2s;
        }
        .chevron.active {
            transform: rotate(180deg);
        }
    </style>

    <div class="px-4">
        <h1 class="section-title">Edit Internship Details</h1>

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('course.update.int', $courseDetail->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <!-- Basic Information Section -->
                <div class="collapsible-section full-width">
                    <div class="collapsible-header" onclick="toggleSection(this)">
                        <span>Basic Information</span>
                        <svg class="chevron w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="collapsible-content active">
                        <div class="form-grid">
                            <!-- Internship Name -->
                            <div class="field-container">
                                <label for="course_name">Internship Name</label>
                                <select name="internship_id" id="internship_id">
                                    @foreach($course_name as $course)
                                        <option value="{{ $course->id }}" {{ $courseDetail->internship_id == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                                    @endforeach
                                </select>
                                @error('course_name')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Internship Rating -->
                            <div class="field-container">
                                <label for="course_rating">Internship Rating (0-5)</label>
                                <input type="number" name="course_rating" id="course_rating" step="0.1" min="0" max="5" value="{{ old('course_rating', $courseDetail->course_rating) }}">
                                @error('course_rating')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Internship Rating by Student Number -->
                            <div class="field-container">
                                <label for="course_rating_student_number">Rated by Students (e.g., 15K)</label>
                                <input type="text" name="course_rating_student_number" id="course_rating_student_number" value="{{ old('course_rating_student_number', $courseDetail->course_rating_student_number) }}">
                                @error('course_rating_student_number')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Learners Enrolled -->
                            <div class="field-container">
                                <label for="course_learner_enrolled">Learners Enrolled (e.g., 30K)</label>
                                <input type="text" name="course_learner_enrolled" id="course_learner_enrolled" value="{{ old('course_learner_enrolled', $courseDetail->course_learner_enrolled) }}">
                                @error('course_learner_enrolled')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Internship Lecture Hours -->
                            <div class="field-container">
                                <label for="course_lecture_hours">Lecture Hours (e.g., 60)</label>
                                <input type="number" name="course_lecture_hours" id="course_lecture_hours" min="0" value="{{ old('course_lecture_hours', $courseDetail->course_lecture_hours) }}">
                                @error('course_lecture_hours')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Internship Problems -->
                            <div class="field-container">
                                <label for="course_problem_counts">Problems (e.g., 350)</label>
                                <input type="number" name="course_problem_counts" id="course_problem_counts" min="0" value="{{ old('course_problem_counts', $courseDetail->course_problem_counts) }}">
                                @error('course_problem_counts')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Internship Banner -->
                            <div class="field-container">
                                <label for="course_banner">Internship Banner Image</label>
                                <input type="file" name="course_banner" id="course_banner" accept="image/*">
                                @if($courseDetail->course_banner)
                                    <p class="text-sm text-gray-500">Current: <a href="{{ asset('storage/' . $courseDetail->course_banner) }}" target="_blank">View Banner</a></p>
                                @endif
                                @error('course_banner')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Internship Description Section -->
                <div class="collapsible-section full-width">
                    <div class="collapsible-header" onclick="toggleSection(this)">
                        <span>Internship Description</span>
                        <svg class="chevron w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="collapsible-content">
                        <div class="field-container">
                            <label for="course_description">Internship Description</label>
                            <textarea name="course_description" id="course_description">{{ old('course_description', $courseDetail->course_description) }}</textarea>
                            @error('course_description')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Internship Overview Description Section -->
                <div class="collapsible-section full-width">
                    <div class="collapsible-header" onclick="toggleSection(this)">
                        <span>About Internship Overview Description</span>
                        <svg class="chevron w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="collapsible-content">
                        <div class="field-container">
                            <label for="course_overview_description">Internship Overview Description</label>
                            <textarea name="course_overview_description" id="course_overview_description">{{ old('course_overview_description', $courseDetail->course_overview_description) }}</textarea>
                            @error('course_overview_description')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Learning Outcomes Section -->
                <div class="collapsible-section full-width">
                    <div class="collapsible-header" onclick="toggleSection(this)">
                        <span>Learning Outcomes</span>
                        <svg class="chevron w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="collapsible-content">
                        <div class="field-container">
                            <div id="outcomes-container" class="dynamic-section">
                                @foreach($courseDetail->learning_outcomes ?? [] as $index => $outcome)
                                    <div class="dynamic-field">
                                        <input type="text" name="learning_outcomes[]" required value="{{ old('learning_outcomes.' . $index, $outcome) }}">
                                        <button type="button" class="button remove-button" onclick="removeOutcome(this)">Remove</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="button add-button mt-2" onclick="addOutcome()">Add Learning Outcome</button>
                            @error('learning_outcomes')
                                <div class="error">{{ $message }}</div>
                            @enderror
                            @error('learning_outcomes.*')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Internship Curriculum Section -->
                <div class="collapsible-section full-width">
                    <div class="collapsible-header" onclick="toggleSection(this)">
                        <span>Internship Curriculum</span>
                        <svg class="chevron w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="collapsible-content">
                        <div class="field-container">
                            <div id="curriculum-container" class="dynamic-section">
                                @foreach($courseDetail->course_curriculum ?? [] as $moduleIndex => $module)
                                    <div class="dynamic-field">
                                        <div class="form-grid">
                                            <div class="field-container">
                                                <label>Module Number (e.g., Module {{ $moduleIndex }})</label>
                                                <input type="text" name="course_curriculum[{{ $moduleIndex }}][module_number]" required value="{{ old('course_curriculum.' . $moduleIndex . '.module_number', $module['module_number']) }}">
                                                @error('course_curriculum.' . $moduleIndex . '.module_number')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="field-container">
                                                <label>Title (e.g., Programming Fundamentals)</label>
                                                <input type="text" name="course_curriculum[{{ $moduleIndex }}][title]" required value="{{ old('course_curriculum.' . $moduleIndex . '.title', $module['title']) }}">
                                                @error('course_curriculum.' . $moduleIndex . '.title')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="field-container">
                                                <label>Duration (e.g., 4 Weeks)</label>
                                                <input type="text" name="course_curriculum[{{ $moduleIndex }}][duration]" required value="{{ old('course_curriculum.' . $moduleIndex . '.duration', $module['duration']) }}">
                                                @error('course_curriculum.' . $moduleIndex . '.duration')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="field-container full-width">
                                                <label>Description</label>
                                                <textarea name="course_curriculum[{{ $moduleIndex }}][description]" required>{{ old('course_curriculum.' . $moduleIndex . '.description', $module['description']) }}</textarea>
                                                @error('course_curriculum.' . $moduleIndex . '.description')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <!-- Topics Section -->
                                            <div class="field-container full-width">
                                                <label>Topics</label>
                                                @foreach($module['topics'] ?? [] as $topicIndex => $topic)
                                                    <div class="nested-section" id="topics-{{ $moduleIndex }}-{{ $topicIndex }}">
                                                        <div class="form-grid">
                                                            <div class="field-container">
                                                                <label>Topic Category (e.g., HTML)</label>
                                                                <input type="text" name="course_curriculum[{{ $moduleIndex }}][topics][{{ $topicIndex }}][category]" required value="{{ old('course_curriculum.' . $moduleIndex . '.topics.' . $topicIndex . '.category', $topic['category']) }}">
                                                            </div>
                                                            <div class="field-container full-width">
                                                                <label>Subtopics (one per line)</label>
                                                                <textarea name="course_curriculum[{{ $moduleIndex }}][topics][{{ $topicIndex }}][subtopics]" required placeholder="Enter subtopics, one per line">{{ old('course_curriculum.' . $moduleIndex . '.topics.' . $topicIndex . '.subtopics', implode("\n", $topic['subtopics'] ?? [])) }}</textarea>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="button remove-button mt-2" onclick="removeTopic({{ $moduleIndex }}, this)">Remove Topic</button>
                                                    </div>
                                                @endforeach
                                                <button type="button" class="button add-button mt-2" onclick="addTopic({{ $moduleIndex }})">Add Topic</button>
                                            </div>
                                        </div>
                                        <button type="button" class="button remove-button mt-2" onclick="removeCurriculum(this)">Remove Module</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="button add-button mt-2" onclick="addCurriculum()">Add Curriculum Module</button>
                            @error('course_curriculum')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Demo Syllabus Section -->
                <div class="collapsible-section full-width">
                    <div class="collapsible-header" onclick="toggleSection(this)">
                        <span>Demo Syllabus</span>
                        <svg class="chevron w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="collapsible-content">
                        <div class="field-container">
                            <div id="demo-syllabus-container" class="dynamic-section">
                                @foreach($courseDetail->demo_syllabus ?? [] as $moduleIndex => $module)
                                    <div class="dynamic-field">
                                        <div class="form-grid">
                                            <div class="field-container">
                                                <label>Module Number (e.g., Module {{ $moduleIndex }})</label>
                                                <input type="text" name="demo_syllabus[{{ $moduleIndex }}][module_number]" required value="{{ old('demo_syllabus.' . $moduleIndex . '.module_number', $module['module_number']) }}">
                                                @error('demo_syllabus.' . $moduleIndex . '.module_number')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="field-container">
                                                <label>Title (e.g., Programming Fundamentals)</label>
                                                <input type="text" name="demo_syllabus[{{ $moduleIndex }}][title]" required value="{{ old('demo_syllabus.' . $moduleIndex . '.title', $module['title']) }}">
                                                @error('demo_syllabus.' . $moduleIndex . '.title')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="field-container">
                                                <label>Duration (e.g., 4 Weeks)</label>
                                                <input type="text" name="demo_syllabus[{{ $moduleIndex }}][duration]" required value="{{ old('demo_syllabus.' . $moduleIndex . '.duration', $module['duration']) }}">
                                                @error('demo_syllabus.' . $moduleIndex . '.duration')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="field-container full-width">
                                                <label>Description</label>
                                                <textarea name="demo_syllabus[{{ $moduleIndex }}][description]" required>{{ old('demo_syllabus.' . $moduleIndex . '.description', $module['description']) }}</textarea>
                                                @error('demo_syllabus.' . $moduleIndex . '.description')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <!-- Topics Section -->
                                            <div class="field-container full-width">
                                                <label>Topics</label>
                                                @foreach($module['topics'] ?? [] as $topicIndex => $topic)
                                                    <div class="nested-section" id="demo-topics-{{ $moduleIndex }}-{{ $topicIndex }}">
                                                        <div class="form-grid">
                                                            <div class="field-container">
                                                                <label>Topic Category (e.g., HTML)</label>
                                                                <input type="text" name="demo_syllabus[{{ $moduleIndex }}][topics][{{ $topicIndex }}][category]" required value="{{ old('demo_syllabus.' . $moduleIndex . '.topics.' . $topicIndex . '.category', $topic['category']) }}">
                                                            </div>
                                                            <div class="field-container full-width">
                                                                <label>Subtopics (use comma(,) for multiple subtopics)</label>
                                                                <textarea name="demo_syllabus[{{ $moduleIndex }}][topics][{{ $topicIndex }}][subtopics]" required placeholder="Enter subtopics, (use comma(,) for multiple subtopics)">{{ old('demo_syllabus.' . $moduleIndex . '.topics.' . $topicIndex . '.subtopics', implode(',', $topic['subtopics'] ?? [])) }}</textarea>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="button remove-button mt-2" onclick="removeDemoTopic({{ $moduleIndex }}, this)">Remove Topic</button>
                                                    </div>
                                                @endforeach
                                                <button type="button" class="button add-button mt-2" onclick="addDemoTopic({{ $moduleIndex }})">Add Topic</button>
                                            </div>
                                        </div>
                                        <button type="button" class="button remove-button mt-2" onclick="removeDemoSyllabus(this)">Remove Module</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="button add-button mt-2" onclick="addDemoSyllabus()">Add Demo Syllabus Module</button>
                            @error('demo_syllabus')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Key Points Section -->
                <div class="collapsible-section full-width">
                    <div class="collapsible-header" onclick="toggleSection(this)">
                        <span>Key Points of Learning</span>
                        <svg class="chevron w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="collapsible-content">
                        <div class="field-container">
                            <div id="points-container" class="dynamic-section">
                                @foreach($courseDetail->key_points ?? [] as $index => $point)
                                    <div class="dynamic-field">
                                        <input type="text" name="points[]" required value="{{ old('points.' . $index, $point) }}">
                                        <button type="button" class="button remove-button" onclick="removePoint(this)">Remove</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="button add-button mt-2" onclick="addPoint()">Add Key Point</button>
                            @error('points')
                                <div class="error">{{ $message }}</div>
                            @enderror
                            @error('points.*')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Instructors Section -->
                <div class="collapsible-section full-width">
                    <div class="collapsible-header" onclick="toggleSection(this)">
                        <span>Instructors</span>
                        <svg class="chevron w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="楽しむ-content">
                        <div class="field-container">
                            <label for="instructor_ids">Select Instructors</label>
                            <select name="instructor_ids[]" id="instructor_ids" multiple required>
                                <option value="">Select Instructor</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ in_array($instructor->id, $courseDetail->instructor_ids ?? []) ? 'selected' : '' }}>{{ $instructor->name }}</option>
                                @endforeach
                            </select>
                            @error('instructor_ids')
                                <div class="error">{{ $message }}</div>
                            @enderror
                            @error('instructor_ids.*')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- FAQs Section -->
                <div class="collapsible-section full-width">
                    <div class="collapsible-header" onclick="toggleSection(this)">
                        <span>FAQs</span>
                        <svg class="chevron w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="collapsible-content">
                        <div class="field-container">
                            <div id="faqs-container" class="dynamic-section">
                                @foreach($courseDetail->faqs ?? [] as $index => $faq)
                                    <div class="dynamic-field">
                                        <div class="form-grid">
                                            <div class="field-container">
                                                <label>Question</label>
                                                <input type="text" name="faqs[{{ $index }}][question]" required value="{{ old('faqs.' . $index . '.question', $faq['question']) }}">
                                                @error('faqs.' . $index . '.question')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="field-container full-width">
                                                <label>Answer</label>
                                                <textarea name="faqs[{{ $index }}][answer]" required>{{ old('faqs.' . $index . '.answer', $faq['answer']) }}</textarea>
                                                @error('faqs.' . $index . '.answer')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <button type="button" class="button remove-button mt-2" onclick="removeFaq(this)">Remove FAQ</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="button add-button mt-2" onclick="addFaq()">Add FAQ</button>
                            @error('faqs')
                                <div class="error">{{ $message }}</div>
                            @enderror
                            @error('faqs.*')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Key Features Section -->
                <div class="collapsible-section full-width" style="background-color: #fefcbf;">
                    <div class="collapsible-header" onclick="toggleSection(this)">
                        <span>Key Features</span>
                        <svg class="chevron w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="collapsible-content">
                        <div class="field-container">
                            <div id="key-features-container" class="dynamic-section">
                                @foreach($courseDetail->key_features ?? [] as $index => $feature)
                                    <div class="dynamic-field">
                                        <div class="form-grid">
                                            <div class="field-container">
                                                <label>Icon (e.g., 📅 or fas fa-calendar)</label>
                                                <input type="text" name="key_features[{{ $index }}][icon]" required value="{{ old('key_features.' . $index . '.icon', $feature['icon']) }}">
                                                @error('key_features.' . $index . '.icon')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="field-container">
                                                <label>Topic (e.g., Self-paced Learning)</label>
                                                <input type="text" name="key_features[{{ $index }}][topic]" required value="{{ old('key_features.' . $index . '.topic', $feature['topic']) }}">
                                                @error('key_features.' . $index . '.topic')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="field-container full-width">
                                                <label>Description</label>
                                                <textarea name="key_features[{{ $index }}][description]" required>{{ old('key_features.' . $index . '.description', $feature['description']) }}</textarea>
                                                @error('key_features.' . $index . '.description')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <button type="button" class="button remove-button mt-2" onclick="removeKeyFeature(this)">Remove Feature</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="button add-button mt-2" onclick="addKeyFeature()">Add Key Feature</button>
                            @error('key_features')
                                <div class="error">{{ $message }}</div>
                            @enderror
                            @error('key_features.*')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Certifications Section -->
                <div class="collapsible-section full-width" style="background-color: #fefcbf;">
                    <div class="collapsible-header" onclick="toggleSection(this)">
                        <span>Internship Certificates</span>
                        <svg class="chevron w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="collapsible-content">
                        <div class="field-container">
                            <!-- Certificate Image -->
                            <div class="field-container">
                                <label for="certificate_image">Certificate Image</label>
                                <input type="file" name="certificate_image" id="certificate_image" accept="image/*">
                                @if($courseDetail->certificate_image)
                                    <p class="text-sm text-gray-500">Current: <a href="{{ asset('storage/' . $courseDetail->certificate_image) }}" target="_blank">View Certificate</a></p>
                                @endif
                                @error('certificate_image')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Certifications -->
                            <div id="certifications-container" class="dynamic-section">
                                @foreach($courseDetail->certifications ?? [] as $index => $certification)
                                    <div class="dynamic-field">
                                        <div class="form-grid">
                                            <div class="field-container full-width">
                                                <label>Certification Name (e.g., CCBA – Certification of Competency in Business Analysis)</label>
                                                <input type="text" name="certifications[{{ $index }}][name]" required value="{{ old('certifications.' . $index . '.name', $certification['name']) }}" placeholder="Enter certification name">
                                                @error('certifications.' . $index . '.name')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <button type="button" class="button remove-button mt-2" onclick="removeCertification(this)">Remove Certification</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="button add-button mt-2" onclick="addCertification()">Add Certification</button>
                            @error('certifications')
                                <div class="error">{{ $message }}</div>
                            @enderror
                            @error('certifications.*')
                                <div class="error">{{ $message }}</div>
                            @enderror
                            <!-- Certificate Descriptions -->
                            <div id="certificate-descriptions-container" class="dynamic-section">
                                @foreach($courseDetail->certificate_description ?? [] as $index => $description)
                                    <div class="dynamic-field">
                                        <div class="form-grid">
                                            <div class="field-container full-width">
                                                <label>Certificate Description (e.g., Our business analyst Master’s program...)</label>
                                                <textarea name="certificate_description[{{ $index }}][text]" required placeholder="Enter certificate description">{{ old('certificate_description.' . $index . '.text', $description['text']) }}</textarea>
                                                @error('certificate_description.' . $index . '.text')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <button type="button" class="button remove-button mt-2" onclick="removeCertificateDescription(this)">Remove Description</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="button add-button mt-2" onclick="addCertificateDescription()">Add Certificate Description</button>
                            @error('certificate_description')
                                <div class="error">{{ $message }}</div>
                            @enderror
                            @error('certificate_description.*')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="field-container">
                    <button type="submit" class="button submit-button">Update Internship Detail</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let moduleCount = {{ count($courseDetail->course_curriculum ?? []) }};
        let demoModuleCount = {{ count($courseDetail->demo_syllabus ?? []) }};
        let faqCount = {{ count($courseDetail->faqs ?? []) }};
        let keyFeatureCount = {{ count($courseDetail->key_features ?? []) }};
        let certificationCount = {{ count($courseDetail->certifications ?? []) }};
        let certificateDescriptionCount = {{ count($courseDetail->certificate_description ?? []) }};
        let topicCounts = {};
        let demoTopicCounts = {};

        // Initialize topic counts for existing curriculum modules
        @foreach($courseDetail->course_curriculum ?? [] as $index => $module)
            topicCounts[{{ $index }}] = {{ count($module['topics'] ?? []) }};
        @endforeach

        // Initialize topic counts for existing demo syllabus modules
        @foreach($courseDetail->demo_syllabus ?? [] as $index => $module)
            demoTopicCounts[{{ $index }}] = {{ count($module['topics'] ?? []) }};
        @endforeach

        function toggleSection(element) {
            const content = element.nextElementSibling;
            const chevron = element.querySelector('.chevron');
            content.classList.toggle('active');
            chevron.classList.toggle('active');
        }

        function addOutcome() {
            const container = document.getElementById('outcomes-container');
            const div = document.createElement('div');
            div.className = 'dynamic-field';
            div.innerHTML = `
                <input type="text" name="learning_outcomes[]" required>
                <button type="button" class="button remove-button" onclick="removeOutcome(this)">Remove</button>
            `;
            container.appendChild(div);
        }

        function removeOutcome(button) {
            const fields = document.querySelectorAll('#outcomes-container .dynamic-field');
            if (fields.length > 1) {
                button.parentElement.remove();
            } else {
                alert('At least one learning outcome is required.');
            }
        }

        function addCurriculum() {
            topicCounts[moduleCount] = 1; // Initialize topic count for new module
            const container = document.getElementById('curriculum-container');
            const div = document.createElement('div');
            div.className = 'dynamic-field';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="field-container">
                        <label>Module Number (e.g., Module ${moduleCount})</label>
                        <input type="text" name="course_curriculum[${moduleCount}][module_number]" required>
                    </div>
                    <div class="field-container">
                        <label>Title</label>
                        <input type="text" name="course_curriculum[${moduleCount}][title]" required>
                    </div>
                    <div class="field-container">
                        <label>Duration (e.g., 4 Weeks)</label>
                        <input type="text" name="course_curriculum[${moduleCount}][duration]" required>
                    </div>
                    <div class="field-container full-width">
                        <label>Description</label>
                        <textarea name="course_curriculum[${moduleCount}][description]" required></textarea>
                    </div>
                    <div class="field-container full-width">
                        <label>Topics</label>
                        <div class="nested-section" id="topics-${moduleCount}-0">
                            <div class="form-grid">
                                <div class="field-container">
                                    <label>Topic Category (e.g., HTML)</label>
                                    <input type="text" name="course_curriculum[${moduleCount}][topics][0][category]" required>
                                </div>
                                <div class="field-container full-width">
                                    <label>Subtopics (one per line)</label>
                                    <textarea name="course_curriculum[${moduleCount}][topics][0][subtopics]" required placeholder="Enter subtopics, one per line"></textarea>
                                </div>
                            </div>
                            <button type="button" class="button remove-button mt-2" onclick="removeTopic(${moduleCount}, this)">Remove Topic</button>
                        </div>
                        <button type="button" class="button add-button mt-2" onclick="addTopic(${moduleCount})">Add Topic</button>
                    </div>
                </div>
                <button type="button" class="button remove-button mt-2" onclick="removeCurriculum(this)">Remove Module</button>
            `;
            container.appendChild(div);
            moduleCount++;
        }

        function removeCurriculum(button) {
            const fields = document.querySelectorAll('#curriculum-container .dynamic-field');
            if (fields.length > 1) {
                const moduleIndex = Array.from(fields).indexOf(button.parentElement);
                delete topicCounts[moduleIndex];
                button.parentElement.remove();
                moduleCount--;
            } else {
                alert('At least one curriculum module is required.');
            }
        }

        function addTopic(moduleIndex) {
            if (!topicCounts[moduleIndex]) topicCounts[moduleIndex] = 0;
            const topicIndex = topicCounts[moduleIndex]++;
            const container = document.getElementById(`topics-${moduleIndex}-${topicIndex - 1}`).parentElement;
            const div = document.createElement('div');
            div.className = 'nested-section';
            div.id = `topics-${moduleIndex}-${topicIndex}`;
            div.innerHTML = `
                <div class="form-grid">
                    <div class="field-container">
                        <label>Topic Category (e.g., HTML)</label>
                        <input type="text" name="course_curriculum[${moduleIndex}][topics][${topicIndex}][category]" required>
                    </div>
                    <div class="field-container full-width">
                        <label>Subtopics (one per line)</label>
                        <textarea name="course_curriculum[${moduleIndex}][topics][${topicIndex}][subtopics]" required placeholder="Enter subtopics, one per line"></textarea>
                    </div>
                </div>
                <button type="button" class="button remove-button mt-2" onclick="removeTopic(${moduleIndex}, this)">Remove Topic</button>
            `;
            container.insertBefore(div, container.lastElementChild);
        }

        function removeTopic(moduleIndex, button) {
            const topicContainer = button.parentElement.parentElement;
            const topics = topicContainer.querySelectorAll('.nested-section');
            if (topics.length > 1) {
                button.parentElement.remove();
                topicCounts[moduleIndex]--;
            } else {
                alert('At least one topic is required per module.');
            }
        }

        function addDemoSyllabus() {
            demoTopicCounts[demoModuleCount] = 1; // Initialize topic count for new demo module
            const container = document.getElementById('demo-syllabus-container');
            const div = document.createElement('div');
            div.className = 'dynamic-field';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="field-container">
                        <label>Module Number (e.g., Module ${demoModuleCount})</label>
                        <input type="text" name="demo_syllabus[${demoModuleCount}][module_number]" required>
                    </div>
                    <div class="field-container">
                        <label>Title</label>
                        <input type="text" name="demo_syllabus[${demoModuleCount}][title]" required>
                    </div>
                    <div class="field-container">
                        <label>Duration (e.g., 4 Weeks)</label>
                        <input type="text" name="demo_syllabus[${demoModuleCount}][duration]" required>
                    </div>
                    <div class="field-container full-width">
                        <label>Description</label>
                        <textarea name="demo_syllabus[${demoModuleCount}][description]" required></textarea>
                    </div>
                    <div class="field-container full-width">
                        <label>Topics</label>
                        <div class="nested-section" id="demo-topics-${demoModuleCount}-0">
                            <div class="form-grid">
                                <div class="field-container">
                                    <label>Topic Category (e.g., HTML)</label>
                                    <input type="text" name="demo_syllabus[${demoModuleCount}][topics][0][category]" required>
                                </div>
                                <div class="field-container full-width">
                                    <label>Subtopics (use comma(,) for multiple subtopics)</label>
                                    <textarea name="demo_syllabus[${demoModuleCount}][topics][0][subtopics]" required placeholder="Enter subtopics, (use comma(,) for multiple subtopics)"></textarea>
                                </div>
                            </div>
                            <button type="button" class="button remove-button mt-2" onclick="removeDemoTopic(${demoModuleCount}, this)">Remove Topic</button>
                        </div>
                        <button type="button" class="button add-button mt-2" onclick="addDemoTopic(${demoModuleCount})">Add Topic</button>
                    </div>
                </div>
                <button type="button" class="button remove-button mt-2" onclick="removeDemoSyllabus(this)">Remove Module</button>
            `;
            container.appendChild(div);
            demoModuleCount++;
        }

        function removeDemoSyllabus(button) {
            const fields = document.querySelectorAll('#demo-syllabus-container .dynamic-field');
            if (fields.length > 1) {
                const moduleIndex = Array.from(fields).indexOf(button.parentElement);
                delete demoTopicCounts[moduleIndex];
                button.parentElement.remove();
                demoModuleCount--;
            } else {
                alert('At least one demo syllabus module is required.');
            }
        }

        function addDemoTopic(moduleIndex) {
            if (!demoTopicCounts[moduleIndex]) demoTopicCounts[moduleIndex] = 0;
            const topicIndex = demoTopicCounts[moduleIndex]++;
            const container = document.getElementById(`demo-topics-${moduleIndex}-${topicIndex - 1}`).parentElement;
            const div = document.createElement('div');
            div.className = 'nested-section';
            div.id = `demo-topics-${moduleIndex}-${topicIndex}`;
            div.innerHTML = `
                <div class="form-grid">
                    <div class="field-container">
                        <label>Topic Category (e.g., HTML)</label>
                        <input type="text" name="demo_syllabus[${moduleIndex}][topics][${topicIndex}][category]" required>
                    </div>
                    <div class="field-container full-width">
                        <label>Subtopics (use comma(,) for multiple subtopics)</label>
                        <textarea name="demo_syllabus[${moduleIndex}][topics][${topicIndex}][subtopics]" required placeholder="Enter subtopics, (use comma(,) for multiple subtopics)"></textarea>
                    </div>
                </div>
                <button type="button" class="button remove-button mt-2" onclick="removeDemoTopic(${moduleIndex}, this)">Remove Topic</button>
            `;
            container.insertBefore(div, container.lastElementChild);
        }

        function removeDemoTopic(moduleIndex, button) {
            const topicContainer = button.parentElement.parentElement;
            const topics = topicContainer.querySelectorAll('.nested-section');
            if (topics.length > 1) {
                button.parentElement.remove();
                demoTopicCounts[moduleIndex]--;
            } else {
                alert('At least one topic is required per demo module.');
            }
        }

        function addPoint() {
            const container = document.getElementById('points-container');
            const div = document.createElement('div');
            div.className = 'dynamic-field';
            div.innerHTML = `
                <input type="text" name="points[]" required>
                <button type="button" class="button remove-button" onclick="removePoint(this)">Remove</button>
            `;
            container.appendChild(div);
        }

        function removePoint(button) {
            const fields = document.querySelectorAll('#points-container .dynamic-field');
            if (fields.length > 1) {
                button.parentElement.remove();
            } else {
                alert('At least one key point is required.');
            }
        }

        function addFaq() {
            const container = document.getElementById('faqs-container');
            const div = document.createElement('div');
            div.className = 'dynamic-field';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="field-container">
                        <label>Question</label>
                        <input type="text" name="faqs[${faqCount}][question]" required>
                    </div>
                    <div class="field-container full-width">
                        <label>Answer</label>
                        <textarea name="faqs[${faqCount}][answer]" required></textarea>
                    </div>
                </div>
                <button type="button" class="button remove-button mt-2" onclick="removeFaq(this)">Remove FAQ</button>
            `;
            container.appendChild(div);
            faqCount++;
        }

        function removeFaq(button) {
            const fields = document.querySelectorAll('#faqs-container .dynamic-field');
            if (fields.length > 1) {
                button.parentElement.remove();
                faqCount--;
            } else {
                alert('At least one FAQ is required.');
            }
        }

        function addKeyFeature() {
            const container = document.getElementById('key-features-container');
            const div = document.createElement('div');
            div.className = 'dynamic-field';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="field-container">
                        <label>Icon (e.g., 📅 or fas fa-calendar)</label>
                        <input type="text" name="key_features[${keyFeatureCount}][icon]" required>
                    </div>
                    <div class="field-container">
                        <label>Topic</label>
                        <input type="text" name="key_features[${keyFeatureCount}][topic]" required>
                    </div>
                    <div class="field-container full-width">
                        <label>Description</label>
                        <textarea name="key_features[${keyFeatureCount}][description]" required></textarea>
                    </div>
                </div>
                <button type="button" class="button remove-button mt-2" onclick="removeKeyFeature(this)">Remove Feature</button>
            `;
            container.appendChild(div);
            keyFeatureCount++;
        }

        function removeKeyFeature(button) {
            const fields = document.querySelectorAll('#key-features-container .dynamic-field');
            if (fields.length > 1) {
                button.parentElement.remove();
                keyFeatureCount--;
            } else {
                alert('At least one key feature is required.');
            }
        }

        function addCertification() {
            const container = document.getElementById('certifications-container');
            const div = document.createElement('div');
            div.className = 'dynamic-field';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="field-container full-width">
                        <label>Certification Name (e.g., CCBA – Certification of Competency in Business Analysis)</label>
                        <input type="text" name="certifications[${certificationCount}][name]" required placeholder="Enter certification name">
                    </div>
                </div>
                <button type="button" class="button remove-button mt-2" onclick="removeCertification(this)">Remove Certification</button>
            `;
            container.appendChild(div);
            certificationCount++;
        }

        function removeCertification(button) {
            const fields = document.querySelectorAll('#certifications-container .dynamic-field');
            if (fields.length > 1) {
                button.parentElement.remove();
                certificationCount--;
            } else {
                alert('At least one certification is required.');
            }
        }

        function addCertificateDescription() {
            const container = document.getElementById('certificate-descriptions-container');
            const div = document.createElement('div');
            div.className = 'dynamic-field';
            div.innerHTML = `
                <div class="form-grid">
                    <div class="field-container full-width">
                        <label>Certificate Description (e.g., Our business analyst Master’s program...)</label>
                        <textarea name="certificate_description[${certificateDescriptionCount}][text]" required placeholder="Enter certificate description"></textarea>
                    </div>
                </div>
                <button type="button" class="button remove-button mt-2" onclick="removeCertificateDescription(this)">Remove Description</button>
            `;
            container.appendChild(div);
            certificateDescriptionCount++;
        }

        function removeCertificateDescription(button) {
            const fields = document.querySelectorAll('#certificate-descriptions-container .dynamic-field');
            if (fields.length > 1) {
                button.parentElement.remove();
                certificateDescriptionCount--;
            } else {
                alert('At least one certificate description is required.');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#instructor_ids", {
                placeholder: "Select Instructor",
                maxItems: null,
                create: false,
                sortField: 'text',
                closeAfterSelect: false
            });
        });
    </script>
@endsection
