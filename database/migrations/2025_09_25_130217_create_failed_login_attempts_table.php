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
        Schema::create('failed_login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->integer('attempt_count')->default(1);
            $table->timestamp('locked_until')->nullable();
            $table->timestamp('last_attempt_at')->useCurrent();
            $table->timestamps();
            
            // Index for faster queries
            $table->index(['email', 'ip_address']);
            $table->index('locked_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('failed_login_attempts');
    }
};
