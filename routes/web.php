<?php

use App\Http\Controllers\AdminAssignmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminInternshipClassCreateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\StudentQuizController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\CodingQuestionController;
use App\Http\Controllers\CodingTestController;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use App\Models\Internship;
use App\Models\Batch;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminLiveClassController;
use App\Http\Controllers\AdminRecordingController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CareerHighlightController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\CourseDetailsController;
use App\Http\Controllers\StudentClassController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\InternshipRegistrationController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventCategoryController;
use App\Http\Controllers\InternshipBatchController;
use App\Http\Controllers\InternshipEnrollmentController;
use App\Http\Controllers\InternshipRecordingController;
use App\Http\Controllers\NewsCategoryController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\WebinarController;
use App\Http\Controllers\YouTubeReviewController;
use App\Http\Controllers\CourseToInternshipController;
use App\Http\Controllers\HireController;

 use App\Models\Student;
use App\Models\Assignment;
use App\Models\Quiz;



use App\Http\Controllers\ChatbotController;

Route::get('/chat-bot', [ChatbotController::class, 'index']);
Route::post('/chat-bot/send', [ChatbotController::class, 'send']);



Route::get('admin/internship-recordings-by-course/{courseId}', [InternshipRecordingController::class, 'getRecordingsByCourse']);
Route::get('/api/batches', [BatchController::class, 'getBatchesByCourse'])->name('api.batches');
Route::get('/api/batches-int', [BatchController::class, 'getBatchesByCourseInt'])->name('api.batches.int');
Route::get('/register', [BatchController::class, 'show'])->name('register');

Route::get('/register-int', [BatchController::class, 'showInt'])->name('register.int');
// Route::post('/register/submit', [BatchController::class, 'submit'])->name('register.submit');
Route::post('/register/submit', [BatchController::class, 'submitr'])->name('register.submit');
Route::post('/register/submit-int', [BatchController::class, 'submitrInt'])->name('register.submit.int');
Route::get('/student/quiz-sets', [StudentQuizController::class, 'index'])->name('student.quiz_sets');
Route::get('/student/quiz-sets/{id}/take', [StudentQuizController::class, 'takeQuiz'])->name('student.quiz_sets.take');
Route::post('/student/quiz-sets/{id}/submit', [StudentQuizController::class, 'submitQuiz'])->name('student.quiz_sets.submit');
//Route::get('/student/batch/{batchId}/quiz-ranking', [StudentQuizController::class, 'batchQuizRanking'])
  //  ->name('student.batch_quiz_ranking');
Route::get('/admin/quiz-sets', [QuizController::class, 'index'])->name('admin.quiz_sets');
Route::get('/admin/quiz-sets/create', [QuizController::class, 'createSet'])->name('admin.quiz_sets.create');
Route::post('/admin/quiz-sets/store', [QuizController::class, 'storeSet'])->name('admin.quiz_sets.store');
Route::get('/admin/quiz-sets/{id}/edit', [QuizController::class, 'editSet'])->name('admin.quiz_sets.edit');
Route::put('/admin/quiz-sets/{id}/update', [QuizController::class, 'updateSet'])->name('admin.quiz_sets.update');
Route::delete('/admin/quiz-sets/{id}', [QuizController::class, 'deleteSet'])->name('admin.quiz_sets.delete');

// Quizzes Routes
Route::get('/admin/quiz-sets/{id}/quizzes', [QuizController::class, 'showQuizzes'])->name('admin.quiz_sets.show_quizzes');
Route::get('/admin/quiz-sets/{id}/add-quizzes', [QuizController::class, 'addQuizzes'])->name('admin.quiz_sets.add_quizzes');
Route::post('/admin/quiz-sets/{id}/store-quizzes', [QuizController::class, 'storeQuizzes'])->name('admin.quiz_sets.store_quizzes');
Route::get('/admin/quizzes/{id}/edit', [QuizController::class, 'editQuiz'])->name('admin.quizzes.edit');
Route::put('/admin/quizzes/{id}/update', [QuizController::class, 'updateQuiz'])->name('admin.quizzes.update');
Route::delete('/admin/quizzes/{id}', [QuizController::class, 'deleteQuiz'])->name('admin.quizzes.delete');


// Route::get('/', function () {
//     if (Auth::user() && Auth::user()->role == 1) {
//         return to_route('admin.dash');
//     } elseif (Auth::user() && Auth::user()->role == 2) {
//         return to_route('trainer.dashboard');
//     } elseif (Auth::user() && Auth::user()->role == 3) {
//         return to_route('student.dashboard');
//     }

//     return to_route('home-page');
// })->name('home-page');

 Route::get('/', [HomeController::class, 'index'])->name('home-page');



Route::get('/about', function () {
    if (Auth::user() && Auth::user()->role == 1) {
        return to_route('admin.dash');
    } elseif (Auth::user() && Auth::user()->role == 2) {
        return to_route('trainer.dashboard');
    } elseif (Auth::user() && Auth::user()->role == 3) {
        return to_route('student.dashboard');
    }
    return view('website.about');
})->name('about-page');

// Route::get('/reveiws', function () {
//     if (Auth::user() && Auth::user()->role == 1) {
//         return to_route('admin.dash');
//     } elseif (Auth::user() && Auth::user()->role == 2) {
//         return to_route('trainer.dashboard');
//     } elseif (Auth::user() && Auth::user()->role == 3) {
//         return to_route('student.dashboard');
//     }
//     return view('website.reviews');
// })->name('website.reviews');

Route::get('/contact', function () {
    if (Auth::user() && Auth::user()->role == 1) {
        return to_route('admin.dash');
    } elseif (Auth::user() && Auth::user()->role == 2) {
        return to_route('trainer.dashboard');
    } elseif (Auth::user() && Auth::user()->role == 3) {
        return to_route('student.dashboard');
    }
    return view('website.contact_us');
})->name('website.contact');

