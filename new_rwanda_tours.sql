-- =======================================================================
-- VIRUNGA ECOTOURS - 10 EDITORIAL RWANDA JOURNEYS & EXPERIENCES
-- Compatible with MySQL 5.7+, 8.0+, MariaDB 10.4+, and phpMyAdmin
-- =======================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+00:00';
SET NAMES utf8mb4;

-- -----------------------------------------------------------------------
-- 1. Ensure Table Structures Exist (Safe creation)
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

-- -----------------------------------------------------------------------
-- 2. Clean up existing Rwanda tours and their child records
-- -----------------------------------------------------------------------

DELETE FROM `tour_days` WHERE `tour_id` IN (SELECT `tour_id` FROM `tours` WHERE LOWER(TRIM(`country`)) = 'rwanda');
DELETE FROM `tour_highlights` WHERE `tour_id` IN (SELECT `tour_id` FROM `tours` WHERE LOWER(TRIM(`country`)) = 'rwanda');
DELETE FROM `tour_included` WHERE `tour_id` IN (SELECT `tour_id` FROM `tours` WHERE LOWER(TRIM(`country`)) = 'rwanda');
DELETE FROM `tour_excluded` WHERE `tour_id` IN (SELECT `tour_id` FROM `tours` WHERE LOWER(TRIM(`country`)) = 'rwanda');
DELETE FROM `tour_to_bring` WHERE `tour_id` IN (SELECT `tour_id` FROM `tours` WHERE LOWER(TRIM(`country`)) = 'rwanda');
DELETE FROM `pricing_tiers` WHERE `tour_id` IN (SELECT `tour_id` FROM `tours` WHERE LOWER(TRIM(`country`)) = 'rwanda');
DELETE FROM `pricing_notes` WHERE `tour_id` IN (SELECT `tour_id` FROM `tours` WHERE LOWER(TRIM(`country`)) = 'rwanda');
DELETE FROM `tours` WHERE LOWER(TRIM(`country`)) = 'rwanda';

-- -----------------------------------------------------------------------
-- 3. Insert 10 Editorial Rwanda Journeys & Experiences
-- -----------------------------------------------------------------------

