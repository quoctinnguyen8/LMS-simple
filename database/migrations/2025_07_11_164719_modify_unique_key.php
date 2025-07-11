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
        // gỡ unique trên cột số điện thoại khi đăng ký
        Schema::table('course_registrations', function (Blueprint $table) {
            // Kiểm tra nếu cột 'student_phone' tồn tại
            if (Schema::hasColumn('course_registrations', 'student_phone')) {
                // Xóa unique constraint trên cột 'student_phone'
                $table->dropUnique(['student_phone']);
                // tạo index mới không unique cho cột 'student_phone'
                $table->index('student_phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
