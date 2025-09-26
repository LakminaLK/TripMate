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
        Schema::create('hotel_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->string('type'); // 'booking', 'review', 'payment'
            $table->string('title');
            $table->text('message');
            $table->string('action_url')->nullable(); // URL to redirect when clicked
            $table->foreignId('related_id')->nullable(); // booking_id or review_id
            $table->string('related_type')->nullable(); // 'booking' or 'review'
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['hotel_id', 'is_read']);
            $table->index(['hotel_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_notifications');
    }
};
