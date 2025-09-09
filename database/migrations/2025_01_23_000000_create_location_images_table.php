<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('location_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->string('path'); // Path in storage
            $table->string('caption')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('location_images');
    }
};
