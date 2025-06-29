<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_notifications', function (Blueprint $table) {
            $table->text('resolved')->nullable();
            $table->text('unresolved')->nullable();
            $table->date('resolution_date')->nullable()->after('unresolved');
        });
    }

    public function down()
    {
        Schema::table('user_notifications', function (Blueprint $table) {
            $table->dropColumn(['resolved', 'unresolved']);
        });
    }
}
;