Route::get('/events', function () {
    if (Auth::user() && Auth::user()->role == 1) {
        return to_route('admin.dash');
    } elseif (Auth::user() && Auth::user()->role == 2) {
        return to_route('trainer.dashboard');
    } elseif (Auth::user() && Auth::user()->role == 3) {
        return to_route('student.dashboard');
    }
    return view('website.events');
})->name('website.events');


// Route::get('/webinar', function () {
//     if (Auth::user() && Auth::user()->role == 1) {
//         return to_route('admin.dash');
//     } elseif (Auth::user() && Auth::user()->role == 2) {
//         return to_route('trainer.dashboard');
//     } elseif (Auth::user() && Auth::user()->role == 3) {
//         return to_route('student.dashboard');
//     }
//     return view('website.webinars');
// })->name('website.webinar');


Route::get('/course', function () {
    if (Auth::user() && Auth::user()->role == 1) {
        return to_route('admin.dash');
    } elseif (Auth::user() && Auth::user()->role == 2) {
        return to_route('trainer.dashboard');
    } elseif (Auth::user() && Auth::user()->role == 3) {
        return to_route('student.dashboard');
    }
    $courses = Course::all();
    return view('website.course', compact('courses'));
})->name('website.course');

// Route::get('/internship_details', function () {
//     if (Auth::user() && Auth::user()->role == 1) {
//         return to_route('admin.dash');
//     } elseif (Auth::user() && Auth::user()->role == 2) {
//         return to_route('trainer.dashboard');
//     } elseif (Auth::user() && Auth::user()->role == 3) {
//         return to_route('student.dashboard');
//     }
//     $internships = Internship::all();
//     return view('website.internship_course', compact('internships'));
// })->name('website.internship_details');
// Route::get('/course_details', function () {
//     if (Auth::user() && Auth::user()->role == 1) {
//         return to_route('admin.dash');
//     } elseif (Auth::user() && Auth::user()->role == 2) {
//         return to_route('trainer.dashboard');
//     } elseif (Auth::user() && Auth::user()->role == 3) {
//         return to_route('student.dashboard');
//     }
//     return view('website.course_details');
// })->name('website.course_details');


// Route::get('/course_details', [CourseController::class, 'courseDetails'])->name('website.course_details');
// Edit Profile route
Route::get('/edit-profile', [BatchController::class, 'editProfile'])->name('edit-profile');

// Update Profile route
Route::put('/update-profile', [BatchController::class, 'updateProfile'])->name('update-profile');
Route::get('/profile', [BatchController::class, 'profile'])->name('profile');
Route::get('/login', function () {

    if (Auth::user() && Auth::user()->role == 1) {
        return to_route('admin.dash');
    } elseif (Auth::user() && Auth::user()->role == 2) {
        return to_route('trainer.dashboard');
    } elseif (Auth::user() && Auth::user()->role == 3) {
        return to_route('student.dashboard');
    }
    return view('website.login');
})->name('login');


