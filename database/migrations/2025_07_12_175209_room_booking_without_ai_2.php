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
        // tạo bản room_bookings mới
        Schema::create('room_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id')->nullable();
            // lý do đặt phòng
            $table->string('reason')->nullable();
            // ngày đặt phòng
            $table->date('start_date');
            $table->date('end_date')->nullable();
            // thời gian sử dụng phòng
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            // trang thái đặt phòng
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled_by_customer', 'cancelled_by_admin'])
                ->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();

            // thông tin khách hàng
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            // số người tham gia
            $table->unsignedInteger('participants_count')->nullable()->default(0);
            // ghi chú
            $table->string('notes', 500)->nullable();

            // mã code để khách hàng có thể xem hoặc cancel booking
            // dùng mã hash md5
            // chỉ có thể cancel trước khi được duyệt
            $table->string('booking_code', 50)->nullable()->unique();
            // trường mới để lưu các ngày lặp lại trong tuần
            $table->json('repeat_days')->nullable()
                ->comment('Các ngày trong tuần sẽ lặp lại (monday, tuesday, ...)');
            

            $table->timestamps();

            // khóa ngoại & indexes
            $table->foreign('room_id', 'room_bookings_room_id_foreign_20250713')->references('id')->on('rooms')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cancelled_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['room_id', 'start_date', 'end_date']);
            $table->index(['status', 'start_date', 'end_date']);
            $table->index(['approved_by', 'rejected_by', 'cancelled_by']);
            $table->index(['created_by', 'start_date', 'end_date']);
        });

        // tạo bảng room_booking_details
        Schema::create('room_booking_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_booking_id');
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            // status của chi tiết đặt phòng
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])
                ->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();
            // đánh dấu hủy bởi khách hàng hay admin
            $table->boolean('cancelled_by_customer')->default(false);
            $table->timestamps();

            // khóa ngoại
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cancelled_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('room_booking_id')->references('id')->on('room_bookings')->onDelete('cascade');
            $table->index(['room_booking_id', 'booking_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_booking_details');
        Schema::dropIfExists('room_bookings');
    }
};
