<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="shortcut icon"
      href="../images/logos/icon.png"
      type="image/x-icon"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/earthy-theme.css" />
    <style>
.hero-section {
  position: relative;
  height: 70vh;
  min-height: 500px;
  background: linear-gradient(
    135deg,
    var(--primary-green) 0%,
    var(--accent-sage) 100%
  );
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}

.hero-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  /*filter: brightness(1) contrast(1);*/
  z-index: 1;
}

.hero-section::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    135deg,
    rgba(42, 72, 88, 0.7) 0%,
    rgba(42, 72, 88, 0.4) 100%
  );
  z-index: 2;
}

.hero-content {
  position: relative;
  z-index: 3;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 100%;
  padding: 0 20px;
  text-align: center;
  color: var(--text-light);
  max-width: 1000px;
}

.hero-title {
  font-size: clamp(2rem, 5vw, 3.5rem);
  /*font-weight: 300;*/
  margin-bottom: 25px;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
  letter-spacing: -0.02em;
  /*line-height: 1.1;*/
}

.hero-title span {
  color: var(--accent-terracotta);
  /*font-weight: 400;*/
  display: block;
  margin-top: 10px;
}

.hero-subtitle {
  font-size: clamp(1.1rem, 3vw, 1.6rem);
  max-width: 700px;
  margin-bottom: 40px;
  text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.6);
  font-weight: 300;
  opacity: 0.95;
}

.content-section {
  padding: 80px 20px;
  max-width: 1200px;
  margin: 0 auto;
}

.section-title {
  text-align: center;
  margin-bottom: 60px;
  position: relative;
}

.section-title::after {
  content: "";
  position: absolute;
  bottom: -20px;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 3px;
  background: linear-gradient(
    90deg,
    var(--accent-terracotta) 0%,
    var(--primary-brown) 100%
  );
  border-radius: 2px;
}

.section-title h2 {
  font-size: clamp(2.2rem, 5vw, 3.2rem);
  font-weight: 300;
  margin-bottom: 15px;
  color: var(--text-dark);
  letter-spacing: -0.01em;
}

.section-title h2 span {
  color: var(--accent-terracotta);
  font-weight: 400;
}

.section-description {
  text-align: center;
  max-width: 900px;
  margin: 0 auto 50px;
  font-size: 1.1rem;
  color: var(--text-medium);
  line-height: 1.8;
  font-weight: 300;
}

.section-description a {
  color: var(--primary-green);
  text-decoration: none;
  font-weight: 500;
  border-bottom: 1px solid transparent;
  transition: border-bottom-color 0.3s ease;
}

.section-description a:hover {
  border-bottom-color: var(--primary-green);
}

.divider {
  height: 2px;
  background: linear-gradient(
    90deg,
    transparent 0%,
    var(--neutral-beige) 20%,
    var(--accent-terracotta) 50%,
    var(--neutral-beige) 80%,
    transparent 100%
  );
  margin: 60px auto;
  width: 100%;
  border: none;
}

.two-column {
  display: flex;
  flex-wrap: wrap;
  gap: 50px;
  margin-top: 40px;
}

.column {
  flex: 1;
  min-width: 300px;
  background-color: var(--neutral-cream);
  padding: 40px;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(58, 48, 38, 0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.column:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 30px rgba(58, 48, 38, 0.12);
}

.column p {
  margin-bottom: 20px;
  font-size: 1.05rem;
  line-height: 1.7;
  color: var(--text-dark);
}

.column a {
  color: var(--primary-green);
  text-decoration: none;
  font-weight: 500;
  border-bottom: 1px solid transparent;
  transition: border-bottom-color 0.3s ease;
}

.column a:hover {
  border-bottom-color: var(--primary-green);
}

@media (max-width: 768px) {
  .hero-section {
    height: 50vh;
  }

  .hero-title {
    font-size: clamp(2rem, 8vw, 3.5rem);
  }

  .hero-subtitle {
    font-size: clamp(0.9rem, 3vw, 1.2rem);
  }

  .content-section {
    padding: 40px 20px;
  }

  .two-column {
    flex-direction: column;
    gap: 30px;
  }
}

@media (max-width: 480px) {
  .hero-section {
    height: 40vh;
  }

  .content-section {
    padding: 30px 15px;
  }
}

.grid-container {
  display: grid;
  grid-template-columns: 1fr 1fr;
  width: 100%;
  margin: 60px 0;
}

.grid-item {
  position: relative;
  min-height: 600px;
  overflow: hidden;
}

.grid-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  position: absolute;
  top: 0;
  left: 0;
  transition: transform 0.6s ease;
  filter: brightness(0.9) contrast(1.1);
}

.grid-item:hover img {
  transform: scale(1.05);
}

.parallax-img {
  transition: transform 0.5s ease-out;
}

