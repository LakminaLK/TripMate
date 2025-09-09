<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hotel_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade');
            $table->integer('room_count')->default(0); // Number of rooms of this type
            $table->decimal('price_per_night', 10, 2)->nullable(); // Hotel-specific pricing
            $table->boolean('is_available')->default(true); // Whether this room type is currently bookable
            $table->text('notes')->nullable(); // Any specific notes about this room type for this hotel
            $table->timestamps();
            
            // Ensure a hotel can't have duplicate room type entries
            $table->unique(['hotel_id', 'room_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_rooms');
    }
};
