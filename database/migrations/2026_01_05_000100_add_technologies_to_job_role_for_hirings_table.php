<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_role_for_hirings', function (Blueprint $table) {
            if (!Schema::hasColumn('job_role_for_hirings', 'technologies')) {
                $table->json('technologies')->nullable()->after('suggestions');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_role_for_hirings', function (Blueprint $table) {
            if (Schema::hasColumn('job_role_for_hirings', 'technologies')) {
                $table->dropColumn('technologies');
            }
        });
    }
};
