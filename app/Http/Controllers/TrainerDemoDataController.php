<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AvailableSlot;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\InterviewBooking;
use App\Models\Internship;
use App\Models\InternshipBatch;
use App\Models\LiveClass;
use App\Models\Message;
use App\Models\TrainerDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TrainerDemoDataController extends Controller
{
    public function populate()
    {
        if (!auth()->check() || (int) auth()->user()->role !== 2) {
            abort(403, 'Only trainers can run this action.');
        }

        DB::transaction(function () {
            $trainer = User::updateOrCreate(
                ['email' => 'trainer@gmail.com'],
                [
                    'name' => 'Trainer Demo Account',
                    'phone' => '9999999999',
                    'role' => 2,
                    'password' => Hash::make('123456'),
                    'internship' => 1,
                ]
            );

            $student = User::updateOrCreate(
                ['email' => 'trainer.student.demo@gmail.com'],
                [
                    'name' => 'Trainer Demo Student',
                    'phone' => '8888888888',
                    'role' => 3,
                    'password' => Hash::make('123456'),
                ]
            );

            $courseOne = Course::updateOrCreate(
                ['slug' => 'trainer-demo-course-1'],
                [
                    'name' => 'Trainer Demo Course - Full Stack',
                    'logo' => 'images/default-course.png',
                    'description' => 'Demo course for trainer sidebar and dashboard data.',
                    'price' => 24999,
                    'placed_learner' => 120,
                    'duration' => '6 Months',
                    'rating' => '4.8',
                    'course_code_id' => 'TDC-001',
                ]
            );

            $courseTwo = Course::updateOrCreate(
                ['slug' => 'trainer-demo-course-2'],
                [
                    'name' => 'Trainer Demo Course - Data Science',
                    'logo' => 'images/default-course.png',
                    'description' => 'Second demo course for trainer data.',
                    'price' => 29999,
                    'placed_learner' => 85,
                    'duration' => '5 Months',
                    'rating' => '4.7',
                    'course_code_id' => 'TDC-002',
                ]
            );

            TrainerDetail::updateOrCreate(
                ['user_id' => $trainer->id],
                [
                    'experience' => '5 Years',
                    'teaching_hours' => 25,
                    'course_ids' => [$courseOne->id, $courseTwo->id],
                ]
            );

            $batchOne = Batch::updateOrCreate(
                [
                    'teacher_id' => $trainer->id,
                    'course_id' => $courseOne->id,
                    'batch_name' => 'Trainer Demo Batch A',
                ],
                [
                    'start_date' => Carbon::now()->subWeeks(2)->toDateString(),
                    'status' => 'active',
                    'days' => 'Mon,Wed,Fri',
                    'duration' => '6',
                    'time_slot' => '10:00 AM - 12:00 PM',
                    'price' => 24999,
                    'emi_price' => 5000,
                    'emi_available' => true,
                    'emi_plans' => [3, 6],
                    'discount_info' => 'Demo Offer',
                    'slots_available' => 40,
                    'slots_filled' => 12,
                ]
            );

            $batchTwo = Batch::updateOrCreate(
                [
                    'teacher_id' => $trainer->id,
                    'course_id' => $courseTwo->id,
                    'batch_name' => 'Trainer Demo Batch B',
                ],
                [
                    'start_date' => Carbon::now()->addWeek()->toDateString(),
                    'status' => 'upcoming',
                    'days' => 'Tue,Thu,Sat',
                    'duration' => '5',
                    'time_slot' => '03:00 PM - 05:00 PM',
                    'price' => 29999,
                    'emi_price' => 6000,
                    'emi_available' => true,
                    'emi_plans' => [3, 6],
                    'discount_info' => 'Demo Offer',
                    'slots_available' => 35,
                    'slots_filled' => 7,
                ]
            );

            $liveClassOne = LiveClass::updateOrCreate(
                [
                    'batch_id' => $batchOne->id,
                    'topic' => 'HTML & CSS Foundations',
                ],
                [
                    'course_id' => $courseOne->id,
                    'folder_id' => null,
                    'google_meet_link' => 'https://meet.google.com/demo-class-one',
                    'class_datetime' => Carbon::now()->addDay()->setTime(10, 0, 0),
                    'duration_minutes' => 90,
                    'recording_id' => null,
                    'status' => 'Scheduled',
                ]
            );

            LiveClass::updateOrCreate(
                [
                    'batch_id' => $batchOne->id,
                    'topic' => 'JavaScript Core Concepts',
                ],
                [
                    'course_id' => $courseOne->id,
                    'folder_id' => null,
                    'google_meet_link' => 'https://meet.google.com/demo-class-two',
                    'class_datetime' => Carbon::now()->subDays(1)->setTime(11, 0, 0),
                    'duration_minutes' => 60,
                    'recording_id' => null,
                    'status' => 'Scheduled',
                ]
            );

            LiveClass::updateOrCreate(
                [
                    'batch_id' => $batchTwo->id,
                    'topic' => 'Python for Analytics',
                ],
                [
                    'course_id' => $courseTwo->id,
                    'folder_id' => null,
                    'google_meet_link' => 'https://meet.google.com/demo-class-three',
                    'class_datetime' => Carbon::now()->addDays(2)->setTime(15, 30, 0),
                    'duration_minutes' => 75,
                    'recording_id' => null,
                    'status' => 'Scheduled',
                ]
            );

            Enrollment::updateOrCreate(
                [
                    'user_id' => $student->id,
                    'batch_id' => $batchOne->id,
                ],
                [
                    'email' => $student->email,
                    'status' => 'active',
                ]
            );

            DB::table('leaves')->updateOrInsert(
                [
                    'user_id' => $student->id,
                    'leave_date' => Carbon::now()->addDays(3)->toDateString(),
                ],
                [
                    'reason' => 'Demo leave for trainer attendance panel',
                    'status' => 'pending',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            Assignment::updateOrCreate(
                [
                    'batch_id' => $batchOne->id,
                    'course_id' => $courseOne->id,
                    'title' => 'Build Responsive Landing Page',
                ],
                [
                    'live_class_id' => $liveClassOne->id,
                    'description' => 'Submit responsive page using HTML/CSS/JS.',
                    'due_date' => Carbon::now()->addDays(5),
                    'file_path' => null,
                ]
            );

            Assignment::updateOrCreate(
                [
                    'batch_id' => $batchTwo->id,
                    'course_id' => $courseTwo->id,
                    'title' => 'Data Cleaning Exercise',
                ],
                [
                    'live_class_id' => null,
                    'description' => 'Clean dataset and submit notebook output.',
                    'due_date' => Carbon::now()->addDays(7),
                    'file_path' => null,
                ]
            );

            $slotOne = AvailableSlot::updateOrCreate(
                [
                    'teacher_id' => $trainer->id,
                    'batch_id' => $batchOne->id,
                    'start_time' => Carbon::now()->addDay()->setTime(18, 0, 0),
                ],
                [
                    'course_id' => $courseOne->id,
                    'mock_type' => 'Technical',
                    'duration_minutes' => 60,
                    'slot_number' => 1,
                    'status' => 'pending',
                    'is_booked' => true,
                ]
            );

            AvailableSlot::updateOrCreate(
                [
                    'teacher_id' => $trainer->id,
                    'batch_id' => $batchTwo->id,
                    'start_time' => Carbon::now()->addDays(2)->setTime(19, 0, 0),
                ],
                [
                    'course_id' => $courseTwo->id,
                    'mock_type' => 'HR',
                    'duration_minutes' => 45,
                    'slot_number' => 1,
                    'status' => 'pending',
                    'is_booked' => false,
                ]
            );

            InterviewBooking::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'slot_id' => $slotOne->id,
                ],
                [
                    'meeting_link' => 'https://meet.google.com/demo-mock-interview',
                    'status' => 'confirmed',
                    'marks' => 78,
                    'teacher_notes' => 'Good communication, improve DSA depth.',
                    'joined_at' => Carbon::now()->subHours(2),
                ]
            );

            $internshipOne = Internship::updateOrCreate(
                ['name' => 'Trainer Demo Internship - Web Development'],
                [
                    'logo' => 'images/default-internship.png',
                    'duration' => '3 Months',
                    'project' => '4 Live Projects',
                    'price' => 14999,
                    'applicant' => '40',
                    'certified_button' => 'Yes',
                ]
            );

            $internshipTwo = Internship::updateOrCreate(
                ['name' => 'Trainer Demo Internship - Data Analytics'],
                [
                    'logo' => 'images/default-internship.png',
                    'duration' => '2 Months',
                    'project' => '3 Case Studies',
                    'price' => 12999,
                    'applicant' => '30',
                    'certified_button' => 'Yes',
                ]
            );

            InternshipBatch::updateOrCreate(
                [
                    'teacher_id' => $trainer->id,
                    'internship_id' => $internshipOne->id,
                    'batch_name' => 'Trainer Demo Internship Batch A',
                ],
                [
                    'start_date' => Carbon::now()->subWeek()->toDateString(),
                    'status' => 'active',
                    'days' => 'Mon,Wed,Fri',
                    'duration' => '3',
                    'time_slot' => '11:00 AM - 12:00 PM',
                    'price' => 14999,
                    'emi_price' => 4000,
                    'emi_available' => true,
                    'emi_plans' => [3],
                    'discount_info' => 'Demo Offer',
                    'slots_available' => 25,
                    'slots_filled' => 8,
                ]
            );

            InternshipBatch::updateOrCreate(
                [
                    'teacher_id' => $trainer->id,
                    'internship_id' => $internshipTwo->id,
                    'batch_name' => 'Trainer Demo Internship Batch B',
                ],
                [
                    'start_date' => Carbon::now()->addDays(10)->toDateString(),
                    'status' => 'upcoming',
                    'days' => 'Tue,Thu',
                    'duration' => '2',
                    'time_slot' => '04:00 PM - 05:00 PM',
                    'price' => 12999,
                    'emi_price' => 3500,
                    'emi_available' => true,
                    'emi_plans' => [2],
                    'discount_info' => 'Demo Offer',
                    'slots_available' => 20,
                    'slots_filled' => 5,
                ]
            );

            Message::updateOrCreate(
                [
                    'sender_id' => $student->id,
                    'receiver_id' => $trainer->id,
                    'message' => 'Hello sir, I need help with the assignment.',
                ],
                [
                    'is_read' => 0,
                ]
            );
        });

        return redirect()->back()->with(
            'success',
            'Trainer demo data populated for trainer@gmail.com (password: 123456).'
        );
    }
}
