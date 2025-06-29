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
        Schema::create('tbl_complaint', function (Blueprint $table) {
            $table->id('complaintID');
            $table->foreignId('userID')->constrained('tbl_userinfo')->references('userID')->on('tbl_userinfo')->onDelete('cascade');
            $table->foreignId('driverID')->constrained('tbl_driverinfo')->references('driverID')->on('tbl_driverinfo')->onDelete('cascade');
            
            $table->timestamp('dateSubmitted');
            $table->string('location');
            $table->text('description');
            $table->string('status');
            $table->foreignId('id')->constrained('users')->references('id')->on('users')->onDelete('cascade');
            $table->text('resolutionDetail')->nullable();
            $table->timestamp('dateResolve')->nullable();
            $table->timestamps();

            // $table->index('userID');
            // $table->index('driverID');
            // $table->index('adminID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_complaint');
    }
};
