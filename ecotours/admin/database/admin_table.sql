CREATE TABLE IF NOT EXISTS `admins` (
  `admin_id` INT PRIMARY KEY AUTO_INCREMENT,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL, -- For storing hashed passwords
  `phone` VARCHAR(20),
  `profile_image` VARCHAR(255) DEFAULT 'costa-rica.jpg',
  `last_login` DATETIME,
  `login_attempts` INT DEFAULT 0,
  `account_status` ENUM('active', 'suspended', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create table for admin activity logs
CREATE TABLE IF NOT EXISTS `admin_activity_logs` (
  `log_id` INT PRIMARY KEY AUTO_INCREMENT,
  `admin_id` INT NOT NULL,
  `action` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `ip_address` VARCHAR(45),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`admin_id`) REFERENCES `admins`(`admin_id`) ON DELETE CASCADE,
  INDEX `activity_admin_index` (`admin_id`),
  INDEX `activity_date_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
