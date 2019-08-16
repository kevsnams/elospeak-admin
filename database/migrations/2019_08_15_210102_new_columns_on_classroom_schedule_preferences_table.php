<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewColumnsOnClassroomSchedulePreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classroom_schedule_preferences', function (Blueprint $table) {
            $table->string('monday', 250)->nullable()->default(null)->after('id');
            $table->string('tuesday', 250)->nullable()->default(null)->after('monday');
            $table->string('wednesday', 250)->nullable()->default(null)->after('tuesday');
            $table->string('thursday', 250)->nullable()->default(null)->after('wednesday');
            $table->string('friday', 250)->nullable()->default(null)->after('thursday');
            $table->string('saturday', 250)->nullable()->default(null)->after('friday');
            $table->string('sunday', 250)->nullable()->default(null)->after('saturday');
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
            $table->dropColumn(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
        });
    }
}
