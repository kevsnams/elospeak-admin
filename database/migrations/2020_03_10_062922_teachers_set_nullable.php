<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TeachersSetNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('full_name', 100)->nullable()->change();
            $table->string('email', 150)->nullable()->change();
            $table->string('skype', 30)->nullable()->change();
            $table->string('address', 250)->nullable()->change();
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
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('full_name', 100)->change();
            $table->string('email', 150)->change();
            $table->string('skype', 30)->change();
            $table->string('address', 250)->change();
            $table->date('birthday')->change();
        });
    }
}
