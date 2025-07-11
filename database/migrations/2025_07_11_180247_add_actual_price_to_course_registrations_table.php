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
        Schema::table('course_registrations', function (Blueprint $table) {
            $table->decimal('actual_price', 10, 2)->nullable()->after('payment_status')
                ->comment('Giá thực tế khi đăng ký (lưu để tránh thay đổi khi admin sửa giá khóa học)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_registrations', function (Blueprint $table) {
            $table->dropColumn('actual_price');
        });
    }
};
