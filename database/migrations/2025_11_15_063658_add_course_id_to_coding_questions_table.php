<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coding_questions', function (Blueprint $table) {
            $table->unsignedInteger('course_id')->nullable()->after('id')
                ->comment('The course that owns this coding question');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coding_questions', function (Blueprint $table) {
            $table->dropColumn('course_id');
        });
    }
};
