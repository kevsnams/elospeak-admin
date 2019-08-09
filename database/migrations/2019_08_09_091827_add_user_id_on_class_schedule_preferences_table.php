<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdOnClassSchedulePreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classroom_schedule_preferences', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')->after('end_minute');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classroom_schedule_preferences', function (Blueprint $table) {
            $table->dropColumn('student_id');
        });
    }
}