-- ============================================================
-- TOUR 01: 01 — THE LIVING VIRUNGA JOURNEY (7 Days)
-- ============================================================
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`, `created_at`)
VALUES ('01 — THE LIVING VIRUNGA JOURNEY', 'Signature Journeys', 'rwanda', 7, 'images/hero/7.jpg', 'Come for the gorillas. Leave knowing Virunga. Our signature 7-day journey through the Virunga — combining gorilla trekking with the people, flavours, landscapes and quieter moments that reveal the character of the region.', 'Gorilla trekking · A day with a local host · Volcanic Highlands · Twin Lakes by boat · The Musanze Table · Coffee origin · Private journey coordination. Best for travellers who want the gorilla experience to be the beginning of their understanding of the Virunga, rather than the whole story.', NOW());
SET @tour_id_1 = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id_1, 1, 'Kigali to Musanze', 'Leave Kigali behind and travel north into the volcanic highlands. Arrive in Musanze and settle into your chosen stay.'),
(@tour_id_1, 2, 'A Day with the People of the Virunga', 'Spend time with a local host and take part in the rhythms of everyday life. Depending on the season and host, this may include food preparation, farming, craft or household traditions.'),
(@tour_id_1, 3, 'Into the Forest (Gorilla Trekking)', 'Enter Volcanoes National Park for your gorilla trek. The experience unfolds according to the location of your assigned gorilla family and the conditions of the forest.'),
(@tour_id_1, 4, 'The Volcanic Highlands', 'Move beyond the park and into the landscape shaped by the volcanoes — exploring rural roads, cultivated slopes and the communities living beneath the peaks.'),
(@tour_id_1, 5, 'Two Lakes, Many Lives', 'Travel towards Lake Burera and Lake Ruhondo. Take to the water by boat before continuing through the surrounding villages and returning to Musanze.'),
(@tour_id_1, 6, 'The Musanze Table & Coffee Origin', 'Meet local ingredients and the people behind them through a hosted food experience, followed by an introduction to coffee origin, processing and tasting.'),
(@tour_id_1, 7, 'Departure', 'A final morning in Musanze before your onward journey.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id_1, 'images/hero/Trekikng-Musanze-Gorillas.jpg', 1),
(@tour_id_1, 'images/hero/culture.jpeg', 2),
(@tour_id_1, 'images/hero/two.jpeg', 3),
(@tour_id_1, 'images/hero/Culture.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id_1, 'Private transport and dedicated journey coordination'),
(@tour_id_1, 'Professional safari and cultural guiding'),
(@tour_id_1, 'All experiences described in the itinerary'),
(@tour_id_1, 'Twin Lakes scenic boat experience'),
(@tour_id_1, '6 nights accommodation with daily breakfast according to selected property plan'),
(@tour_id_1, 'Hosted food and specialty coffee cupping experience');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id_1, 'Gorilla permit (unless specified in your proposal)'),
(@tour_id_1, 'Park fees not expressly listed'),
(@tour_id_1, 'International flights and entry visas'),
(@tour_id_1, 'Travel and medical insurance'),
(@tour_id_1, 'Personal expenses, laundry, and unspecified drinks'),
(@tour_id_1, 'Gratuities for guides and local hosts');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id_1, 'Sturdy, waterproof hiking boots'),
(@tour_id_1, 'Waterproof rain jacket and trousers'),
(@tour_id_1, 'Gardening gloves for forest foliage'),
(@tour_id_1, 'Long-sleeved breathable shirts and neutral-colored clothing'),
(@tour_id_1, 'Insect repellent and sun protection'),
(@tour_id_1, 'High-quality camera and extra memory cards');

-- ============================================================
-- TOUR 02: 02 — THE VIRUNGA WAY (5 Days)
-- ============================================================
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`, `created_at`)
VALUES ('02 — THE VIRUNGA WAY', 'Signature Journeys', 'rwanda', 5, 'images/hero/gorille.jpg', 'A slower introduction to the place beyond the park. A considered 5-day introduction to Musanze, combining the forest with the volcanic landscapes and people around it.', 'Gorilla trekking · Local host experience · Twin Lakes · Musanze food culture · Volcanic landscapes. Best for travellers with several days in Musanze who want a deeper introduction without committing to a longer journey.', NOW());
SET @tour_id_2 = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id_2, 1, 'Arrival in Musanze', 'Travel from Kigali into the Virunga highlands and settle into your chosen accommodation.'),
(@tour_id_2, 2, 'With a Local Host', 'Step into everyday life through a participatory experience shaped by your host and the season.'),
(@tour_id_2, 3, 'Gorilla Trekking', 'A full day in Volcanoes National Park, following the forest and the gorilla family assigned to you.'),
(@tour_id_2, 4, 'The Twin Lakes', 'Discover the volcanic landscape around Lake Burera and Lake Ruhondo, including a boat journey and time among surrounding communities.'),
(@tour_id_2, 5, 'The Musanze Table & Departure', 'A final taste of the region before continuing your journey.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id_2, 'images/hero/Trekikng-Musanze-Gorillas.jpg', 1),
(@tour_id_2, 'images/hero/two.jpeg', 2),
(@tour_id_2, 'images/hero/culture.jpeg', 3),
(@tour_id_2, 'images/hero/musanze.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id_2, 'Private 4x4 transport and guiding throughout'),
(@tour_id_2, 'All listed experiences and activities'),
(@tour_id_2, 'Twin Lakes boat excursion'),
(@tour_id_2, '4 nights accommodation with breakfast'),
(@tour_id_2, 'Hosted culinary encounter');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id_2, 'Gorilla permit (unless specified)'),
(@tour_id_2, 'Unlisted park fees'),
(@tour_id_2, 'International flights'),
(@tour_id_2, 'Personal expenses and gratuities');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id_2, 'Hiking boots with good ankle support'),
(@tour_id_2, 'Rain jacket and layered warm clothing'),
(@tour_id_2, 'Sun hat and sunglasses'),
(@tour_id_2, 'Daypack and reusable water bottle');

