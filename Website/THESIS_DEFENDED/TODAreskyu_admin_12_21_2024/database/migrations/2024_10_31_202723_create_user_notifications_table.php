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
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_registered_id')->references('complaint_registered_id')->on('registered_complaint')->onDelete('cascade');
            $table->foreignId('complaint_unregistered_id')->references('complaint_unregistered_id')->on('unregistered_complaint')->onDelete('cascade');
            $table->string('notification_type'); // Either "Meeting Set" or "Denied"
            $table->date('meeting_date')->nullable(); // Only used if notification_type is "Meeting Set"
            $table->text('denial_reason')->nullable(); // Only used if notification_type is "Denied"
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
        Schema::dropIfExists('user_notifications');
    }
};