Route::get('/login_check', [LoginController::class, 'login_check'])->name('logincheck');
Route::get('/register-web', [LoginController::class, 'register']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('trainer-management', [AdminController::class, 'trainer_management'])->name('trainer-management');
Route::post('admin/trainers', [AdminController::class, 'store'])->name('admin.trainers.store');
Route::get('admin/trainers/{id}/edit', [AdminController::class, 'edit'])->name('admin.trainers.edit');
Route::put('admin/trainers/{id}', [AdminController::class, 'update'])->name('admin.trainers.update');
Route::delete('admin/trainers/{id}/delete', [AdminController::class, 'destroy'])->name('admin.trainers.delete');

Route::get('/student-management', [AdminController::class, 'student_management'])->name('student-management');
Route::get('admin/student/{id}/edit', [AdminController::class, 'editStudent'])->name('admin.student.edit');
Route::put('admin/student/{id}', [AdminController::class, 'updateStudent'])->name('admin.student.update');
Route::delete('admin/student/{id}', [AdminController::class, 'deleteStudent'])->name('admin.student.delete');

Route::get('/upload', [ImageUploadController::class, 'showUploadForm'])->name('upload.form');
Route::post('/upload', [ImageUploadController::class, 'uploadImage'])->name('upload.image');

Route::get('/student-dashboard', function () {
    return view('student.dashboard');
})->name('student.dashboard');

Route::get('/trainer-dashboard', function () {
    return view('website.trainerdashboard');
})->name('trainer.dashboard');

//Route::middleware(['auth'])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('admin.dashboard');
    // })->name('admin.dash');

   


use App\Models\Payment;
use App\Models\Trainer;
use Carbon\Carbon;
  use App\Models\User;


Route::get('/dashboard', function () {
    // 1. Registrations
 

// All-time student registrations
$totalRegistrations = User::where('role', 3)->count();

// This month student registrations
$thisMonthRegistrations = User::where('role', 3)
    ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
    ->count();


    // 2. Active Students + Batches
    $today = Carbon::now();

$activeStudents = DB::table('users')
    ->where('users.role', 3) // sirf students
    ->join('enrollments', 'users.id', '=', 'enrollments.user_id')
    ->join('batches', 'enrollments.batch_id', '=', 'batches.id')
    ->select('users.id', 'batches.start_date', 'batches.duration')
    ->orderBy('batches.start_date', 'desc')
    ->get()
    ->unique('id') // ek student ka sirf latest batch
    ->filter(function ($row) use ($today) {
        // ✅ duration validation
        if (!ctype_digit((string)$row->duration) || (int)$row->duration > 12) {
            $duration = 6; // default 3 months
        } else {
            $duration = (int)$row->duration;
        }

        // ✅ batch end date
        $endDate = Carbon::parse($row->start_date)->addMonths($duration);

        // ✅ active agar abhi chal raha hai
        return $endDate->greaterThan($today);
    })
    ->count();
    $totalBatches = Batch::count();


    // 3. Revenue
    $totalRevenue = Payment::sum('amount');
 

    $thisMonthRevenue = Payment::whereMonth('created_at', Carbon::now()->month)->sum('amount');
    $lastMonthRevenue = Payment::whereMonth('created_at', Carbon::now()->subMonth()->month)->sum('amount');
    $monthlyGrowth = $lastMonthRevenue > 0 
        ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2)
        : 0;

    // 4. Trainers
  
    $totalTrainers = User::where('role', 2)->count();

    //  dd([
    //     'totalRegistrations' => $totalRegistrations,
    //     'thisMonthRegistrations' => $thisMonthRegistrations,
    //     'activeStudents' => $activeStudents,
    //     'totalBatches' => $totalBatches,
    //     'totalRevenue' => $totalRevenue,
        
    //     'monthlyGrowth' => $monthlyGrowth,
    //     'totalTrainers' => $totalTrainers,
    // ]);

    return view('admin.dashboard', compact(
        'totalRegistrations',
        'thisMonthRegistrations',
        'activeStudents',
        'totalBatches',
        'totalRevenue',
          'thisMonthRevenue',    // ✅ new
        'lastMonthRevenue'  ,   // ✅ new
        'monthlyGrowth',
        'totalTrainers'
    ));
})->name('admin.dash');



    Route::prefix('admin')->name('admin.')->group(function () {
        Route::prefix('courses')->name('course.')->group(function () {
            Route::get('/add', [CourseController::class, 'addCourse'])->name('add');
            Route::post('/store', [CourseController::class, 'storeCourse'])->name('store');
            Route::get('/list', [CourseController::class, 'courseList'])->name('list');
            Route::get('/{course}/edit', [CourseController::class, 'edit'])->name('edit');
            Route::put('/{course}', [CourseController::class, 'update'])->name('update');
            Route::delete('/{course}', [CourseController::class, 'destroy'])->name('delete');
        });

        Route::prefix('internship')->name('internship.')->group(function () {
            Route::get('/add', [InternshipController::class, 'create'])->name('add');
            Route::post('/store', [InternshipController::class, 'store'])->name('store');
            Route::get('/list', [InternshipController::class, 'internshipList'])->name('list');
            Route::get('/{internship}/edit', [InternshipController::class, 'edit'])->name('edit');
            Route::put('/{internship}', [InternshipController::class, 'update'])->name('update');
            Route::delete('/{internship}', [InternshipController::class, 'destroy'])->name('destroy');
        });
//Route::get('/add-int', [BatchController::class, 'createInt'])->name('add.int');
        Route::prefix('batches')->name('batches.')->group(function () {
            Route::get('/add', [BatchController::class, 'create'])->name('add');
            Route::get('/add-int', [BatchController::class, 'createInt'])->name('add.int');
            Route::post('/store', [BatchController::class, 'store'])->name('store');
             Route::post('/store-int', [BatchController::class, 'storeInt'])->name('store.int');
            Route::get('/index', [BatchController::class, 'index'])->name('index'); // Listing route
            Route::get('/index-int', [BatchController::class, 'indexInt'])->name('index.int');
            Route::delete('/batch/{id}', [BatchController::class, 'destroy'])->name('destroy'); // Delete route
            Route::get('/{id}/edit', [BatchController::class, 'edit'])->name('edit');
            Route::put('/{id}', [BatchController::class, 'update'])->name('update');
        });

        Route::get('/recordings', [AdminRecordingController::class, 'index'])->name('recordings.index');

                Route::get('/recordings-int', [AdminRecordingController::class, 'indexInt'])->name('recordings.index.int');
       // Route::get('/recordings/create', [AdminRecordingController::class, 'create'])->name('recordings.create');
        Route::post('/recordings', [AdminRecordingController::class, 'store'])->name('recordings.store');
        Route::get('/recordings/{id}/edit', [AdminRecordingController::class, 'edit'])->name('recordings.edit');
        Route::put('/recordings/{id}', [AdminRecordingController::class, 'update'])->name('recordings.update');
        Route::delete('/recordings/{id}', [AdminRecordingController::class, 'destroy'])->name('recordings.destroy');

        Route::get('/live-classes', [AdminLiveClassController::class, 'index'])->name('live_classes.index');
                Route::get('/live-classes-int', [AdminLiveClassController::class, 'indexInt'])->name('live_classes.index.int');
        Route::get('/live-classes/create', [AdminLiveClassController::class, 'create'])->name('live_classes.create');
       
                Route::get('/live-classes-int/create', [AdminLiveClassController::class, 'createInt'])->name('live_classes.create.int');

        Route::post('/live-classes', [AdminLiveClassController::class, 'store'])->name('live_classes.store');
                Route::post('/live-classes-int', [AdminLiveClassController::class, 'storeInt'])->name('live_classes.store.int');
      //  Route::get('/live-classes/recordings/{batchId}', [AdminLiveClassController::class, 'getRecordings'])->name('live_classes.recordings');
        Route::get('/live-classes/{id}/edit', [AdminLiveClassController::class, 'edit'])->name('live_classes.edit');
        Route::put('/live-classes/{id}', [AdminLiveClassController::class, 'update'])->name('live_classes.update');
        Route::delete('/live-classes/{id}', [AdminLiveClassController::class, 'destroy'])->name('live_classes.destroy');
    });
Route::get('/live-classes/folders/{batchId}', [AdminLiveClassController::class, 'getFoldersByBatch'])->name('admin.live_classes.folders');

