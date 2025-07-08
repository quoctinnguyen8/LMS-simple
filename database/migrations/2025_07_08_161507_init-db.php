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
        // Settings table for system configurations
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key', 50)->unique();
            $table->text('setting_value')->nullable();
            $table->timestamps();
        });

        // Course categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Courses table (with category_id for one-to-many relationship)
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image', 255)->nullable();
            $table->decimal('price', 19, 0)->default(0);
            $table->unsignedBigInteger('category_id');
            $table->date('end_registration_date')->nullable();
            $table->date('start_date')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
            
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
        });

        // Rooms table for room booking
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->integer('capacity');
            $table->string('location', 255)->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'maintenance', 'unavailable'])->default('available');
            $table->timestamps();
        });

        // Course registrations table
        Schema::create('course_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->timestamp('registration_date')->useCurrent();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->timestamps();
            
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Room booking groups table (for recurring bookings)
        Schema::create('room_booking_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->string('title', 255);
            $table->string('purpose', 255)->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('recurrence_type', ['none', 'weekly'])->default('none');
            $table->string('recurrence_days', 20)->nullable(); // For weekly: '1,3,5' (Monday, Wednesday, Friday)
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
        });

        // Room bookings table (individual booking instances)
        Schema::create('room_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_group_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('purpose', 255)->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->timestamps();
            
            $table->foreign('booking_group_id')->references('id')->on('room_booking_groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
        });

        // Add indexes for better performance
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->index('booking_date', 'idx_room_bookings_date');
            $table->index(['room_id', 'booking_date'], 'idx_room_bookings_room_date');
        });

        Schema::table('room_booking_groups', function (Blueprint $table) {
            $table->index(['start_date', 'end_date'], 'idx_room_booking_groups_dates');
            $table->index('room_id', 'idx_room_booking_groups_room');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_bookings');
        Schema::dropIfExists('room_booking_groups');
        Schema::dropIfExists('course_registrations');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('settings');
    }
};
