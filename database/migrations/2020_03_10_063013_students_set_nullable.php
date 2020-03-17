<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StudentsSetNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('full_name', 100)->nullable()->change();
            $table->string('email', 150)->nullable()->change();
            $table->string('skype', 30)->nullable()->change();
            $table->date('birthday')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('full_name', 100)->change();
            $table->string('email', 150)->change();
            $table->string('skype', 30)->change();
            $table->date('birthday')->change();
        });
    }
}
