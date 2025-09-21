<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_batch_and_course_to_internship_enrollments.php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration {
    public function up(): void
    {
        Schema::table('internship_enrollments', function (Blueprint $table) {
            if (!Schema::hasColumn('internship_enrollments', 'batch_id')) {
                $table->unsignedBigInteger('batch_id')->nullable()->after('name');
                $table->index('batch_id');
            }
            if (!Schema::hasColumn('internship_enrollments', 'course_id')) {
                $table->unsignedBigInteger('course_id')->nullable()->after('batch_id');
                $table->index('course_id');
            }
        });
    }
    public function down(): void
    {
        Schema::table('internship_enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('internship_enrollments', 'course_id')) {
                $table->dropIndex(['course_id']); $table->dropColumn('course_id');
            }
            if (Schema::hasColumn('internship_enrollments', 'batch_id')) {
                $table->dropIndex(['batch_id']); $table->dropColumn('batch_id');
            }
        });
    }
};
