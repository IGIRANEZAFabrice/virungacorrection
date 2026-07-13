// Display and hide modal functions
function showModal(sectionId) {
  const modal = document.getElementById("editModal");
  const sectionNameSpan = document.getElementById("sectionName");

  // Set the section name in the modal
  switch (sectionId) {
    case "hero":
      sectionNameSpan.textContent = "Hero Section";
      document.getElementById("sectionTitle").value = "About virunga ecotours";
      document.getElementById("sectionContent").value = "";
      break;
    case "intro":
      sectionNameSpan.textContent = "Introduction";
      document.getElementById("sectionTitle").value = "";
      document.getElementById("sectionContent").value =
        "Welcome to Virunga Ecotours, your gateway to an extraordinary eco-adventure! Immerse yourself in nature as you explore stunning landscapes and vibrant wildlife through sustainable tourism. Trek through lush rainforests and observe animals in their natural habitats, all while we prioritize environmental responsibility. Our tours not only allow you to connect with the beauty of biodiversity but also support local communities and conservation efforts committed to protecting our planet. Embark on a meaningful journey that fosters appreciation for nature with Virunga Ecotours where eco adventure meets ecological harmony!";
      break;
    case "why-us":
      sectionNameSpan.textContent = "Why Us";
      document.getElementById("sectionTitle").value = "Why us";
      document.getElementById("sectionContent").value =
        "Sustainable Ecotourism: Virunga Ecotours champions sustainable ecotourism, focusing on enriching travel experiences that deepen connections with nature, communities, and cultural diversity.\n\nLocal Impact: We prioritize hiring local guides and porters, ensuring our tours benefit the local economy and promote cultural understanding.\n\nSpecialized Experience: Operating across the Virunga Massif (Rwanda, DRCongo, and Uganda), we offer sustainable, tailor-made community activities, adventure, and wellness tours.\n\nPremium Adventures: We provide high-quality, land-based adventures, including luxury gorilla trekking for small groups (2-8 people).";
      break;
    // Add similar cases for other sections
    case "philosophy":
      sectionNameSpan.textContent = "Our Philosophy";
      document.getElementById("sectionTitle").value = "Our Philosophy";
      document.getElementById("sectionContent").value =
        "Virunga Ecotours promotes a mindful, slow travel philosophy aimed at fostering deep engagement with local cultures and communities. Our mission centers on enhancing community-based tourism by empowering local economies and facilitating cultural exchanges while maintaining environmental integrity. We focus on creating sustainable, meaningful travel experiences that benefit both travelers and local residents through collaborations with local businesses and an emphasis on fair trade. By prioritizing enriching journeys over mere cost-competitiveness, we ensure exceptional service that encourages personal development and drives positive societal change. Join us to embark on a journey that leaves a lasting impact.";
      break;
    case "history":
      sectionNameSpan.textContent = "How It Started";
      document.getElementById("sectionTitle").value = "How It Started";
      document.getElementById("sectionContent").value =
        "Virunga Ecotours was founded in 2017 to promote sustainable tourism within the Virunga Massif, which encompasses Rwanda, Uganda, and the Democratic Republic of Congo. This initiative focuses on harmonizing conservation efforts with the economic development of local communities. It attracts visitors through distinctive experiences such as gorilla trekking, a significant draw due to the presence of mountain gorillas. The revenue generated supports crucial conservation initiatives, park management, and community projects. Additionally, Virunga Ecotours aims to raise awareness about park-related threats like poaching and habitat destruction and encourages responsible tourism practices. By engaging local communities, especially women, in tourism operations, the initiative fosters job creation and empowers residents to actively safeguard their natural environment, thus integrating conservation and community empowerment for a sustainable future.";
      break;
    default:
      sectionNameSpan.textContent = "Section";
  }

  modal.style.display = "flex";
}

function hideModal() {
  document.getElementById("editModal").style.display = "none";
}

// Show success alert
function showSuccessAlert() {
  const alert = document.getElementById("successAlert");
  alert.style.display = "block";

  // Hide after 3 seconds
  setTimeout(() => {
    alert.style.display = "none";
  }, 3000);
}

// Helper to render alert
function showAlert(type, message) {
  const alertContainer = document.getElementById('alertContainer');
  alertContainer.innerHTML = `
    <div class="alert alert-${type}" style="margin-bottom:10px;">
      <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}
    </div>
  `;
  setTimeout(() => { alertContainer.innerHTML = ''; }, 4000);
}

