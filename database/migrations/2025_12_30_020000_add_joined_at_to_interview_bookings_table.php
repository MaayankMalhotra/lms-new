<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interview_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('interview_bookings', 'joined_at')) {
                $table->timestamp('joined_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('interview_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('interview_bookings', 'joined_at')) {
                $table->dropColumn('joined_at');
            }
        });
    }
};