-- ============================================================
-- TOUR 03: 03 — THE FOREST & THE PEOPLE (3 Days)
-- ============================================================
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`, `created_at`)
VALUES ('03 — THE FOREST & THE PEOPLE', 'Signature Journeys', 'rwanda', 3, 'images/hero/Trekikng-Musanze-Gorillas.jpg', 'Meet the gorillas. Meet the people. Understand the place. A short, purposeful 3-day journey pairing the forest with the human landscape around it.', 'Meet the mountain gorillas in their ancient rainforest habitat and connect with the mountain communities living in their shadow. Best for travellers with limited time who want the forest experience placed within a wider sense of place.', NOW());
SET @tour_id_3 = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id_3, 1, 'Kigali to Musanze', 'Travel north through Rwanda’s changing landscapes and arrive beneath the Virunga volcanoes.'),
(@tour_id_3, 2, 'Gorillas & the People of the Virunga', 'Enter the forest for your gorilla trek. Subject to timing and operating conditions, continue with a hosted local experience.'),
(@tour_id_3, 3, 'The Landscape & Departure', 'Spend the morning exploring the volcanic landscape before continuing to your next destination.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id_3, 'images/hero/7.jpg', 1),
(@tour_id_3, 'images/hero/culture.jpeg', 2),
(@tour_id_3, 'images/hero/volucanic.jpeg', 3),
(@tour_id_3, 'images/hero/one.jpeg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id_3, 'Private vehicle and professional safari guide'),
(@tour_id_3, 'Park transfers to Volcanoes National Park'),
(@tour_id_3, 'Hosted local community experience'),
(@tour_id_3, '2 nights accommodation and breakfast');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id_3, 'Gorilla trekking permit unless specified'),
(@tour_id_3, 'International flights'),
(@tour_id_3, 'Personal items and tips');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id_3, 'Sturdy walking shoes or hiking boots'),
(@tour_id_3, 'Rain protection gear'),
(@tour_id_3, 'Water bottle and backpack');

-- ============================================================
-- TOUR 04: 04 — GORILLAS, VOLCANOES & TWO LAKES (3 Days)
-- ============================================================
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`, `created_at`)
VALUES ('04 — GORILLAS, VOLCANOES & TWO LAKES', 'Signature Journeys', 'rwanda', 3, 'images/hero/volucanic.jpeg', 'Wildlife, volcanic landscapes and life around the lakes. Three defining landscapes of the Virunga brought together in one concise 3-day journey.', 'Experience the three defining dimensions of the Virunga: pristine volcanic rainforest with mountain gorillas, dramatic volcanic ridges, and tranquil waters of the Twin Lakes. Best for travellers looking for a compact combination of wildlife and landscape.', NOW());
SET @tour_id_4 = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id_4, 1, 'Kigali to the Volcanic Highlands & Twin Lakes', 'Travel north through the highlands before continuing towards Lake Burera and Lake Ruhondo. Take to the water and return to Musanze.'),
(@tour_id_4, 2, 'Gorilla Trekking', 'A day in Volcanoes National Park, following the forest into the world of the mountain gorillas.'),
(@tour_id_4, 3, 'A Quiet Morning & Departure', 'Time to rest, explore or simply enjoy the landscape before departure.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id_4, 'images/hero/two.jpeg', 1),
(@tour_id_4, 'images/hero/gorille.jpg', 2),
(@tour_id_4, 'images/hero/volucanic.jpeg', 3),
(@tour_id_4, 'images/hero/her2.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id_4, 'Private transport from/to Kigali and within Musanze'),
(@tour_id_4, 'Twin Lakes boat tour'),
(@tour_id_4, 'Professional guide'),
(@tour_id_4, '2 nights accommodation with breakfast');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id_4, 'Gorilla permit (unless specified)'),
(@tour_id_4, 'Drinks and personal expenses'),
(@tour_id_4, 'Gratuities');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id_4, 'Lightweight rain jacket'),
(@tour_id_4, 'Walking/hiking footwear'),
(@tour_id_4, 'Sun protection and camera');

