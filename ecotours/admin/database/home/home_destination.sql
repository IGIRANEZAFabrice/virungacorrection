CREATE TABLE `home_destinations` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `country` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert destinations data
INSERT INTO `home_destinations` (`country`, `description`, `image_url`) VALUES
('Rwanda', 'Discover the thrill of cycling through breathtaking landscapes and scenic trails across Africa\'s most beautiful communities.', '../../images/home/destinations/cover6.JPG'),
('Congo', 'Unforgettable encounters with mountain gorillas in their natural habitat', '../../images/home/destinations/gorille.jpg'),
('Uganda', 'Connect with local communities and experience authentic African traditions', '../../images/home/destinations/cover5.JPG');
