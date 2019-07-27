<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username', 50);
            $table->char('password', 60);
            $table->string('full_name', 100);
            $table->string('email', 150);
            $table->string('skype', 30);
            $table->string('address', 250);

            // Possible values: 0 = Undergraduate / 1 = College graduate
            $table->unsignedTinyInteger('educational_attainment');

            $table->date('birthday');
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
        Schema::dropIfExists('teachers');
    }
}
