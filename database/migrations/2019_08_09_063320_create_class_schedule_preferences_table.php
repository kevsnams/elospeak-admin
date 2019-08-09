<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassSchedulePreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classroom_schedule_preferences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('monday')->default(0);
            $table->unsignedTinyInteger('tuesday')->default(0);
            $table->unsignedTinyInteger('wednesday')->default(0);
            $table->unsignedTinyInteger('thursday')->default(0);
            $table->unsignedTinyInteger('friday')->default(0);
            $table->unsignedTinyInteger('saturday')->default(0);
            $table->unsignedTinyInteger('start_hour')->default(0);
            $table->unsignedTinyInteger('start_minute')->default(0);
            $table->unsignedTinyInteger('end_hour')->default(0);
            $table->unsignedTinyInteger('end_minute')->default(0);
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
        Schema::dropIfExists('classroom_schedule_preferences');
    }
}