.content-box {
  padding: 80px 50px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  height: 100%;
  position: relative;
}

.content-box::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    135deg,
    rgba(255, 255, 255, 0.05) 0%,
    rgba(255, 255, 255, 0.02) 100%
  );
  z-index: 1;
}

.content-box > * {
  position: relative;
  z-index: 2;
}

.content-box p {
  color: var(--text-light);
  font-size: 1.1rem;
  line-height: 1.8;
  margin-bottom: 20px;
  font-weight: 300;
}

.pink-bg {
  background: linear-gradient(
    135deg,
    var(--accent-terracotta) 0%,
    var(--primary-brown) 100%
  );
  color: var(--text-light);
}

.teal-bg {
  background: linear-gradient(
    135deg,
    var(--primary-green) 0%,
    var(--accent-sage) 100%
  );
  color: var(--text-light);
}

.content-box h2 {
  font-size: clamp(1.8rem, 3vw, 2.8rem);
  margin-bottom: 30px;
  line-height: 1.3;
  color: var(--text-light);
  font-weight: 300;
  letter-spacing: -0.01em;
}

.content-box p {
  font-size: clamp(1rem, 1.5vw, 1.15rem);
  margin-bottom: 20px;
  line-height: 1.8;
}

.content-box ul {
  list-style-type: none;
  margin-bottom: 25px;
}

.content-box li {
  font-size: clamp(1rem, 1.5vw, 1.1rem);
  margin-bottom: 15px;
  position: relative;
  padding-left: 30px;
  line-height: 1.7;
}

.content-box li::before {
  content: "→";
  position: absolute;
  left: 0;
  color: var(--neutral-cream);
  font-weight: bold;
  font-size: 1.2em;
}

.highlight {
  font-weight: 500;
  color: var(--neutral-cream);
  background: rgba(255, 255, 255, 0.1);
  padding: 2px 6px;
  border-radius: 4px;
}

.content-link {
  color: var(--neutral-cream);
  text-decoration: none;
  font-weight: 500;
  border-bottom: 1px solid rgba(255, 255, 255, 0.3);
  transition: border-bottom-color 0.3s ease;
}

.content-link:hover {
  border-bottom-color: var(--neutral-cream);
}

@media (max-width: 1024px) {
  .content-box {
    padding: 40px 30px;
  }
}

@media (max-width: 768px) {
  .grid-container {
    grid-template-columns: 1fr;
  }

  .grid-item {
    min-height: 400px;
  }

  .content-box {
    padding: 40px 25px;
  }

  /* Adjust order for mobile */
  .mobile-order-1 {
    order: 1;
  }
  .mobile-order-2 {
    order: 2;
  }
  .mobile-order-3 {
    order: 3;
  }
  .mobile-order-4 {
    order: 4;
  }
  .mobile-order-5 {
    order: 5;
  }
  .mobile-order-6 {
    order: 6;
  }
  .mobile-order-7 {
    order: 7;
  }
  .mobile-order-8 {
    order: 8;
  }
}

@media (max-width: 480px) {
  .grid-item {
    min-height: 300px;
  }

  .content-box {
    padding: 30px 20px;
  }

  h2 {
    font-size: clamp(1.3rem, 5vw, 1.8rem);
  }
}

.form-container {
  background: linear-gradient(
    135deg,
    var(--neutral-cream) 0%,
    var(--neutral-beige) 100%
  );
  padding: 80px 20px;
  position: relative;
}

.form-container::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: url("../images/hero/page-cover.jpg");
  background-size: cover;
  background-position: center;
  opacity: 0.05;
  z-index: 1;
}

.form-inner {
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
  z-index: 2;
  background: rgba(255, 255, 255, 0.95);
  padding: 60px;
  border-radius: 20px;
  box-shadow: 0 20px 60px rgba(58, 48, 38, 0.15);
}

.form-header {
  text-align: center;
  margin-bottom: 50px;
  position: relative;
}

.form-header::after {
  content: "";
  position: absolute;
  bottom: -20px;
  left: 50%;
  transform: translateX(-50%);
  width: 100px;
  height: 3px;
  background: linear-gradient(
    90deg,
    var(--primary-green) 0%,
    var(--accent-terracotta) 100%
  );
  border-radius: 2px;
}

.form-header h1 {
  font-size: clamp(2.5rem, 4vw, 3.5rem);
  color: var(--text-dark);
  margin-bottom: 20px;
  font-weight: 300;
  letter-spacing: -0.02em;
}

.form-header p {
  font-size: clamp(1.1rem, 2vw, 1.3rem);
  color: var(--text-medium);
  font-weight: 300;
  line-height: 1.6;
}

.form-section {
  margin-bottom: 40px;
}

