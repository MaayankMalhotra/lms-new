<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizSet;
use App\Models\StudentQuizAnswer;
use App\Models\StudentQuizSetAttempt;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class QuizDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $teacher = User::updateOrCreate(
                ['email' => 'mentor.quiz@example.com'],
                [
                    'name' => 'Mentor Quizmaster',
                    'role' => 2,
                    'password' => Hash::make('password'),
                ]
            );

            $studentProfiles = [
                ['email' => 'riya.student@example.com', 'name' => 'Riya Kapoor'],
                ['email' => 'arjun.student@example.com', 'name' => 'Arjun Mehta'],
                ['email' => 'meera.student@example.com', 'name' => 'Meera Iyer'],
            ];

            $students = [];
            foreach ($studentProfiles as $profile) {
                $students[$profile['email']] = User::updateOrCreate(
                    ['email' => $profile['email']],
                    [
                        'name' => $profile['name'],
                        'role' => 3,
                        'password' => Hash::make('password'),
                    ]
                );
            }

            $coursesData = [
                [
                    'course' => [
                        'slug' => 'full-stack-career',
                        'name' => 'Full Stack Career Accelerator',
                        'course_code_id' => 'FS-101',
                        'logo' => 'https://dummyimage.com/200x200/1e40af/f8fafc&text=FS',
                        'duration' => '16 Weeks',
                        'placed_learner' => 320,
                        'rating' => 4.8,
                        'price' => 49999,
                    ],
                    'batch' => [
                        'batch_name' => 'FS Jan Super 30',
                        'start_date' => now()->subWeeks(1),
                        'status' => 'running',
                        'days' => 'Mon - Fri',
                        'duration' => '16 Weeks',
                        'time_slot' => '19:00 - 21:00',
                        'price' => 49999,
                        'emi_price' => 5499,
                        'discount_info' => 'Flat 15% scholarship',
                        'slots_available' => 30,
                        'slots_filled' => 12,
                        'emi_available' => true,
                        'emi_plans' => [
                            '3 Months' => '₹5,499 / mo',
                            '6 Months' => '₹2,999 / mo',
                        ],
                    ],
                    'enrollments' => [
                        'riya.student@example.com',
                        'arjun.student@example.com',
                    ],
                    'quiz_sets' => [
                        [
                            'title' => 'Module 1 · Web Fundamentals',
                            'locked' => false,
                            'quizzes' => [
                                [
                                    'question' => 'Which HTML tag wraps the visible page content?',
                                    'options' => ['<meta>', '<body>', '<link>', '<section>'],
                                    'correct_option' => 2,
                                ],
                                [
                                    'question' => 'Which CSS layout module enables two-dimensional layouts?',
                                    'options' => ['Flexbox', 'CSS Grid', 'Floats', 'Position fixed'],
                                    'correct_option' => 2,
                                ],
                                [
                                    'question' => 'What HTTP status represents a successful GET response?',
                                    'options' => ['200', '301', '401', '503'],
                                    'correct_option' => 1,
                                ],
                            ],
                            'attempts' => [
                                'riya.student@example.com' => [2, 2, 1],
                                'arjun.student@example.com' => [2, 1, 4],
                            ],
                        ],
                        [
                            'title' => 'Module 2 · APIs & Auth',
                            'locked' => true,
                            'quizzes' => [
                                [
                                    'question' => 'Which header carries the JWT in REST APIs?',
                                    'options' => ['X-Auth', 'Authorization', 'Cookie', 'X-Token'],
                                    'correct_option' => 2,
                                ],
                                [
                                    'question' => 'Which HTTP verb is idempotent?',
                                    'options' => ['POST', 'PATCH', 'PUT', 'CONNECT'],
                                    'correct_option' => 3,
                                ],
                            ],
                            'attempts' => [],
                        ],
                    ],
                ],
                [
                    'course' => [
                        'slug' => 'data-science-lab',
                        'name' => 'Data Science Launchpad',
                        'course_code_id' => 'DS-204',
                        'logo' => 'https://dummyimage.com/200x200/0f766e/f8fafc&text=DS',
                        'duration' => '20 Weeks',
                        'placed_learner' => 210,
                        'rating' => 4.7,
                        'price' => 54999,
                    ],
                    'batch' => [
                        'batch_name' => 'DS Weekend WAR',
                        'start_date' => now()->subWeeks(3),
                        'status' => 'running',
                        'days' => 'Sat - Sun',
                        'duration' => '20 Weeks',
                        'time_slot' => '10:00 - 13:00',
                        'price' => 54999,
                        'emi_price' => 5999,
                        'discount_info' => 'Weekend warrior cohort',
                        'slots_available' => 25,
                        'slots_filled' => 8,
                        'emi_available' => true,
                        'emi_plans' => [
                            '3 Months' => '₹5,999 / mo',
                            '6 Months' => '₹3,199 / mo',
                        ],
                    ],
                    'enrollments' => [
                        'arjun.student@example.com',
                        'meera.student@example.com',
                    ],
                    'quiz_sets' => [
                        [
                            'title' => 'Sprint 1 · Statistics Primer',
                            'locked' => false,
                            'quizzes' => [
                                [
                                    'question' => 'Mean of [2, 4, 6, 8] is?',
                                    'options' => ['4', '5', '6', '8'],
                                    'correct_option' => 2,
                                ],
                                [
                                    'question' => 'Which plot shows feature correlation best?',
                                    'options' => ['Histogram', 'Scatter Plot', 'Pie Chart', 'Line Chart'],
                                    'correct_option' => 2,
                                ],
                                [
                                    'question' => 'Which metric punishes false positives the most?',
                                    'options' => ['Recall', 'Precision', 'Accuracy', 'Support'],
                                    'correct_option' => 2,
                                ],
                            ],
                            'attempts' => [
                                'meera.student@example.com' => [2, 2, 2],
                            ],
                        ],
                    ],
                ],
            ];

            foreach ($coursesData as $coursePayload) {
                $course = Course::updateOrCreate(
                    ['slug' => $coursePayload['course']['slug']],
                    $coursePayload['course']
                );

                $batch = Batch::updateOrCreate(
                    ['batch_name' => $coursePayload['batch']['batch_name']],
                    array_merge($coursePayload['batch'], [
                        'course_id' => $course->id,
                        'teacher_id' => $teacher->id,
                    ])
                );

                foreach ($coursePayload['enrollments'] as $studentEmail) {
                    if (!isset($students[$studentEmail])) {
                        continue;
                    }

                    Enrollment::updateOrCreate(
                        [
                            'user_id' => $students[$studentEmail]->id,
                            'batch_id' => $batch->id,
                        ],
                        [
                            'email' => $studentEmail,
                            'status' => 'approved',
                        ]
                    );
                }

                foreach ($coursePayload['quiz_sets'] as $quizSetPayload) {
                    $quizSet = QuizSet::updateOrCreate(
                        [
                            'title' => $quizSetPayload['title'],
                            'course_id' => $course->id,
                            'batch_id' => $batch->id,
                        ],
                        [
                            'teacher_id' => $teacher->id,
                            'total_quizzes' => count($quizSetPayload['quizzes']),
                            'locked' => $quizSetPayload['locked'],
                        ]
                    );

                    $quizRecords = [];
                    foreach ($quizSetPayload['quizzes'] as $quizPayload) {
                        $quizRecords[] = Quiz::updateOrCreate(
                            [
                                'quiz_set_id' => $quizSet->id,
                                'question' => $quizPayload['question'],
                            ],
                            [
                                'option_1' => $quizPayload['options'][0],
                                'option_2' => $quizPayload['options'][1],
                                'option_3' => $quizPayload['options'][2],
                                'option_4' => $quizPayload['options'][3],
                                'correct_option' => $quizPayload['correct_option'],
                            ]
                        );
                    }

                    $quizSet->setRelation('quizzes', collect($quizRecords));
                    foreach ($quizSetPayload['attempts'] as $studentEmail => $answers) {
                        if (!isset($students[$studentEmail])) {
                            continue;
                        }

                        $student = $students[$studentEmail];
                        $attempt = StudentQuizSetAttempt::updateOrCreate(
                            [
                                'user_id' => $student->id,
                                'quiz_set_id' => $quizSet->id,
                            ],
                            ['score' => 0]
                        );

                        $attempt->answers()->delete();

                        $score = 0;
                        foreach ($quizSet->quizzes as $index => $quiz) {
                            $studentAnswer = $answers[$index] ?? null;
                            if (!$studentAnswer) {
                                continue;
                            }

                            StudentQuizAnswer::create([
                                'attempt_id' => $attempt->id,
                                'quiz_id' => $quiz->id,
                                'student_answer' => $studentAnswer,
                                'user_id' => $student->id,
                            ]);

                            if ($studentAnswer === (int) $quiz->correct_option) {
                                $score++;
                            }
                        }

                        $attempt->update(['score' => $score]);
                    }
                }
            }
        });
    }
}
