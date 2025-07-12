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
            $table->integer('max_students')->nullable()->after('status');
            $table->boolean('allow_overflow')->default(false)->after('max_students')->comment('Cho phép nhận thêm học viên khi đã đủ số lượng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['max_students', 'allow_overflow']);
        });
    }
};
