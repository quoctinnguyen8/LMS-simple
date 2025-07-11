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
            $table->string('student_name');
            $table->string('student_email');
            $table->string('student_phone')->unique();
            $table->string('student_address')
                ->nullable()
                ->default(null);
            $table->date('student_birth_date');
            $table->enum('student_gender', ['male', 'female', 'other'])
                ->default('other');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_registrations', function (Blueprint $table) {
            //
        });
    }
};
