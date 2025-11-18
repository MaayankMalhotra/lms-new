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
        // Clean up any partially created table from previous failed runs
        Schema::dropIfExists('job_role_applications');

        Schema::create('job_role_applications', function (Blueprint $table) {
            $table->id();
            // Use plain IDs + indexes to avoid FK type mismatch issues with existing tables
            $table->unsignedBigInteger('job_role_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('resume_path');
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_role_applications');
    }
};
