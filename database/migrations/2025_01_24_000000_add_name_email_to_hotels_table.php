<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            // Add after id if you like; order is optional
            if (!Schema::hasColumn('hotels', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('hotels', 'email')) {
                // nullable+unique is OK in MySQL (multiple NULLs allowed)
                $table->string('email')->nullable()->unique()->after('name');
            }
            // ensure status & location_id exist (skip if you already have them)
            if (!Schema::hasColumn('hotels', 'status')) {
                $table->string('status')->default('Active')->after('password');
            }
            if (!Schema::hasColumn('hotels', 'location_id')) {
                $table->unsignedBigInteger('location_id')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            if (Schema::hasColumn('hotels', 'email'))  $table->dropUnique(['email']);
            if (Schema::hasColumn('hotels', 'email'))  $table->dropColumn('email');
            if (Schema::hasColumn('hotels', 'name'))   $table->dropColumn('name');
            // don’t drop status/location_id on down unless you really want to
        });
    }
};
