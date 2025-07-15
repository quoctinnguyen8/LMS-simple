<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create stored procedure for recurring bookings
        // DB::unprepared('
        //     CREATE PROCEDURE CreateRecurringBookings(
        //         IN p_booking_group_id INT,
        //         IN p_user_id INT,
        //         IN p_room_id INT,
        //         IN p_course_id INT,
        //         IN p_start_date DATE,
        //         IN p_end_date DATE,
        //         IN p_start_time TIME,
        //         IN p_end_time TIME,
        //         IN p_purpose VARCHAR(255),
        //         IN p_recurrence_type ENUM("none", "weekly"),
        //         IN p_recurrence_days VARCHAR(20)
        //     )
        //     BEGIN
        //         DECLARE v_current_date DATE DEFAULT p_start_date;
        //         DECLARE v_day_of_week INT;
        //         DECLARE v_should_create BOOLEAN DEFAULT FALSE;
                
        //         WHILE v_current_date <= p_end_date DO
        //             SET v_should_create = FALSE;
        //             SET v_day_of_week = DAYOFWEEK(v_current_date) - 1; -- 0=Sunday, 1=Monday, etc.
                    
        //             CASE p_recurrence_type
        //                 WHEN "none" THEN
        //                     SET v_should_create = (v_current_date = p_start_date);
        //                 WHEN "weekly" THEN
        //                     IF p_recurrence_days IS NOT NULL AND FIND_IN_SET(v_day_of_week, p_recurrence_days) > 0 THEN
        //                         SET v_should_create = TRUE;
        //                     END IF;
        //             END CASE;
                    
        //             IF v_should_create THEN
        //                 INSERT INTO room_bookings (booking_group_id, user_id, room_id, course_id, booking_date, start_time, end_time, purpose, is_recurring, created_at, updated_at)
        //                 VALUES (p_booking_group_id, p_user_id, p_room_id, p_course_id, v_current_date, p_start_time, p_end_time, p_purpose, TRUE, NOW(), NOW());
        //             END IF;
                    
        //             SET v_current_date = DATE_ADD(v_current_date, INTERVAL 1 DAY);
        //         END WHILE;
        //     END
        // ');

        // // Create function to check room conflicts
        // DB::unprepared('
        //     CREATE FUNCTION CheckRoomConflict(
        //         p_room_id INT,
        //         p_booking_date DATE,
        //         p_start_time TIME,
        //         p_end_time TIME,
        //         p_exclude_booking_id INT
        //     ) RETURNS BOOLEAN
        //     READS SQL DATA
        //     DETERMINISTIC
        //     BEGIN
        //         DECLARE v_conflict_count INT DEFAULT 0;
                
        //         SELECT COUNT(*) INTO v_conflict_count
        //         FROM room_bookings
        //         WHERE room_id = p_room_id 
        //         AND booking_date = p_booking_date
        //         AND status IN ("pending", "approved")
        //         AND (p_exclude_booking_id IS NULL OR id != p_exclude_booking_id)
        //         AND (
        //             (start_time <= p_start_time AND end_time > p_start_time) OR
        //             (start_time < p_end_time AND end_time >= p_end_time) OR
        //             (start_time >= p_start_time AND end_time <= p_end_time)
        //         );
                
        //         RETURN v_conflict_count > 0;
        //     END
        // ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // DB::unprepared('DROP PROCEDURE IF EXISTS CreateRecurringBookings');
        // DB::unprepared('DROP FUNCTION IF EXISTS CheckRoomConflict');
    }
};
