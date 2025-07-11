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
        Schema::table('courses', function (Blueprint $table) {
            // thêm thông tin người tạo khóa học vào bảng courses
            $table->unsignedBigInteger('created_by')->nullable()->after('id');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            // thêm thuộc tính cho phép ẩn hoặc hiện giá khóa học
            $table->boolean('is_price_visible')->default(true)->after('price');
        });

        Schema::table('course_registrations', function (Blueprint $table) {
            // xóa cột user_id trong bảng course_registrations
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            // thêm cột created_by
            $table->unsignedBigInteger('created_by')->nullable()->after('id');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            // cho phép student_email, student_birth_date, student_gender có giá trị null
            $table->string('student_email')->nullable()->change();
            $table->date('student_birth_date')->nullable()->change();
            $table->string('student_gender')->nullable()->change();
        });

        // bảng equipments
        Schema::table('equipments', function (Blueprint $table) {
            // thêm cột created_by
            $table->unsignedBigInteger('created_by')->nullable()->after('id');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            // thêm cột đánh dấu trang thiết bị là miễn phí hay không
            $table->boolean('is_free')->default(false)->after('price');
            // sửa cột price để cho phép giá trị null, 15 chữ số, 2 chữ số thập phân
            $table->decimal('price', 15, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            $table->dropColumn('is_price_visible');
        });

        Schema::table('course_registrations', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            $table->string('student_email')->change();
            $table->date('student_birth_date')->change();
            $table->string('student_gender')->change();
        });
        Schema::table('equipments', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            $table->dropColumn('is_free');
            $table->decimal('price', 15, 2)->change();
        });
    }
};