.form-section h2 {
  font-size: 1.5rem;
  margin-bottom: 20px;
  color: var(--primary-green);
}

.form-row {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-bottom: 20px;
}

.form-group {
  flex: 1;
  min-width: 250px;
}

.form-control {
  width: 100%;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  background-color: white;
}

textarea.form-control {
  min-height: 120px;
  resize: vertical;
}

select.form-control {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 15px center;
  background-size: 15px;
}

input[type="date"].form-control {
  padding-right: 15px;
}

.checkbox-group {
  display: flex;
  flex-wrap: wrap;
  gap: 10px 30px;
}

.checkbox-container {
  display: flex;
  align-items: flex-start;
  margin-bottom: 10px;
  flex: 1 1 250px;
}

.checkbox-container input[type="checkbox"] {
  margin-right: 10px;
  margin-top: 5px;
}

.checkbox-container label {
  font-size: 0.95rem;
}

.submit-section {
  text-align: center;
  margin-top: 30px;
}

.btn-submit {
  background-color: var(--accent-terracotta);
  color: var(--text-light);
  border: none;
  padding: 15px 40px;
  font-size: 1.1rem;
  border-radius: 4px;
  cursor: pointer;
  width: 100%;
  max-width: 500px;
  text-transform: uppercase;
  font-weight: bold;
  transition: background-color 0.3s;
}

.btn-submit:hover {
  background-color: var(--primary-brown);
}

/* Newsletter section */
.newsletter-section {
  background-image: url("../images/hero/page-cover2.jpg");
  background-size: cover;
  background-position: center;
  color: white;
  text-align: center;
  padding: 80px 20px;
  position: relative;
}

.newsletter-section::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(42, 72, 88, 0.7); /* primary-green with opacity */
  z-index: 1;
}

.newsletter-content {
  position: relative;
  z-index: 2;
  max-width: 800px;
  margin: 0 auto;
}

.newsletter-content h2 {
  font-size: 2.5rem;
  margin-bottom: 20px;
}

.newsletter-link {
  color: var(--neutral-cream);
  text-decoration: none;
  font-weight: bold;
}

.newsletter-link:hover {
  text-decoration: underline;
}

/* Progress dots */
.progress-dots {
  display: flex;
  justify-content: center;
  margin-bottom: 20px;
}

.dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  margin: 0 5px;
  background-color: var(--neutral-beige);
}

.dot.active {
  background-color: var(--accent-terracotta);
}

@media (max-width: 1024px) {
  .form-inner {
    padding: 40px 30px;
  }
}

@media (max-width: 768px) {
  .form-container {
    padding: 30px 15px;
  }

  .form-inner {
    padding: 30px 20px;
  }

  .form-group {
    min-width: 100%;
  }

  .checkbox-container {
    flex: 1 1 100%;
  }

  .content-box {
    padding: 40px 25px;
  }

  h2 {
    font-size: clamp(1.3rem, 5vw, 1.8rem);
  }
}

@media (max-width: 480px) {
  .form-inner {
    padding: 20px 15px;
  }
}

/* Enhanced animations and transitions */
.column,
.content-box,
.form-inner {
  animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Smooth scrolling for the entire page */
html {
  scroll-behavior: smooth;
}

/* Enhanced focus states for accessibility */
.form-control:focus {
  outline: none;
  border-color: var(--primary-green);
  box-shadow: 0 0 0 3px rgba(42, 72, 88, 0.1);
}

.btn-submit:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(150, 114, 89, 0.3);
}

/* Loading state for form submission */
.btn-submit:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

