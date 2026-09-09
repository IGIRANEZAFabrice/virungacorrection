/**
 * ============================================================================
 * Virunga Ecotours - Tours Management JavaScript
 * Handles Modern Multi-Step Modals, Dynamic Form Builders, Quick View,
 * AJAX CRUD, File Upload Dropzones, and Toast Notifications
 * ============================================================================
 */

// Step Tabs Configuration
const FORM_TABS = ['tab-basics', 'tab-itinerary', 'tab-media', 'tab-inclusions', 'tab-pricing'];
let currentTabIndex = 0;

/* ==========================================================================
   Toast Notification System
   ========================================================================== */
function showToast(message, type = 'success') {
  const container = document.getElementById('toastContainer');
  if (!container) return;

  const iconClass = type === 'success' ? 'fa-circle-check' : (type === 'error' ? 'fa-circle-exclamation' : 'fa-circle-info');
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.innerHTML = `
    <div class="toast-icon"><i class="fas ${iconClass}"></i></div>
    <div class="toast-message">${escapeHTML(message)}</div>
    <button type="button" class="toast-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    <div class="toast-progress"><div class="toast-progress-bar"></div></div>
  `;

  container.appendChild(toast);

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(100%)';
    setTimeout(() => toast.remove(), 350);
  }, 4000);
}

