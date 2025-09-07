<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Batch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
{
    if (Auth::check()) {
        if (Auth::user()->role == 1) {
            return to_route('admin.dash');
        } elseif (Auth::user()->role == 2) {
            return to_route('trainer.dashboard');
        } elseif (Auth::user()->role == 3) {
            return to_route('student.dashboard');
        }
    }

    $placements = DB::select("SELECT * FROM home_placements WHERE is_active = 1 LIMIT 2");
    $courses = DB::select("SELECT * FROM home_courses WHERE is_active = 1 LIMIT 3");
    $upcomingCourses = DB::select("SELECT * FROM home_upcoming_courses WHERE is_active = 1 LIMIT 3");
    $internships = DB::select("SELECT * FROM home_internships WHERE is_active = 1 LIMIT 3");
    $instructors = DB::select("SELECT * FROM home_instructors WHERE is_active = 1 LIMIT 4");
    $testimonials = DB::select("SELECT * FROM home_testimonials WHERE is_active = 1 LIMIT 3");
    $faqs = DB::select("SELECT * FROM home_faqs WHERE is_active = 1");

    return view('website.home', compact(
        'placements',
        'courses',
        'upcomingCourses',
        'internships',
        'instructors',
        'testimonials',
        'faqs'
    ));
}
}