/* Enhanced hover effects */
.form-control:hover {
  border-color: var(--accent-sage);
  transition: border-color 0.3s ease;
}

    </style>
    <script src="../js/header.js" defer></script>
    <title>Custom</title>
  </head>
  <body>
  <?php include('./includes/header.php'); ?>
    <section class="hero-section">
      <img
        src="../images/hero/build2.jpg"
        alt="Rwanda traditional weaving"
        class="hero-bg"
      />
      <div class="hero-content">
        <h1 class="hero-title">Custom <span>Trip Planner</span></h1>
      </div>
    </section>

    <section class="content-section">
      <div class="section-title">
        <h2>Personalized <span>Virunga Massif Travel Itineraries</span></h2>
      </div>
      <div class="section-description">
        <p>
          Discover the Spirit of the Virunga Massif – A Journey That Heals, Transforms, and Connects The Virunga Massif, stretching across Rwanda, Uganda, and the DRC, offers more than stunning landscapes and thrilling gorilla treks. It’s a journey that touches your soul. The misty peaks, ancient forests, and vibrant wildlife are intertwined with the spirit of local communities who have nurtured this land for generations.
        </p>
      </div>

      <div class="divider"></div>

      <div class="section-title">
        <h2>Your Virunga Journey: Crafted for Connection and<span> Conservation</span></h2>
      </div>

      <div class="two-column">
        <div class="column">
          <p>
            Virunga Ecotours specializes in crafting personalized travel itineraries that provide an authentic 
            and eco-conscious experience in the Virunga Massif. By focusing on the natural landscapes, diverse wildlife, 
            and local communities, we tailor each journey to match the unique interests and values of our travelers. 
            Our itineraries include a mix of activities such as wildlife tracking, hiking, and cultural exchanges, 
            offering opportunities to explore the region’s biodiversity while promoting sustainable tourism practices.
            Through our personalized approach, we ensure that each traveler engages meaningfully with the environment
            and supports local conservation efforts, fostering a deep connection to the region’s exceptional natural heritage.
          
          </p>
        </div>
      </div>
    </section>
    
    <div class="form-container">
      <div class="form-inner">
        <div class="form-header">
          <h1> Customize Your private trip</h1>
          <p>This journey through the Virunga Massif, spanning Rwanda, the Democratic Republic of Congo (DRC), 
            and Uganda, offers an engaging exploration of the region’s rich biodiversity and unique landscapes. 
            The focus is on sustainable travel and conservation, with an emphasis on both education and responsible 
            tourism.</p>

        <form method="POST" action="process_build.php">
          <!-- About you section -->
          <div class="form-section">
            <h2>About you</h2>
            <div class="form-row">
              <div class="form-group">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Full Names*"
                  name="names"
                  required
                />
              </div>
             <div class="form-group">
                <input
                  type="email"
                  class="form-control"
                  placeholder="Email* (e.g. example@domain.com)"
                  name="email"
                  required
                />
              </div>
            </div>

            <div class="form-row">
              
              <div class="form-group">
                <input
                  type="tel"
                  class="form-control"
                  placeholder="Phone* (e.g. 0123456789)"
                  name="phone"
                  pattern="[0-9]{10,15}"
                  required
                />
              </div>
              <div class="form-group">
                <select class="form-control" name="referral_source" required>
                  <option value="" disabled selected>
                    How did you find out about us?
                  </option>
                  <option value="google">Google</option>
                  <option value="facebook">Facebook</option>
                  <option value="instagram">Instagram</option>
                  <option value="friend">Friend Recommendation</option>
                  <option value="other">Other</option>
                </select>
              </div>
            </div>

            

            <div class="form-row">
              <div class="form-group" style="flex: 1 1 100%">
                <textarea
                  class="form-control"
                  name="travelers_info"
                  placeholder="Please tell us more about the people traveling with you: ages, physical and medical condition, special interests. If you travel with kids, please specify their age. Also leave certain preferences like room distribution."
                ></textarea>
              </div>
            </div>
          </div>

          <!-- About your travel plan section -->
          <div class="form-section">
            <h2>About your travel plan</h2>
            <div class="form-row">
              <div class="form-group">
                <input
                  type="number"
                  class="form-control"
                  placeholder="For how many days do you want to travel with us? *"
                  name="trip_days"
                  min="1"
                  required
                />
              </div>
              <div class="form-group">
                <input
                  type="number"
                  class="form-control"
                  placeholder="And what's your group size?*"
                  name="group_size"
                  min="1"
                  required
                />
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label style="display: block; margin-bottom: 10px"
                  >Which date are you looking to travel? Select start
                  date*</label
                >
                <input
                  type="date"
                  class="form-control"
                  placeholder="dd/mm/yyyy"
                  name="travel_date"
                  required
                />
              </div>
              
            </div>

            <div class="form-row">
              <div class="form-group" style="flex: 1 1 100%">
                <textarea
                  class="form-control"
                  name="budget_notes"
                  placeholder="Share more about (in)flexible dates and about your budget, so that we know within which framework we can work our magic "
                ></textarea>
              </div>
            </div>
          </div>

        
          <!-- Ready to get in touch section -->
          <div class="form-section">
            <h2>Ready to get in touch?</h2>

            <div class="checkbox-group" style="margin: 20px 0">
              <div class="checkbox-container" style="flex: 1 1 100%">
                <input type="checkbox" id="data-agreement" required />
                <label for="data-agreement"
                  >I agree that VirungaTravel saves and processes my personal
                  data*</label
                >
              </div>
            </div>

            <div class="submit-section">
              <button type="submit" class="btn-submit">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    </div>

    <div class="newsletter-section">
      <div class="newsletter-content">
        <h2>Experience a Virunga Massif Eco Adventure</h2>
        <p>
          Ready to embark on your own cultural immersion journey in the Virunga
          region? Our expert guides will connect you with authentic homestay
          experiences tailored to your interests, comfort level, and travel
          style.
        </p>
      </div>
    </div>
    
   <?php include 'includes/footer.php'; ?>
  </body>
</html>
