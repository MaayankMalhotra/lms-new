<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('available_slots', function (Blueprint $table) {
            if (!Schema::hasColumn('available_slots', 'mock_type')) {
                $table->string('mock_type', 100)->nullable()->after('course_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('available_slots', function (Blueprint $table) {
            if (Schema::hasColumn('available_slots', 'mock_type')) {
                $table->dropColumn('mock_type');
            }
        });
    }
};