-- ============================================================
-- TOUR 05: 05 — THE MAMA FOR A DAY (1 Days)
-- ============================================================
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`, `created_at`)
VALUES ('05 — THE MAMA FOR A DAY', 'Private Experiences', 'rwanda', 1, 'images/hero/culture.jpeg', 'An invitation into everyday life. Spend time with a local host and take part rather than simply observe. Food preparation, farming, craft and household traditions form part of the experience.', 'Flow: Meet your host → participate in daily rhythms → share time together → return to Musanze. A genuine, intimate invitation into Rwandan family life shaped by mutual respect and depth.', NOW());
SET @tour_id_5 = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id_5, 1, 'Everyday Life with a Local Host', 'Meet your host in Musanze, step into everyday life through agricultural rhythms, food preparation, artisan crafting, and conversation over shared traditions.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id_5, 'images/hero/culturedance.jpeg', 1),
(@tour_id_5, 'images/hero/five.jpeg', 2),
(@tour_id_5, 'images/hero/Culture.jpg', 3),
(@tour_id_5, 'images/hero/six.jpeg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id_5, 'Host coordination and private cultural guide/translator'),
(@tour_id_5, 'All participatory materials and farming/cooking activities'),
(@tour_id_5, 'Shared traditional refreshments');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id_5, 'Accommodation (available on request)'),
(@tour_id_5, 'Private transport (optional add-on)'),
(@tour_id_5, 'Personal shopping');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id_5, 'Comfortable outdoor clothes suitable for hands-on activities'),
(@tour_id_5, 'Flat closed shoes or walking shoes'),
(@tour_id_5, 'Hat and sunscreen');

-- ============================================================
-- TOUR 06: 06 — TWO LAKES, MANY LIVES (1 Days)
-- ============================================================
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`, `created_at`)
VALUES ('06 — TWO LAKES, MANY LIVES', 'Slow & Nature Experiences', 'rwanda', 1, 'images/hero/two.jpeg', 'Water, villages and the volcanic landscape. A half-day journey into the northern lakes and the communities shaped by them.', 'Route: Musanze → Volcanic Highlands → Lake Burera → Lake Ruhondo → Boat → Village landscapes → Musanze. A peaceful immersion into crater lake ecology, wooden boats, and lakeside agrarian life.', NOW());
SET @tour_id_6 = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id_6, 1, 'Lakes Burera & Ruhondo Expedition', 'Scenic transfer from Musanze to the twin crater lakes, private traditional boat cruise with panoramic volcano views, and walks through surrounding lakeside communities.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id_6, 'images/hero/two.jpeg', 1),
(@tour_id_6, 'images/hero/her2.jpg', 2),
(@tour_id_6, 'images/hero/volucanic.jpeg', 3),
(@tour_id_6, 'images/hero/one.jpeg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id_6, 'Private experience coordination and local boat crew'),
(@tour_id_6, 'Scenic boat trip on the Twin Lakes'),
(@tour_id_6, 'Professional guide');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id_6, 'Lunch and drinks'),
(@tour_id_6, 'Accommodation'),
(@tour_id_6, 'Personal expenses');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id_6, 'Windbreaker or light jacket for the boat'),
(@tour_id_6, 'Sunglasses and sun hat'),
(@tour_id_6, 'Camera');

