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
        Schema::create('tbl_driverinfo', function (Blueprint $table) {
            $table->id('driverID');
            $table->string('driverName');
            $table->string('plateNumber');
            $table->string('tinPlate');
            $table->string('qrCode');
            $table->foreignId('todaID')->constrained('tbl_toda')->references('todaID')->on('tbl_toda')->onDelete('cascade');
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
        Schema::dropIfExists('tbl_driverinfo');
    }
};
