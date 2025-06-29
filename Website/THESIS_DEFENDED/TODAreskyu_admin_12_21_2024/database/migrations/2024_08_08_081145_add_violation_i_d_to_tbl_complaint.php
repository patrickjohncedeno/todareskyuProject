<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_complaint', function (Blueprint $table) {
            $table->unsignedBigInteger('violationID')->after('driverID');

            $table->foreign('violationID')
                ->references('violationID')
                ->on('tbl_violation')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_complaint', function (Blueprint $table) {
            $table->dropForeign(['violationID']);

            $table->dropColumn('violationID');
        });
    }
};
