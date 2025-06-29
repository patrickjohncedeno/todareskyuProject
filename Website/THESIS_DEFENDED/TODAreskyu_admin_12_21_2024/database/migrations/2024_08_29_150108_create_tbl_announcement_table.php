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
        Schema::create('tbl_announcement', function (Blueprint $table) {
            $table->id('announcementID');
            $table->string('title', 255); 
            $table->longText('content'); 
            $table->string('author', 255); 
            $table->timestamp('datePosted');
            $table->timestamp('dateExpiry')->nullable();
            $table->string('status', 50)->default('Pending'); 
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
        Schema::dropIfExists('tbl_announcement');
    }
};