Route::get('/live-classes/folders-int/{batchId}', [AdminLiveClassController::class, 'getFoldersByBatchInt'])->name('admin.live_classes.folders.int');

Route::get('/live-classes/recordings/{folderId}', [AdminLiveClassController::class, 'getRecordingsByFolder'])->name('admin.live_classes.recordings');
Route::get('/live-classes/recordings-int/{folderId}', [AdminLiveClassController::class, 'getRecordingsByFolderInt'])->name('admin.live_classes.recordings.int');
    Route::get('/attendance/monthly', [AttendanceController::class, 'showMonthlyAttendance']);
  //  Route::post('/leave/apply', [AttendanceController::class, 'applyLeave'])->name('leave.apply');
   // Route::post('/leave/{leave}/approve', [AttendanceController::class, 'approveLeave'])->name('leave.approve');

    Route::get('/index-create-cd', [CourseDetailsController::class, 'index'])->name('course-details-index');
        Route::get('/index-create-cd-int', [CourseDetailsController::class, 'indexInt'])->name('course-details-index-int');
//});

Route::get('/course-details/{id}/edit', [CourseDetailsController::class, 'edit'])->name('course.edit');
Route::put('/course-details/{id}', [CourseDetailsController::class, 'update'])->name('course.update');


// // Enrollment Management Routes
// Route::get('/admin/enrollments', [EnrollmentController::class, 'index'])->name('admin.enrollment.index');
// Route::get('/admin/enrollment/add', [EnrollmentController::class, 'create'])->name('admin.enrollment.add');
// Route::get('/admin/enrollment/edit/{id}', [EnrollmentController::class, 'edit'])->name('admin.enrollment.edit');
// Route::put('/admin/enrollment/update/{id}', [EnrollmentController::class, 'update'])->name('admin.enrollment.update');
// Route::delete('/admin/enrollment/destroy/{id}', [EnrollmentController::class, 'destroy'])->name('admin.enrollment.destroy');

// Add this to your existing enrollment routes
// Route::post('/admin/enrollment/approve/{id}', [EnrollmentController::class, 'approve'])->name('admin.enrollment.approve');

Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('admin.enrollment.index');

