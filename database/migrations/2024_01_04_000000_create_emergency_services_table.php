<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('emergency_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['hospital', 'police', 'fire_station', 'pharmacy', 'ambulance']);
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('phone');
            $table->string('emergency_phone')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('google_place_id')->nullable();
            $table->boolean('is_24_7')->default(false);
            $table->json('operating_hours')->nullable();
            $table->timestamps();

            $table->index(['latitude', 'longitude']);
            $table->index('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('emergency_services');
    }
};
