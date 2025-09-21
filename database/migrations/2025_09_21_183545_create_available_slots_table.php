<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('available_slots', 'batch_id')) {
            Schema::table('available_slots', function (Blueprint $table) {
                // add the column (no FK since you’re joining, not relating)
                $table->unsignedBigInteger('batch_id')->nullable()->after('teacher_id');
                $table->index('batch_id', 'available_slots_batch_id_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('available_slots', 'batch_id')) {
            Schema::table('available_slots', function (Blueprint $table) {
                $table->dropIndex('available_slots_batch_id_idx');
                $table->dropColumn('batch_id');
            });
        }
    }
};