// Admin routes for coding questions
Route::prefix('admin')->group(function () {
    Route::get('/coding-questions', [CodingQuestionController::class, 'index'])->name('admin.coding_questions.index');
    Route::get('/coding-questions/create', [CodingQuestionController::class, 'create'])->name('admin.coding_questions.create');
    Route::post('/coding-questions', [CodingQuestionController::class, 'store'])->name('admin.coding_questions.store');
    Route::get('/coding-questions/{id}/edit', [CodingQuestionController::class, 'edit'])->name('admin.coding_questions.edit');
    Route::put('/coding-questions/{id}', [CodingQuestionController::class, 'update'])->name('admin.coding_questions.update');
    Route::delete('/coding-questions/{id}', [CodingQuestionController::class, 'destroy'])->name('admin.coding_questions.destroy');

    //news for admin

  // News Routes
  Route::get('/news', [NewsController::class, 'adminIndex'])->name('admin.news.index');
  Route::get('/news/create', [NewsController::class, 'create'])->name('admin.news.create');
  Route::post('/news', [NewsController::class, 'store'])->name('admin.news.store');
  Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('admin.news.edit');
  Route::put('/news/{news}', [NewsController::class, 'update'])->name('admin.news.update');
  Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('admin.news.destroy');

  // News Category Routes
  Route::get('/news-categories', [NewsCategoryController::class, 'index'])->name('admin.news-categories.index');
  Route::get('/news-categories/create', [NewsCategoryController::class, 'create'])->name('admin.news-categories.create');
  Route::post('/news-categories', [NewsCategoryController::class, 'store'])->name('admin.news-categories.store');
  Route::get('/news-categories/{category}/edit', [NewsCategoryController::class, 'edit'])->name('admin.news-categories.edit');
  Route::put('/news-categories/{category}', [NewsCategoryController::class, 'update'])->name('admin.news-categories.update');
  Route::delete('/news-categories/{category}', [NewsCategoryController::class, 'destroy'])->name('admin.news-categories.destroy');

    Route::get('/events', [EventController::class, 'adminIndex'])->name('admin.events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('admin.events.create');
    Route::post('/events', [EventController::class, 'store'])->name('admin.events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('admin.events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('admin.events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('admin.events.destroy');
    Route::get('/events/enrollments', [EventController::class, 'enrollments'])->name('admin.events.enrollments');

    Route::get('/event-categories', [EventCategoryController::class, 'index'])->name('admin.event-categories.index');
    Route::get('/event-categories/create', [EventCategoryController::class, 'create'])->name('admin.event-categories.create');
    Route::post('/event-categories', [EventCategoryController::class, 'store'])->name('admin.event-categories.store');
    Route::get('/event-categories/{category}/edit', [EventCategoryController::class, 'edit'])->name('admin.event-categories.edit');
    Route::put('/event-categories/{category}', [EventCategoryController::class, 'update'])->name('admin.event-categories.update');
    Route::delete('/event-categories/{category}', [EventCategoryController::class, 'destroy'])->name('admin.event-categories.destroy');
});
Route::get('/coding-questions/delete-solution', [CodingQuestionController::class, 'deleteSolution'])->name('admin.coding_questions.delete_solution');
// Student routes for coding tests
Route::prefix('student')->middleware('auth')->group(function () {
    Route::get('/coding-tests', [CodingTestController::class, 'index'])->name('student.coding_tests.index');
    Route::get('/coding-tests/{id}', [CodingTestController::class, 'show'])->name('student.coding_tests.show');
    Route::post('/coding-tests/{id}/submit', [CodingTestController::class, 'submit'])->name('student.coding_tests.submit');
});

// Add this to your existing admin routes
Route::get('/admin/coding-questions/{id}/submissions', [CodingQuestionController::class, 'showSubmissions'])->name('admin.coding_questions.show_submissions');
Route::get('course_details/{slug?}', [CourseController::class, 'courseDetails'])->name('website.course_details');

Route::get('internship_details/{id?}', [InternshipController::class, 'internshipDetails'])->name('website.internship_details');


Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/messages/{receiverId}', [ChatController::class, 'fetchMessages']);
    Route::get('/message/send', [ChatController::class, 'sendMessage']);
});

Route::get('my-classes', [StudentClassController::class, 'index'])->name('student.classes.index');

Route::get('my-classes-int', [StudentClassController::class, 'indexInt'])->name('student.classes.index.int');

Route::get('/student/join-class/{liveClassId}', [StudentClassController::class, 'joinClass'])->name('student.join-class');
Route::get('/student/batch/quiz-ranking', [StudentQuizController::class, 'batchQuizRanking'])
    ->name('student.batch_quiz_ranking');

Route::middleware(['auth'])->group(function () {
    // Student Routes
    Route::get('/student/attendance', [AttendanceController::class, 'studentAttendance'])
        ->name('student.attendance');
    Route::post('/student/leave/apply', [AttendanceController::class, 'applyLeave'])
        ->name('leave.apply');

    // Admin Routes
    Route::get('/admin/leaves', [AttendanceController::class, 'adminLeaves'])
        ->name('admin.leaves');
    Route::post('/admin/leave/{leave}/approve', [AttendanceController::class, 'approveLeave'])
        ->name('leave.approve');

    Route::get('/recordings', [StudentClassController::class, 'recordings'])->name('recordings');

    Route::get('/assignments/create', [AdminAssignmentController::class, 'create'])->name('admin.assignments.create');
    Route::post('/assignments', [AdminAssignmentController::class, 'store'])->name('admin.assignments.store');

    Route::get('/assignment', [StudentClassController::class, 'assignment'])->name('assignment');

    //career hightlight
    
    Route::get('/career-highlights-create',[CareerHighlightController::class, 'create'])->name('admin.career_highlight.create');
    Route::post('/career-highlights-store',[CareerHighlightController::class, 'store'])->name('admin.career_highlight.store');
    Route::get('/career-highlights-show',[CareerHighlightController::class, 'show_career_highlight'])->name('admin.career_highlight.show');
    Route::delete('/admin/career-highlight/delete-all', [CareerHighlightController::class, 'deleteAll'])->name('admin.career_highlight.deleteAll');
    Route::get('/testimonials/index', [TestimonialController::class, 'index'])->name('admin.testimonials.index');
    Route::get('/testimonials/create', [TestimonialController::class, 'create'])->name('admin.testimonials.create');
   // Route::post('/testimonials/', [TestimonialController::class, 'store'])->name('admin.testimonials.store');
    Route::get('/testimonials/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('admin.testimonials.edit');
   // Route::put('/testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('admin.testimonials.update');
    Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('admin.testimonials.destroy');
    Route::get('/youtubereview/index', [YouTubeReviewController::class, 'index'])->name('admin.youtubereview.index');
    Route::get('/youtubereview/create', [YouTubeReviewController::class, 'create'])->name('admin.youtubereview.create');
    Route::post('/youtubereview', [YouTubeReviewController::class, 'store'])->name('admin.youtubereview.store'); 
    Route::get('/youtubereview/{id}/edit', [YouTubeReviewController::class, 'edit'])->name('admin.youtubereview.edit');
    Route::put('/youtubereview/{id}', [YouTubeReviewController::class, 'update'])->name('admin.youtubereview.update');
    Route::delete('/youtubereview/{id}', [YouTubeReviewController::class, 'destroy'])->name('admin.youtubereview.destroy');
    Route::get('/webinar/index', [WebinarController::class, 'index'])->name('admin.webinar.index');
    Route::get('/webinar/create', [WebinarController::class, 'create'])->name('admin.webinar.create');
    Route::post('/webinar', [WebinarController::class, 'store'])->name('admin.webinar.store'); 
    Route::get('/webinar/{id}/edit', [WebinarController::class, 'edit'])->name('admin.webinar.edit');
    Route::put('/webinar/{id}', [WebinarController::class, 'update'])->name('admin.webinar.update');
    Route::delete('/webinar/{id}', [WebinarController::class, 'destroy'])->name('admin.webinar.destroy');
    Route::get('/webinar-enrollments',[WebinarController::class, 'enrollments'])->name('admin.webinar.enrollments');
    Route::post('/webinar/send-confirmation', [WebinarController::class, 'sendConfirmation'])->name('admin.webinar.send-confirmation');
    Route::get('/verify-presence', [WebinarController::class, 'verifyPresence'])->name('webinar-attendance');
    Route::post('/attendance_submit_webinar', [WebinarController::class, 'attendanceSubmitWebinar'])->name('attendance.submit.webinar');
    Route::post('/webinar/{id}/send-certificate',[WebinarController::class, 'sendWebinarCertificate'])->name('admin.webinar.send-certificate');
    Route::get('/contact-us', [ContactUsController::class, 'contactindex'])->name('admin.contactus.index');
    Route::post('/contact-us/{id}/resolve', [ContactUsController::class, 'resolve'])->name('admin.contactus.resolve');

    Route::get('/job-roles', [HireController::class, 'index'])->name('admin.job-roles.index');
    Route::get('/job-roles/create', [HireController::class, 'create'])->name('admin.job-roles.create');
    Route::post('/job-roles', [HireController::class, 'store'])->name('admin.job-roles.store');
    Route::get('/job-roles/{id}/edit', [HireController::class, 'edit'])->name('admin.job-roles.edit');
    Route::put('/job-roles/{id}', [HireController::class, 'update'])->name('admin.job-roles.update');
    Route::delete('/job-roles/{id}', [HireController::class, 'destroy'])->name('admin.job-roles.destroy');

    //end

    Route::get('/view-batch-enrollment',[InternshipEnrollmentController::class, 'assignBatchView'])->name('view-batch-enrollment');

    Route::get('/internship-class-create', [AdminInternshipClassCreateController::class, 'create'])->name('admin.internship.class.create');

    Route::get('/internship-class-index', [AdminInternshipClassCreateController::class, 'index'])->name('admin.internship.class.index');
    // New routes for adding notes
Route::post('/internship-class/{id}/add-notes', [AdminInternshipClassCreateController::class, 'addNotes'])->name('admin.internship.class.addNotes');
Route::post('/internship-class/{id}/add-notes-2', [AdminInternshipClassCreateController::class, 'addNotes2'])->name('admin.internship.class.addNotes2');
    Route::get('/internship-class-edit/{id}', [AdminInternshipClassCreateController::class, 'edit'])
    ->name('admin.internship.class.edit');

    Route::put('/admin/internship-class-update/{id}', [AdminInternshipClassCreateController::class, 'update'])->name('admin.internship.class.update');

    Route::delete('/internship-class-destroy/{id}', [AdminInternshipClassCreateController::class, 'destroy'])->name('admin.internship-classes.destroy');

//////
// Recording Courses
Route::get('/internship-recording-courses', [InternshipRecordingController::class, 'index'])->name('admin.internship-recording-courses.index');
Route::post('/internship-recording-courses', [InternshipRecordingController::class, 'store'])->name('admin.internship-recording-courses.store');
Route::put('/internship-recording-courses/{recordingCourse}', [InternshipRecordingController::class, 'update'])->name('admin.internship-recording-courses.update');
Route::delete('/internship-recording-courses/{recordingCourse}', [InternshipRecordingController::class, 'destroy'])->name('admin.internship-recording-courses.destroy');

// Recordings
Route::get('/internship-recordings/create', [InternshipRecordingController::class, 'create'])->name('admin.internship-recordings.create');
Route::post('/internship-recordings', [InternshipRecordingController::class, 'storeRecording'])->name('admin.internship-recordings.store');
Route::get('/internship-recordings/{recording}/edit', [InternshipRecordingController::class, 'edit'])->name('admin.internship-recordings.edit');
Route::put('/internship-recordings/{recording}', [InternshipRecordingController::class, 'updateRecording'])->name('admin.internship-ecordings.update');
Route::delete('/internship-recordings/{recording}', [InternshipRecordingController::class, 'destroyRecording'])->name('admin.internship-recordings.destroy');

// Fetch recordings by course (for live class creation)
Route::get('/internship-enrollment-view',[InternshipEnrollmentController::class, 'viewEnrollments'])->name('admin.internship-enrollment-view');
Route::patch('/admin/internship-enrollments/{id}/toggle-status', [InternshipEnrollmentController::class, 'toggleEnrollmentStatus'])
    ->name('admin.internship-enrollments.toggleStatus');
});

Route::get('admin/internship-enrollments/{id}/edit', [InternshipEnrollmentController::class, 'edit'])->name('admin.internship-enrollments.edit');
Route::put('admin/internship-enrollments/{id}', [InternshipEnrollmentController::class, 'update'])->name('admin.internship-enrollments.update');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('internship-batches', InternshipBatchController::class)->except(['show']);
});

