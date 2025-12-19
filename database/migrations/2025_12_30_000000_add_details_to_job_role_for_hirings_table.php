<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_role_for_hirings', function (Blueprint $table) {
            if (!Schema::hasColumn('job_role_for_hirings', 'company_name')) {
                $table->string('company_name')->nullable()->after('title');
            }
            if (!Schema::hasColumn('job_role_for_hirings', 'salary_package')) {
                $table->string('salary_package')->nullable()->after('company_name');
            }
            if (!Schema::hasColumn('job_role_for_hirings', 'location')) {
                $table->string('location')->nullable()->after('salary_package');
            }
            if (!Schema::hasColumn('job_role_for_hirings', 'apply_link')) {
                $table->string('apply_link')->nullable()->after('location');
            }
            if (!Schema::hasColumn('job_role_for_hirings', 'image_url')) {
                $table->string('image_url')->nullable()->after('apply_link');
            }
            if (!Schema::hasColumn('job_role_for_hirings', 'last_date_to_apply')) {
                $table->date('last_date_to_apply')->nullable()->after('image_url');
            }
            if (!Schema::hasColumn('job_role_for_hirings', 'suggestions')) {
                $table->text('suggestions')->nullable()->after('last_date_to_apply');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_role_for_hirings', function (Blueprint $table) {
            $columns = [
                'company_name',
                'salary_package',
                'location',
                'apply_link',
                'image_url',
                'last_date_to_apply',
                'suggestions',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('job_role_for_hirings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
