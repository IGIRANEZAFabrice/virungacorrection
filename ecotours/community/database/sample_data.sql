-- Sample Data for Community Program Website
-- Insert sample data for testing and demonstration

USE `dmxewbmy_ecodatabase`;

-- Insert sample admin users
INSERT INTO `community_admins` (`username`, `password`, `email`, `full_name`, `role`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@virungaecotours.com', 'Community Admin', 'super_admin'),
('manager', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager@virungaecotours.com', 'Program Manager', 'admin');

-- Insert sample categories
INSERT INTO `community_categories` (`name`, `description`, `icon`, `color`) VALUES
('Education', 'Educational programs and initiatives', 'fas fa-graduation-cap', '#2a4858'),
('Health', 'Healthcare and wellness programs', 'fas fa-heartbeat', '#967259'),
('Conservation', 'Environmental conservation projects', 'fas fa-leaf', '#2a4858'),
('Economic Development', 'Community economic empowerment', 'fas fa-chart-line', '#8b7355'),
('Infrastructure', 'Community infrastructure development', 'fas fa-hammer', '#967259'),
('Women Empowerment', 'Programs focused on empowering women', 'fas fa-female', '#2a4858');

-- Insert sample programs
INSERT INTO `community_programs` (`title`, `description`, `short_description`, `country`, `category`, `location`, `image`, `date_started`, `status`, `impact_summary`, `beneficiaries`, `featured`, `slug`) VALUES
('Gorilla Conservation Education Program', 'A comprehensive education program aimed at teaching local communities about gorilla conservation, sustainable tourism, and environmental protection in the Virunga Massif region.', 'Educating communities about gorilla conservation and sustainable practices.', 'rwanda', 'Conservation', 'Volcanoes National Park, Rwanda', 'gorilla-education.jpg', '2023-01-15', 'active', 'Reached over 500 community members with conservation education, resulting in 30% reduction in human-wildlife conflicts.', 500, 1, 'gorilla-conservation-education'),

('Community Health Clinic Initiative', 'Establishing and supporting community health clinics in remote areas around the Virunga region, providing essential healthcare services and training local health workers.', 'Providing essential healthcare services to remote communities.', 'congo', 'Health', 'Goma, North Kivu, DRC', 'health-clinic.jpg', '2022-06-01', 'active', 'Established 3 health clinics serving 2,000+ people, trained 15 community health workers.', 2000, 1, 'community-health-clinic'),

('Women\'s Cooperative Development', 'Supporting women\'s cooperatives in handicraft production, sustainable agriculture, and small business development to improve economic opportunities.', 'Empowering women through cooperative business development.', 'uganda', 'Women Empowerment', 'Kisoro District, Uganda', 'womens-coop.jpg', '2023-03-10', 'active', 'Supported 8 women\'s cooperatives, increased average income by 40%, trained 120 women in business skills.', 120, 1, 'womens-cooperative-development'),

('Clean Water Access Project', 'Installing clean water systems and training communities in water management and hygiene practices in rural areas around the Virunga region.', 'Providing clean water access and hygiene education.', 'rwanda', 'Infrastructure', 'Musanze District, Rwanda', 'clean-water.jpg', '2022-09-01', 'completed', 'Installed 12 water systems serving 1,500 people, reduced waterborne diseases by 60%.', 1500, 0, 'clean-water-access'),

('Sustainable Agriculture Training', 'Training farmers in sustainable agriculture practices, organic farming, and climate-resilient crops to improve food security and environmental sustainability.', 'Teaching sustainable farming practices for food security.', 'congo', 'Economic Development', 'Rutshuru Territory, DRC', 'sustainable-farming.jpg', '2023-05-20', 'active', 'Trained 200 farmers, increased crop yields by 35%, introduced 5 climate-resilient crop varieties.', 200, 0, 'sustainable-agriculture-training'),

('Youth Environmental Leadership', 'Developing young environmental leaders through training programs, conservation projects, and community engagement initiatives.', 'Developing the next generation of environmental leaders.', 'uganda', 'Education', 'Bwindi Region, Uganda', 'youth-leaders.jpg', '2023-02-01', 'active', 'Trained 80 youth leaders, implemented 15 community conservation projects.', 80, 0, 'youth-environmental-leadership');

-- Insert sample testimonials
INSERT INTO `community_testimonials` (`name`, `role`, `organization`, `message`, `rating`, `location`, `program_id`, `featured`) VALUES
('Marie Uwimana', 'Community Leader', 'Musanze Women\'s Group', 'The gorilla conservation program has transformed our community\'s understanding of wildlife protection. Our children now see gorillas as treasures to protect, not threats to fear.', 5, 'Musanze, Rwanda', 1, 1),
('Dr. Jean Baptiste', 'Health Coordinator', 'North Kivu Health District', 'The community health clinic initiative has been a lifesaver for our remote communities. We\'ve seen dramatic improvements in maternal and child health outcomes.', 5, 'Goma, DRC', 2, 1),
('Grace Nakato', 'Cooperative Member', 'Kisoro Women\'s Handicraft Cooperative', 'Through the women\'s cooperative program, I\'ve been able to start my own business and send my children to school. This program has given us hope and dignity.', 5, 'Kisoro, Uganda', 3, 1),
('Emmanuel Nzeyimana', 'Farmer', 'Rutshuru Farmers Association', 'The sustainable agriculture training has doubled my harvest. I can now feed my family and sell surplus crops at the market. Thank you for teaching us these new methods.', 5, 'Rutshuru, DRC', 5, 0);

-- Insert sample team members
INSERT INTO `community_team` (`name`, `title`, `bio`, `email`, `facebook`, `twitter`, `linkedin`, `order_position`) VALUES
('Sarah Mukamana', 'Community Programs Director', 'Sarah leads our community development initiatives across the Virunga region. With over 10 years of experience in community development and conservation, she ensures our programs create lasting positive impact.', 'sarah@virungaecotours.com', 'https://facebook.com/sarah.mukamana', 'https://twitter.com/sarahmukamana', 'https://linkedin.com/in/sarah-mukamana', 1),

('Dr. James Mugisha', 'Health Programs Coordinator', 'Dr. Mugisha oversees all health-related community programs. As a qualified medical doctor with extensive experience in rural healthcare, he ensures our health initiatives meet the highest standards.', 'james@virungaecotours.com', 'https://facebook.com/james.mugisha', '', 'https://linkedin.com/in/james-mugisha', 2),

('Agnes Nyiramana', 'Women\'s Empowerment Specialist', 'Agnes focuses on women\'s empowerment and economic development programs. Her background in microfinance and cooperative development helps women in our communities achieve economic independence.', 'agnes@virungaecotours.com', 'https://facebook.com/agnes.nyiramana', 'https://twitter.com/agnesnyiramana', 'https://linkedin.com/in/agnes-nyiramana', 3),

('Peter Ssebunya', 'Conservation Education Officer', 'Peter develops and implements our conservation education programs. His passion for wildlife conservation and community engagement helps bridge the gap between conservation goals and community needs.', 'peter@virungaecotours.com', 'https://facebook.com/peter.ssebunya', '', 'https://linkedin.com/in/peter-ssebunya', 4);
