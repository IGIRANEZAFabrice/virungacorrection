-- Table structure for storing about section data
CREATE TABLE IF NOT EXISTS home_about (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slide_description TEXT NOT NULL,
    youtube_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data
INSERT INTO home_about (slide_title, slide_description, youtube_url) 
VALUES (
    'Pedal toward new horizons!', 
    'Discover the thrill of cycling through breathtaking landscapes and scenic trails across Africa\'s most beautiful communities.',
    'https://www.youtube.com/embed/xaaYgVRZTnE'
);