-- ============================================================
-- TOUR 07: 07 — THE MUSANZE TABLE (1 Days)
-- ============================================================
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`, `created_at`)
VALUES ('07 — THE MUSANZE TABLE', 'Food & Culinary', 'rwanda', 1, 'images/hero/Culture.jpg', 'A table shaped by the place. Meet local ingredients, the people who work with them and the traditions behind them. Participate in preparation, then sit down together to share the result.', 'This is a hosted food experience celebrating volcanic terroir and ancient recipes — not a conventional cooking class. Discover native crops, seasonal harvesting, and community dining.', NOW());
SET @tour_id_7 = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id_7, 1, 'Hosted Harvest & Communal Dining', 'Connect with local growers and farmers in Musanze, engage in ingredient harvesting and culinary preparation, and gather around a shared table for an authentic tasting feast.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id_7, 'images/hero/Culture.jpg', 1),
(@tour_id_7, 'images/hero/IMG_0288.JPG', 2),
(@tour_id_7, 'images/hero/culture.jpeg', 3),
(@tour_id_7, 'images/hero/musanze.jpg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id_7, 'Hosted culinary experience and storytelling'),
(@tour_id_7, 'All fresh local ingredients and food tasting'),
(@tour_id_7, 'Expert food host');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id_7, 'Alcoholic beverages'),
(@tour_id_7, 'Accommodation'),
(@tour_id_7, 'Transport (available on request)');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id_7, 'Appetite and curiosity'),
(@tour_id_7, 'Casual comfortable clothing');

-- ============================================================
-- TOUR 08: 08 — FROM SOIL TO CUP (1 Days)
-- ============================================================
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`, `created_at`)
VALUES ('08 — FROM SOIL TO CUP', 'Food & Culinary', 'rwanda', 1, 'images/hero/musanze.jpg', 'Follow coffee from origin to cup. Explore the volcanic landscape, people and processes behind Rwandan specialty coffee, from cherry harvesting and washing to roasting and cupping.', 'Step onto rich volcanic slopes and trace one of the world\'s finest Arabica coffees with farmers and artisanal roasters. The experience follows the seasonal cycle and grower availability.', NOW());
SET @tour_id_8 = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id_8, 1, 'Specialty Coffee Journey & Cupping', 'Guided tour through lush coffee groves, interactive demonstration of washing and drying methods, small-batch roasting, and professional sensory cupping session.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id_8, 'images/hero/musanze.jpg', 1),
(@tour_id_8, 'images/hero/IMG_0288.JPG', 2),
(@tour_id_8, 'images/hero/Culture.jpg', 3),
(@tour_id_8, 'images/hero/volucanic.jpeg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id_8, 'Plantation and washing station visit'),
(@tour_id_8, 'Full coffee processing walkthrough'),
(@tour_id_8, 'Professional cupping/tasting session');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id_8, 'Retail coffee bean purchases'),
(@tour_id_8, 'Transport and accommodation');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id_8, 'Walking shoes suitable for hillside coffee farms'),
(@tour_id_8, 'Hat and water bottle');

