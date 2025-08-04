<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('activity_location', function (Blueprint $table) {
        $table->id();
        $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade'); // Link to activities
        $table->foreignId('location_id')->constrained('locations')->onDelete('cascade'); // Link to locations
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_location');
    }
};
