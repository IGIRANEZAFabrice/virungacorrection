-- Create table for styleguide cards (main cards shown in grid)
CREATE TABLE styleguide_cards (
    card_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    thumbnail_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create table for styleguide content (detailed content shown in styleguideopen)
CREATE TABLE styleguide_content (
    content_id INT PRIMARY KEY AUTO_INCREMENT,
    card_id INT NOT NULL,
    hero_image VARCHAR(255),
    intro_text TEXT,
    main_content TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (card_id) REFERENCES styleguide_cards(card_id)
);

-- Example insert for a card
INSERT INTO styleguide_cards (title, thumbnail_image) VALUES 
('Gorilla Trekking in Rwanda: A Complete Guide', 
'https://visitrwanda.com/wp-content/uploads/fly-images/1630/Visit-Rwanda-NH_OO_Lifestyle_Canopy_Walk_0657_MASTER-700x467.jpg');

-- Example insert for its content
INSERT INTO styleguide_content (card_id, hero_image, intro_text, main_content) VALUES 
(1, 
'https://visitrwanda.com/wp-content/uploads/fly-images/1630/Visit-Rwanda-NH_OO_Lifestyle_Canopy_Walk_0657_MASTER-700x467.jpg',
'Exploring Rwanda for family vacations enjoyable for young children, lively teenagers, or slightly older ones...',
'<h2>Welcome to the Adventure</h2><p>Rwanda, with its rich cultural heritage, stunning landscapes...</p>');