Route::post('/admin/quiz-sets/{quizSetId}/bulk-upload', [QuizController::class, 'bulkUpload'])
    ->name('admin.quiz_sets.bulk_upload');

    Route::get('/internship-enrollment-view',[InternshipEnrollmentController::class, 'viewEnrollments'])->name('admin.internship-enrollment-view');

Route::get('/get-trainer-course', [TrainerController::class, 'myCourse'])->name('get-trainer-course');
// extra code 
Route::get('/student/quiz-attempt/{attemptId}', [StudentQuizController::class, 'viewAttempt'])
    ->name('student.quiz_attempt')
    ->middleware('auth');


Route::post('/course-form', [CourseDetailsController::class, 'store'])->name('course.store');
Route::post('/course-form-int', [CourseDetailsController::class, 'storeInt'])->name('course.store.int');

Route::get('/internship/register/{id}', [InternshipRegistrationController::class, 'show'])->name('internship.register');
Route::post('/internship/register/submit', [InternshipRegistrationController::class, 'store'])->name('internship.register.submit');


Route::get('/admin/internship/content/create', [InternshipController::class, 'contentCreate'])->name('admin.internship.content.create');
Route::post('/admin/internship/content', [InternshipController::class, 'contentstore'])->name('admin.internship.content.store');
Route::get('/student-internships-classes', [InternshipController::class, 'internshipclasses'])->name('student.internship.class');
Route::get('/student-internships', [InternshipController::class, 'showOnStudentDashboard'])->name('student.internships.index');
Route::get('/student/internship/{enrollmentId}/content', [InternshipController::class, 'studentInternshipContent'])->name('student.internship.content');
Route::post('/student/internship/content/{contentId}/submit', [InternshipController::class, 'studentInternshipSubmit'])->name('student.internship.submit');

Route::get('get-internship-list', [InternshipController::class, 'getInternshipList'])->name('get-internship-list');
Route::get('/admin/internships/{internship}/submissions', [InternshipRegistrationController::class, 'submissions'])->name('admin.internship.submissions');
Route::post('/admin/internship/submissions/{submission}/feedback', [InternshipRegistrationController::class, 'submitFeedback'])->name('admin.internship.submission.feedback');


Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/news/image/{news}', [NewsController::class, 'showImage'])->name('news.image');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{slug}/enroll', [EventController::class, 'enroll'])->name('events.enroll');

