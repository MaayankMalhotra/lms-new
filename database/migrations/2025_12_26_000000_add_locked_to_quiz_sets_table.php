<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('quiz_sets', 'locked')) {
            Schema::table('quiz_sets', function (Blueprint $table) {
                $table->boolean('locked')->default(false)->after('total_quizzes');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('quiz_sets', 'locked')) {
            Schema::table('quiz_sets', function (Blueprint $table) {
                $table->dropColumn('locked');
            });
        }
    }
};
