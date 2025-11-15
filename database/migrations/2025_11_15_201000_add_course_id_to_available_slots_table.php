<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('available_slots', 'course_id')) {
            Schema::table('available_slots', function (Blueprint $table) {
                $table->unsignedBigInteger('course_id')->nullable()->after('teacher_id');
                $table->index('course_id', 'available_slots_course_id_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('available_slots', 'course_id')) {
            Schema::table('available_slots', function (Blueprint $table) {
                $table->dropIndex('available_slots_course_id_idx');
                $table->dropColumn('course_id');
            });
        }
    }
};