Route::post('/assign-students-to-batch', [InternshipEnrollmentController::class, 'assignStudentsToBatch'])->name('assign.students.to.batch');

Route::post('/admin/internship-classes', [AdminInternshipClassCreateController::class, 'store'])->name('admin.internship-classes.store');

// routes/web.php
Route::post('/store-batch-data', [BatchController::class, 'storeBatchData'])->name('store.batch.data');
Route::post('/store-batch-data-int', [BatchController::class, 'storeBatchDataInt'])->name('store.batch.data.int');

Route::get('/register-website', function () {
    return view('website.register-page');
})->name('website-register-page');

Route::post('/register-teacher', [BatchController::class, 'register_teacher'])->name('register.submit.teacher');
// Route::post('/register', [LoginController::class, 'register'])->name('register.submit');

Route::get('/career-highlights',[CareerHighlightController::class, 'show'])->name('career_hightlight_show');
Route::get('/webinars', [WebinarController::class, 'show'])->name('webinar.show');
Route::get('/webinars/{id}', [WebinarController::class, 'showWebinar'])->name('webinars.show');
Route::post('/webinars/{id}/enroll', [WebinarController::class, 'enroll'])->name('webinars.enroll');

Route::get('/contactus', [ContactUsController::class, 'index'])->name('contact.index');
// Route::post('/contact-us', [ContactUsController::class, 'store'])->name('contact.store');

// Route::get('/reveiws', function () {
//     if (Auth::user() && Auth::user()->role == 1) {
//         return to_route('admin.dash');
//     } elseif (Auth::user() && Auth::user()->role == 2) {
//         return to_route('trainer.dashboard');
//     } elseif (Auth::user() && Auth::user()->role == 3) {
//         return to_route('student.dashboard');
//     }
//     return view('website.reviews');
// })->name('website.reviews');

// Route::get('/webinar-detail',function(){
//     return view('website.webinar.webinar_detail');
// });

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/home', [AdminController::class, 'home'])->name('admin.home');
    Route::post('/home/placements', [AdminController::class, 'storePlacement'])->name('admin.placements.store');
    Route::put('/home/placements/{id}', [AdminController::class, 'updatePlacement'])->name('admin.placements.update');
    Route::delete('/home/placements/{id}', [AdminController::class, 'deletePlacement'])->name('admin.placements.delete');
    Route::post('/home/courses', [AdminController::class, 'storeCourse'])->name('admin.courses.store');
    Route::put('/home/courses/{id}', [AdminController::class, 'updateCourse'])->name('admin.courses.update');
    Route::delete('/home/courses/{id}', [AdminController::class, 'deleteCourse'])->name('admin.courses.delete');
    Route::post('/home/upcoming-courses', [AdminController::class, 'storeUpcomingCourse'])->name('admin.upcoming_courses.store');
    Route::put('/home/upcoming-courses/{id}', [AdminController::class, 'updateUpcomingCourse'])->name('admin.upcoming_courses.update');
    Route::delete('/home/upcoming-courses/{id}', [AdminController::class, 'deleteUpcomingCourse'])->name('admin.upcoming_courses.delete');
    Route::post('/home/internships', [AdminController::class, 'storeInternship'])->name('admin.internships.store');
    Route::put('/home/internships/{id}', [AdminController::class, 'updateInternship'])->name('admin.internships.update');
    Route::delete('/home/internships/{id}', [AdminController::class, 'deleteInternship'])->name('admin.internships.delete');
    Route::post('/home/instructors', [AdminController::class, 'storeInstructor'])->name('admin.instructors.store');
    Route::put('/home/instructors/{id}', [AdminController::class, 'updateInstructor'])->name('admin.instructors.update');
    Route::delete('/home/instructors/{id}', [AdminController::class, 'deleteInstructor'])->name('admin.instructors.delete');
    Route::post('/home/testimonials', [AdminController::class, 'storeTestimonial'])->name('admin.testimonials.store');
    Route::put('/home/testimonials/{id}', [AdminController::class, 'updateTestimonial'])->name('admin.testimonials.update');
    Route::delete('/home/testimonials/{id}', [AdminController::class, 'deleteTestimonial'])->name('admin.testimonials.delete');
    Route::post('/home/faqs', [AdminController::class, 'storeFaq'])->name('admin.faqs.store');
    Route::put('/home/faqs/{id}', [AdminController::class, 'updateFaq'])->name('admin.faqs.update');
    Route::delete('/home/faqs/{id}', [AdminController::class, 'deleteFaq'])->name('admin.faqs.delete');
});


    Route::get('/assignment', [AssignmentController::class, 'assignment'])->name('student.assignments');
    Route::post('/assignment/{assignmentId}/submit', [AssignmentController::class, 'submitAssignment'])->name('student.assignment.submit');

    Route::get('/assignments/{liveClassId}', [AssignmentController::class, 'viewClassAssignments'])->name('teacher.assignments.view');

        Route::get('/assignment-int', [AssignmentController::class, 'assignmentInt'])->name('student.assignments.int');
    Route::post('/assignment-int/{assignmentId}/submit', [AssignmentController::class, 'submitAssignmentInt'])->name('student.assignment.submit.int');

    Route::get('/assignments-int/{liveClassId}', [AssignmentController::class, 'viewClassAssignmentsInt'])->name('teacher.assignments.view.int');
    //Route::get('/recordings/view', [AdminRecordingController::class, 'view'])->name('admin.recordings.storeView');
    // Route::post('/recordings/view', [AdminRecordingController::class, 'storeView'])->name('admin.recordings.store');
    // Route::get('/get-folders/{courseId}', [AdminRecordingController::class, 'getFolders']);
    // Route::post('/add-folder/{courseId}', [AdminRecordingController::class, 'addFolder']);

 //   Route::prefix('admin')->group(function () {
    Route::get('/recordings/create', [AdminRecordingController::class, 'create'])->name('admin.recordings.create');
    Route::post('/recordings/folder', [AdminRecordingController::class, 'storeFolder'])->name('recordings.storeFolder');
    Route::post('/recordings/topic', [AdminRecordingController::class, 'storeTopic'])->name('recordings.storeTopic');
    Route::post('/recordings/recording', [AdminRecordingController::class, 'storeRecording'])->name('recordings.storeRecording');
    Route::get('/recordings/view', [AdminRecordingController::class, 'view'])->name('recordings.view');

