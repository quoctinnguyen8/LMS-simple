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
        // đổi tên bảng room_bookings nếu nó tồn tại
        if (Schema::hasTable('room_bookings')) {
            Schema::rename('room_bookings', 'room_booking_backups');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // nếu bảng room_booking_backups tồn tại, đổi tên lại thành room_bookings
        if (Schema::hasTable('room_booking_backups')) {
            Schema::rename('room_booking_backups', 'room_bookings');
        }
    }
};