// Render all sections
function renderSections(sections) {
  const container = document.getElementById('sectionsContainer');
  container.innerHTML = '';
  sections.forEach(section => {
    const card = document.createElement('div');
    card.className = `section-card ${section.is_new ? 'new-section' : (section.is_updated ? 'updated-section' : '')}`;
    card.innerHTML = `
      <div class="section-card-header">
        <h2 class="section-title">
          <i class="${section.icon_class}"></i> ${section.section_title}
        </h2>
        <div class="section-actions">
          <i class="fas fa-edit action-icon edit-section"
            data-section='${JSON.stringify(section)}'></i>
          <i class="fas fa-trash action-icon delete-section" data-section-id="${section.id}"></i>
        </div>
      </div>
      ${section.image_path ? `
        <div class="section-media">
          <img src="../../${section.image_path.replace(/^\//, '')}" alt="${section.section_title}" />
        </div>
      ` : ''}
      <div class="section-preview">
        ${section.content.length > 200 ? section.content.substring(0, 200) + '...' : section.content}
      </div>
      <div class="section-stats">
        <span class="stat"><i class="fas fa-clock"></i> Last edited: ${section.last_updated_human || ''}</span>
        <span class="stat"><i class="fas fa-user"></i> ${section.updated_by || ''}</span>
      </div>
    `;
    container.appendChild(card);
  });
}

// Fetch and render sections
function fetchSections() {
  fetch('aboutusHandler.php')
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        renderSections(data.sections);
      }
    });
}

// Open edit modal and fill form
function openEditModal(section) {
  document.getElementById('editModal').style.display = 'flex';
  document.getElementById('sectionName').textContent = section.section_name || '';
  document.getElementById('sectionId').value = section.id;
  document.getElementById('sectionTitle').value = section.section_title;
  document.getElementById('sectionContent').value = section.content;
  document.getElementById('currentImagePath').value = section.image_path || '';
  // Reset file input
  document.getElementById('sectionImage').value = '';
  // Image preview
  const imagePreview = document.getElementById('imagePreview');
  if (section.image_path) {
    imagePreview.style.display = 'block';
    imagePreview.querySelector('img').src = '../../' + section.image_path.replace(/^\//, '');
  } else {
    imagePreview.style.display = 'none';
    imagePreview.querySelector('img').src = '';
  }
}

// Handle edit form submit
document.getElementById('editForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  fetch('aboutusHandler.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    showAlert(data.success ? 'success' : 'danger', data.message);
    if (data.success) {
      document.getElementById('editModal').style.display = 'none';
      window.location.reload();
    }
  });
});

// Handle add form submit
document.getElementById('addForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  fetch('aboutusHandler.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    showAlert(data.success ? 'success' : 'danger', data.message);
    if (data.success) {
      document.getElementById('addModal').style.display = 'none';
      window.location.reload();
    }
  });
});

// Handle delete form submit
document.getElementById('deleteForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  fetch('aboutusHandler.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    showAlert(data.success ? 'success' : 'danger', data.message);
    if (data.success) {
      document.getElementById('deleteModal').style.display = 'none';
      window.location.reload();
    }
  });
});

document.addEventListener('DOMContentLoaded', function() {
  // Add event listeners for edit buttons
  document.querySelectorAll('.edit-section').forEach(btn => {
    btn.addEventListener('click', function() {
      const section = JSON.parse(this.getAttribute('data-section'));
      openEditModal(section);
    });
  });

  // Add event listeners for delete buttons
  document.querySelectorAll('.delete-section').forEach(btn => {
    btn.addEventListener('click', function() {
      const sectionId = this.getAttribute('data-section-id');
      document.getElementById('deleteSectionId').value = sectionId;
      document.getElementById('deleteModal').style.display = 'flex';
    });
  });

  // Add new section button
  document.getElementById('addNewCardBtn').addEventListener('click', function() {
    document.getElementById('addModal').style.display = 'flex';
  });

  // Close modals
  document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('editModal').style.display = 'none';
  });

  document.getElementById('closeAddModal').addEventListener('click', function() {
    document.getElementById('addModal').style.display = 'none';
  });

  document.getElementById('closeDeleteModal').addEventListener('click', function() {
    document.getElementById('deleteModal').style.display = 'none';
  });

  // Cancel buttons
  document.getElementById('cancelEdit').addEventListener('click', function() {
    document.getElementById('editModal').style.display = 'none';
  });

  document.getElementById('cancelAdd').addEventListener('click', function() {
    document.getElementById('addModal').style.display = 'none';
  });

  document.getElementById('cancelDelete').addEventListener('click', function() {
    document.getElementById('deleteModal').style.display = 'none';
  });

  // Image upload handlers
  document.getElementById('newImageUploader').addEventListener('click', function() {
    document.getElementById('newSectionImage').click();
  });

  document.getElementById('newSectionImage').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('newImagePreview').style.display = 'block';
        document.getElementById('newImagePreview').querySelector('img').src = e.target.result;
      }
      reader.readAsDataURL(e.target.files[0]);
    }
  });

  document.getElementById('imageUploader').addEventListener('click', function() {
    document.getElementById('sectionImage').click();
  });

  document.getElementById('sectionImage').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const imagePreview = document.getElementById('imagePreview');
        imagePreview.style.display = 'block';
        imagePreview.querySelector('img').src = e.target.result;
      }
      reader.readAsDataURL(e.target.files[0]);
    }
  });
});