Route::post('/admin/{type}', [AdminRecordingController::class, 'store'])->name('store.item');
//Route::put('/admin/{type}/{id}', [AdminRecordingController::class, 'update'])->name('update.item');
Route::post('/admin/{type}/{id}/toggle-lock', [AdminRecordingController::class, 'toggleLock'])->name('toggle.lock');
//});
Route::post('/admin/topic-and-recording/create', [AdminRecordingController::class, 'createTopicAndRecording'])->name('create.topic.and.recording');
Route::post('/admin/folder/create', [AdminRecordingController::class, 'createFolder'])->name('create.folder');
Route::post('/admin/topic/create', [AdminRecordingController::class, 'createTopic'])->name('create.topic');
Route::post('/admin/recording/create', [AdminRecordingController::class, 'createRecording'])->name('create.recording');
Route::put('/admin/folder/{id}', [AdminRecordingController::class, 'updateFolder'])->name('update.folder');
Route::put('/admin/topic/{id}', [AdminRecordingController::class, 'updateTopic'])->name('update.topic');
Route::put('/admin/recording/{id}', [AdminRecordingController::class, 'updateRecording'])->name('update.recording');
Route::post('/admin/{type}/{id}/toggle-lock', [AdminRecordingController::class, 'toggleLock'])->name('toggle.lock');
Route::put('/admin/item/{id}', [AdminRecordingController::class, 'updateItem'])->name('update.item');


Route::get('/course-details-int/{id}/edit', [CourseDetailsController::class, 'editInt'])->name('course.edit.int');
Route::put('/course-details-int-int/{id}', [CourseDetailsController::class, 'updateInt'])->name('course.update.int');

Route::get('/admin-int/{type}/{id}/toggle-lock', [AdminRecordingController::class, 'toggleLockInt'])->name('toggle.lock.int');
Route::get('/admin-int/folder/create', [AdminRecordingController::class, 'createFolderInt'])->name('create.folder.int');
Route::get('/admin-int/topic/create', [AdminRecordingController::class, 'createTopicInt'])->name('create.topic.int');
Route::get('/admin-int/recording/create', [AdminRecordingController::class, 'createRecordingInt'])->name('create.recording.int');
Route::get('/admin-int/topic-and-recording/create', [AdminRecordingController::class, 'createTopicAndRecordingInt'])->name('create.topic.and.recording.int');
Route::get('/admin-int/folder/{id}', [AdminRecordingController::class, 'updateFolderInt'])->name('update.folder.int');
Route::get('/admin-int/item/{id}', [AdminRecordingController::class, 'updateItemInt'])->name('update.item.int');

Route::get('/student/assignments', [AdminAssignmentController::class, 'index'])->name('student.assignments.all');
Route::get('/student/assignments/batch/{batchId}', [AdminAssignmentController::class, 'getAssignmentsByBatch'])->name('admin.assignments.batch');

Route::get('/admin/assignments/download/{assignmentId}', [AdminAssignmentController::class, 'download'])->name('admin.assignments.download');


Route::get('/enrollment-report', [CourseToInternshipController::class, 'index'])->name('enrollment.report');

Route::post('/enrollment-report/send-offer', [CourseToInternshipController::class, 'sendOfferLetter'])->name('enrollment.send-offer');
Route::get('/test-email', [CourseToInternshipController::class, 'sendTestEmail'])->name('test.email');

Route::get('/hire-with-us',[HireController::class, 'show'])->name('hire.show');
Route::post('/mentor', [HireController::class, 'storeMentor'])->name('mentor.store');

use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ResumeController;

Route::get('/MaayankMalhotraResume', [ResumeController::class, 'index'])->name('resume.index');
Route::post('/MaayankMalhotraResume/store', [ResumeController::class, 'store'])->name('resume.store');

// Teacher routes

    Route::get('/teacher/slots', [TeacherController::class, 'index'])->name('teacher.slots');
    Route::post('/teacher/slots', [TeacherController::class, 'createSlot']);
    Route::get('/teacher/bookings', [TeacherController::class, 'viewBookings'])->name('teacher.bookings');
    Route::post('/teacher/bookings/{id}/upload-link', [TeacherController::class, 'uploadLink'])->name('teacher.bookings.upload-link');
Route::post('/teacher/update-slot-status/{slotId}', [TeacherController::class, 'updateSlotStatus'])->name('teacher.update-slot-status');

// Student routes

    Route::get('/student/slots', [StudentController::class, 'viewAvailableSlots'])->name('student.slots');
    Route::post('/student/book/{slotId}', [StudentController::class, 'bookSlot'])->name('student.book');
    Route::get('/student/interview', [StudentController::class, 'joinInterview'])->name('student.interview');
use App\Http\Controllers\LeadController;

Route::post('/leads/store', [LeadController::class, 'store'])->name('leads.store');


Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');

Route::post('/admin/leads/{id}/send-email', [LeadController::class, 'sendEmail'])->name('admin.leads.sendEmail');



