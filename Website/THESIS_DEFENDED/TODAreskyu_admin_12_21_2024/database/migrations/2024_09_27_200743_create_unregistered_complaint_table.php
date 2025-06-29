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
        Schema::create('unregistered_complaint', function (Blueprint $table) {
            $table->id('complaint_unregistered_ID'); // Primary Key
            $table->unsignedBigInteger('userID'); // Foreign key to users table
            $table->unsignedBigInteger('violationID'); // Foreign key to violations table
            $table->timestamp('dateSubmitted'); // Timestamp of when the complaint was submitted
            $table->string('location'); // Complaint location
            $table->text('description'); // Description of the complaint
            $table->string('status'); // Status of the complaint
            $table->unsignedBigInteger('id'); // Another foreign key
            $table->text('reasonForDenying')->nullable();
            $table->text('resolutionDetail')->nullable(); // Resolution details (nullable)
            $table->timestamp('dateResolve')->nullable(); // Date when the complaint was resolved (nullable)
            $table->timestamp('meetingDate')->nullable();
            $table->string('evidencePhoto')->nullable();
            $table->string('plateNumber')->nullable();
            $table->string('tricycleColor')->nullable();
            $table->string('tricycleDescription')->nullable();


            // Define foreign keys if necessary
            $table->foreign('userID')->references('userID')->on('tbl_userinfo');
            $table->foreign('violationID')->references('violationID')->on('tbl_violation');
            $table->foreign('id')->references('id')->on('users');

            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unregistered_complaint');
    }
};
