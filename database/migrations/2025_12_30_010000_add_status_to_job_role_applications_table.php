<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_role_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('job_role_applications', 'status')) {
                $table->string('status')->default('applied')->after('message');
                $table->index('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_role_applications', function (Blueprint $table) {
            if (Schema::hasColumn('job_role_applications', 'status')) {
                $table->dropIndex(['status']);
                $table->dropColumn('status');
            }
        });
    }
};
