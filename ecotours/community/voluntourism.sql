-- ============================================
-- Voluntourism Page Database Schema
-- Volunteer & Community Engagement Page
-- ============================================

-- Drop tables if they exist
DROP TABLE IF EXISTS `voluntourism_faq`;
DROP TABLE IF EXISTS `voluntourism_table_rows`;
DROP TABLE IF EXISTS `voluntourism_programs`;
DROP TABLE IF EXISTS `voluntourism_activities`;
DROP TABLE IF EXISTS `voluntourism_highlights`;
DROP TABLE IF EXISTS `voluntourism_how_it_works`;
DROP TABLE IF EXISTS `voluntourism_hero`;

-- Table 1: Hero & Introduction Content
CREATE TABLE `voluntourism_hero` (
  `id` INT(11) PRIMARY KEY AUTO_INCREMENT,
  `hero_title` VARCHAR(255) NOT NULL,
  `hero_subtitle` VARCHAR(255) NOT NULL,
  `hero_description` TEXT NOT NULL,
  `hero_image` VARCHAR(255) DEFAULT NULL,
  `intro_title` VARCHAR(255) NOT NULL,
  `intro_description` TEXT NOT NULL,
  `intro_image` VARCHAR(255) DEFAULT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table 2: Introduction Highlights (4 cards)
CREATE TABLE `voluntourism_highlights` (
  `id` INT(11) PRIMARY KEY AUTO_INCREMENT,
  `highlight_icon` VARCHAR(100) NOT NULL,
  `highlight_title` VARCHAR(255) NOT NULL,
  `highlight_description` TEXT NOT NULL,
  `display_order` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table 3: Voluntourism Activities (5 main activities)
CREATE TABLE `voluntourism_activities` (
  `id` INT(11) PRIMARY KEY AUTO_INCREMENT,
  `activity_title` VARCHAR(255) NOT NULL,
  `activity_icon` VARCHAR(100) NOT NULL,
  `activity_description` TEXT NOT NULL,
  `activity_image` VARCHAR(255) DEFAULT NULL,
  `display_order` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table 4: Volunteer Programs (4 main programs)
CREATE TABLE `voluntourism_programs` (
  `id` INT(11) PRIMARY KEY AUTO_INCREMENT,
  `program_title` VARCHAR(255) NOT NULL,
  `program_category` VARCHAR(100) NOT NULL,
  `program_description` TEXT NOT NULL,
  `program_image` VARCHAR(255) DEFAULT NULL,
  `duration` VARCHAR(100) NOT NULL,
  `skills_required` VARCHAR(255) NOT NULL,
  `display_order` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table 5: Activities Table Rows (comparison table)
CREATE TABLE `voluntourism_table_rows` (
  `id` INT(11) PRIMARY KEY AUTO_INCREMENT,
  `category_name` VARCHAR(255) NOT NULL,
  `category_icon` VARCHAR(100) NOT NULL,
  `visitor_involvement` TEXT NOT NULL,
  `visitor_benefits` TEXT NOT NULL,
  `community_benefits` TEXT NOT NULL,
  `display_order` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table 6: How It Works Section
CREATE TABLE `voluntourism_how_it_works` (
  `id` INT(11) PRIMARY KEY AUTO_INCREMENT,
  `section_title` VARCHAR(255) NOT NULL,
  `section_description` TEXT NOT NULL,
  `process_image` VARCHAR(255) DEFAULT NULL,
  `overlay_title` VARCHAR(255) NOT NULL,
  `overlay_description` TEXT NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table 7: How It Works Features (4 feature items)
CREATE TABLE `voluntourism_how_it_works_features` (
  `id` INT(11) PRIMARY KEY AUTO_INCREMENT,
  `feature_icon` VARCHAR(100) NOT NULL,
  `feature_title` VARCHAR(255) NOT NULL,
  `feature_description` TEXT NOT NULL,
  `display_order` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table 8: FAQ Items
CREATE TABLE `voluntourism_faq` (
  `id` INT(11) PRIMARY KEY AUTO_INCREMENT,
  `question` TEXT NOT NULL,
  `answer` TEXT NOT NULL,
  `display_order` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- INSERT STATEMENTS
-- ============================================

-- Insert Hero & Introduction Content
INSERT INTO `voluntourism_hero` (`hero_title`, `hero_subtitle`, `hero_description`, `hero_image`, `intro_title`, `intro_description`, `intro_image`) VALUES
('Voluntourism & Community Engagement',
 'Transformative Travel Through Meaningful Service',
 'Experience community-based tourism while contributing your skills to meaningful projects. Our voluntourism programs ensure communities lead decisions and receive direct benefits while creating authentic, transformative experiences for travelers.',
 '../images/voluntourism/hero.jpg',
 'Voluntourism at Virunga Ecotours',
 'Voluntourism at Virunga Ecotours is a distinctive form of community-based tourism that merges travel with service, offering visitors the opportunity to contribute meaningfully to local development while exploring the natural and cultural treasures of the Virunga Massif. It is not limited to observation; rather, it emphasizes active participation, cultural exchange, and mutual learning, creating deeper connections between travelers and host communities. By doing so, voluntourism transforms tourism into a shared journey of discovery, responsibility, and empowerment.',
 '../images/voluntourism/HO2A3457.jpg');

-- Insert Introduction Highlights
INSERT INTO `voluntourism_highlights` (`highlight_icon`, `highlight_title`, `highlight_description`, `display_order`) VALUES
('fas fa-hands-helping', 'Active Participation', 'Meaningful contribution to local development projects', 1),
('fas fa-exchange-alt', 'Cultural Exchange', 'Mutual learning between visitors and communities', 2),
('fas fa-heart', 'Deeper Connections', 'Authentic relationships with host communities', 3),
('fas fa-seedling', 'Shared Empowerment', 'Journey of discovery, responsibility, and growth', 4);

-- Insert Voluntourism Activities
INSERT INTO `voluntourism_activities` (`activity_title`, `activity_icon`, `activity_description`, `activity_image`, `display_order`) VALUES
('Educational Support',
 'fas fa-graduation-cap',
 'Assisting in local schools with conversational English, storytelling, or classroom engagement. These exchanges strengthen student confidence and broaden horizons.',
 '../images/voluntourism/ed.jpg',
 1),
('Women\'s and Single Mothers\' Cooperatives',
 'fas fa-female',
 'Working alongside women in weaving, handicrafts, or traditional cooking while sharing mentorship, encouragement, or business insights.',
 'assets/images/womens-cooperatives.jpg',
 2),
('Environmental Engagement',
 'fas fa-tree',
 'Participating in tree planting, waste clean-up drives, or small agricultural projects that enhance food security and sustainability.',
 'assets/images/environmental-projects.jpg',
 3),
('Youth Empowerment and Sports',
 'fas fa-futbol',
 'Engaging young people through football, music, or storytelling sessions, fostering confidence and cultural exchange.',
 '../images/voluntourism/spo.jpg',
 4),
('Health and Well-Being',
 'fas fa-heartbeat',
 'Supporting local awareness programs on nutrition, hygiene, or wellness, often in partnership with community facilitators.',
 'assets/images/health-wellness.jpg',
 5);

-- Insert Volunteer Programs
INSERT INTO `voluntourism_programs` (`program_title`, `program_category`, `program_description`, `program_image`, `duration`, `skills_required`, `display_order`) VALUES
('Education & Youth Development',
 'Education',
 'Support local schools and youth programs while learning about traditional education methods and community knowledge systems. Youth are trained as guides, performers, and hospitality staff for skill transfer and employment.',
 '../images/voluntourism/ed2.jpg',
 '2-12 weeks',
 'Teaching, mentoring',
 1),
('Conservation & Environmental Restoration',
 'Conservation',
 'Participate in measurable conservation actions while learning about traditional ecological knowledge. Tourism supports biodiversity through funding conservation initiatives and creating alternatives to environmentally harmful activities.',
 '../images/voluntourism/ed3.jpg',
 '1-8 weeks',
 'Environmental science, research',
 2),
('Healthcare & Community Wellness',
 'Healthcare',
 'Support community health initiatives while learning about traditional medicine. CBT improves public health as tourism revenue funds clean water, clinics, and nutrition initiatives while supporting health education.',
 'assets/images/healthcare-volunteer.jpg',
 '2-16 weeks',
 'Healthcare, public health',
 3),
('Economic Development & Entrepreneurship',
 'Economic Development',
 'Support local entrepreneurship and economic empowerment. CBT supports local entrepreneurship by integrating artisans, farmers, and service providers into tourism supply chains, ensuring sustainable income.',
 'assets/images/economic-volunteer.jpg',
 '3-12 weeks',
 'Business, finance, marketing',
 4);

-- Insert Activities Table Rows
INSERT INTO `voluntourism_table_rows` (`category_name`, `category_icon`, `visitor_involvement`, `visitor_benefits`, `community_benefits`, `display_order`) VALUES
('Education Support',
 'fas fa-graduation-cap',
 'Teaching English, reading, or helping in classrooms',
 'Meaningful engagement, cultural exchange',
 'Improved learning outcomes, student motivation',
 1),
('Women & Single Mothers\' Groups',
 'fas fa-female',
 'Weaving, handicraft production, cooking, mentoring',
 'Learning traditional skills, cross-cultural exchange',
 'Income generation, empowerment, market skills',
 2),
('Environmental Initiatives',
 'fas fa-tree',
 'Tree planting, clean-up drives, community farming',
 'Practical involvement in sustainability',
 'Stronger ecosystems, food security, climate resilience',
 3),
('Youth Empowerment',
 'fas fa-futbol',
 'Sports coaching, music, storytelling',
 'Connection with youth, experiential learning',
 'Confidence building, inspiration, strengthened youth identity',
 4),
('Health & Well-Being',
 'fas fa-heartbeat',
 'Awareness sessions on hygiene, nutrition, or wellness',
 'Insight into rural health realities, contribution to well-being',
 'Community health awareness, improved daily practices',
 5);

-- Insert How It Works Section
INSERT INTO `voluntourism_how_it_works` (`section_title`, `section_description`, `process_image`, `overlay_title`, `overlay_description`) VALUES
('How It Works',
 'Virunga Ecotours designs voluntourism activities as flexible, short-term opportunities that complement wildlife or cultural excursions. These may take the form of half-day or full-day engagements, tailored to the visitor\'s time, skills, and interests. The model ensures that community participation is well-organized, respectful, and impactful. Guests contribute to tangible outcomes while simultaneously learning about local traditions, challenges, and aspirations.',
 '../images/voluntourism/HO2A3360.jpg',
 'Tailored Experiences',
 'Every activity is designed to match your interests and skills');

-- Insert How It Works Features
INSERT INTO `voluntourism_how_it_works_features` (`feature_icon`, `feature_title`, `feature_description`, `display_order`) VALUES
('fas fa-clock', 'Flexible Duration', 'Half-day to full-day engagements that fit your schedule', 1),
('fas fa-user-cog', 'Tailored Activities', 'Customized based on your time, skills, and interests', 2),
('fas fa-handshake', 'Well-Organized', 'Respectful and impactful community participation', 3),
('fas fa-target', 'Tangible Outcomes', 'Meaningful contributions with measurable impact', 4);

-- Insert FAQ Items
INSERT INTO `voluntourism_faq` (`question`, `answer`, `display_order`) VALUES
('How should volunteers prepare for CBT experiences?',
 'Research local customs, learn basic language phrases, pack appropriately, and approach with openness and respect. Complete our pre-departure training and cultural orientation programs.',
 1),
('What should volunteers expect from homestays?',
 'Simple accommodations, authentic meals, family interaction, and opportunities to participate in daily activities. Homestays offer genuine cultural immersion and learning opportunities.',
 2),
('How can volunteers support local economies beyond tourism?',
 'Purchase local crafts, support community projects, maintain connections through fair trade partnerships, and continue supporting communities after your volunteer period ends.',
 3),
('What role does storytelling play in CBT?',
 'Storytelling preserves oral traditions, educates volunteers, and creates emotional connections between cultures. It\'s a key component of authentic cultural exchange.',
 4),
('How does regenerative tourism influence volunteer behavior beyond the trip?',
 'Through immersive, respectful experiences, volunteers develop environmental awareness and cultural appreciation that persist long-term, fostering environmental responsibility and conscious consumption.',
 5),
('What makes travel truly transformative?',
 'Deep cultural exchange, meaningful participation, personal reflection, and lasting connections that inspire positive change in both volunteers and host communities.',
 6);