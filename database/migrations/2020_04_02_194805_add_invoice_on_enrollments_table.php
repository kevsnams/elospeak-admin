<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceOnEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn('invoice_id');
            $table->string('pdf_path')->nullable()->default(null)->after('student_id');
            $table->tinyInteger('paid')->unsigned()->default(0)->after('pdf_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->bigInteger('invoice_id')->unsigned()->nullable()->default(null)->after('student_id');
            $table->dropColumn('pdf_path');
            $table->dropColumn('paid');
        });
    }
}
