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
    Schema::create('activities', function (Blueprint $table) {
        $table->id();
        $table->string('name');  // Activity name (e.g., "Hiking", "Sightseeing")
        $table->text('description')->nullable(); // Description of the activity
        $table->timestamps(); // Created at & Updated at
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
