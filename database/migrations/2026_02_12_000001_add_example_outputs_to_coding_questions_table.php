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
        Schema::table('coding_questions', function (Blueprint $table) {
            $table->text('example_output_1')->nullable()->after('description');
            $table->text('example_output_2')->nullable()->after('example_output_1');
            $table->text('example_output_3')->nullable()->after('example_output_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coding_questions', function (Blueprint $table) {
            $table->dropColumn([
                'example_output_1',
                'example_output_2',
                'example_output_3',
            ]);
        });
    }
};
