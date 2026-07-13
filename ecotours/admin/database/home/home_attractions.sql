CREATE TABLE `home_attractions` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `home_attractions` (`title`, `image_url`) VALUES 
('Mount Karisimbi', '../../images/home/attractions/cover6.JPG'),
('Bisoke Volcano', '../../images/home/attractions/gorille.jpg'),
('Mount Nyiragongo', '../../images/home/attractions/cover5.JPG'),
('Mount Sabyinyo', '../../images/home/attractions/cover6.JPG'),
('Nyungwe Forest', '../../images/home/attractions/cover6.JPG'),
('Gishwati-Mukura', '../../images/home/attractions/gorille.jpg'),
('Akagera National Park', '../../images/home/attractions/cover5.JPG'),
('Lake Kivu', '../../images/home/attractions/cover6.JPG');