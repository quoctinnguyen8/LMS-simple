-- File này chứa cấu trúc cơ sở dữ liệu cho dự án LMS đơn giản, bao gồm các bảng cho cài đặt hệ thống, danh mục khóa học, khóa học, phòng học, đăng ký khóa học và đặt phòng. Nó cũng bao gồm các thủ tục lưu trữ để tạo đặt phòng định kỳ và kiểm tra xung đột phòng.
-- Không dùng để chạy trực tiếp, mà chỉ để tham khảo cấu trúc cơ sở dữ liệu. 
-- Thay vào đó, bạn nên sử dụng các lệnh Artisan của Laravel để tạo và chạy migrations.


-- Create database for simple LMS project
CREATE DATABASE IF NOT EXISTS lms_simple CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE lms_simple;

-- Settings table for system configurations
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Course categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Courses table (with category_id for one-to-many relationship)
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT,
    content LONGTEXT,
    featured_image VARCHAR(255),
    price DECIMAL(19, 0) DEFAULT 0,
    category_id INT NOT NULL,
    end_registration_date DATE NULL,
    start_date DATE NULL,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);

-- Rooms table for room booking
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    capacity INT NOT NULL,
    location VARCHAR(255),
    description TEXT,
    status ENUM('available', 'maintenance', 'unavailable') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Course registrations table
CREATE TABLE course_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    payment_status ENUM('unpaid', 'paid', 'refunded') DEFAULT 'unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- Room booking groups table (for recurring bookings)
CREATE TABLE room_booking_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    course_id INT NULL,
    title VARCHAR(255) NOT NULL,
    purpose VARCHAR(255),
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    recurrence_type ENUM('none', 'weekly') DEFAULT 'none',
    recurrence_days VARCHAR(20) NULL, -- For weekly: '1,3,5' (Monday, Wednesday, Friday)
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE SET NULL
);

-- Room bookings table (individual booking instances)
CREATE TABLE room_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_group_id INT NULL,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    course_id INT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    purpose VARCHAR(255),
    is_recurring BOOLEAN DEFAULT FALSE,
    status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_group_id) REFERENCES room_booking_groups(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE SET NULL
);

-- Insert some initial settings
INSERT INTO settings (setting_key, setting_value) VALUES 
('center_name', 'Learning Center'),
('address', '123 Education Street, Knowledge City'),
('phone', '+1234567890'),
('email', 'contact@learningcenter.com'),
('logo', 'logo.png');

-- Insert sample rooms
INSERT INTO rooms (name, capacity, location, description) VALUES
('Room A1', 30, 'Building A - Floor 1', 'Standard classroom with projector'),
('Room B2', 50, 'Building B - Floor 2', 'Large seminar room with audio system'),
('Lab C1', 20, 'Building C - Floor 1', 'Computer lab with 20 workstations');

-- Insert sample categories
INSERT INTO categories (name, slug, description) VALUES
('Programming', 'programming', 'Software development courses'),
('Design', 'design', 'UI/UX and graphic design courses'),
('Business', 'business', 'Business and management courses');

-- Add indexes for better performance
CREATE INDEX idx_room_bookings_date ON room_bookings(booking_date);
CREATE INDEX idx_room_bookings_room_date ON room_bookings(room_id, booking_date);
CREATE INDEX idx_room_booking_groups_dates ON room_booking_groups(start_date, end_date);
CREATE INDEX idx_room_booking_groups_room ON room_booking_groups(room_id);

-- Stored procedure để tạo booking định kỳ
DELIMITER //

CREATE PROCEDURE CreateRecurringBookings(
    IN p_booking_group_id INT,
    IN p_user_id INT,
    IN p_room_id INT,
    IN p_course_id INT,
    IN p_start_date DATE,
    IN p_end_date DATE,
    IN p_start_time TIME,
    IN p_end_time TIME,
    IN p_purpose VARCHAR(255),
    IN p_recurrence_type ENUM('none', 'weekly'),
    IN p_recurrence_days VARCHAR(20)
)
BEGIN
    DECLARE v_current_date DATE DEFAULT p_start_date;
    DECLARE v_day_of_week INT;
    DECLARE v_should_create BOOLEAN DEFAULT FALSE;
    
    WHILE v_current_date <= p_end_date DO
        SET v_should_create = FALSE;
        SET v_day_of_week = DAYOFWEEK(v_current_date) - 1; -- 0=Sunday, 1=Monday, etc.
        
        CASE p_recurrence_type
            WHEN 'none' THEN
                SET v_should_create = (v_current_date = p_start_date);
            WHEN 'weekly' THEN
                IF p_recurrence_days IS NOT NULL AND FIND_IN_SET(v_day_of_week, p_recurrence_days) > 0 THEN
                    SET v_should_create = TRUE;
                END IF;
        END CASE;
        
        IF v_should_create THEN
            INSERT INTO room_bookings (booking_group_id, user_id, room_id, course_id, booking_date, start_time, end_time, purpose, is_recurring)
            VALUES (p_booking_group_id, p_user_id, p_room_id, p_course_id, v_current_date, p_start_time, p_end_time, p_purpose, TRUE);
        END IF;
        
        SET v_current_date = DATE_ADD(v_current_date, INTERVAL 1 DAY);
    END WHILE;
END //

-- Function để kiểm tra conflict
CREATE FUNCTION CheckRoomConflict(
    p_room_id INT,
    p_booking_date DATE,
    p_start_time TIME,
    p_end_time TIME,
    p_exclude_booking_id INT
) RETURNS BOOLEAN
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_conflict_count INT DEFAULT 0;
    
    SELECT COUNT(*) INTO v_conflict_count
    FROM room_bookings
    WHERE room_id = p_room_id 
    AND booking_date = p_booking_date
    AND status IN ('pending', 'approved')
    AND (p_exclude_booking_id IS NULL OR id != p_exclude_booking_id)
    AND (
        (start_time <= p_start_time AND end_time > p_start_time) OR
        (start_time < p_end_time AND end_time >= p_end_time) OR
        (start_time >= p_start_time AND end_time <= p_end_time)
    );
    
    RETURN v_conflict_count > 0;
END //

DELIMITER ;
