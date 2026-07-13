CREATE TABLE `home_hero` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert hero carousel slides data
INSERT INTO `home_hero` (`title`, `description`, `image_url`) VALUES
('Pedal toward new horizons!', 'Discover the thrill of cycling through breathtaking landscapes and scenic trails across Africa\'s most beautiful communities.', '../../images/hero/cover6.JPG'),
('Land of a Thousand Thrills: Adventures Await!', 'Unforgettable encounters with mountain gorillas in their natural habitat', '../../images/hero/gorille.jpg'),
('Hearts and Hands in Harmony', 'Connect with local communities and experience authentic African traditions', '../../images/hero/cover5.JPG');
