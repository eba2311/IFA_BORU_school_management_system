-- ============================================
-- IFA BORU AMURU SCHOOL MANAGEMENT SYSTEM
-- DATABASE SCHEMA
-- ============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS `ifa_boru_sms`;
USE `ifa_boru_sms`;

-- ============================================
-- 1. ADMINS TABLEhttp://localhost/ifa-boru-sms/
-- ============================================
CREATE TABLE IF NOT EXISTS `admins` (
    `admin_id` INT PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_username` (`username`),
    INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 2. SUBJECTS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `subjects` (
    `subject_id` INT PRIMARY KEY AUTO_INCREMENT,
    `subject_name` VARCHAR(100) NOT NULL UNIQUE,
    `subject_code` VARCHAR(20) NOT NULL UNIQUE,
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_subject_name` (`subject_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 3. TEACHERS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `teachers` (
    `teacher_id` INT PRIMARY KEY AUTO_INCREMENT,
    `teacher_code` VARCHAR(50) UNIQUE NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `phone` VARCHAR(20),
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `date_of_birth` DATE,
    `gender` ENUM('Male', 'Female', 'Other'),
    `address` TEXT,
    `hire_date` DATE,
    `status` ENUM('Active', 'Inactive') DEFAULT 'Active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_teacher_code` (`teacher_code`),
    INDEX `idx_username` (`username`),
    INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 4. GRADES TABLE (for Grade levels: 9, 10, 11, 12)
-- ============================================
CREATE TABLE IF NOT EXISTS `grades` (
    `grade_id` INT PRIMARY KEY AUTO_INCREMENT,
    `grade_level` INT NOT NULL UNIQUE,
    `grade_name` VARCHAR(50),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 5. SECTIONS TABLE (A, B, C, etc.)
-- ============================================
CREATE TABLE IF NOT EXISTS `sections` (
    `section_id` INT PRIMARY KEY AUTO_INCREMENT,
    `grade_id` INT NOT NULL,
    `section_name` VARCHAR(50) NOT NULL,
    `max_students` INT DEFAULT 50,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`grade_id`) REFERENCES `grades`(`grade_id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_grade_section` (`grade_id`, `section_name`),
    INDEX `idx_grade_id` (`grade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 6. CLASSES TABLE (Represents teacher + subject + section)
-- ============================================
CREATE TABLE IF NOT EXISTS `classes` (
    `class_id` INT PRIMARY KEY AUTO_INCREMENT,
    `teacher_id` INT NOT NULL,
    `subject_id` INT NOT NULL,
    `section_id` INT NOT NULL,
    `academic_year` VARCHAR(9) NOT NULL DEFAULT '2026',
    `semester` INT DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`teacher_id`) REFERENCES `teachers`(`teacher_id`) ON DELETE CASCADE,
    FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`subject_id`) ON DELETE CASCADE,
    FOREIGN KEY (`section_id`) REFERENCES `sections`(`section_id`) ON DELETE CASCADE,
    INDEX `idx_teacher_id` (`teacher_id`),
    INDEX `idx_subject_id` (`subject_id`),
    INDEX `idx_section_id` (`section_id`),
    INDEX `idx_academic_year` (`academic_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 7. STUDENTS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `students` (
    `student_id` INT PRIMARY KEY AUTO_INCREMENT,
    `student_code` VARCHAR(50) UNIQUE NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `date_of_birth` DATE NOT NULL,
    `gender` ENUM('Male', 'Female', 'Other'),
    `grade_id` INT NOT NULL,
    `section_id` INT NOT NULL,
    `parent_name` VARCHAR(100),
    `parent_phone` VARCHAR(20),
    `address` TEXT,
    `email` VARCHAR(100),
    `phone` VARCHAR(20),
    `photo` VARCHAR(255),
    `status` ENUM('Active', 'Inactive') DEFAULT 'Active',
    `enrolled_date` DATE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`grade_id`) REFERENCES `grades`(`grade_id`) ON DELETE CASCADE,
    FOREIGN KEY (`section_id`) REFERENCES `sections`(`section_id`) ON DELETE CASCADE,
    INDEX `idx_student_code` (`student_code`),
    INDEX `idx_full_name` (`full_name`),
    INDEX `idx_grade_id` (`grade_id`),
    INDEX `idx_section_id` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 8. GRADES (MARKS) TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `student_grades` (
    `grade_id` INT PRIMARY KEY AUTO_INCREMENT,
    `student_id` INT NOT NULL,
    `class_id` INT NOT NULL,
    `assignment_score` DECIMAL(5,2),
    `test_score` DECIMAL(5,2),
    `mid_exam_score` DECIMAL(5,2),
    `final_exam_score` DECIMAL(5,2),
    `total_score` DECIMAL(5,2),
    `grade_letter` VARCHAR(2),
    `academic_year` VARCHAR(9) NOT NULL DEFAULT '2026',
    `semester` INT DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`student_id`) ON DELETE CASCADE,
    FOREIGN KEY (`class_id`) REFERENCES `classes`(`class_id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_student_class_year` (`student_id`, `class_id`, `academic_year`, `semester`),
    INDEX `idx_student_id` (`student_id`),
    INDEX `idx_class_id` (`class_id`),
    INDEX `idx_academic_year` (`academic_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 9. AUDIT LOG TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `log_id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_type` ENUM('Admin', 'Teacher', 'Student') NOT NULL,
    `user_id` INT NOT NULL,
    `action` VARCHAR(255) NOT NULL,
    `table_name` VARCHAR(100),
    `record_id` INT,
    `old_value` JSON,
    `new_value` JSON,
    `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_user_type_id` (`user_type`, `user_id`),
    INDEX `idx_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 10. SYSTEM SETTINGS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `settings` (
    `setting_id` INT PRIMARY KEY AUTO_INCREMENT,
    `setting_key` VARCHAR(100) UNIQUE NOT NULL,
    `setting_value` TEXT,
    `description` TEXT,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 11. INSERT SAMPLE DATA
-- ============================================

-- Insert Grades (9-12) - Use INSERT IGNORE to avoid duplicates
INSERT IGNORE INTO `grades` (`grade_level`, `grade_name`) VALUES 
(9, 'Grade 9'),
(10, 'Grade 10'),
(11, 'Grade 11'),
(12, 'Grade 12');

-- Insert Subjects - Use INSERT IGNORE to avoid duplicates
INSERT IGNORE INTO `subjects` (`subject_name`, `subject_code`, `description`) VALUES 
('Mathematics', 'MATH', 'Mathematics'),
('English Language', 'ENG', 'English Language'),
('Physics', 'PHY', 'Physics'),
('Chemistry', 'CHEM', 'Chemistry'),
('Biology', 'BIO', 'Biology'),
('History', 'HIST', 'History'),
('Geography', 'GEO', 'Geography'),
('Amharic', 'AMH', 'Amharic Language');

-- Insert Sections for each grade - Use INSERT IGNORE to avoid duplicates
INSERT IGNORE INTO `sections` (`grade_id`, `section_name`) VALUES 
(1, 'A'), (1, 'B'), (1, 'C'),
(2, 'A'), (2, 'B'), (2, 'C'),
(3, 'A'), (3, 'B'),
(4, 'A'), (4, 'B');

-- Insert Default Admin (CHANGE THIS PASSWORD!) - Use INSERT IGNORE to avoid duplicates
INSERT IGNORE INTO `admins` (`username`, `email`, `password`, `full_name`, `phone`) VALUES 
('admin', 'admin@ifaboru.edu.et', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', '+251911223344');

-- Insert System Settings - Use INSERT IGNORE to avoid duplicates
INSERT IGNORE INTO `settings` (`setting_key`, `setting_value`, `description`) VALUES 
('school_name', 'IFA BORU AMURU Secondary School', 'Name of the school'),
('academic_year', '2026', 'Current academic year'),
('current_semester', '1', 'Current semester (1 or 2)'),
('grading_scale', 'A=90-100, B=80-89, C=70-79, D=60-69, F=0-59', 'Grading scale'),
('max_assignment', '10', 'Maximum assignment score'),
('max_test', '10', 'Maximum test score'),
('max_midterm', '20', 'Maximum mid-term exam score'),
('max_final', '60', 'Maximum final exam score');

-- ============================================
-- END OF SCHEMA
-- ============================================
