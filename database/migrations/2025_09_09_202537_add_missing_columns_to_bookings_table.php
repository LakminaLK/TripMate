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
        Schema::table('bookings', function (Blueprint $table) {
            // Add missing columns that should have been in the original bookings table
            $table->foreignId('tourist_id')->nullable()->constrained('tourists')->onDelete('cascade')->after('id');
            $table->foreignId('hotel_id')->nullable()->constrained('hotels')->onDelete('cascade')->after('tourist_id');
            $table->foreignId('room_type_id')->nullable()->constrained('room_types')->onDelete('cascade')->after('hotel_id');
            $table->date('check_in_date')->nullable()->after('room_type_id');
            $table->date('check_out_date')->nullable()->after('check_in_date');
            $table->integer('rooms_booked')->default(1)->after('check_out_date');
            $table->integer('guests_count')->default(1)->after('rooms_booked');
            $table->decimal('price_per_night', 10, 2)->nullable()->after('guests_count');
            $table->decimal('total_amount', 10, 2)->nullable()->after('price_per_night');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending')->after('total_amount');
            $table->text('special_requests')->nullable()->after('status');
            $table->string('booking_reference')->nullable()->unique()->after('special_requests');
            $table->timestamp('booking_date')->nullable()->useCurrent()->after('booking_reference');
            
            // Add indexes for performance
            $table->index(['hotel_id', 'check_in_date', 'check_out_date']);
            $table->index(['tourist_id', 'status']);
            $table->index('booking_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop the indexes first
            $table->dropIndex(['hotel_id', 'check_in_date', 'check_out_date']);
            $table->dropIndex(['tourist_id', 'status']);
            $table->dropIndex(['booking_reference']);
            
            // Drop the added columns
            $table->dropForeign(['tourist_id']);
            $table->dropForeign(['hotel_id']);
            $table->dropForeign(['room_type_id']);
            
            $table->dropColumn([
                'tourist_id',
                'hotel_id',
                'room_type_id',
                'check_in_date',
                'check_out_date',
                'rooms_booked',
                'guests_count',
                'price_per_night',
                'total_amount',
                'status',
                'special_requests',
                'booking_reference',
                'booking_date'
            ]);
        });
    }
};
