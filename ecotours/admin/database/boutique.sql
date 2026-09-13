-- =======================================================================
-- VIRUNGA COLLECTIVE & BOUTIQUE JOURNEYS - SQL DUMP
-- =======================================================================
-- Safe, non-destructive import script (NO DROP TABLE statements)
-- Target Database: `virungaecotoursdb`
-- Compatible with MySQL 5.7+, MySQL 8.0+, MariaDB 10.4+, and phpMyAdmin.
-- =======================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- -----------------------------------------------------------------------
-- Safe Table Structure Definitions (Only created if they do not exist)
-- -----------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `tours` (
  `tour_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `days_count` int(11) NOT NULL,
  `cover_image_path` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `why_attend` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tour_days` (
  `day_id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `day_number` int(11) NOT NULL,
  `day_title` varchar(255) NOT NULL,
  `day_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`day_id`),
  KEY `tour_id` (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tour_highlights` (
  `highlight_id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `display_order` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`highlight_id`),
  KEY `tour_id` (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tour_included` (
  `included_id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `item_description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`included_id`),
  KEY `tour_id` (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tour_excluded` (
  `excluded_id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `item_description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`excluded_id`),
  KEY `tour_id` (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tour_to_bring` (
  `to_bring_id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `item_description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`to_bring_id`),
  KEY `tour_id` (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pricing_tiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `group_size` varchar(100) NOT NULL,
  `price_per_person` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pricing_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `note` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =======================================================================
-- DEMOCRATIC REPUBLIC OF CONGO - SIGNATURE JOURNEYS & PRIVATE EXPERIENCES
-- =======================================================================

-- -----------------------------------------------------------------------
-- TOUR 01: 01 — THE CONGO VIRUNGA IMMERSION (5 Days / 4 Nights)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '01 — THE CONGO VIRUNGA IMMERSION',
  'Signature Journeys',
  'congo',
  5,
  'images/tours/congo_virunga_immersion.jpg',
  'An immersive journey through Goma and the Virunga region, bringing together volcanic landscapes, Lake Kivu, conservation, culture and the possibility of encountering mountain gorillas.',
  'Experience a rare and profound journey through Goma and the Virunga landscape. Shaped by volcanic terrain, Lake Kivu, community stories, and world-renowned conservation efforts, this 5-day private journey offers an unparalleled connection to eastern Congo.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — ARRIVAL IN GOMA (Goma & Lake Kivu)', 'Arrive in Goma and settle into the city’s distinctive volcanic setting. The afternoon unfolds beside Lake Kivu, with time to take in the landscape and begin discovering the character of eastern Congo. Overnight: Goma.'),
(@tour_id, 2, 'DAY 2 — THE GOMA STORY (City, Culture & Volcanoes)', 'Move beyond the usual view of Goma through a private encounter with its people, creative life and volcanic surroundings. Explore the city’s character and the ways life has developed alongside the volcanoes and Lake Kivu. Overnight: Goma.'),
(@tour_id, 3, 'DAY 3 — INTO THE VIRUNGA LANDSCAPE (Volcanic Country & Conservation)', 'Leave the city behind and enter the wider Virunga landscape. Walk through volcanic terrain and natural surroundings while learning about the ecosystems, wildlife and conservation efforts that define this extraordinary region. Overnight: Goma / Virunga region.'),
(@tour_id, 4, 'DAY 4 — MOUNTAIN GORILLAS (Into the Forest)', 'When gorilla trekking is operating and permits are available, enter the forest for a guided mountain gorilla encounter. The experience extends beyond the sighting itself, revealing the forest habitat and the conservation work that makes the encounter possible. Overnight: Goma.'),
(@tour_id, 5, 'DAY 5 — LAKE KIVU & DEPARTURE (A Final Morning by the Lake)', 'Spend the final morning beside Lake Kivu, with time for a relaxed lakeside experience before continuing with onward travel. Journey concludes.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/congo_immersion_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/congo_immersion_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/congo_immersion_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/congo_immersion_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, '4 nights accommodation'),
(@tour_id, 'Private transportation'),
(@tour_id, 'Local guiding'),
(@tour_id, 'Relevant park access and permits'),
(@tour_id, 'Gorilla permit when operational and included in the confirmed journey'),
(@tour_id, 'Lake Kivu experience'),
(@tour_id, 'Selected cultural and conservation experiences'),
(@tour_id, 'Selected meals');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'International flights'),
(@tour_id, 'DRC visa'),
(@tour_id, 'Travel insurance'),
(@tour_id, 'Personal expenses'),
(@tour_id, 'Tips and gratuities'),
(@tour_id, 'Optional experiences'),
(@tour_id, 'Any permits or services not specifically listed under Included');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Valid Passport & DRC Visa'),
(@tour_id, 'Yellow Fever Vaccination Certificate'),
(@tour_id, 'Hiking boots with good grip'),
(@tour_id, 'Rain jacket & warm layers'),
(@tour_id, 'Camera & extra batteries'),
(@tour_id, 'Insect repellent & sunscreen');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: On request'),
(@tour_id, 'CTA: PLAN YOUR CONGO'),
(@tour_id, 'TRAVEL NOTE: Journeys in eastern Congo are subject to current security conditions, park operations, access requirements and permit availability. Every experience is confirmed individually before travel.');


-- -----------------------------------------------------------------------
-- TOUR 02: 02 — THE NYIRAGONGO JOURNEY (2 Days / 1 Night)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '02 — THE NYIRAGONGO JOURNEY',
  'Signature Journeys',
  'congo',
  2,
  'images/tours/nyiragongo_journey.jpg',
  'A focused ascent of Mount Nyiragongo, moving from Goma through stark volcanic terrain toward one of the Virunga massif’s most dramatic summits.',
  'Climb one of Africa''s most active and iconic stratovolcanoes. Settle into mountain cabins at the rim overlooking dramatic volcanic geology and wake to dawn breaking across the vast Virunga chain.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — THE ASCENT (Goma → Nyiragongo)', 'Travel from Goma toward the trailhead and begin the climb. The route rises through changing volcanic terrain, gradually leaving the city behind as the mountain takes over the landscape. Reach the summit and settle into mountain accommodation, surrounded by the high volcanic landscape. Overnight: Nyiragongo summit.'),
(@tour_id, 2, 'DAY 2 — SUMMIT MORNING & DESCENT (The Mountain Revealed)', 'Begin the morning above the Virunga landscape before starting the descent. Return through the volcanic terrain toward Goma for onward travel. Journey concludes.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/nyiragongo_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/nyiragongo_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/nyiragongo_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/nyiragongo_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, '1 night mountain accommodation'),
(@tour_id, 'Private transportation'),
(@tour_id, 'Local guiding'),
(@tour_id, 'Volcano permit when operational'),
(@tour_id, 'Relevant park access'),
(@tour_id, 'Selected meals');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'International flights'),
(@tour_id, 'DRC visa'),
(@tour_id, 'Travel insurance'),
(@tour_id, 'Porter fees'),
(@tour_id, 'Personal expenses'),
(@tour_id, 'Tips and gratuities'),
(@tour_id, 'Optional services');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Thermal layers & warm winter clothing for summit overnight'),
(@tour_id, 'Sturdy hiking boots & gaiters'),
(@tour_id, 'Waterproof jacket & rain poncho'),
(@tour_id, 'Headlamp or flashlight with extra batteries'),
(@tour_id, 'Personal sleeping bag liner (optional)'),
(@tour_id, 'High-energy snacks and refillable water bottles');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: On request'),
(@tour_id, 'CTA: PLAN YOUR CONGO'),
(@tour_id, 'TRAVEL NOTE: Mount Nyiragongo ascents are subject to park operational status and security clearance. Confirmed individually.');


-- -----------------------------------------------------------------------
-- TOUR 03: 03 — THE KAHUZI-BIEGA FOREST JOURNEY (3 Days / 2 Nights)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '03 — THE KAHUZI-BIEGA FOREST JOURNEY',
  'Signature Journeys',
  'congo',
  3,
  'images/tours/kahuzi_biega.jpg',
  'A forest-led journey through Bukavu and Kahuzi-Biega, centred on rainforest, mountain landscapes, conservation and the remarkable Grauer’s gorilla.',
  'Kahuzi-Biega National Park is the last stronghold of the magnificent Grauer''s (Eastern Lowland) gorilla. Experience dense primary rainforest, mountain ecosystems, and intimate conservation stories based from beautiful Bukavu on Lake Kivu.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — ARRIVAL IN BUKAVU (Bukavu & Lake Kivu)', 'Arrive in Bukavu and settle beside Lake Kivu. Spend the afternoon exploring the city’s hills and lakeside surroundings, beginning to understand the distinctive character of this part of Congo. Overnight: Bukavu.'),
(@tour_id, 2, 'DAY 2 — INTO KAHUZI-BIEGA (Rainforest & Mountain Country)', 'Travel from Bukavu toward Kahuzi-Biega and enter the forest with local guides. Follow the changing terrain through rainforest and mountain habitat, with time to explore the biodiversity and conservation story of this remarkable ecosystem. Overnight: Bukavu / Kahuzi-Biega region.'),
(@tour_id, 3, 'DAY 3 — GRAUER’S GORILLAS & DEPARTURE (A Forest Encounter)', 'When gorilla trekking is operating and permits are available, enter the forest for a guided encounter with Grauer’s gorillas. Return toward Bukavu before continuing with onward travel. Journey concludes.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/kahuzi_biega_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/kahuzi_biega_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/kahuzi_biega_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/kahuzi_biega_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, '2 nights accommodation'),
(@tour_id, 'Private transportation'),
(@tour_id, 'Local guiding'),
(@tour_id, 'Park access'),
(@tour_id, 'Gorilla permit when operational and included in the confirmed journey'),
(@tour_id, 'Forest and conservation experiences'),
(@tour_id, 'Selected meals');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'International flights'),
(@tour_id, 'DRC visa'),
(@tour_id, 'Travel insurance'),
(@tour_id, 'Personal expenses'),
(@tour_id, 'Tips and gratuities'),
(@tour_id, 'Optional experiences'),
(@tour_id, 'Any permits or services not specifically listed under Included');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Valid Passport & DRC Visa'),
(@tour_id, 'Sturdy walking shoes/hiking boots'),
(@tour_id, 'Long trousers and long-sleeved shirts for forest walking'),
(@tour_id, 'Gardening gloves (for grip on branches)'),
(@tour_id, 'Rain jacket & waterproof bag for electronics');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: On request'),
(@tour_id, 'CTA: PLAN YOUR CONGO'),
(@tour_id, 'TRAVEL NOTE: Kahuzi-Biega activities and Grauer gorilla permits are confirmed individually subject to park operational status and visitor regulations.');


-- -----------------------------------------------------------------------
-- TOUR 04: 04 — THE LAKE KIVU & BUKAVU JOURNEY (3 Days / 2 Nights)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '04 — THE LAKE KIVU & BUKAVU JOURNEY',
  'Signature Journeys',
  'congo',
  3,
  'images/tours/lake_kivu_bukavu.jpg',
  'A slower journey through Bukavu and the shores of Lake Kivu, shaped by lake country, local life, food, coffee and the cultural character of eastern Congo.',
  'Uncover the gentle, cultural side of Lake Kivu. Savor volcanic specialty coffee, cruise scenic bays, meet local communities and artisans, and soak in Bukavu''s historic peninsula architecture.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — ARRIVAL IN BUKAVU (City & Lake)', 'Arrive in Bukavu and settle into the city. Spend the afternoon among its hills and lakeside surroundings, beginning a journey shaped by the meeting of city, water and landscape. Overnight: Bukavu.'),
(@tour_id, 2, 'DAY 2 — LAKE KIVU & LOCAL LIFE (Along the Shore)', 'Follow the shores of Lake Kivu through changing scenery and communities. The day can unfold through time on the water, a lakeside meal, local encounters or simply a slower immersion in the rhythm of lake life. Overnight: Bukavu.'),
(@tour_id, 3, 'DAY 3 — COFFEE, CULTURE & DEPARTURE (The Taste of Kivu)', 'Begin with a coffee or culinary experience connected to the region. Continue through Bukavu before departing for your onward journey. Journey concludes.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/lake_kivu_bukavu_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/lake_kivu_bukavu_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/lake_kivu_bukavu_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/lake_kivu_bukavu_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, '2 nights accommodation'),
(@tour_id, 'Private transportation'),
(@tour_id, 'Local guiding'),
(@tour_id, 'Lake Kivu experience'),
(@tour_id, 'Selected cultural experiences'),
(@tour_id, 'Coffee or culinary experience'),
(@tour_id, 'Selected meals');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'International flights'),
(@tour_id, 'DRC visa'),
(@tour_id, 'Travel insurance'),
(@tour_id, 'Gorilla or other park permits'),
(@tour_id, 'Personal expenses'),
(@tour_id, 'Tips and gratuities'),
(@tour_id, 'Optional experiences');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Comfortable lightweight walking clothes'),
(@tour_id, 'Sun hat & sunglasses'),
(@tour_id, 'Swimwear (optional for lake relaxation)'),
(@tour_id, 'Camera'),
(@tour_id, 'Personal medications');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: On request'),
(@tour_id, 'CTA: PLAN YOUR CONGO');


-- -----------------------------------------------------------------------
-- TOUR 05: 05 — THE CONGO COMMUNITY EXPERIENCE (1 Day)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '05 — THE CONGO COMMUNITY EXPERIENCE',
  'Private Experiences',
  'congo',
  1,
  'images/tours/congo_community.jpg',
  'Go beyond the usual introduction to Congo through time spent with local hosts, makers and communities in Goma or Bukavu.',
  'Go beyond the surface and connect directly with local artisans, storytellers, musicians, and culinary hosts. A deeply personal and respectful cultural encounter.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — THE CONGO COMMUNITY EXPERIENCE (Culture, Craft & Connection)', 'Depending on location, the experience may unfold through food, craft, storytelling, music or everyday traditions — creating space for participation, conversation and a more personal connection with Congolese culture. Style: Private. Duration: Half Day / Full Day.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/congo_community_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/congo_community_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/congo_community_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/congo_community_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Private local guide and host'),
(@tour_id, 'Community and artisan visits'),
(@tour_id, 'Cultural activities, storytelling or music session'),
(@tour_id, 'Local refreshments / lunch depending on duration'),
(@tour_id, 'Community contribution fees');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Personal purchases & artisan souvenirs'),
(@tour_id, 'Tips and gratuities'),
(@tour_id, 'Transportation (available on request)');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Comfortable footwear'),
(@tour_id, 'Modest casual clothing'),
(@tour_id, 'Camera with permission for portraits'),
(@tour_id, 'Open mind and conversational spirit');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Duration: Half Day / Full Day'),
(@tour_id, 'Style: Private'),
(@tour_id, 'Price: On request'),
(@tour_id, 'CTA: EXPLORE THE EXPERIENCE');


-- -----------------------------------------------------------------------
-- TOUR 06: 06 — THE LAKES OF KIVU (1 Day)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '06 — THE LAKES OF KIVU',
  'Private Experiences',
  'congo',
  1,
  'images/tours/lakes_of_kivu.jpg',
  'A private exploration of Lake Kivu and the landscapes surrounding Bukavu, featuring shoreline trails, boat cruises, and lake life.',
  'Explore the tranquil waters and islands of Lake Kivu by private boat. Enjoy unhurried vistas, lakeside dining, photography, and the serene pulse of fishing communities.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — THE LAKES OF KIVU (Water, Shoreline & Island Life)', 'Travel along the shoreline or onto the water, taking in the changing scenery and the communities whose lives are connected to the lake. The experience can be shaped around a private boat outing, lakeside meal, local encounter, photography or an unhurried afternoon beside the water.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/lakes_of_kivu_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/lakes_of_kivu_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/lakes_of_kivu_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/lakes_of_kivu_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Private boat cruise on Lake Kivu'),
(@tour_id, 'Local guide & boat captain'),
(@tour_id, 'Lakeside refreshments or lunch'),
(@tour_id, 'Island / shoreline landing fees');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Personal expenses'),
(@tour_id, 'Alcoholic beverages'),
(@tour_id, 'Tips and gratuities');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Sunscreen & hat'),
(@tour_id, 'Light windbreaker / jacket for the boat'),
(@tour_id, 'Camera & binoculars');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Duration: Half Day / Full Day'),
(@tour_id, 'Style: Private'),
(@tour_id, 'Price: On request'),
(@tour_id, 'CTA: EXPLORE THE EXPERIENCE');


-- =======================================================================
-- UGANDA - SIGNATURE JOURNEYS & PRIVATE EXPERIENCES
-- =======================================================================

-- -----------------------------------------------------------------------
-- TOUR 07: 01 — THE UGANDA VIRUNGA IMMERSION (5 Days / 4 Nights)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '01 — THE UGANDA VIRUNGA IMMERSION',
  'Signature Journeys',
  'uganda',
  5,
  'images/tours/uganda_virunga_immersion.jpg',
  'The most complete introduction to the Ugandan side of the Virunga region, bringing together highland landscapes, Lake Mutanda, Batwa heritage, local life and a gorilla encounter in Bwindi.',
  'The definitive 5-day Ugandan journey combining volcanic lake vistas in Kisoro, ancestral Batwa forest wisdom, vibrant highland traditions, and an unforgettable mountain gorilla trek in Bwindi Impenetrable Forest.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — ARRIVAL IN KISORO', 'Arrive in Kisoro and settle into the rhythm of the highlands. The afternoon offers a gentle introduction to the volcanic landscape and surrounding communities. Overnight: Kisoro.'),
(@tour_id, 2, 'DAY 2 — LAKE MUTANDA & HIGHLAND LIFE', 'Journey towards Lake Mutanda, surrounded by the volcanic peaks of the Virunga. Experience the lake and its surrounding communities, with time to discover the landscape at an unhurried pace. Overnight: Kisoro.'),
(@tour_id, 3, 'DAY 3 — BATWA HERITAGE & BWINDI', 'Travel towards Bwindi, with a Batwa community experience offering insight into forest heritage, traditional knowledge and living cultural traditions. Continue into the Bwindi region for the evening. Overnight: Bwindi.'),
(@tour_id, 4, 'DAY 4 — GORILLA TREKKING IN BWINDI', 'An early departure into Bwindi for a guided gorilla trekking experience. The day unfolds within the forest, with time dedicated to the gorilla family once encountered. Return for a quiet evening surrounded by the forest landscape. Overnight: Bwindi.'),
(@tour_id, 5, 'DAY 5 — THE FINAL MORNING & DEPARTURE', 'A final morning in the region before beginning your onward journey. Journey concludes.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/uganda_immersion_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/uganda_immersion_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/uganda_immersion_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/uganda_immersion_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, '4 nights accommodation'),
(@tour_id, 'Private transportation throughout'),
(@tour_id, 'Local guiding'),
(@tour_id, 'Gorilla trekking permit'),
(@tour_id, 'Batwa heritage experience'),
(@tour_id, 'Lake Mutanda experience'),
(@tour_id, 'Selected meals'),
(@tour_id, 'Selected cultural experiences');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'International flights'),
(@tour_id, 'Visa fees'),
(@tour_id, 'Travel insurance'),
(@tour_id, 'Personal expenses'),
(@tour_id, 'Optional experiences not specified above');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Valid Passport & East Africa Tourist Visa / Uganda Visa'),
(@tour_id, 'Sturdy hiking boots with ankle support'),
(@tour_id, 'Waterproof rain jacket & gaiters'),
(@tour_id, 'Long lightweight hiking trousers & socks'),
(@tour_id, 'Gardening/outdoor gloves for forest trekking'),
(@tour_id, 'Insect repellent, sun hat & sunscreen');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: On request'),
(@tour_id, 'CTA: PLAN YOUR VIRUNGA');


-- -----------------------------------------------------------------------
-- TOUR 08: 02 — THE KISORO HIGHLANDS (3 Days / 2 Nights)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '02 — THE KISORO HIGHLANDS',
  'Signature Journeys',
  'uganda',
  3,
  'images/tours/kisoro_highlands.jpg',
  'A slower exploration of Kisoro, created for travellers drawn to volcanic landscapes, local life, food and the quieter character of the highlands.',
  'Escape the rush and immerse yourself in the tranquil volcanic scenery of southwestern Uganda. Canoeing on Lake Mutanda, single-origin coffee tastings, and authentic cultural moments.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — ARRIVAL & THE VOLCANIC HIGHLANDS', 'Arrive in Kisoro and settle into your accommodation. Spend the afternoon discovering the surrounding highlands and gaining a first sense of the communities that shape this landscape. Overnight: Kisoro.'),
(@tour_id, 2, 'DAY 2 — LAKE MUTANDA & LOCAL LIFE', 'Journey to Lake Mutanda for a private lakeside experience. Discover the landscape and communities surrounding the lake, with time for a relaxed exploration of everyday life. Overnight: Kisoro.'),
(@tour_id, 3, 'DAY 3 — COFFEE, CULTURE & DEPARTURE', 'Begin with a local coffee experience before spending time discovering more of Kisoro’s cultural character. Continue your onward journey. Journey concludes.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/kisoro_highlands_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/kisoro_highlands_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/kisoro_highlands_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/kisoro_highlands_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, '2 nights accommodation'),
(@tour_id, 'Private transportation'),
(@tour_id, 'Local guiding'),
(@tour_id, 'Lake Mutanda experience'),
(@tour_id, 'Coffee experience'),
(@tour_id, 'Selected meals'),
(@tour_id, 'Selected cultural experiences');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Gorilla or wildlife permits'),
(@tour_id, 'International flights'),
(@tour_id, 'Visa fees'),
(@tour_id, 'Travel insurance'),
(@tour_id, 'Personal expenses');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Comfortable walking shoes & warm layer for cool highland evenings'),
(@tour_id, 'Sun protection (hat, glasses, lotion)'),
(@tour_id, 'Camera'),
(@tour_id, 'Daypack for daily outings');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: On request'),
(@tour_id, 'CTA: PLAN YOUR VIRUNGA');


-- -----------------------------------------------------------------------
-- TOUR 09: 03 — THE BWINDI FOREST JOURNEY (3 Days / 2 Nights)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '03 — THE BWINDI FOREST JOURNEY',
  'Signature Journeys',
  'uganda',
  3,
  'images/tours/bwindi_forest.jpg',
  'A focused journey into Bwindi, created around the forest itself — its wildlife, landscapes and conservation story.',
  'Direct, immersive gorilla tracking in UNESCO World Heritage Bwindi Impenetrable Forest. Focused purely on wildlife, pristine rainforest habitat, and intimate conservation insights.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — ARRIVAL IN BWINDI', 'Travel into the Bwindi region and settle into your forest surroundings. Spend the afternoon with a gentle introduction to the landscape and its conservation story. Overnight: Bwindi.'),
(@tour_id, 2, 'DAY 2 — GORILLA TREKKING', 'Enter Bwindi for a guided gorilla trekking experience. The day is dedicated to the forest and the gorilla family encountered within it. Return to your accommodation for a relaxed evening. Overnight: Bwindi.'),
(@tour_id, 3, 'DAY 3 — FOREST & DEPARTURE', 'Enjoy a final morning surrounded by the forest before beginning your onward journey. Journey concludes.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/bwindi_forest_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/bwindi_forest_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/bwindi_forest_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/bwindi_forest_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, '2 nights accommodation'),
(@tour_id, 'Private transportation'),
(@tour_id, 'Local guiding'),
(@tour_id, 'Gorilla trekking permit'),
(@tour_id, 'Selected meals');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'International flights'),
(@tour_id, 'Visa fees'),
(@tour_id, 'Travel insurance'),
(@tour_id, 'Personal expenses'),
(@tour_id, 'Optional experiences not specified above');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Gorilla trekking gear: boots, rain poncho, walking stick, gloves'),
(@tour_id, 'Long trousers tucked into socks (to prevent fire ants)'),
(@tour_id, 'Camera (no flash allowed during gorilla encounter)'),
(@tour_id, 'Reusable water bottle & packed lunch');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: On request'),
(@tour_id, 'CTA: PLAN YOUR VIRUNGA');


-- -----------------------------------------------------------------------
-- TOUR 10: 04 — THE BATWA FOREST EXPERIENCE (1 Day)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '04 — THE BATWA FOREST EXPERIENCE',
  'Private Experiences',
  'uganda',
  1,
  'images/tours/batwa_forest.jpg',
  'A personal encounter with Batwa heritage, traditional forest knowledge and living cultural traditions.',
  'Meet members of the indigenous Batwa community. Through storytelling, ancient botanical knowledge, hunting traditions, music and dance, discover living heritage with genuine cultural exchange.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — THE BATWA FOREST EXPERIENCE (Heritage, Stories & Living Traditions)', 'Meet members of the Batwa community and discover stories, knowledge and traditions connected to the forest. Through conversation, storytelling, traditional practices, music and dance, the experience offers a closer understanding of a culture whose history is deeply connected to the forest. The experience is intentionally personal, leaving space for exchange rather than observation alone. Duration: Half Day / Full Day. Private: Yes.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/batwa_forest_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/batwa_forest_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/batwa_forest_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/batwa_forest_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Batwa elder and community guide'),
(@tour_id, 'Forest walk and traditional demonstration'),
(@tour_id, 'Storytelling, music and cultural exchange'),
(@tour_id, 'Community support contribution');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Transportation (can be added)'),
(@tour_id, 'Personal tips and gifts');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Comfortable forest walking shoes'),
(@tour_id, 'Casual outdoor clothes'),
(@tour_id, 'Water bottle & camera');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Duration: Half Day / Full Day'),
(@tour_id, 'Private: Yes'),
(@tour_id, 'Price: On request'),
(@tour_id, 'CTA: EXPLORE THE EXPERIENCE');


-- -----------------------------------------------------------------------
-- TOUR 11: 05 — THE LAKES OF KISORO (1 Day)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '05 — THE LAKES OF KISORO',
  'Private Experiences',
  'uganda',
  1,
  'images/tours/lakes_of_kisoro.jpg',
  'A private exploration of the volcanic lakes and landscapes surrounding Kisoro.',
  'Glide across mirror-like volcanic lakes in traditional wooden dugout canoes or motorized boat, framed by the dramatic peaks of Muhabura, Gahinga, and Sabyinyo.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — THE LAKES OF KISORO (Volcanoes, Water & Highland Life)', 'Begin beside Lake Mutanda, framed by the Virunga volcanoes. Take to the water by canoe before continuing through the surrounding highlands and communities. The experience can be shaped around photography, local life, a relaxed lakeside meal or the changing light of the afternoon. Duration: Half Day / Full Day. Private: Yes.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/lakes_of_kisoro_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/lakes_of_kisoro_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/lakes_of_kisoro_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/lakes_of_kisoro_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Private canoe or boat excursion on Lake Mutanda'),
(@tour_id, 'Local guide & boat skipper'),
(@tour_id, 'Lakeside walking tour'),
(@tour_id, 'Refreshments / picnic lunch');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Personal expenses'),
(@tour_id, 'Tips and gratuities');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Sun protection (hat, sunscreen)'),
(@tour_id, 'Light jacket'),
(@tour_id, 'Camera & binoculars');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Duration: Half Day / Full Day'),
(@tour_id, 'Private: Yes'),
(@tour_id, 'Price: On request'),
(@tour_id, 'CTA: EXPLORE THE EXPERIENCE');


-- =======================================================================
-- RWANDA: SIGNATURE EXPERIENCES, SLOW & NATURE & PRIVATE DISCOVERIES (10 TOURS)
-- =======================================================================

-- -----------------------------------------------------------------------
-- TOUR 12: 01 — THE BUHANGA STILLNESS
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '01 — THE BUHANGA STILLNESS',
  'Slow & Nature Experiences',
  'rwanda',
  1,
  'images/tours/buhanga_stillness.jpg',
  'A private immersion in Buhanga Sacred Forest, where ancient trees, birdsong and Rwanda’s living heritage create an exceptional setting for stillness.',
  'Buhanga is more than a forest. It is a landscape shaped by nature, memory and cultural heritage. This experience offers a different way of encountering Rwanda — not through spectacle, but through attention, stillness and a deeper sense of place.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — BUHANGA SACRED FOREST (Silence • Nature • Reflection)', 'Enter one of Rwanda’s most culturally significant forests and leave the pace of ordinary travel behind. A gentle walk leads through the forest, introducing its ancient trees, plant life, birdlife and cultural heritage. As the forest deepens, the pace slows. In a secluded setting, a privately guided meditation invites you to breathe, listen and become fully present within the landscape. The experience continues in silence, allowing time for personal reflection before a quiet closing moment with a locally prepared tea or coffee.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/buhanga_stillness_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/buhanga_stillness_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/buhanga_stillness_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/buhanga_stillness_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Cultural host and nature guide'),
(@tour_id, 'Buhanga Sacred Forest access permit'),
(@tour_id, 'Privately guided meditation session'),
(@tour_id, 'Dedicated silent reflection time'),
(@tour_id, 'Locally prepared tea or coffee');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Transportation outside the agreed arrangement'),
(@tour_id, 'Personal purchases'),
(@tour_id, 'Tips and gratuities');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Comfortable walking shoes'),
(@tour_id, 'Warm layer or light jacket'),
(@tour_id, 'Personal journal / notebook (optional)'),
(@tour_id, 'Refillable water bottle'),
(@tour_id, 'Quiet, open mindset');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: Available on private request'),
(@tour_id, 'CTA: RESERVE YOUR EXPERIENCE'),
(@tour_id, 'FORMAT: Private | DURATION: Approx. 2 hours | LOCATION: Buhanga Sacred Forest, Northern Rwanda');


-- -----------------------------------------------------------------------
-- TOUR 13: 02 — THE HARVEST OF GRATITUDE (Umuganura)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '02 — THE HARVEST OF GRATITUDE',
  'Signature Experiences',
  'rwanda',
  1,
  'images/tours/harvest_gratitude.jpg',
  'Discover the Rwandan tradition of celebrating what has been achieved—and sharing what the land has given.',
  'A harvest can reveal how a society understands work, gratitude and prosperity. You will leave understanding why, in Rwanda, celebrating what has been achieved has traditionally been inseparable from sharing it with others.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — THE HARVEST ENCOUNTER (Celebrate Achievements & Share the Table)', 'Enter Umuganura, Rwanda’s traditional harvest celebration, through the people, food and landscape that continue to give the tradition meaning. Meet your hosts and discover the meaning of Umuganura. Walk through the fields, understand the crops, seasons and agricultural work, participate in seasonal harvest activities, and taste selected traditional foods around a shared table.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/harvest_gratitude_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/harvest_gratitude_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/harvest_gratitude_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/harvest_gratitude_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Cultural host'),
(@tour_id, 'Seasonal harvest encounter'),
(@tour_id, 'Selected hands-on activities'),
(@tour_id, 'Traditional food experience'),
(@tour_id, 'Shared meal'),
(@tour_id, 'Cultural interpretation');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Transportation outside the agreed arrangement'),
(@tour_id, 'Personal purchases'),
(@tour_id, 'Activities unavailable outside the relevant season');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Comfortable outdoor walking shoes'),
(@tour_id, 'Sun protection & hat'),
(@tour_id, 'Camera'),
(@tour_id, 'Curiosity and respect for local customs');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: Upon request'),
(@tour_id, 'CTA: PLAN YOUR EXPERIENCE'),
(@tour_id, 'DURATION: 3–4 hours | GROUP SIZE: 2–8 guests | SETTING: Rural Virunga community | AVAILABILITY: Seasonal');


-- -----------------------------------------------------------------------
-- TOUR 14: 03 — THE ART OF THE RWANDAN UNION (Gusaba no Gukwa)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '03 — THE ART OF THE RWANDAN UNION',
  'Signature Experiences',
  'rwanda',
  1,
  'images/tours/art_of_rwandan_union.jpg',
  'Discover how a Rwandan marriage traditionally begins with two families coming together.',
  'A marriage ceremony can tell you how a society understands family. Here, guests discover that marriage traditionally involved more than two individuals—it created a relationship between families, with obligations, respect and social meaning.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — GUSABA NO GUKWA (Family, Etiquette & Symbolic Gifts)', 'Discover Gusaba no Gukwa, a traditional Rwandan marriage custom centred on the formal coming together of families. Understand who represents each family, explore the etiquette surrounding the formal request, discover the symbolism of bridewealth and traditional gifts, and learn how acceptance and social relationships are expressed through selected music, food, and language.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/art_of_rwandan_union_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/art_of_rwandan_union_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/art_of_rwandan_union_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/art_of_rwandan_union_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Cultural host'),
(@tour_id, 'Cultural interpretation'),
(@tour_id, 'Selected traditional demonstrations'),
(@tour_id, 'Storytelling'),
(@tour_id, 'Selected refreshments where arranged');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Transportation outside the agreed arrangement'),
(@tour_id, 'Personal purchases'),
(@tour_id, 'Participation in private family ceremonies without invitation');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Respectful attire for cultural setting'),
(@tour_id, 'Notebook / camera'),
(@tour_id, 'Open curiosity');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: Upon request'),
(@tour_id, 'CTA: PLAN YOUR EXPERIENCE'),
(@tour_id, 'DURATION: 2–3 hours | GROUP SIZE: 2–8 guests | AVAILABILITY: Year-round | SETTING: Cultural or family setting');


-- -----------------------------------------------------------------------
-- TOUR 15: 04 — THE GIFT THAT CREATES A BOND (Guhana Inka)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '04 — THE GIFT THAT CREATES A BOND',
  'Signature Experiences',
  'rwanda',
  1,
  'images/tours/gift_creates_bond.jpg',
  'Discover why giving cattle could express friendship, trust, generosity and lasting relationship.',
  'A material gift becomes culturally powerful when it represents a relationship. You will discover why cattle have historically carried meanings that extend beyond economic value into trust, social bonds, and identity.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — GUHANA INKA (Cattle Culture, Friendship & Generosity)', 'Discover the cultural meaning of Guhana Inka through the people, cattle and stories that keep this heritage understandable today. Meet cattle-owning hosts, spend time with the animals, learn about pastoral traditions, explore the historical relationship between cattle, wealth, friendship and social identity, and understand the etiquette surrounding cattle exchange.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/gift_creates_bond_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/gift_creates_bond_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/gift_creates_bond_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/gift_creates_bond_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Cultural host'),
(@tour_id, 'Rural cattle encounter'),
(@tour_id, 'Cultural interpretation'),
(@tour_id, 'Storytelling'),
(@tour_id, 'Refreshment where arranged');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Transportation outside the agreed arrangement'),
(@tour_id, 'Personal purchases'),
(@tour_id, 'Actual livestock transactions');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Comfortable outdoor/farm footwear'),
(@tour_id, 'Sun hat and sunscreen'),
(@tour_id, 'Camera');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: Upon request'),
(@tour_id, 'CTA: PLAN YOUR EXPERIENCE'),
(@tour_id, 'DURATION: 2–3 hours | GROUP SIZE: 2–8 guests | AVAILABILITY: Year-round | SETTING: Rural community');


-- -----------------------------------------------------------------------
-- TOUR 16: 05 — THE TRADITIONAL ARCHER (Kumasha)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '05 — THE TRADITIONAL ARCHER',
  'Private Discoveries',
  'rwanda',
  1,
  'images/tours/traditional_archer.jpg',
  'Learn the discipline behind a traditional Rwandan skill—and put your own hands to the test.',
  'A skill becomes memorable when you try it. Instead of simply photographing a traditional practice, you leave having learned something with your own hands from a master practitioner.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — KUMASHA (Discipline, Technique & Guided Practice)', 'Discover traditional Rwandan archery through a skilled practitioner who introduces the technique, context and discipline. Watch the master demonstrate proper form, learn the fundamentals of bowmanship, and test your concentration and aim in a safe, controlled outdoor cultural setting.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/traditional_archer_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/traditional_archer_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/traditional_archer_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/traditional_archer_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Cultural host'),
(@tour_id, 'Archery equipment'),
(@tour_id, 'Safety briefing'),
(@tour_id, 'Guided practice'),
(@tour_id, 'Cultural interpretation');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Transportation outside the agreed arrangement'),
(@tour_id, 'Personal purchases');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Comfortable outdoor clothes and closed shoes'),
(@tour_id, 'Sun protection'),
(@tour_id, 'Refillable water bottle');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: Upon request'),
(@tour_id, 'CTA: PLAN YOUR EXPERIENCE'),
(@tour_id, 'DURATION: 1.5–2 hours | GROUP SIZE: 2–8 guests | SETTING: Outdoor cultural setting');


-- -----------------------------------------------------------------------
-- TOUR 17: 06 — THE DAY WE BUILD TOGETHER (Umuganda)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '06 — THE DAY WE BUILD TOGETHER',
  'Private Discoveries',
  'rwanda',
  1,
  'images/tours/day_we_build_together.jpg',
  'Experience Rwanda’s culture of collective action by contributing alongside the community.',
  'Community is difficult to understand from a distance. For a few hours, you share a practical responsibility with people who live there, transforming the relationship between traveller and destination.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — UMUGANDA (Collective Responsibility & Community Action)', 'Connect respectfully with an organised community-led activity. Work alongside local residents, understand Rwanda’s home-grown approach to collective development, engage in meaningful conversation with community members, and reflect on what collective responsibility means today.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/day_we_build_together_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/day_we_build_together_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/day_we_build_together_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/day_we_build_together_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Community coordination'),
(@tour_id, 'Local host'),
(@tour_id, 'Participation in selected activity'),
(@tour_id, 'Cultural interpretation'),
(@tour_id, 'Required basic equipment');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Transportation outside the agreed arrangement'),
(@tour_id, 'Personal purchases'),
(@tour_id, 'Donations unless independently chosen');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Work clothes suitable for outdoors'),
(@tour_id, 'Sturdy walking shoes or boots'),
(@tour_id, 'Work gloves (optional)'),
(@tour_id, 'Water bottle');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: Upon request'),
(@tour_id, 'CTA: PLAN YOUR EXPERIENCE'),
(@tour_id, 'DURATION: 2–4 hours | GROUP SIZE: 2–8 guests | SETTING: Community setting | TIMING: Morning');


-- -----------------------------------------------------------------------
-- TOUR 18: 07 — THE GACACA LEGACY (Gacaca)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '07 — THE GACACA LEGACY',
  'Cultural Heritage Experiences',
  'rwanda',
  1,
  'images/tours/gacaca_legacy.jpg',
  'Understand how Rwanda confronted its past through truth, accountability and community justice.',
  'Rwanda’s present cannot be separated from its history. Understanding Gacaca offers a deeper perspective on how communities confronted an extraordinary challenge—and how justice, memory and reconciliation became part of Rwanda’s rebuilding.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — GACACA (Truth, Justice & Reconciliation)', 'Explore the history and legacy of Gacaca, a traditional Rwandan community justice practice adapted after the 1994 Genocide against the Tutsi. A carefully facilitated historical encounter built around context, appropriate voices, testimony where available, and thoughtful guided conversation on reconciliation and rebuilding.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/gacaca_legacy_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/gacaca_legacy_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/gacaca_legacy_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/gacaca_legacy_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Specialist or cultural host'),
(@tour_id, 'Historical interpretation'),
(@tour_id, 'Curated conversation'),
(@tour_id, 'Appropriate contextual materials');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Transportation outside the agreed arrangement'),
(@tour_id, 'Personal purchases'),
(@tour_id, 'Photography or recording where restricted');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Notebook and pen'),
(@tour_id, 'Respectful demeanor'),
(@tour_id, 'Openness for thoughtful reflection');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: Upon request'),
(@tour_id, 'CTA: PLAN YOUR EXPERIENCE'),
(@tour_id, 'DURATION: 2–2.5 hours | GROUP SIZE: 2–8 guests | AVAILABILITY: Year-round | SETTING: Carefully selected cultural setting');


-- -----------------------------------------------------------------------
-- TOUR 19: 08 — THE SEEKING OF GUIDANCE (Kuraguza)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '08 — THE SEEKING OF GUIDANCE',
  'Private Discoveries',
  'rwanda',
  1,
  'images/tours/seeking_of_guidance.jpg',
  'Enter a traditional knowledge system to understand how uncertainty was once interpreted.',
  'Every culture has ways of asking questions when certainty is impossible. Kuraguza offers a rare opportunity to understand one of Rwanda’s traditional approaches to seeking guidance and interpreting signs.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — KURAGUZA (Traditional Knowledge & Worldview)', 'Explore Kuraguza through the knowledge of an appropriate cultural practitioner. Discover the traditional worldview, the role of the practitioner, and historical ways of interpreting uncertainty and signs through intimate, respectful conversation and cultural interpretation.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/seeking_of_guidance_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/seeking_of_guidance_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/seeking_of_guidance_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/seeking_of_guidance_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Cultural host'),
(@tour_id, 'Knowledge-holder encounter'),
(@tour_id, 'Cultural interpretation'),
(@tour_id, 'Guided conversation');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Transportation outside the agreed arrangement'),
(@tour_id, 'Personal purchases'),
(@tour_id, 'Guarantees of supernatural outcomes'),
(@tour_id, 'Spiritual services beyond the agreed cultural encounter');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Respectful mindset'),
(@tour_id, 'Notebook for cultural insights');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: Upon request'),
(@tour_id, 'CTA: PLAN YOUR EXPERIENCE'),
(@tour_id, 'DURATION: 1.5–2 hours | GROUP SIZE: 2–6 guests | AVAILABILITY: By advance arrangement | SETTING: Appropriate cultural setting');


-- -----------------------------------------------------------------------
-- TOUR 20: 09 — THE MEMORY OF THE ANCESTORS (Guterekera)
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '09 — THE MEMORY OF THE ANCESTORS',
  'Private Discoveries',
  'rwanda',
  1,
  'images/tours/memory_of_ancestors.jpg',
  'Explore how traditional Rwandan culture understood continuity between the living and those who came before.',
  'Some heritage is held in buildings. Some is carried in memory. This experience opens a window into a traditional Rwandan understanding of ancestry and the continuing relationship between generations.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — GUTEREKERA (Ancestry, Remembrance & Lineage)', 'Discover Guterekera through historical context, cultural interpretation and stories from an appropriate custodian. Explore how family memory, lineage, symbolic remembrance, and continuity were carried forward across generations.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/memory_of_ancestors_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/memory_of_ancestors_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/memory_of_ancestors_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/memory_of_ancestors_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Cultural host'),
(@tour_id, 'Historical interpretation'),
(@tour_id, 'Knowledge-holder conversation'),
(@tour_id, 'Guided reflection');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Transportation outside the agreed arrangement'),
(@tour_id, 'Personal purchases'),
(@tour_id, 'Reproduction of sacred practices for entertainment'),
(@tour_id, 'Participation in restricted ceremonies');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Respectful mindset and modest dress'),
(@tour_id, 'Notebook');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: Upon request'),
(@tour_id, 'CTA: PLAN YOUR EXPERIENCE'),
(@tour_id, 'DURATION: 1.5–2 hours | GROUP SIZE: 2–6 guests | AVAILABILITY: By advance arrangement | SETTING: Culturally appropriate setting');


-- -----------------------------------------------------------------------
-- TOUR 21: 10 — THE RWANDAN WEDDING
-- -----------------------------------------------------------------------
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`) 
VALUES (
  '10 — THE RWANDAN WEDDING',
  'Rare Cultural Encounters',
  'rwanda',
  1,
  'images/tours/rwandan_wedding.jpg',
  'Be welcomed into a real celebration—not a performance created for visitors.',
  'Some experiences cannot be manufactured. A wedding belongs to the people celebrating it. Being invited into that moment allows the traveller to witness culture as something living, personal and shared.'
);
SET @tour_id = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id, 1, 'DAY 1 — THE WEDDING CELEBRATION (Genuine Celebration & Living Tradition)', 'When trusted community relationships create a genuine invitation, be welcomed respectfully into an authentic Rwandan wedding celebration. Witness families gathering, traditional dress (umushanana), music, celebratory dances, gifts, and shared hospitality as they naturally unfold.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id, 'images/tours/highlights/rwandan_wedding_hl1.jpg', 1),
(@tour_id, 'images/tours/highlights/rwandan_wedding_hl2.jpg', 2),
(@tour_id, 'images/tours/highlights/rwandan_wedding_hl3.jpg', 3),
(@tour_id, 'images/tours/highlights/rwandan_wedding_hl4.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Cultural coordination'),
(@tour_id, 'Appropriate local host'),
(@tour_id, 'Access where invitation permits'),
(@tour_id, 'Cultural interpretation'),
(@tour_id, 'Selected food and refreshments where included');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Transportation outside the agreed arrangement'),
(@tour_id, 'Personal purchases or gifts'),
(@tour_id, 'Access to private ceremonies without permission');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id, 'Formal / elegant respectful attire'),
(@tour_id, 'Camera (respecting privacy)'),
(@tour_id, 'Gift/contribution if desired');

INSERT INTO `pricing_notes` (`tour_id`, `note`) VALUES
(@tour_id, 'Price: Upon request'),
(@tour_id, 'CTA: ENQUIRE ABOUT INVITATIONS'),
(@tour_id, 'FORMAT: Rare Cultural Encounter | AVAILABILITY: Occasional / by genuine invitation');

-- =======================================================================
-- Re-enable foreign key checks
-- =======================================================================
SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
