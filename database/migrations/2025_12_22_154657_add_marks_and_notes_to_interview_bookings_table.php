<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interview_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('interview_bookings', 'marks')) {
                $table->integer('marks')->nullable()->after('status');
            }

            if (!Schema::hasColumn('interview_bookings', 'teacher_notes')) {
                $table->text('teacher_notes')->nullable()->after('marks');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('interview_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('interview_bookings', 'teacher_notes')) {
                $table->dropColumn('teacher_notes');
            }

            if (Schema::hasColumn('interview_bookings', 'marks')) {
                $table->dropColumn('marks');
            }
        });
    }
};
