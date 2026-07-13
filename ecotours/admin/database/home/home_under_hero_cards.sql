CREATE TABLE `home_under_hero_cards` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert under hero cards data
INSERT INTO `home_under_hero_cards` (`title`, `description`, `image_url`) VALUES
('Pedal toward new horizons!', 'Discover the thrill of cycling through breathtaking landscapes and scenic trails across Africa\'s most beautiful communities.', '../../images/underHeroCards/cover6.JPG'),
('Land of a Thousand Thrills: Adventures Await!', 'Unforgettable encounters with mountain gorillas in their natural habitat', '../../images/underHeroCards/gorille.jpg');

