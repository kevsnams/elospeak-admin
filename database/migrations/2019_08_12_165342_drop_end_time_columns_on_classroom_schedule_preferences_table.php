<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropEndTimeColumnsOnClassroomSchedulePreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classroom_schedule_preferences', function (Blueprint $table) {
            $table->dropColumn('end_hour');
            $table->dropColumn('end_minute');
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
            $table->unsignedTinyInteger('end_hour')->default(0)->after('start_minute');
            $table->unsignedTinyInteger('end_minute')->default(0)->after('end_minute');
        });
    }
}
