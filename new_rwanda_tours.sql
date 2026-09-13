-- =======================================================================
-- RWANDA TOURS & EXPERIENCES INSERT SCRIPT
-- Database: virungaecotoursdb
-- Encoding: UTF-8 (utf8mb4)
-- =======================================================================

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- -----------------------------------------------------------------------
-- TOUR 01: 01 — THE BUHANGA STILLNESS
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
-- TOUR 02: 02 — THE HARVEST OF GRATITUDE (Umuganura)
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
-- TOUR 03: 03 — THE ART OF THE RWANDAN UNION (Gusaba no Gukwa)
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
-- TOUR 04: 04 — THE GIFT THAT CREATES A BOND (Guhana Inka)
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
-- TOUR 05: 05 — THE TRADITIONAL ARCHER (Kumasha)
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
-- TOUR 06: 06 — THE DAY WE BUILD TOGETHER (Umuganda)
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
-- TOUR 07: 07 — THE GACACA LEGACY (Gacaca)
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
-- TOUR 08: 08 — THE SEEKING OF GUIDANCE (Kuraguza)
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
-- TOUR 09: 09 — THE MEMORY OF THE ANCESTORS (Guterekera)
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
-- TOUR 10: 10 — THE RWANDAN WEDDING
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