function escapeHTML(str) {
  return String(str || '').replace(/[&<>"']/g, (m) => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  }[m]));
}

/* ==========================================================================
   Modal Open & Close Helpers
   ========================================================================== */
function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (!modal) return;
  modal.style.display = 'flex';
  setTimeout(() => {
    modal.classList.add('show');
    modal.setAttribute('aria-hidden', 'false');
  }, 10);
  document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (!modal) return;
  modal.classList.remove('show');
  modal.setAttribute('aria-hidden', 'true');
  setTimeout(() => {
    modal.style.display = 'none';
    if (!document.querySelector('.modal-backdrop.show')) {
      document.body.style.overflow = '';
    }
  }, 250);
}

function closeTourModal() {
  closeModal('tourFormModal');
}

function closeQuickViewModal() {
  closeModal('quickViewModal');
}

function closeDeleteModal() {
  closeModal('deleteModal');
}

function closeLightbox() {
  const lb = document.getElementById('lightboxModal');
  if (lb) lb.classList.remove('show');
}

function openLightbox(src, caption = '') {
  const lb = document.getElementById('lightboxModal');
  const img = document.getElementById('lightboxImg');
  const cap = document.getElementById('lightboxCaption');
  if (!lb || !img) return;

  img.src = src;
  if (cap) cap.textContent = caption;
  lb.classList.add('show');
}

/* ==========================================================================
   Stepper Navigation
   ========================================================================== */
function switchStepTab(targetTabId) {
  const index = FORM_TABS.indexOf(targetTabId);
  if (index === -1) return;

  currentTabIndex = index;

  // Update tabs content
  document.querySelectorAll('#tourFormModal .tab-pane').forEach((pane) => {
    pane.classList.remove('active');
  });
  const activePane = document.getElementById(targetTabId);
  if (activePane) activePane.classList.add('active');

  // Update stepper buttons
  document.querySelectorAll('#formStepper .step-btn').forEach((btn, i) => {
    btn.classList.toggle('active', i === currentTabIndex);
    btn.classList.toggle('completed', i < currentTabIndex);
  });

  // Footer navigation buttons
  const btnPrev = document.getElementById('btnPrevStep');
  const btnNext = document.getElementById('btnNextStep');
  const btnSubmit = document.getElementById('btnSubmitForm');

  if (btnPrev) btnPrev.style.display = currentTabIndex > 0 ? 'inline-flex' : 'none';
  if (btnNext) btnNext.style.display = currentTabIndex < FORM_TABS.length - 1 ? 'inline-flex' : 'none';
  if (btnSubmit) btnSubmit.style.display = currentTabIndex === FORM_TABS.length - 1 ? 'inline-flex' : 'none';
}

function validateCurrentTab() {
  const currentPane = document.getElementById(FORM_TABS[currentTabIndex]);
  if (!currentPane) return true;

  const requiredInputs = currentPane.querySelectorAll('input[required], select[required], textarea[required]');
  let isValid = true;

  requiredInputs.forEach((input) => {
    if (!input.value.trim()) {
      input.style.borderColor = 'var(--tour-accent-rose)';
      isValid = false;
    } else {
      input.style.borderColor = '';
    }
  });

  if (!isValid) {
    showToast('Please fill out all required fields before continuing.', 'error');
  }

  return isValid;
}

/* ==========================================================================
   Add / Edit Modal Initialization & Reset
   ========================================================================== */
function resetFormToAddMode() {
  const form = document.getElementById('tourForm');
  if (!form) return;

  form.reset();
  document.getElementById('formTourId').value = '';

  document.getElementById('modalFormTitle').textContent = 'Add New Tour Package';
  document.getElementById('submitBtnLabel').textContent = 'Create Tour Package';

  // Reset previews
  const coverPreview = document.getElementById('coverPreview');
  if (coverPreview) {
    coverPreview.innerHTML = `
      <div class="dropzone-empty-prompt">
        <i class="fas fa-cloud-arrow-up"></i>
        <h5>Click or Drag Cover Image Here</h5>
        <p>Recommended: 1920x1080px (JPG, PNG, WebP up to 10MB)</p>
      </div>
    `;
  }

  for (let i = 1; i <= 4; i++) {
    const hp = document.getElementById(`highlight${i}Preview`);
    if (hp) {
      hp.innerHTML = `<i class="fas fa-image"></i><span>Highlight ${i}</span>`;
    }
  }

  // Reset itinerary to 1 empty activity
  const daysContainer = document.getElementById('daysContainer');
  if (daysContainer) {
    daysContainer.innerHTML = `
      <div class="day-card-item">
        <div class="day-card-header">
          <div class="day-num-badge"><i class="fas fa-map-pin"></i> Activity 1</div>
          <button type="button" class="btn-remove-day" title="Remove Activity"><i class="fas fa-trash-can"></i></button>
        </div>
        <div class="form-group">
          <label class="form-label">Activity Title</label>
          <input type="text" class="activity-title" placeholder="e.g. Arrival in Kigali & Scenic Transfer to Musanze" required />
        </div>
        <div class="form-group">
          <label class="form-label">Activity Description</label>
          <textarea class="activity-desc" rows="3" placeholder="Describe the day's schedule, meals, and accommodations..." required></textarea>
        </div>
      </div>
    `;
  }

  // Reset list builders
  resetListItems('includedList', 'e.g. Gorilla Trekking Permit');
  resetListItems('excludedList', 'e.g. International Airfare');
  resetListItems('bringList', 'e.g. Waterproof Hiking Boots');

  // Reset Pricing Tiers
  const pricingList = document.getElementById('pricingTiersList');
  if (pricingList) {
    pricingList.innerHTML = `
      <div class="pricing-tier-row">
        <div class="tier-col-group">
          <label>Group Size</label>
          <input type="text" class="tier-group" placeholder="e.g. 1 Person (Solo)" value="1 Person" required />
        </div>
        <div class="tier-col-price">
          <label>Price (USD)</label>
          <div class="input-dollar">
            <span>$</span>
            <input type="number" step="0.01" class="tier-price" placeholder="1500.00" required />
          </div>
        </div>
        <button type="button" class="btn-remove-tier"><i class="fas fa-trash-can"></i></button>
      </div>
    `;
  }

  // Reset Pricing Notes
  const notesList = document.getElementById('pricingNotesList');
  if (notesList) {
    notesList.innerHTML = `
      <div class="pricing-note-row">
        <input type="text" class="pricing-note" placeholder="e.g. Prices are subject to park permit availability." />
        <button type="button" class="btn-remove-row"><i class="fas fa-times"></i></button>
      </div>
    `;
  }

  switchStepTab('tab-basics');
}

function resetListItems(listId, placeholder) {
  const list = document.getElementById(listId);
  if (!list) return;
  list.innerHTML = `
    <div class="dynamic-item-row">
      <input type="text" placeholder="${placeholder}" />
      <button type="button" class="btn-remove-row"><i class="fas fa-times"></i></button>
    </div>
  `;
}

function openAddTourModal() {
  resetFormToAddMode();
  openModal('tourFormModal');
}

/* ==========================================================================
   Edit Tour (Fetch & Populate)
   ========================================================================== */
function editTour(tourId) {
  if (!tourId) return;

  // Show loading indicator on trigger buttons
  const triggerBtns = document.querySelectorAll(`[data-id="${tourId}"] .btn-edit, [data-id="${tourId}"] .btn-card-edit`);
  triggerBtns.forEach((btn) => (btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'));

  fetch(`?fetch_tour=1&id=${tourId}`)
    .then((r) => r.json())
    .then((res) => {
      triggerBtns.forEach((btn) => (btn.innerHTML = '<i class="fas fa-pen-to-square"></i>'));

      if (!res.success) {
        showToast(res.message || 'Could not load tour details.', 'error');
        return;
      }

      const tour = res.data;
      resetFormToAddMode();

      document.getElementById('formTourId').value = tour.tour_id;
      document.getElementById('modalFormTitle').textContent = `Edit Tour: ${tour.title}`;
      document.getElementById('submitBtnLabel').textContent = 'Update Tour Package';

      // Step 1: Basics
      document.getElementById('tourTitle').value = tour.title || '';
      document.getElementById('tourCountry').value = (tour.country || '').toLowerCase();
      document.getElementById('tourDays').value = tour.days_count || 1;
      document.getElementById('tourDesc').value = tour.short_description || '';
      document.getElementById('whyAttend').value = tour.why_attend || '';

      // Set category or add option if custom
      const catSelect = document.getElementById('tourCategory');
      let catFound = false;
      for (let opt of catSelect.options) {
        if (opt.value === tour.category) {
          opt.selected = true;
          catFound = true;
          break;
        }
      }
      if (!catFound && tour.category) {
        const newOpt = new Option(tour.category, tour.category, true, true);
        catSelect.insertBefore(newOpt, catSelect.querySelector('option[value="add_new"]'));
      }

      // Step 2: Itinerary
      const daysContainer = document.getElementById('daysContainer');
      if (daysContainer && tour.days && tour.days.length > 0) {
        daysContainer.innerHTML = '';
        tour.days.forEach((d, idx) => {
          const item = document.createElement('div');
          item.className = 'day-card-item';
          item.innerHTML = `
            <div class="day-card-header">
              <div class="day-num-badge"><i class="fas fa-map-pin"></i> Activity ${idx + 1}</div>
              <button type="button" class="btn-remove-day" title="Remove Activity"><i class="fas fa-trash-can"></i></button>
            </div>
            <div class="form-group">
              <label class="form-label">Activity Title</label>
              <input type="text" class="activity-title" value="${escapeHTML(d.day_title)}" required />
            </div>
            <div class="form-group">
              <label class="form-label">Activity Description</label>
              <textarea class="activity-desc" rows="3" required>${escapeHTML(d.day_description)}</textarea>
            </div>
          `;
          daysContainer.appendChild(item);
        });
      }

      // Step 3: Media
      if (tour.cover_image_path) {
        const cp = document.getElementById('coverPreview');
        if (cp) {
          cp.innerHTML = `<img src="../../${escapeHTML(tour.cover_image_path)}" alt="Cover Image">`;
        }
      }

      if (tour.highlights && tour.highlights.length > 0) {
        tour.highlights.forEach((h) => {
          const hp = document.getElementById(`highlight${h.display_order}Preview`);
          if (hp && h.image_path) {
            hp.innerHTML = `<img src="../../${escapeHTML(h.image_path)}" alt="Highlight ${h.display_order}">`;
          }
        });
      }

      // Step 4: Inclusions / Exclusions / Bring
      const populateList = (listId, items, key, placeholder) => {
        const list = document.getElementById(listId);
        if (!list) return;
        list.innerHTML = '';
        if (items && items.length > 0) {
          items.forEach((item) => {
            const val = item[key] || '';
            const row = document.createElement('div');
            row.className = 'dynamic-item-row';
            row.innerHTML = `
              <input type="text" value="${escapeHTML(val)}" placeholder="${placeholder}" />
              <button type="button" class="btn-remove-row"><i class="fas fa-times"></i></button>
            `;
            list.appendChild(row);
          });
        } else {
          resetListItems(listId, placeholder);
        }
      };

      populateList('includedList', tour.included, 'item_description', 'e.g. Gorilla Trekking Permit');
      populateList('excludedList', tour.excluded, 'item_description', 'e.g. International Airfare');
      populateList('bringList', tour.to_bring, 'item_description', 'e.g. Waterproof Hiking Boots');

      // Step 5: Pricing Tiers & Notes
      const pricingList = document.getElementById('pricingTiersList');
      if (pricingList) {
        pricingList.innerHTML = '';
        if (tour.pricing_tiers && tour.pricing_tiers.length > 0) {
          tour.pricing_tiers.forEach((t) => {
            const row = document.createElement('div');
            row.className = 'pricing-tier-row';
            row.innerHTML = `
              <div class="tier-col-group">
                <label>Group Size</label>
                <input type="text" class="tier-group" value="${escapeHTML(t.group_size)}" required />
              </div>
              <div class="tier-col-price">
                <label>Price (USD)</label>
                <div class="input-dollar">
                  <span>$</span>
                  <input type="number" step="0.01" class="tier-price" value="${escapeHTML(t.price_per_person)}" required />
                </div>
              </div>
              <button type="button" class="btn-remove-tier"><i class="fas fa-trash-can"></i></button>
            `;
            pricingList.appendChild(row);
          });
        } else {
          pricingList.innerHTML = `
            <div class="pricing-tier-row">
              <div class="tier-col-group">
                <label>Group Size</label>
                <input type="text" class="tier-group" placeholder="e.g. 1 Person (Solo)" value="1 Person" required />
              </div>
              <div class="tier-col-price">
                <label>Price (USD)</label>
                <div class="input-dollar">
                  <span>$</span>
                  <input type="number" step="0.01" class="tier-price" placeholder="1500.00" required />
                </div>
              </div>
              <button type="button" class="btn-remove-tier"><i class="fas fa-trash-can"></i></button>
            </div>
          `;
        }
      }

      const notesList = document.getElementById('pricingNotesList');
      if (notesList) {
        notesList.innerHTML = '';
        if (tour.pricing_notes && tour.pricing_notes.length > 0) {
          tour.pricing_notes.forEach((n) => {
            const row = document.createElement('div');
            row.className = 'pricing-note-row';
            row.innerHTML = `
              <input type="text" class="pricing-note" value="${escapeHTML(n.note)}" />
              <button type="button" class="btn-remove-row"><i class="fas fa-times"></i></button>
            `;
            notesList.appendChild(row);
          });
        } else {
          notesList.innerHTML = `
            <div class="pricing-note-row">
              <input type="text" class="pricing-note" placeholder="e.g. Prices are subject to park permit availability." />
              <button type="button" class="btn-remove-row"><i class="fas fa-times"></i></button>
            </div>
          `;
        }
      }

      switchStepTab('tab-basics');
      openModal('tourFormModal');
    })
    .catch((err) => {
      triggerBtns.forEach((btn) => (btn.innerHTML = '<i class="fas fa-pen-to-square"></i>'));
      console.error(err);
      showToast('An unexpected network error occurred.', 'error');
    });
}

/* ==========================================================================
   Quick View Modal (Preview)
   ========================================================================== */
function openPreviewModal(tourId) {
  if (!tourId) return;

  fetch(`?fetch_tour=1&id=${tourId}`)
    .then((r) => r.json())
    .then((res) => {
      if (!res.success) {
        showToast(res.message || 'Could not load tour preview.', 'error');
        return;
      }

      const tour = res.data;

      // Hero
      const qvHero = document.getElementById('qvHero');
      const coverUrl = tour.cover_image_path ? `../../${tour.cover_image_path}` : '../../images/default-tour.jpg';
      if (qvHero) qvHero.style.backgroundImage = `url("${coverUrl}")`;

      document.getElementById('qvTitle').textContent = tour.title;
      document.getElementById('qvCategoryBadge').innerHTML = `<i class="fas fa-tag"></i> ${escapeHTML(tour.category)}`;
      document.getElementById('qvCountryBadge').innerHTML = `<i class="fas fa-globe"></i> ${escapeHTML(tour.country ? tour.country.toUpperCase() : '')}`;
      document.getElementById('qvDurationBadge').innerHTML = `<i class="fas fa-clock"></i> ${tour.days_count} ${tour.days_count == 1 ? 'Day' : 'Days'}`;

      // Overview
      document.getElementById('qvSummary').textContent = tour.short_description || 'No description provided.';
      const whyWrap = document.getElementById('qvWhyAttendWrap');
      const whyBox = document.getElementById('qvWhyAttend');
      if (tour.why_attend && tour.why_attend.trim()) {
        whyWrap.style.display = 'block';
        whyBox.textContent = tour.why_attend;
      } else {
        whyWrap.style.display = 'none';
      }

      // Itinerary Timeline
      const timeline = document.getElementById('qvItineraryTimeline');
      timeline.innerHTML = '';
      if (tour.days && tour.days.length > 0) {
        tour.days.forEach((d, idx) => {
          const item = document.createElement('div');
          item.className = 'qv-timeline-item';
          item.innerHTML = `
            <div class="qv-timeline-dot"></div>
            <div class="qv-timeline-day">Activity ${idx + 1}</div>
            <h4 class="qv-timeline-title">${escapeHTML(d.day_title)}</h4>
            <p class="qv-timeline-desc">${escapeHTML(d.day_description)}</p>
          `;
          timeline.appendChild(item);
        });
      } else {
        timeline.innerHTML = '<p class="text-muted">No itinerary activities specified for this tour.</p>';
      }

      // Gallery Highlights
      const gallery = document.getElementById('qvGalleryGrid');
      gallery.innerHTML = '';
      if (tour.highlights && tour.highlights.length > 0) {
        tour.highlights.forEach((h) => {
          if (h.image_path) {
            const item = document.createElement('div');
            item.className = 'qv-gallery-item';
            item.innerHTML = `<img src="../../${escapeHTML(h.image_path)}" alt="Highlight Photo" loading="lazy" />`;
            item.onclick = () => openLightbox(`../../${h.image_path}`, `Highlight ${h.display_order} - ${tour.title}`);
            gallery.appendChild(item);
          }
        });
      }
      if (gallery.children.length === 0) {
        gallery.innerHTML = '<p class="text-muted">No highlight photos uploaded yet.</p>';
      }

      // Inclusions / Exclusions / Bring
      const renderBulletList = (elId, items, key) => {
        const el = document.getElementById(elId);
        el.innerHTML = '';
        if (items && items.length > 0) {
          items.forEach((it) => {
            const li = document.createElement('li');
            li.textContent = it[key];
            el.appendChild(li);
          });
        } else {
          el.innerHTML = '<li class="text-muted">None specified</li>';
        }
      };

      renderBulletList('qvIncludedList', tour.included, 'item_description');
      renderBulletList('qvExcludedList', tour.excluded, 'item_description');
      renderBulletList('qvBringList', tour.to_bring, 'item_description');

      // Pricing Tiers
      const tiersContainer = document.getElementById('qvTiersCards');
      tiersContainer.innerHTML = '';
      if (tour.pricing_tiers && tour.pricing_tiers.length > 0) {
        tour.pricing_tiers.forEach((t) => {
          const c = document.createElement('div');
          c.className = 'qv-tier-card';
          c.innerHTML = `
            <div class="qv-tier-size">${escapeHTML(t.group_size)}</div>
            <div class="qv-tier-price">$${parseFloat(t.price_per_person).toLocaleString()} <span>/ person</span></div>
          `;
          tiersContainer.appendChild(c);
        });
      } else {
        tiersContainer.innerHTML = '<p class="text-muted">Inquire for custom group pricing.</p>';
      }

      // Pricing Notes
      const notesContainer = document.getElementById('qvNotesList');
      const notesWrap = document.getElementById('qvNotesWrap');
      notesContainer.innerHTML = '';
      if (tour.pricing_notes && tour.pricing_notes.length > 0) {
        notesWrap.style.display = 'block';
        tour.pricing_notes.forEach((n) => {
          const li = document.createElement('li');
          li.textContent = n.note;
          notesContainer.appendChild(li);
        });
      } else {
        notesWrap.style.display = 'none';
      }

      // Edit Button inside Quick View
      const qvEditBtn = document.getElementById('qvEditBtn');
      if (qvEditBtn) {
        qvEditBtn.onclick = () => {
          closeQuickViewModal();
          setTimeout(() => editTour(tour.tour_id), 150);
        };
      }

      // Reset Quick View sub-tabs
      document.querySelectorAll('.qv-tab-btn').forEach((b, i) => b.classList.toggle('active', i === 0));
      document.querySelectorAll('.qv-tab-pane').forEach((p, i) => p.classList.toggle('active', i === 0));

      openModal('quickViewModal');
    })
    .catch((err) => {
      console.error(err);
      showToast('Could not open tour preview.', 'error');
    });
}

/* ==========================================================================
   Delete Confirmation
   ========================================================================== */
function promptDeleteTour(tourId, tourTitle) {
  document.getElementById('deleteTourId').value = tourId;
  document.getElementById('deleteTourName').textContent = `"${tourTitle}"`;
  openModal('deleteModal');
}

/* ==========================================================================
   DOM Ready Bindings & Event Handlers
   ========================================================================== */
document.addEventListener('DOMContentLoaded', () => {
  // 1. Open Add Tour Modal Button
  const openAddBtn = document.getElementById('openAddTourBtn');
  if (openAddBtn) {
    openAddBtn.addEventListener('click', openAddTourModal);
  }

  // 2. Stepper Tab Click Navigation
  document.querySelectorAll('#formStepper .step-btn').forEach((btn) => {
    btn.addEventListener('click', function () {
      const targetTab = this.getAttribute('data-tab');
      switchStepTab(targetTab);
    });
  });

  // 3. Step Navigation Buttons (Prev / Next)
  const btnNext = document.getElementById('btnNextStep');
  if (btnNext) {
    btnNext.addEventListener('click', () => {
      if (validateCurrentTab()) {
        if (currentTabIndex < FORM_TABS.length - 1) {
          switchStepTab(FORM_TABS[currentTabIndex + 1]);
        }
      }
    });
  }

  const btnPrev = document.getElementById('btnPrevStep');
  if (btnPrev) {
    btnPrev.addEventListener('click', () => {
      if (currentTabIndex > 0) {
        switchStepTab(FORM_TABS[currentTabIndex - 1]);
      }
    });
  }

  // 4. Quick View Tab Switching
  document.querySelectorAll('.qv-tab-btn').forEach((btn) => {
    btn.addEventListener('click', function () {
      const targetPaneId = this.getAttribute('data-qv');
      document.querySelectorAll('.qv-tab-btn').forEach((b) => b.classList.remove('active'));
      document.querySelectorAll('.qv-tab-pane').forEach((p) => p.classList.remove('active'));
      this.classList.add('active');
      const pane = document.getElementById(targetPaneId);
      if (pane) pane.classList.add('active');
    });
  });

  // 5. Dynamic Activity Builder Add / Remove
  const setupActivityAdd = (btnId) => {
    const btn = document.getElementById(btnId);
    if (!btn) return;
    btn.addEventListener('click', () => {
      const container = document.getElementById('daysContainer');
      const count = container.querySelectorAll('.day-card-item').length + 1;
      const item = document.createElement('div');
      item.className = 'day-card-item';
      item.innerHTML = `
        <div class="day-card-header">
          <div class="day-num-badge"><i class="fas fa-map-pin"></i> Activity ${count}</div>
          <button type="button" class="btn-remove-day" title="Remove Activity"><i class="fas fa-trash-can"></i></button>
        </div>
        <div class="form-group">
          <label class="form-label">Activity Title</label>
          <input type="text" class="activity-title" placeholder="Enter activity title" required />
        </div>
        <div class="form-group">
          <label class="form-label">Activity Description</label>
          <textarea class="activity-desc" rows="3" placeholder="Describe the day's schedule..." required></textarea>
        </div>
      `;
      container.appendChild(item);
      item.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
  };

  setupActivityAdd('addActivityBtn');
  setupActivityAdd('addActivityBtnBottom');

  // Remove Activity Delegation
  const daysContainer = document.getElementById('daysContainer');
  if (daysContainer) {
    daysContainer.addEventListener('click', (e) => {
      const remBtn = e.target.closest('.btn-remove-day');
      if (remBtn) {
        const item = remBtn.closest('.day-card-item');
        if (daysContainer.children.length > 1) {
          item.remove();
          daysContainer.querySelectorAll('.day-card-item').forEach((it, idx) => {
            it.querySelector('.day-num-badge').innerHTML = `<i class="fas fa-map-pin"></i> Activity ${idx + 1}`;
          });
        } else {
          item.querySelector('.activity-title').value = '';
          item.querySelector('.activity-desc').value = '';
        }
      }
    });
  }

  // 6. Dynamic Inclusions, Exclusions, Bring Lists
  const setupDynamicList = (addBtnId, listId, placeholder) => {
    const btn = document.getElementById(addBtnId);
    const list = document.getElementById(listId);
    if (!btn || !list) return;

    btn.addEventListener('click', () => {
      const row = document.createElement('div');
      row.className = 'dynamic-item-row';
      row.innerHTML = `
        <input type="text" placeholder="${placeholder}" />
        <button type="button" class="btn-remove-row"><i class="fas fa-times"></i></button>
      `;
      list.appendChild(row);
      row.querySelector('input').focus();
    });

    list.addEventListener('click', (e) => {
      const remBtn = e.target.closest('.btn-remove-row');
      if (remBtn) {
        const row = remBtn.closest('.dynamic-item-row');
        if (list.children.length > 1) {
          row.remove();
        } else {
          row.querySelector('input').value = '';
        }
      }
    });
  };

  setupDynamicList('addIncludedBtn', 'includedList', 'e.g. Gorilla Trekking Permit');
  setupDynamicList('addExcludedBtn', 'excludedList', 'e.g. International Airfare');
  setupDynamicList('addBringBtn', 'bringList', 'e.g. Waterproof Hiking Boots');

  // 7. Dynamic Pricing Tiers
  const addPricingBtn = document.getElementById('addPricingBtn');
  const pricingList = document.getElementById('pricingTiersList');
  if (addPricingBtn && pricingList) {
    addPricingBtn.addEventListener('click', () => {
      const row = document.createElement('div');
      row.className = 'pricing-tier-row';
      row.innerHTML = `
        <div class="tier-col-group">
          <label>Group Size</label>
          <input type="text" class="tier-group" placeholder="e.g. 2-3 People" required />
        </div>
        <div class="tier-col-price">
          <label>Price (USD)</label>
          <div class="input-dollar">
            <span>$</span>
            <input type="number" step="0.01" class="tier-price" placeholder="1200.00" required />
          </div>
        </div>
        <button type="button" class="btn-remove-tier"><i class="fas fa-trash-can"></i></button>
      `;
      pricingList.appendChild(row);
    });

    pricingList.addEventListener('click', (e) => {
      const remBtn = e.target.closest('.btn-remove-tier');
      if (remBtn) {
        const row = remBtn.closest('.pricing-tier-row');
        if (pricingList.children.length > 1) {
          row.remove();
        } else {
          row.querySelector('.tier-group').value = '';
          row.querySelector('.tier-price').value = '';
        }
      }
    });
  }

  // 8. Dynamic Pricing Notes
  const addPricingNoteBtn = document.getElementById('addPricingNoteBtn');
  const notesList = document.getElementById('pricingNotesList');
  if (addPricingNoteBtn && notesList) {
    addPricingNoteBtn.addEventListener('click', () => {
      const row = document.createElement('div');
      row.className = 'pricing-note-row';
      row.innerHTML = `
        <input type="text" class="pricing-note" placeholder="Enter pricing note..." />
        <button type="button" class="btn-remove-row"><i class="fas fa-times"></i></button>
      `;
      notesList.appendChild(row);
      row.querySelector('input').focus();
    });

    notesList.addEventListener('click', (e) => {
      const remBtn = e.target.closest('.btn-remove-row');
      if (remBtn) {
        const row = remBtn.closest('.pricing-note-row');
        if (notesList.children.length > 1) {
          row.remove();
        } else {
          row.querySelector('input').value = '';
        }
      }
    });
  }

  // 9. Inline Category Creation
  const tourCategorySelect = document.getElementById('tourCategory');
  const newCatContainer = document.getElementById('newCategoryContainer');
  const newCatInput = document.getElementById('newCategoryInput');
  const confirmCatBtn = document.getElementById('confirmNewCategory');
  const cancelCatBtn = document.getElementById('cancelNewCategory');

  if (tourCategorySelect) {
    tourCategorySelect.addEventListener('change', function () {
      if (this.value === 'add_new') {
        newCatContainer.style.display = 'flex';
        newCatInput.focus();
        this.value = '';
      } else {
        newCatContainer.style.display = 'none';
      }
    });
  }

  if (confirmCatBtn) {
    confirmCatBtn.addEventListener('click', () => {
      const val = newCatInput.value.trim();
      if (val) {
        const opt = new Option(val, val, true, true);
        tourCategorySelect.insertBefore(opt, tourCategorySelect.querySelector('option[value="add_new"]'));
        newCatContainer.style.display = 'none';
        newCatInput.value = '';
      }
    });
  }

  if (cancelCatBtn) {
    cancelCatBtn.addEventListener('click', () => {
      newCatContainer.style.display = 'none';
      newCatInput.value = '';
    });
  }

  // 10. File Upload Dropzones
  const setupDropzone = (dropzoneId, inputId, previewId) => {
    const dropzone = document.getElementById(dropzoneId);
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    if (!dropzone || !input || !preview) return;

    dropzone.addEventListener('click', () => input.click());

    dropzone.addEventListener('dragover', (e) => {
      e.preventDefault();
      dropzone.classList.add('dragover');
    });

    dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));

    dropzone.addEventListener('drop', (e) => {
      e.preventDefault();
      dropzone.classList.remove('dragover');
      if (e.dataTransfer.files.length) {
        input.files = e.dataTransfer.files;
        renderImagePreview(input.files[0], preview);
      }
    });

    input.addEventListener('change', () => {
      if (input.files.length) {
        renderImagePreview(input.files[0], preview);
      }
    });
  };

  const renderImagePreview = (file, previewContainer) => {
    if (!file || !file.type.startsWith('image/')) return;
    const reader = new FileReader();
    reader.onload = (e) => {
      previewContainer.innerHTML = `<img src="${e.target.result}" alt="Preview" />`;
    };
    reader.readAsDataURL(file);
  };

  setupDropzone('coverDropzone', 'coverImage', 'coverPreview');
  for (let i = 1; i <= 4; i++) {
    setupDropzone(`highlight${i}Dropzone`, `highlight${i}`, `highlight${i}Preview`);
  }

  // 11. View Switcher (Table List vs Grid Cards)
  const lvBtn = document.getElementById('listViewBtn');
  const cvBtn = document.getElementById('cardViewBtn');
  const toursDisplay = document.getElementById('toursDisplay');

  if (lvBtn && cvBtn && toursDisplay) {
    const setViewMode = (mode) => {
      toursDisplay.classList.toggle('list-view', mode === 'list');
      toursDisplay.classList.toggle('card-view', mode === 'card');
      lvBtn.classList.toggle('active', mode === 'list');
      lvBtn.setAttribute('aria-pressed', mode === 'list');
      cvBtn.classList.toggle('active', mode === 'card');
      cvBtn.setAttribute('aria-pressed', mode === 'card');
      localStorage.setItem('virungaTourViewMode', mode);
    };

    lvBtn.addEventListener('click', () => setViewMode('list'));
    cvBtn.addEventListener('click', () => setViewMode('card'));

    const savedMode = localStorage.getItem('virungaTourViewMode') || 'list';
    setViewMode(savedMode);
  }

  // 12. Main Tour Form Submission (AJAX)
  const tourForm = document.getElementById('tourForm');
  if (tourForm) {
    tourForm.addEventListener('submit', function (e) {
      e.preventDefault();

      if (!validateCurrentTab()) return;

      const submitBtn = document.getElementById('btnSubmitForm');
      const origBtnHtml = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

      const formData = new FormData(this);

      // Collect Activities
      const activities = Array.from(document.querySelectorAll('#daysContainer .day-card-item'))
        .map((d, i) => ({
          day_number: i + 1,
          title: d.querySelector('.activity-title')?.value.trim() || '',
          description: d.querySelector('.activity-desc')?.value.trim() || ''
        }))
        .filter((a) => a.title);
      formData.set('activities', JSON.stringify(activities));

      // Collect Inclusions / Exclusions / Bring
      const getListValues = (selector) =>
        Array.from(document.querySelectorAll(selector))
          .map((input) => input.value.trim())
          .filter(Boolean);

      formData.set('includedItems', JSON.stringify(getListValues('#includedList input')));
      formData.set('excludedItems', JSON.stringify(getListValues('#excludedList input')));
      formData.set('toBringItems', JSON.stringify(getListValues('#bringList input')));

      // Collect Pricing Tiers
      const pricingTiers = Array.from(document.querySelectorAll('#pricingTiersList .pricing-tier-row'))
        .map((r) => ({
          group_size: r.querySelector('.tier-group')?.value.trim() || '',
          price_per_person: r.querySelector('.tier-price')?.value.trim() || ''
        }))
        .filter((t) => t.group_size && t.price_per_person);
      formData.set('pricingTiers', JSON.stringify(pricingTiers));

      // Collect Pricing Notes
      formData.set('pricingNotes', JSON.stringify(getListValues('#pricingNotesList .pricing-note')));

      fetch('tours.php', {
        method: 'POST',
        headers: { Accept: 'application/json' },
        body: formData
      })
        .then((r) => r.json())
        .then((res) => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = origBtnHtml;

          if (res.success) {
            showToast(res.message, 'success');
            closeTourModal();
            setTimeout(() => window.location.reload(), 800);
          } else {
            showToast(res.message || 'Failed to save tour.', 'error');
          }
        })
        .catch((err) => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = origBtnHtml;
          console.error(err);
          showToast('An error occurred while saving the tour.', 'error');
        });
    });
  }

  // 13. Delete Form Submission (AJAX)
  const deleteForm = document.getElementById('deleteForm');
  if (deleteForm) {
    deleteForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const btn = document.getElementById('confirmDeleteBtn');
      const origHtml = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';

      const formData = new FormData(this);

      fetch('tours.php', {
        method: 'POST',
        headers: { Accept: 'application/json' },
        body: formData
      })
        .then((r) => r.json())
        .then((res) => {
          btn.disabled = false;
          btn.innerHTML = origHtml;

          if (res.success) {
            const tourId = formData.get('tour_id');
            const row = document.querySelector(`tr[data-id="${tourId}"]`);
            const card = document.querySelector(`.tour-modern-card[data-id="${tourId}"]`);
            if (row) row.remove();
            if (card) card.remove();

            closeDeleteModal();
            showToast('Tour package deleted successfully!', 'success');

            // If no tours remaining on page, reload
            const remaining = document.querySelectorAll('#toursTableBody tr').length;
            if (remaining === 0) {
              setTimeout(() => window.location.reload(), 600);
            }
          } else {
            showToast(res.message || 'Failed to delete tour.', 'error');
          }
        })
        .catch((err) => {
          btn.disabled = false;
          btn.innerHTML = origHtml;
          console.error(err);
          showToast('An error occurred while deleting tour.', 'error');
        });
    });
  }

  // 14. Global Modal Escape & Backdrop click handlers
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const activeBackdrop = document.querySelector('.modal-backdrop.show');
      if (activeBackdrop) {
        closeModal(activeBackdrop.id);
      }
      closeLightbox();
    }
  });

  document.querySelectorAll('.modal-backdrop').forEach((backdrop) => {
    backdrop.addEventListener('click', (e) => {
      if (e.target === backdrop) {
        closeModal(backdrop.id);
      }
    });
  });
});
