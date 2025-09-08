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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Standard Room", "Deluxe Room", "Suite"
            $table->text('description')->nullable(); // Description of the room type
            $table->string('icon')->nullable(); // Icon class for display
            $table->integer('max_occupancy')->default(2); // Maximum guests
            $table->decimal('base_price', 10, 2)->nullable(); // Base price for reference
            $table->boolean('is_active')->default(true); // Whether this type is available
            $table->integer('sort_order')->default(0); // For ordering display
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
