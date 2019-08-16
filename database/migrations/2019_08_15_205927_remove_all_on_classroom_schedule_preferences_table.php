<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveAllOnClassroomSchedulePreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classroom_schedule_preferences', function (Blueprint $table) {
            $table->dropColumn('start_hour');
            $table->dropColumn('start_minute');
            $table->dropColumn('start_date');
            $table->dropColumn(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
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
            $table->unsignedTinyInteger('monday')->default(0);
            $table->unsignedTinyInteger('tuesday')->default(0);
            $table->unsignedTinyInteger('wednesday')->default(0);
            $table->unsignedTinyInteger('thursday')->default(0);
            $table->unsignedTinyInteger('friday')->default(0);
            $table->unsignedTinyInteger('saturday')->default(0);
            $table->unsignedTinyInteger('start_hour')->default(0);
            $table->unsignedTinyInteger('start_minute')->default(0);
            $table->date('start_date');
        });
    }
}