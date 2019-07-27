<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassroomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Note: When changing values of teacher_id and student_id, don't forget to add the reason to the logs
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('student_id');

            $table->dateTime('start');
            $table->dateTime('end');

            /**
             * Possible values:
             * 0 = Waiting
             * 1 = Active
             * 2 = Done
             * 3 = Cancelled
             * Note: If status is 3 (Cancelled), add the reason to the logs
             */
            $table->unsignedTinyInteger('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classrooms');
    }
}
