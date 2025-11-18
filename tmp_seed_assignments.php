<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$user = DB::table('users')->orderBy('id')->value('id');
if (!$user) {
    echo "no user";
    exit(0);
}

$assignments = DB::table('assignments')->pluck('id');
if ($assignments->isEmpty()) {
    echo "no assignments";
    exit(0);
}

foreach ($assignments as $aid) {
    $base = Carbon::now()->subDays(rand(1, 10));
    $rows = [
        [
            'user_id' => $user,
            'live_class_id' => null,
            'assignment_id' => $aid,
            'file_path' => 'dummy_submission_1.pdf',
            'marks' => rand(50, 95),
            'created_at' => $base,
            'updated_at' => $base,
        ],
        [
            'user_id' => $user,
            'live_class_id' => null,
            'assignment_id' => $aid,
            'file_path' => 'dummy_submission_2.pdf',
            'marks' => rand(50, 95),
            'created_at' => $base->copy()->addHours(3),
            'updated_at' => $base->copy()->addHours(3),
        ],
    ];
    DB::table('assignment_submissions')->insert($rows);
}

echo "done";
