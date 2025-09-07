<!-- Sidebar Container -->
<!-- Sidebar Container -->
<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .sidebar {
        height: 100vh; /* Full height of the viewport */
        overflow-y: auto; /* Enable vertical scrolling */
    }
</style>
<!-- Logo -->
<div class="text-center mb-4 mt-4 flex-shrink-0">
    <img src="https://think-champ.com/wp-content/uploads/2024/05/THINK-CHAMP-logo-1024x502.png" alt="Logo"
        class="h-12 w-auto mx-auto">
</div>

<!-- Navigation -->
<nav class="bg-white shadow-lg rounded-tl-lg rounded-tr-lg p-4 flex-grow sidebar scrollbar-hide">
    <ul class="list-none p-0 m-0 space-y-2 !text-sm">
        <!-- Dashboard -->
        <li>
            <a href="{{ route('admin.dash') }}"
                class="flex items-center p-3 {{ request()->routeIs('admin.dash') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                <i class="fas fa-home mr-3 text-lg"></i> Dashboard
            </a>
        </li>

        @if (auth()->user()->role == 3)
            <!-- Student Quiz -->
            <li>
                <a href="{{ route('student.quiz_sets') }}"
                    class="flex items-center p-3 {{ request()->routeIs('student.quiz_sets') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-question-circle mr-3 text-lg"></i> Quizzes
                </a>
                <a href="{{ route('student.coding_tests.index') }}"
                    class="flex items-center p-3 {{ request()->routeIs('student.coding_tests.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-code mr-3 text-lg"></i> Coding Tests
                </a>
            </li>
               <li x-data="{ isOpen: {{ request()->routeIs('student.slots.*') ? 'true' : 'false' }} }">
            <a href="javascript:void(0)" @click="isOpen = !isOpen"
                class="flex items-center justify-between p-3 {{ request()->routeIs('student.slots.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                <span class="flex items-center">
                    <i class="fas fa-briefcase mr-3 text-lg"></i> Mock Interview
                </span>
                <i class="fas fa-chevron-down text-sm transition-transform" :class="{ 'rotate-180': isOpen }"></i>
            </a>
            <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                <li>
                    <a href="{{ route('student.slots') }}"
                        class="flex items-center p-2 text-sm {{ request()->routeIs('student.slots') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                        <i class="fas fa-plus-circle mr-2"></i> Mock Interview
                    </a>
                </li>         
            </ul>
        </li>
        @endif

        @if (auth()->user()->role == 2 || auth()->user()->role == 3)
            <!-- Chat -->
            <li>
                <a href="{{ route('chat.index') }}"
                    class="flex items-center p-3 {{ request()->routeIs('chat.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-comments mr-3 text-lg"></i> Chat
                </a>
            </li>
        @endif

        @if (auth()->user()->role == 2)
            <!-- Trainer Batches -->
            <li>
                <a href="{{ route('get-trainer-course') }}"
                    class="flex items-center p-3 {{ request()->routeIs('get-trainer-course') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-users mr-3 text-lg"></i> My Batches
                </a>
            </li>
        @endif

        @if (auth()->user()->role == 3)
            <!-- Student Classes -->
            <li>
                <a href="{{ route('student.classes.index') }}"
                    class="flex items-center p-3 {{ request()->routeIs('student.classes.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-chalkboard mr-3 text-lg"></i> My Classes
                </a>
            </li>
            <!-- Attendance -->
            <li>
                <a href="{{ route('student.attendance') }}"
                    class="flex items-center p-3 {{ request()->routeIs('student.attendance') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-check-square mr-3 text-lg"></i> Attendance
                </a>
            </li>
            <!-- Recordings -->
            <li>
                <a href="{{ route('recordings') }}"
                    class="flex items-center p-3 {{ request()->routeIs('recordings') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-video mr-3 text-lg"></i> Recordings
                </a>
            </li>
            <!-- Assignments -->
            <li>
                <a href="{{ route('student.assignments') }}"
                    class="flex items-center p-3 {{ request()->routeIs('student.assignments') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-tasks mr-3 text-lg"></i> Assignments
                </a>
            </li>
            <!-- Internships -->
            <li>
                <a href="{{ route('student.internships.index') }}"
                    class="flex items-center p-3 {{ request()->routeIs('student.internships.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-briefcase mr-3 text-lg"></i> Internships
                </a>
            </li>
            <li>
                <a href="{{ route('student.internship.class') }}"
                    class="flex items-center p-3 {{ request()->routeIs('student.internship.class') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-briefcase mr-3 text-lg"></i> Internship Content
                </a>
            </li>
            <li>
                <a href="{{ route('student.classes.index.int') }}"
                    class="flex items-center p-3 {{ request()->routeIs('student.classes.index.int') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-briefcase mr-3 text-lg"></i> Internship Classes
                </a>
            </li>
        @endif

        @if (auth()->user()->role == 1)
            <!-- Home Page -->
            <li>
                <a href="{{ route('admin.home') }}"
                    class="flex items-center p-3 {{ request()->routeIs('admin.home') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-home mr-3 text-lg"></i> Home Page
                </a>
            </li>
            <!-- Courses -->
            <li x-data="{ isOpen: {{ request()->routeIs('admin.course.*') ? 'true' : 'false' }} }">
                <a href="javascript:void(0)" @click="isOpen = !isOpen"
                    class="flex items-center justify-between p-3 {{ request()->routeIs('admin.course.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <span class="flex items-center">
                        <i class="fas fa-book mr-3 text-lg"></i> Courses
                    </span>
                    <i class="fas fa-chevron-down text-sm transition-transform" :class="{ 'rotate-180': isOpen }"></i>
                </a>
                <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                    <li>
                        <a href="{{ route('admin.course.add') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.course.add') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-plus-circle mr-2"></i> Add Course
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.course.list') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.course.list') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-list mr-2"></i> View Courses
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('course-details-index') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('course-details-index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-plus-circle mr-2"></i> Add Course Details
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Internships -->
            <li x-data="{ isOpen: {{ request()->routeIs('admin.internship.*') || request()->routeIs('admin.internship-batches.*') || request()->routeIs('admin.internship-recording-courses.*') || request()->routeIs('admin.internship.class.*') ? 'true' : 'false' }} }">
                <a href="javascript:void(0)" @click="isOpen = !isOpen"
                    class="flex items-center justify-between p-3 {{ request()->routeIs('admin.internship.*') || request()->routeIs('admin.internship-batches.*') || request()->routeIs('admin.internship-recording-courses.*') || request()->routeIs('admin.internship.class.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <span class="flex items-center">
                        <i class="fas fa-briefcase mr-3 text-lg"></i> Internships
                    </span>
                    <i class="fas fa-chevron-down text-sm transition-transform" :class="{ 'rotate-180': isOpen }"></i>
                </a>
                <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                    <li>
                        <a href="{{ route('admin.internship.add') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.internship.add') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-plus mr-2"></i> Add Internship
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.internship.list') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.internship.list') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-table-list mr-2"></i> View Internships
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('course-details-index-int') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('course-details-index-int') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-table-list mr-2"></i> Add Internship Details
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('get-internship-list') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('get-internship-list') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-clipboard-list mr-2"></i> Internship List
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.batches.add.int') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.batches.add.int') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-users mr-2"></i> Add Internship Batch
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.batches.index.int') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.batches.index.int') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-users mr-2"></i> View Internship Batches
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.live_classes.create.int') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.live_classes.create.int') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-chalkboard mr-2"></i> Add Class
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.live_classes.index.int') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.internship.class.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-chalkboard mr-2"></i> View Classes
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.recordings.index.int') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.recordings.index.int') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-video mr-2"></i> View Recordings
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.internship-enrollment-view') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.internship-enrollment-view') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-users mr-2"></i> View Enrollments
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Enrollments -->
            <li x-data="{ isOpen: {{ request()->routeIs('admin.enrollment.*') ? 'true' : 'false' }} }">
                <a href="javascript:void(0)" @click="isOpen = !isOpen"
                    class="flex items-center justify-between p-3 {{ request()->routeIs('admin.enrollment.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <span class="flex items-center">
                        <i class="fas fa-user-plus mr-3 text-lg"></i> Enrollments
                    </span>
                    <i class="fas fa-chevron-down text-sm transition-transform" :class="{ 'rotate-180': isOpen }"></i>
                </a>
                <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                    <li>
                        <a href="{{ route('admin.enrollment.index') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.enrollment.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-list mr-2"></i> View Enrollments
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Coding Module -->
            <li x-data="{ isOpen: {{ request()->routeIs('admin.coding_questions.*') ? 'true' : 'false' }} }">
                <a href="javascript:void(0)" @click="isOpen = !isOpen"
                    class="flex items-center justify-between p-3 {{ request()->routeIs('admin.coding_questions.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <span class="flex items-center">
                        <i class="fas fa-code mr-3 text-lg"></i> Coding Module
                    </span>
                    <i class="fas fa-chevron-down text-sm transition-transform" :class="{ 'rotate-180': isOpen }"></i>
                </a>
                <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                    <li>
                        <a href="{{ route('admin.coding_questions.index') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.coding_questions.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-list mr-2"></i> View Questions
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Batches -->
            <li x-data="{ isOpen: {{ request()->routeIs('admin.batches.*') ? 'true' : 'false' }} }">
                <a href="javascript:void(0)" @click="isOpen = !isOpen"
                    class="flex items-center justify-between p-3 {{ request()->routeIs('admin.batches.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <span class="flex items-center">
                        <i class="fas fa-users mr-3 text-lg"></i> Batches
                    </span>
                    <i class="fas fa-chevron-down text-sm transition-transform" :class="{ 'rotate-180': isOpen }"></i>
                </a>
                <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                    <li>
                        <a href="{{ route('admin.batches.add') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.batches.add') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-plus-circle mr-2"></i> Add Batch
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.batches.index') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.batches.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-list mr-2"></i> View Batches
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Recordings -->
            <li x-data="{ isOpen: {{ request()->routeIs('admin.recordings.*') ? 'true' : 'false' }} }">
                <a href="javascript:void(0)" @click="isOpen = !isOpen"
                    class="flex items-center justify-between p-3 {{ request()->routeIs('admin.recordings.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <span class="flex items-center">
                        <i class="fas fa-video mr-3 text-lg"></i> Recordings
                    </span>
                    <i class="fas fa-chevron-down text-sm transition-transform" :class="{ 'rotate-180': isOpen }"></i>
                </a>
                <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                    <li>
                        <a href="{{ route('admin.recordings.create') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.recordings.create') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-plus-circle mr-2"></i> Add Recording
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.recordings.index') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.recordings.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-list mr-2"></i> View Recordings
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Live Classes -->
            <li x-data="{ isOpen: {{ request()->routeIs('admin.live_classes.*') ? 'true' : 'false' }} }">
                <a href="javascript:void(0)" @click="isOpen = !isOpen"
                    class="flex items-center justify-between p-3 {{ request()->routeIs('admin.live_classes.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <span class="flex items-center">
                        <i class="fas fa-chalkboard-teacher mr-3 text-lg"></i> Live Classes
                    </span>
                    <i class="fas fa-chevron-down text-sm transition-transform" :class="{ 'rotate-180': isOpen }"></i>
                </a>
                <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                    <li>
                        <a href="{{ route('admin.live_classes.create') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.live_classes.create') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-plus-circle mr-2"></i> Add Live Class
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.live_classes.index') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.live_classes.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-list mr-2"></i> View Live Classes
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Quizzes -->
            <li>
                <a href="{{ route('admin.quiz_sets') }}"
                    class="flex items-center p-3 {{ request()->routeIs('admin.quiz_sets') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-question-circle mr-3 text-lg"></i> Quizzes
                </a>
                <a href="{{ route('student.batch_quiz_ranking') }}"
                    class="flex items-center p-3 {{ request()->routeIs('student.batch_quiz_ranking') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-trophy mr-3 text-lg"></i> Rankings
                </a>
            </li>
            <!-- Students -->
            <li>
                <a href="{{ route('student-management') }}"
                    class="flex items-center p-3 {{ request()->routeIs('student-management') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-user-graduate mr-3 text-lg"></i> Students
                </a>
            </li>
            <!-- Trainers -->
            <li>
                <a href="{{ route('trainer-management') }}"
                    class="flex items-center p-3 {{ request()->routeIs('trainer-management') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-user-tie mr-3 text-lg"></i> Trainers
                </a>
            </li>
            <!-- News -->
            <li x-data="{ isOpen: {{ request()->routeIs('admin.news.*') || request()->routeIs('admin.news-categories.*') ? 'true' : 'false' }} }">
                <a href="javascript:void(0)" @click="isOpen = !isOpen"
                    class="flex items-center justify-between p-3 {{ request()->routeIs('admin.news.*') || request()->routeIs('admin.news-categories.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <span class="flex items-center">
                        <i class="fas fa-newspaper mr-3 text-lg"></i> News
                    </span>
                    <i class="fas fa-chevron-down text-sm transition-transform" :class="{ 'rotate-180': isOpen }"></i>
                </a>
                <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                    <li>
                        <a href="{{ route('admin.news-categories.create') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.news-categories.create') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-folder-plus mr-2"></i> Add Category
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.news-categories.index') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.news-categories.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-folder mr-2"></i> View Categories
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.news.create') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.news.create') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-plus-circle mr-2"></i> Add News
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.news.index') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.news.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-list mr-2"></i> View News
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Events -->
            <li x-data="{ isOpen: {{ request()->routeIs('admin.events.*') || request()->routeIs('admin.event-categories.*') ? 'true' : 'false' }} }">
                <a href="javascript:void(0)" @click="isOpen = !isOpen"
                    class="flex items-center justify-between p-3 {{ request()->routeIs('admin.events.*') || request()->routeIs('admin.event-categories.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <span class="flex items-center">
                        <i class="fas fa-calendar-alt mr-3 text-lg"></i> Events
                    </span>
                    <i class="fas fa-chevron-down text-sm transition-transform" :class="{ 'rotate-180': isOpen }"></i>
                </a>
                <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                    <li>
                        <a href="{{ route('admin.event-categories.create') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.event-categories.create') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-folder-plus mr-2"></i> Add Category
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.event-categories.index') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.event-categories.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-folder mr-2"></i> View Categories
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.events.create') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.events.create') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-plus-circle mr-2"></i> Add Event
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.events.index') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.events.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-list mr-2"></i> View Events
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.events.enrollments') }}"
                            class="flex items-center p-2 text-sm {{ request()->routeIs('admin.events.enrollments') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                            <i class="fas fa-users mr-2"></i> View Enrollments
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if (auth()->user()->role == 1 || auth()->user()->role == 2)
            <!-- Attendance -->
            <li>
                <a href="{{ route('admin.leaves') }}"
                    class="flex items-center p-3 {{ request()->routeIs('admin.leaves') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-check-square mr-3 text-lg"></i> Attendance
                </a>
            </li>
            <!-- Assignments -->
            <li>
                <a href="{{ route('admin.assignments.create') }}"
                    class="flex items-center p-3 {{ request()->routeIs('admin.assignments.create') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                    <i class="fas fa-tasks mr-3 text-lg"></i> Assignments
                </a>
            </li>
        @endif

        @if (auth()->user()->role == 1)
        <!-- Career Highlights and Reviews -->
        <li x-data="{ isOpen: {{ request()->routeIs('admin.career_highlight.*') ? 'true' : 'false' }} }">
            <a href="javascript:void(0)" @click="isOpen = !isOpen"
                class="flex items-center justify-between p-3 {{ request()->routeIs('admin.career_highlight.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                <span class="flex items-center">
                    <i class="fas fa-briefcase mr-3 text-lg"></i> Career Highlights and Reviews
                </span>
                <i class="fas fa-chevron-down text-sm transition-transform" :class="{ 'rotate-180': isOpen }"></i>
            </a>
            <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                <li>
                    <a href="{{ route('admin.career_highlight.create') }}"
                        class="flex items-center p-2 text-sm {{ request()->routeIs('admin.career_highlight.create') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                        <i class="fas fa-plus-circle mr-2"></i> Create Career Highlights
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.career_highlight.show') }}"
                        class="flex items-center p-2 text-sm {{ request()->routeIs('admin.career_highlight.show') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                        <i class="fas fa-plus-circle mr-2"></i> Show Career Highlights
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.testimonials.index') }}"
                        class="flex items-center p-2 text-sm {{ request()->routeIs('admin.testimonials.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                        <i class="fas fa-plus-circle mr-2"></i> Show testimonial
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.youtubereview.index') }}"
                        class="flex items-center p-2 text-sm {{ request()->routeIs('admin.youtubereview.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                        <i class="fas fa-plus-circle mr-2"></i> Show Youtube Reviews
                    </a>
                </li>
            </ul>
        </li>
    @endif

    @if (auth()->user()->role == 1)
        <!-- Webinar -->
        <li x-data="{ isOpen: {{ request()->routeIs('admin.webinar.*') ? 'true' : 'false' }} }">
            <a href="javascript:void(0)" @click="isOpen = !isOpen"
                class="flex items-center justify-between p-3 {{ request()->routeIs('admin.webinar.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                <span class="flex items-center">
                    <i class="fas fa-briefcase mr-3 text-lg"></i> Webinar
                </span>
                <i class="fas fa-chevron-down text-sm transition-transform" :class="{ 'rotate-180': isOpen }"></i>
            </a>
            <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                <li>
                    <a href="{{ route('admin.webinar.index') }}"
                        class="flex items-center p-2 text-sm {{ request()->routeIs('admin.webinar.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                        <i class="fas fa-plus-circle mr-2"></i> Show Webinars 
                    </a>
                </li>         
            </ul>
            <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                <li>
                    <a href="{{ route('admin.webinar.enrollments') }}"
                        class="flex items-center p-2 text-sm {{ request()->routeIs('admin.webinar.enrollments') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                        <i class="fas fa-plus-circle mr-2"></i> Show Webinar Enrollments 
                    </a>
                </li>         
            </ul>
        </li>

    @endif

    @if (auth()->user()->role == 1)
        <!-- Hire With Us -->
        <li x-data="{ isOpen: {{ request()->routeIs('admin.job-roles.*') ? 'true' : 'false' }} }">
            <a href="javascript:void(0)" @click="isOpen = !isOpen"
                class="flex items-center justify-between p-3 {{ request()->routeIs('admin.job-roles.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                <span class="flex items-center">
                    <i class="fas fa-briefcase mr-3 text-lg"></i> Hire With Us
                </span>
                <i class="fas fa-chevron-down text-sm transition-transform" :class="{ 'rotate-180': isOpen }"></i>
            </a>
            <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                <li>
                    <a href="{{ route('admin.job-roles.index') }}"
                        class="flex items-center p-2 text-sm {{ request()->routeIs('admin.job-roles.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                        <i class="fas fa-plus-circle mr-2"></i> Show Job Roles 
                    </a>
                </li>         
            </ul>
        </li>

    @endif

    @if (auth()->user()->role == 1)
        <!-- Enrollment -->
         <li x-data="{ isOpen: {{ request()->routeIs('enrollment.report.*') ? 'true' : 'false' }} }">
            <a href="javascript:void(0)" @click="isOpen = !isOpen"
                class="flex items-center justify-between p-3 {{ request()->routeIs('enrollment.report.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                <span class="flex items-center">
                    <i class="fas fa-briefcase mr-3 text-lg"></i> Issue Internship Offer Letter
                </span>
                <i class="fas fa-chevron-down text-sm transition-transform" :class="{ 'rotate-180': isOpen }"></i>
            </a>
            <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                <li>
                    <a href="{{ route('enrollment.report') }}"
                        class="flex items-center p-2 text-sm {{ request()->routeIs('enrollment.report') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                        <i class="fas fa-plus-circle mr-2"></i> Issue Internship Offer Letter 
                    </a>
                </li>         
            </ul>
        </li>





        <li x-data="{ isOpen: {{ request()->routeIs('teacher.slots.*') ? 'true' : 'false' }} }">
            <a href="javascript:void(0)" @click="isOpen = !isOpen"
                class="flex items-center justify-between p-3 {{ request()->routeIs('teacher.slots.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                <span class="flex items-center">
                    <i class="fas fa-briefcase mr-3 text-lg"></i> Mock Interview
                </span>
                <i class="fas fa-chevron-down text-sm transition-transform" :class="{ 'rotate-180': isOpen }"></i>
            </a>
            <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                <li>
                    <a href="{{ route('teacher.slots') }}"
                        class="flex items-center p-2 text-sm {{ request()->routeIs('teacher.slots') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                        <i class="fas fa-plus-circle mr-2"></i> Mock Interview
                    </a>
                </li>         
            </ul>
        </li>
    @endif

    @if (auth()->user()->role == 1)
        <!-- Webinar -->
        <li x-data="{ isOpen: {{ request()->routeIs('admin.contactus.*') ? 'true' : 'false' }} }">
            <a href="javascript:void(0)" @click="isOpen = !isOpen"
                class="flex items-center justify-between p-3 {{ request()->routeIs('admin.contactus.*') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800] hover:text-white' }} rounded transition">
                <span class="flex items-center">
                    <i class="fas fa-briefcase mr-3 text-lg"></i> Contact Us Enquires
                </span>
                <i class="fas fa-chevron-down text-sm transition-transform" :class="{ 'rotate-180': isOpen }"></i>
            </a>
            <ul x-show="isOpen" x-collapse class="ml-6 mt-2 space-y-2 border-l-2 border-gray-300 pl-4">
                <li>
                    <a href="{{ route('admin.contactus.index') }}"
                        class="flex items-center p-2 text-sm {{ request()->routeIs('admin.contactus.index') ? 'bg-[#ff9800] text-white' : 'hover:bg-[#ff9800]/20' }} rounded transition">
                        <i class="fas fa-plus-circle mr-2"></i> Show Contact Us Enquires 
                    </a>
                </li>         
            </ul>
        </li>
    @endif
    </ul>
</nav>