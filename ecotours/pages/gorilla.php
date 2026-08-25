<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mountain Gorilla Legacies | Virunga Ecotours</title>
    <meta name="description" content="Explore the legendary gorilla families of the Virunga Massif. From the iconic Susa family to the rising dynasties of Igisha, Isimbi, and more.">
    
    <link rel="canonical" href="https://virungajourneys.com/ecotours/pages/gorilla.php" />
    <!-- Favicon -->
    <link rel="shortcut icon" href="../images/logos/icon.png?v=1.1" type="image/x-icon" />
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/gorilla.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Include Header -->
    <?php include __DIR__ . '/includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="gorilla-hero">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <span class="hero-badge">Gorilla Family Legacies</span>
                <h1>Guardians of the Virunga Mountains</h1>
                <p>Discover the stories of resilience, leadership, and heritage behind the legendary mountain gorilla families of Rwanda and the Virunga Massif.</p>
                <div class="hero-actions">
                    <a href="#families" class="btn btn-primary">Explore Families</a>
                    <a href="#history" class="btn btn-outline">Our History</a>
                </div>
            </div>
        </div>
    </section>

    <!-- History & Dian Fossey Section -->
    <section id="history" class="history-section section-padding">
        <div class="container">
            <div class="section-grid">
                <div class="history-image">
                    <img src="../images/gorilla/under.jpg" alt="Dian Fossey and Gorillas" class="main-img">
                    <div class="img-badge">
                        <span class="year">1967</span>
                        <span class="text">Mission Began</span>
                    </div>
                </div>
                <div class="history-text">
                    <span class="section-subtitle">The Beginning</span>
                    <h2 class="section-title">A Legacy from 1967 to Today</h2>
                    <p class="lead">"From Shadows of Extinction to a Symbol of Hope"</p>
                    <p>In 1967, Dian Fossey stepped into the heart of Central Africa with one unshakable mission: to study and protect the mountain gorillas. From her first base in Kabara to the legendary Karisoke Research Center in Rwanda, her work transformed gorilla research from basic observation into a lifetime scientific pursuit.</p>
                    <div class="stat-boxes">
                        <div class="stat-box">
                            <span class="number">~250</span>
                            <span class="label">Gorillas in 1967</span>
                        </div>
                        <div class="stat-box">
                            <span class="number">1,063+</span>
                            <span class="label">Gorillas Today</span>
                        </div>
                    </div>
                    <blockquote class="fossey-quote">
                        "When you realize the value of all life, you dwell less on what is past and concentrate more on the preservation of the future."
                        <cite>— Dian Fossey</cite>
                    </blockquote>
                </div>
            </div>
        </div>
    </section>

    <!-- Families Explorer Section -->
    <section id="families" class="families-section section-padding bg-light">
        <div class="container">
            <div class="section-header text-center">
                <span class="section-subtitle">Meet the Dynasties</span>
                <h2 class="section-title">Legendary Gorilla Families</h2>
                <p class="section-desc">Each family carries a unique history of strength, wisdom, and leadership. Explore the stories of the great silverbacks and their descendants.</p>
            </div>

            <div class="families-grid">
                <!-- Susa Family -->
                <div class="family-card-modern">
                    <div class="card-img-wrapper">
                        <img src="../images/gorilla/hero2.jpg" alt="Susa Family">
                        <div class="card-tag">The Legend</div>
                    </div>
                    <div class="card-body">
                        <h3>Susa Family</h3>
                        <p class="slogan">"From Susa’s Strength, Three Legacies Were Born: Igisha, Isimbi, and Karisimbi."</p>
                        <p class="description">One of the most famous families in Volcanoes National Park, known for its resilience and deep social bonds. Closely studied by Dian Fossey, Susa represents the heart of Rwanda’s gorilla heritage.</p>
                        <div class="descendants">
                            <span class="label">Descendants:</span>
                            <div class="tags">
                                <span class="tag">Igisha</span>
                                <span class="tag">Isimbi</span>
                                <span class="tag">Karisimbi</span>
                            </div>
                        </div>
                        <button class="btn-text" onclick="showFamilyDetails('susa')">Read Story <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>

                <!-- Kwitonda Family -->
                <div class="family-card-modern">
                    <div class="card-img-wrapper">
                        <img src="../images/conservationist/gorilla.jpg" alt="Kwitonda Family">
                        <div class="card-tag">The Wise One</div>
                    </div>
                    <div class="card-body">
                        <h3>Kwitonda Family</h3>
                        <p class="slogan">"From Kwitonda’s Wisdom to New Horizons: Kwisanga, Lisanga, Urwego – Families of the Future."</p>
                        <p class="description">Led by the gentle giant Silverback Kwitonda, this family embodied patience and quiet strength. Arriving from DRC in 2005, they became a symbol of new beginnings.</p>
                        <div class="descendants">
                            <span class="label">Descendants:</span>
                            <div class="tags">
                                <span class="tag">Kwisanga</span>
                                <span class="tag">Lisanga</span>
                                <span class="tag">Urwego</span>
                            </div>
                        </div>
                        <button class="btn-text" onclick="showFamilyDetails('kwitonda')">Read Story <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>

                <!-- Pablo Family -->
                <div class="family-card-modern">
                    <div class="card-img-wrapper">
                        <img src="../images/gorilla/under.jpg" alt="Pablo Family">
                        <div class="card-tag">The Dynasty</div>
                    </div>
                    <div class="card-body">
                        <h3>Pablo Family</h3>
                        <p class="slogan">"From One Silverback’s Dream to a Kingdom in the Clouds."</p>
                        <p class="description">Tracing roots back to Susa, Pablo formed one of the most successful groups recorded. At one point, it reached a record-breaking 65 members—the largest group ever.</p>
                        <div class="descendants">
                            <span class="label">Descendants:</span>
                            <div class="tags">
                                <span class="tag">Musirikare</span>
                                <span class="tag">Isabukuru</span>
                                <span class="tag">Kureba</span>
                            </div>
                        </div>
                        <button class="btn-text" onclick="showFamilyDetails('pablo')">Read Story <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>

                <!-- Amahoro Family -->
                <div class="family-card-modern">
                    <div class="card-img-wrapper">
                        <img src="../images/gorilla/hero.jpg" alt="Amahoro Family">
                        <div class="card-tag">The Peaceful</div>
                    </div>
                    <div class="card-body">
                        <h3>Amahoro Family</h3>
                        <p class="slogan">"From Peace to Togetherness – Amahoro and Umubano, One Legacy, Two Stories."</p>
                        <p class="description">Meaning "Peace" in Kinyarwanda, this family is known for its calm and approachable nature. It represents harmony in the wild and the beauty of family bonds.</p>
                        <div class="descendants">
                            <span class="label">Descendants:</span>
                            <div class="tags">
                                <span class="tag">Umubano</span>
                            </div>
                        </div>
                        <button class="btn-text" onclick="showFamilyDetails('amahoro')">Read Story <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- More Families Grid (Condensed) -->
    <section class="more-families section-padding">
        <div class="container">
            <h3 class="subsection-title">Other Remarkable Families</h3>
            <div class="mini-grid">
                <div class="mini-card" onclick="showFamilyDetails('musilikale')">
                    <h4>Musilikale</h4>
                    <p>"The Soldier" - Strong, vigilant, and protective.</p>
                </div>
                <div class="mini-card" onclick="showFamilyDetails('ntambara')">
                    <h4>Ntambara</h4>
                    <p>"The Fighter" - Born from rebellion and guided by courage.</p>
                </div>
                <div class="mini-card" onclick="showFamilyDetails('segasira')">
                    <h4>Segasira</h4>
                    <p>A legacy of leadership descending from Beetsme.</p>
                </div>
                <div class="mini-card" onclick="showFamilyDetails('noheri')">
                    <h4>Noheri</h4>
                    <p>Born from legacy, thriving in leadership.</p>
                </div>
                <div class="mini-card" onclick="showFamilyDetails('mutobo')">
                    <h4>Mutobo</h4>
                    <p>A dynasty of strength born from strategic battles.</p>
                </div>
                <div class="mini-card" onclick="showFamilyDetails('hirwa')">
                    <h4>Hirwa</h4>
                    <p>"The Lucky Ones" - A story of twins and resilience.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="gorilla-cta section-padding">
        <div class="container">
            <div class="cta-card">
                <div class="cta-text">
                    <h2>Witness the Spirit of Resilience</h2>
                    <p>Reserve your gorilla trekking experience today and walk with history in the misty forests of the Virunga Massif. Your journey helps protect their home and sustain their future.</p>
                    <div class="cta-btns">
                        <a href="../contact.php" class="btn btn-primary">Book Your Trek</a>
                        <a href="mailto:info@virungajourneys.com" class="btn btn-outline-white">Inquire Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Story Modal (Static for now) -->
    <div id="storyModal" class="story-modal">
        <div class="modal-overlay" onclick="closeStoryModal()"></div>
        <div class="modal-container">
            <button class="modal-close" onclick="closeStoryModal()">&times;</button>
            <div class="modal-body" id="modalContent">
                <!-- Content will be injected here via JS -->
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        const familyStories = {
            susa: {
                title: "The Legacy of the Susa Family",
                content: "The Susa Family is one of the most famous gorilla families in Volcanoes National Park, carrying a history filled with both triumph and tragedy. In 2002, the family endured poacher attacks but their story became a symbol of resilience. Today, its legacy lives on through the Igisha, Isimbi, and Karisimbi groups.",
                slogan: "From Susa’s Strength, Three Legacies Were Born: Igisha, Isimbi, and Karisimbi."
            },
            kwitonda: {
                title: "The Legacy of the Kwitonda Family",
                content: "Led by the gentle giant Silverback Kwitonda, this family embodied patience, wisdom, and quiet strength. Arriving from DRC in 2005, their journey became a symbol of resilience. After Kwitonda's passing, the family divided into Kwisanga, Lisanga, and Urwego.",
                slogan: "From Kwitonda’s Wisdom to New Horizons."
            },
            pablo: {
                title: "The Origins of the Pablo Family",
                content: "Formed in the early 1990s, this iconic group traces roots back to Susa. Under Pablo's disciplined leadership, the group thrived in the hagenia forests and reached a record-breaking 65 members—the largest group ever recorded.",
                slogan: "From One Silverback’s Dream to a Kingdom in the Clouds."
            },
            musilikale: {
                title: "The Origins of the Musilikale Family",
                content: "In Kinyarwanda, Musilikale means 'the soldier.' This name perfectly captures his personality—strong, vigilant, and fiercely protective. Tracing roots back to the Pablo lineage, he forged his own path in 2018.",
                slogan: "Born to Protect, Destined to Lead."
            },
            ntambara: {
                title: "The Origins of the Ntambara Family",
                content: "Ntambara means 'war' or 'fighter.' Born from the Shinda lineage, Ntambara made a bold break for independence in 2008. The group embodies the warrior spirit—protective, courageous, and highly territorial.",
                slogan: "Where Strength Leads and Courage Protects."
            },
            amahoro: {
                title: "The Amahoro Family – A Legacy of Peace",
                content: "Amahoro means 'peace.' Since 2000, they have touched hearts with their calm and approachable nature. After their great silverback's passing, a younger silverback Charles formed the Umubano family.",
                slogan: "From Peace to Togetherness."
            }
            // Add more stories as needed
        };

        function showFamilyDetails(key) {
            const story = familyStories[key];
            if (!story) return;

            const modalContent = `
                <div class="modal-story">
                    <h2>${story.title}</h2>
                    <p class="modal-slogan">${story.slogan}</p>
                    <div class="modal-text">
                        <p>${story.content}</p>
                        <p>More details coming soon in our full guide...</p>
                    </div>
                    <div class="modal-footer">
                        <a href="../contact.php" class="btn btn-primary">Book a Trek to meet them</a>
                    </div>
                </div>
            `;
            document.getElementById('modalContent').innerHTML = modalContent;
            document.getElementById('storyModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeStoryModal() {
            document.getElementById('storyModal').classList.remove('active');
            document.body.style.overflow = '';
        }
    </script>
    <script src="../js/header.js" defer></script>
</body>
</html>