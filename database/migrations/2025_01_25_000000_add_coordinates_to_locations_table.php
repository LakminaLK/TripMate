<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('description');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('google_place_id')->nullable()->after('longitude');
        });
    }

    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'google_place_id']);
        });
    }
};
