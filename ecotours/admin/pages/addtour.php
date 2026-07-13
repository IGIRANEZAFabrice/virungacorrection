<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Tour</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../css/addtour.css">
  <script src="../js/tours.js" defer></script>
</head>
<body>
  <div class="container">
    <h2><i class="fas fa-globe-americas"></i> Add New Tour</h2>
    
    <form id="tourForm" method="POST" action="process_tour.php" enctype="multipart/form-data">
      <div class="form-row">
        <div class="form-col">
          <div class="form-group">
            <label for="tourTitle"><i class="fas fa-heading"></i> Tour Title</label>
            <input type="text" id="tourTitle" name="tourTitle" required />
          </div>
        </div>
        <div class="form-col">
          <div class="form-group">
            <label for="tourCountry"><i class="fas fa-flag"></i> Country</label>
            <select id="tourCountry" name="tourCountry" required>
              <option value="rwanda">All</option>
              <option value="rwanda">Rwanda</option>
              <option value="uganda">Uganda</option>
              <option value="congo">DR Congo</option>
              <option value="burundi">Burundi</option>
            </select>
          </div>
        </div>
        <div class="form-col">
          <div class="form-group">
            <label for="tourCategory"><i class="fas fa-tags"></i> Category</label>
            <select id="tourCategory" name="tourCategory" required>
              <option value="">Select a category</option>
              <option value="Adventure">Adventure</option>
              <option value="Cultural">Cultural</option>
              <option value="City Tours">City Tours</option>
              <option value="Community Based Experience">Community Based Experience</option>
              <option value="Family Friendly">Family Friendly</option>
              <option value="Food & Culinary">Food & Culinary</option>
              <option value="Gastronomy">Gastronomy</option>
              <option value="Highlights">Highlights</option>
              <option value="Nature">Nature</option>
              <option value="Off the beaten Path">Off the beaten Path</option>
              <option value="Historical">Historical</option>
              <option value="Spiritual">Spiritual</option>
            </select>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-col">
          <div class="form-group">
            <label for="tourDays"><i class="fas fa-calendar-day"></i> Number of Days</label>
            <input type="number" id="tourDays" name="tourDays" min="1" required />
          </div>
        </div>
      </div>
      
      <div class="form-row">
        <div class="form-col">
          <div class="form-group image-upload">
            <label for="coverImage"><i class="fas fa-image"></i> Cover Image</label>
            <div class="image-preview" id="coverPreview" style="background-size: cover; background-position: center;">
              <i class="fas fa-camera"></i> Cover Image Preview
            </div>
            <input type="file" id="coverImage" name="coverImage" accept="image/*" required />
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="tourDesc"><i class="fas fa-align-left"></i> Short Description</label>
        <textarea
          id="tourDesc"
          name="tourDesc"
          placeholder="Enter a brief description of the tour"
          required
        ></textarea>
      </div>

      <h3 class="section-title"><i class="fas fa-star"></i> Highlight Images</h3>
      <div class="highlight-images">
        <div class="form-group image-upload">
          <div class="image-preview highlight-image" id="highlight1Preview">
            <i class="fas fa-camera"></i> Highlight 1
          </div>
          <input type="file" id="highlight1" name="highlight1" accept="image/*" />
        </div>
        <div class="form-group image-upload">
          <div class="image-preview highlight-image" id="highlight2Preview">
            <i class="fas fa-camera"></i> Highlight 2
          </div>
          <input type="file" id="highlight2" name="highlight2" accept="image/*" />
        </div>
        <div class="form-group image-upload">
          <div class="image-preview highlight-image" id="highlight3Preview">
            <i class="fas fa-camera"></i> Highlight 3
          </div>
          <input type="file" id="highlight3" name="highlight3" accept="image/*" />
        </div>
        <div class="form-group image-upload">
          <div class="image-preview highlight-image" id="highlight4Preview">
            <i class="fas fa-camera"></i> Highlight 4
          </div>
          <input type="file" id="highlight4" name="highlight4" accept="image/*" />
        </div>
      </div>

      <h3 class="section-title"><i class="fas fa-route"></i> Tour Itinerary</h3>
      <div id="activitiesContainer">
        <div class="day-container" data-activity="1">
          <h4 class="day-title"><i class="fas fa-map-marker-alt"></i> Activity 1</h4>
          <div class="form-group">
            <label for="activity1Title">Activity Title</label>
            <input type="text" id="activity1Title" name="activities[1][title]" class="activity-title" placeholder="Enter title for activity" required>
          </div>
          <div class="form-group">
            <label for="activity1Desc">Activity Description</label>
            <textarea id="activity1Desc" name="activities[1][desc]" class="activity-desc" placeholder="Describe the activity" required></textarea>
          </div>
        </div>
      </div>
      <button type="button" class="btn add-btn" id="addActivityBtn">
        <span>+</span> Add More Activities
      </button>

      <h3 class="section-title"><i class="fas fa-check-circle"></i> What's Included</h3>
      <div class="list-container" id="includedList">
        <div class="list-item">
          <input type="text" name="included[]" placeholder="Enter included item" />
          <button type="button" class="btn remove-btn">
            <i class="fas fa-trash"></i> Remove
          </button>
        </div>
      </div>
      <button type="button" class="btn add-btn" id="addIncludedBtn">
        <span>+</span> Add More
      </button>

      <h3 class="section-title"><i class="fas fa-times-circle"></i> What's Excluded</h3>
      <div class="list-container" id="excludedList">
        <div class="list-item">
          <input type="text" name="excluded[]" placeholder="Enter excluded item" />
          <button type="button" class="btn remove-btn">
            <i class="fas fa-trash"></i> Remove
          </button>
        </div>
      </div>
      <button type="button" class="btn add-btn" id="addExcludedBtn">
        <span>+</span> Add More
      </button>

      <h3 class="section-title"><i class="fas fa-suitcase"></i> What to Bring</h3>
      <div class="list-container" id="bringList">
        <div class="list-item">
          <input type="text" name="bring[]" placeholder="Enter item to bring" />
          <button type="button" class="btn remove-btn">
            <i class="fas fa-trash"></i> Remove
          </button>
        </div>
      </div>
      <button type="button" class="btn add-btn" id="addBringBtn">
        <span>+</span> Add More
      </button>

      <h3 class="section-title"><i class="fas fa-question-circle"></i> Why Attend</h3>
      <textarea
        id="whyAttend"
        name="whyAttend"
        placeholder="Enter reasons why people should attend this tour"
      ></textarea>

      <h3 class="section-title"><i class="fas fa-money-bill-wave"></i> Pricing Tiers</h3>
      <div class="list-container" id="pricingTiersList">
        <div class="list-item pricing-tier">
          <input type="text" placeholder="Group Size (e.g. 1-2 people)" class="tier-group" required />
          <input type="number" step="0.01" placeholder="Price per person" class="tier-price" required />
          <button type="button" class="btn remove-btn">
            <i class="fas fa-trash"></i> Remove
          </button>
        </div>
      </div>
      <button type="button" class="btn add-btn" id="addPricingBtn">
        <span>+</span> Add More Tiers
      </button>

      <h3 class="section-title"><i class="fas fa-sticky-note"></i> Pricing Notes</h3>
      <div class="list-container" id="pricingNotesList">
        <div class="list-item">
          <input type="text" name="pricing_notes[]" placeholder="Enter pricing note" class="pricing-note" />
          <button type="button" class="btn remove-btn">
            <i class="fas fa-trash"></i> Remove
          </button>
        </div>
      </div>
      <button type="button" class="btn add-btn" id="addNoteBtn">
        <span>+</span> Add More Notes
      </button>

      <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> Create Tour</button>
    </form>
  </div>
</body>
</html>