<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assignment_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('assignment_submissions', 'status')) {
                $table->string('status')->default('submitted')->after('marks');
            }

            if (!Schema::hasColumn('assignment_submissions', 'feedback')) {
                $table->text('feedback')->nullable()->after('status');
            }

            if (!Schema::hasColumn('assignment_submissions', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('feedback');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignment_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('assignment_submissions', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('assignment_submissions', 'feedback')) {
                $table->dropColumn('feedback');
            }

            if (Schema::hasColumn('assignment_submissions', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }
        });
    }
};