-- ============================================================
-- TOUR 09: 09 — THE VIRUNGA TRIANGLE (8 Days)
-- ============================================================
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`, `created_at`)
VALUES ('09 — THE VIRUNGA TRIANGLE', 'Signature Journeys', 'rwanda', 8, 'images/hero/group-tours.jpg', 'Rwanda and Uganda, connected through the Virunga landscape. A premier 7–9 day regional journey linking two countries through rainforests, volcanic massifs, wildlife encounters and vibrant communities.', 'A cross-border masterpiece: Rwanda (Kigali → Musanze → Volcanoes gorilla trekking → highlands) & Uganda (cross-border → Mgahinga/Bwindi forest → Bunyonyi → community encounters). Tailor-made around permits, routes, and interests.', NOW());
SET @tour_id_9 = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id_9, 1, 'Arrival in Kigali & Transfer to Musanze', 'Meet your expedition guide in Kigali and travel north into the volcanic highlands of Musanze.'),
(@tour_id_9, 2, 'A Day with the People of the Virunga', 'Hosted local encounter in Musanze experiencing everyday mountain traditions and agriculture.'),
(@tour_id_9, 3, 'Volcanoes National Park Gorilla Trekking', 'Trek into Volcanoes National Park for an unforgettable encounter with mountain gorillas.'),
(@tour_id_9, 4, 'Twin Lakes & Cross-Border Passage to Uganda', 'Morning boat cruise on Lake Burera/Ruhondo, followed by a scenic border crossing into Kisoro beneath Mount Muhabura.'),
(@tour_id_9, 5, 'Mgahinga / Bwindi Forest Expedition', 'Golden monkey tracking or pristine afro-montane nature trekking in Mgahinga Gorilla National Park.'),
(@tour_id_9, 6, 'Highland Communities & Forest Heritage', 'Deep cultural encounter exploring forest lore and traditional living in the Ugandan borderlands.'),
(@tour_id_9, 7, 'Lake Bunyonyi Islands & Relaxation', 'Tranquil canoeing and relaxation surrounded by the 29 islands and terraced slopes of Lake Bunyonyi.'),
(@tour_id_9, 8, 'Scenic Return Journey & Departure', 'Scenic overland return drive to Kigali or Entebbe for onward departure flight.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id_9, 'images/hero/gorille.jpg', 1),
(@tour_id_9, 'images/hero/group-tours.jpg', 2),
(@tour_id_9, 'images/hero/volucanic.jpeg', 3),
(@tour_id_9, 'images/hero/two.jpeg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id_9, 'Private 4x4 safari transport across Rwanda and Uganda'),
(@tour_id_9, 'Experienced cross-border safari driver/guide'),
(@tour_id_9, 'All listed experiences, Twin Lakes and Lake Bunyonyi boat excursions'),
(@tour_id_9, '7 nights accommodation with breakfast according to chosen standard'),
(@tour_id_9, 'Border crossing coordination');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id_9, 'Gorilla and golden monkey permits unless specified in proposal'),
(@tour_id_9, 'East Africa Tourist Visa'),
(@tour_id_9, 'International flights'),
(@tour_id_9, 'Travel insurance and personal expenses');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id_9, 'Passport with valid East Africa Tourist Visa'),
(@tour_id_9, 'Sturdy hiking boots and wool socks'),
(@tour_id_9, 'Warm fleece, rain jacket, and gloves'),
(@tour_id_9, 'Binoculars and camera gear');

-- ============================================================
-- TOUR 10: 10 — BESPOKE VIRUNGA (1 Days)
-- ============================================================
INSERT INTO `tours` (`title`, `category`, `country`, `days_count`, `cover_image_path`, `short_description`, `why_attend`, `created_at`)
VALUES ('10 — BESPOKE VIRUNGA', 'Private Experiences', 'rwanda', 1, 'images/hero/four.jpeg', 'Your time. Your interests. Your Virunga. Private tailor-made expeditions designed from the ground up for travellers who want to design the journey rather than choose from a fixed itinerary.', 'Combine Gorillas, golden monkeys, local hosts, volcanic summits, specialty coffee, crater lakes, and cross-border explorations on your own schedule and pace with dedicated concierge coordination.', NOW());
SET @tour_id_10 = LAST_INSERT_ID();

INSERT INTO `tour_days` (`tour_id`, `day_number`, `day_title`, `day_description`) VALUES
(@tour_id_10, 1, 'Bespoke Expedition Design', 'We listen to your dates, rhythm, and passions, crafting a fully customized itinerary with handpicked boutique stays, private logistics, and unmatched access.');

INSERT INTO `tour_highlights` (`tour_id`, `image_path`, `display_order`) VALUES
(@tour_id_10, 'images/hero/four.jpeg', 1),
(@tour_id_10, 'images/hero/7.jpg', 2),
(@tour_id_10, 'images/hero/two.jpeg', 3),
(@tour_id_10, 'images/hero/culture.jpeg', 4);

INSERT INTO `tour_included` (`tour_id`, `item_description`) VALUES
(@tour_id_10, 'Dedicated journey planner and bespoke itinerary design'),
(@tour_id_10, 'Private vehicle and expert guiding on requested days'),
(@tour_id_10, 'Accommodations and permits booked to exact client preferences');

INSERT INTO `tour_excluded` (`tour_id`, `item_description`) VALUES
(@tour_id_10, 'All items quoted based on agreed custom itinerary');

INSERT INTO `tour_to_bring` (`tour_id`, `item_description`) VALUES
(@tour_id_10, 'Travel preferences and bucket list wishlist');

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
