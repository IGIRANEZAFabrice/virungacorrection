/**
 * Tours Management JavaScript
 * Handles form interactions, dynamic lists, and AJAX submission
 */

// Global function definitions
function setupListControls(addBtnId, listId) {
  const addBtn = document.getElementById(addBtnId);
  const list = document.getElementById(listId);

  if (!addBtn || !list) return;

  // Remove existing event listeners to prevent duplicates
  const newAddBtn = addBtn.cloneNode(true);
  addBtn.parentNode.replaceChild(newAddBtn, addBtn);

  // Add new item
  newAddBtn.addEventListener("click", function () {
    const newItem = document.createElement("div");
    newItem.className = "list-item";
    
    // Determine placeholder based on listId
    let placeholder = "Enter item";
    let inputClass = "";
    if (listId === 'pricingNotesList') {
        placeholder = "Enter pricing note";
        inputClass = "pricing-note";
    }

    newItem.innerHTML = `
        <input type="text" placeholder="${placeholder}" class="${inputClass}">
        <button type="button" class="btn remove-btn"><i class="fas fa-trash"></i></button>
    `;
    list.appendChild(newItem);

    // Setup remove button for the new item
    newItem.querySelector(".remove-btn").onclick = () => newItem.remove();
  });

  // Setup existing remove buttons
  list.querySelectorAll(".remove-btn").forEach((btn) => {
    btn.onclick = function () {
      const item = this.closest(".list-item");
      if (list.children.length > 1 || listId.includes('Pricing')) { // Keep at least one item for pricing notes
          item.remove();
      } else {
          item.querySelector('input').value = '';
      }
    };
  });
}

function setupPricingControls(addBtnId, listId) {
  const addBtn = document.getElementById(addBtnId);
  const list = document.getElementById(listId);

  if (!addBtn || !list) return;

  // Remove existing event listeners to prevent duplicates
  const newAddBtn = addBtn.cloneNode(true);
  addBtn.parentNode.replaceChild(newAddBtn, addBtn);

  // Add new item
  newAddBtn.addEventListener("click", function () {
    const newItem = document.createElement("div");
    newItem.className = "list-item pricing-tier";
    newItem.innerHTML = `
        <input type="text" placeholder="Group Size (e.g. 1-2 people)" class="tier-group" required />
        <input type="number" step="0.01" placeholder="Price per person" class="tier-price" required />
        <button type="button" class="btn remove-btn"><i class="fas fa-trash"></i></button>
    `;
    list.appendChild(newItem);

    // Setup remove button for the new item
    newItem.querySelector(".remove-btn").onclick = () => newItem.remove();
  });

  // Setup existing remove buttons
  list.querySelectorAll(".remove-btn").forEach((btn) => {
    btn.onclick = function () {
      const item = this.closest(".list-item");
      if (list.children.length > 1) { // Always keep at least one pricing tier
          item.remove();
      } else {
          item.querySelectorAll('input').forEach(input => input.value = '');
      }
    };
  });
}

function setupImagePreview(inputId, previewId) {
  const input = document.getElementById(inputId);
  const preview = document.getElementById(previewId);

  if (!input || !preview) return;

  preview.addEventListener("click", () => input.click());

  input.addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
      };
      reader.readAsDataURL(file);
    }
  });
}

function resetFormToAddMode() {
  const form = document.getElementById('tourForm');
  if (!form) return;
  
  form.reset();

  // Remove edit mode hidden inputs
  form.querySelectorAll('input[name="tour_id"], input[name="update_tour"]').forEach(input => input.remove());

  // Reset form title and button
  const formTitle = document.querySelector('.form-title');
  const submitBtn = document.querySelector('.submit-btn');
  if (formTitle) formTitle.innerHTML = '<i class="fas fa-plus-circle"></i> Add New Tour';
  if (submitBtn) submitBtn.innerHTML = '<i class="fas fa-save"></i> Create Tour';

  // Reset image previews
  document.getElementById('coverPreview').innerHTML = '<i class="fas fa-cloud-upload-alt"></i><span>Click to upload cover image</span>';
  for (let i = 1; i <= 4; i++) {
      const p = document.getElementById(`highlight${i}Preview`);
      if (p) p.innerHTML = `<i class="fas fa-camera"></i><span>Highlight ${i}</span>`;
  }

  // Reset activities
  const daysContainer = document.getElementById('daysContainer');
  if (daysContainer) {
      daysContainer.innerHTML = `
          <div class="day-container">
              <div class="day-header">
                  <h4 class="day-title"><i class="fas fa-map-marker-alt"></i> Activity 1</h4>
              </div>
              <div class="form-group">
                  <label for="day1Title"><i class="fas fa-heading"></i> Activity Title</label>
                  <input type="text" id="day1Title" name="day1Title" placeholder="Enter title for activity" required>
              </div>
              <div class="form-group">
                  <label for="day1Desc"><i class="fas fa-align-left"></i> Activity Description</label>
                  <textarea id="day1Desc" name="day1Desc" placeholder="Describe the activity" required></textarea>
              </div>
          </div>
      `;
  }
  window.activityCount = 1;

  // Reset lists
  const listConfigs = [
      { id: 'includedList', placeholder: 'Enter included item' },
      { id: 'excludedList', placeholder: 'Enter excluded item' },
      { id: 'bringList', placeholder: 'Enter item to bring' },
      { id: 'pricingNotesList', placeholder: 'Enter pricing note', class: 'pricing-note' }
  ];

  listConfigs.forEach(conf => {
      const list = document.getElementById(conf.id);
      if (list) {
          list.innerHTML = `
              <div class="list-item">
                  <input type="text" placeholder="${conf.placeholder}" class="${conf.class || ''}">
                  <button type="button" class="btn remove-btn"><i class="fas fa-trash"></i></button>
              </div>
          `;
      }
  });

  const pricingTiersList = document.getElementById('pricingTiersList');
  if (pricingTiersList) {
      pricingTiersList.innerHTML = `
          <div class="list-item pricing-tier">
              <input type="text" placeholder="Group Size (e.g. 1-2 people)" class="tier-group">
              <input type="number" step="0.01" placeholder="Price" class="tier-price">
              <button type="button" class="btn remove-btn"><i class="fas fa-trash"></i></button>
          </div>
      `;
  }

  // Re-setup all controls
  setupListControls("addIncludedBtn", "includedList");
  setupListControls("addExcludedBtn", "excludedList");
  setupListControls("addBringBtn", "bringList");
  setupPricingControls("addPricingBtn", "pricingTiersList");
  setupListControls("addPricingNoteBtn", "pricingNotesList");
}

function editTour(tourId) {
    const form = document.getElementById('tourForm');
    if (!form) return;
    
    // Find the button that was clicked to show loading state
    const editBtn = document.querySelector(`tr[data-id="${tourId}"] .edit-btn, .tour-card[data-id="${tourId}"] .edit-btn`);
    const originalBtnHtml = editBtn ? editBtn.innerHTML : '';
    if (editBtn) {
        editBtn.disabled = true;
        editBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    }

    resetFormToAddMode();

    fetch(`?fetch_tour=1&id=${tourId}`)
        .then(response => response.json())
        .then(result => {
            if (editBtn) {
                editBtn.disabled = false;
                editBtn.innerHTML = originalBtnHtml;
            }
            if (result.success) {
                const data = result.data;

                // Populate main fields
                const fieldMap = {
                    'tourTitle': data.title,
                    'tourCategory': data.category,
                    'tourCountry': data.country,
                    'tourDays': data.days_count,
                    'tourDesc': data.short_description,
                    'whyAttend': data.why_attend
                };

                for (let id in fieldMap) {
                    const el = document.getElementById(id);
                    if (el) el.value = fieldMap[id] || '';
                }

                if (data.cover_image_path) {
                    document.getElementById('coverPreview').innerHTML = `<img src="../../${data.cover_image_path}" alt="Cover Image">`;
                }

                if (data.highlights) {
                    data.highlights.forEach((h) => {
                        const p = document.getElementById(`highlight${h.display_order}Preview`);
                        if (p && h.image_path) p.innerHTML = `<img src="../../${h.image_path}" alt="Highlight ${h.display_order}">`;
                    });
                }

                // Helper to populate lists
                const populateList = (listId, items, descKey, placeholder, inputClass = '') => {
                    const list = document.getElementById(listId);
                    if (!list) return;
                    list.innerHTML = '';
                    if (items && items.length > 0) {
                        items.forEach(item => {
                            const val = (item[descKey] || '').replace(/"/g, '&quot;');
                            const div = document.createElement('div');
                            div.className = 'list-item';
                            div.innerHTML = `<input type="text" value="${val}" class="${inputClass}"><button type="button" class="btn remove-btn"><i class="fas fa-trash"></i></button>`;
                            list.appendChild(div);
                        });
                    } else {
                        list.innerHTML = `<div class="list-item"><input type="text" placeholder="${placeholder}" class="${inputClass}"><button type="button" class="btn remove-btn"><i class="fas fa-trash"></i></button></div>`;
                    }
                };

                populateList('includedList', data.included, 'item_description', 'Enter included item');
                populateList('excludedList', data.excluded, 'item_description', 'Enter excluded item');
                populateList('bringList', data.to_bring, 'item_description', 'Enter item to bring');
                populateList('pricingNotesList', data.pricing_notes, 'note', 'Enter pricing note', 'pricing-note');

                // Populate pricing tiers
                const ptList = document.getElementById('pricingTiersList');
                if (ptList) {
                    ptList.innerHTML = '';
                    if (data.pricing_tiers && data.pricing_tiers.length > 0) {
                        data.pricing_tiers.forEach(tier => {
                            const div = document.createElement('div');
                            div.className = 'list-item pricing-tier';
                            div.innerHTML = `
                                <input type="text" value="${(tier.group_size || '').replace(/"/g, '&quot;')}" class="tier-group">
                                <input type="number" step="0.01" value="${tier.price_per_person || ''}" class="tier-price">
                                <button type="button" class="btn remove-btn"><i class="fas fa-trash"></i></button>
                            `;
                            ptList.appendChild(div);
                        });
                    } else {
                        ptList.innerHTML = `<div class="list-item pricing-tier"><input type="text" placeholder="Group Size" class="tier-group"><input type="number" step="0.01" placeholder="Price" class="tier-price"><button type="button" class="btn remove-btn"><i class="fas fa-trash"></i></button></div>`;
                    }
                }

                // Populate activities
                const dsContainer = document.getElementById('daysContainer');
                if (dsContainer) {
                    dsContainer.innerHTML = '';
                    if (data.days && data.days.length > 0) {
                        data.days.forEach((day, index) => {
                            const div = document.createElement('div');
                            div.className = 'day-container';
                            div.innerHTML = `
                                <div class="day-header">
                                    <h4 class="day-title"><i class="fas fa-map-marker-alt"></i> Activity ${index + 1}</h4>
                                    ${index > 0 ? '<button type="button" class="btn remove-btn remove-activity"><i class="fas fa-times"></i></button>' : ''}
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-heading"></i> Activity Title</label>
                                    <input type="text" value="${(day.day_title || '').replace(/"/g, '&quot;')}" class="activity-title" required>
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-align-left"></i> Activity Description</label>
                                    <textarea class="activity-desc" required>${(day.day_description || '').replace(/</g, '&lt;')}</textarea>
                                </div>
                            `;
                            dsContainer.appendChild(div);
                        });
                        window.activityCount = data.days.length;
                    }
                }

                // Switch to form view
                document.getElementById('addTourForm').classList.add('active');
                document.getElementById('tableSection').classList.add('hidden');
                
                const addBtn = document.querySelector('.add-tour-btn');
                if (addBtn) {
                    addBtn.dataset.state = 'close';
                    const btnTxt = addBtn.querySelector('.btn-text');
                    if (btnTxt) btnTxt.textContent = 'Close Form';
                }
                
                const fTitle = document.querySelector('.form-title');
                if (fTitle) fTitle.innerHTML = '<i class="fas fa-edit"></i> Edit Tour';
                const sBtn = document.querySelector('.submit-btn');
                if (sBtn) sBtn.innerHTML = '<i class="fas fa-save"></i> Update Tour';

                form.insertAdjacentHTML('beforeend', `<input type="hidden" name="tour_id" value="${tourId}"><input type="hidden" name="update_tour" value="1">`);

                // Re-setup controls
                setupListControls("addIncludedBtn", "includedList");
                setupListControls("addExcludedBtn", "excludedList");
                setupListControls("addBringBtn", "bringList");
                setupPricingControls("addPricingBtn", "pricingTiersList");
                setupListControls("addPricingNoteBtn", "pricingNotesList");
            } else {
                alert('Error fetching tour data: ' + result.message);
            }
        })
        .catch(error => {
            if (editBtn) {
                editBtn.disabled = false;
                editBtn.innerHTML = originalBtnHtml;
            }
            console.error('Error:', error);
            alert('An error occurred while fetching tour data');
        });
}

document.addEventListener("DOMContentLoaded", function () {
  const tourForm = document.getElementById("tourForm");
  if (!tourForm) return;

  const tourCategorySelect = document.getElementById("tourCategory");
  const newCategoryContainer = document.getElementById("newCategoryContainer");
  const newCategoryInput = document.getElementById("newCategoryInput");
  const confirmNewCategoryBtn = document.getElementById("confirmNewCategory");
  const cancelNewCategoryBtn = document.getElementById("cancelNewCategory");

  // Category Logic
  if (tourCategorySelect) {
      tourCategorySelect.addEventListener("change", function () {
        if (this.value === "add_new") {
          newCategoryContainer.style.display = "block";
          newCategoryInput.focus();
          this.value = "";
        } else {
          newCategoryContainer.style.display = "none";
        }
      });
  }

  if (confirmNewCategoryBtn) {
      confirmNewCategoryBtn.addEventListener("click", function () {
        const val = newCategoryInput.value.trim();
        if (val) {
          const opt = document.createElement("option");
          opt.value = val; opt.textContent = val; opt.selected = true;
          tourCategorySelect.insertBefore(opt, tourCategorySelect.querySelector('option[value="add_new"]'));
          newCategoryContainer.style.display = "none";
          newCategoryInput.value = "";
        }
      });
  }

  if (cancelNewCategoryBtn) {
      cancelNewCategoryBtn.addEventListener("click", () => {
          newCategoryContainer.style.display = "none";
          tourCategorySelect.value = "";
      });
  }

  // Pricing & Lists
  setupListControls("addIncludedBtn", "includedList");
  setupListControls("addExcludedBtn", "excludedList");
  setupListControls("addBringBtn", "bringList");
  setupPricingControls("addPricingBtn", "pricingTiersList");
  setupListControls("addPricingNoteBtn", "pricingNotesList");

  // Activities
  const addActivityBtn = document.getElementById("addActivityBtn");
  const dsContainer = document.getElementById("daysContainer");
  window.activityCount = document.querySelectorAll('.day-container').length || 1;

  if (addActivityBtn && dsContainer) {
      addActivityBtn.addEventListener("click", function() {
        window.activityCount++;
        const div = document.createElement('div');
        div.className = 'day-container';
        div.innerHTML = `
          <div class="day-header">
            <h4 class="day-title"><i class="fas fa-map-marker-alt"></i> Activity ${window.activityCount}</h4>
            <button type="button" class="btn remove-btn remove-activity"><i class="fas fa-times"></i></button>
          </div>
          <div class="form-group">
            <label><i class="fas fa-heading"></i> Activity Title</label>
            <input type="text" class="activity-title" placeholder="Enter title" required>
          </div>
          <div class="form-group">
            <label><i class="fas fa-align-left"></i> Activity Description</label>
            <textarea class="activity-desc" placeholder="Describe activity" required></textarea>
          </div>
        `;
        dsContainer.appendChild(div);
      });

      dsContainer.addEventListener('click', function(e) {
          const remBtn = e.target.closest('.remove-activity');
          if (remBtn) {
              remBtn.closest('.day-container').remove();
              document.querySelectorAll('.day-container').forEach((d, i) => {
                  d.querySelector('.day-title').innerHTML = `<i class="fas fa-map-marker-alt"></i> Activity ${i + 1}`;
              });
              window.activityCount = document.querySelectorAll('.day-container').length;
          }
      });
  }

  // Image Previews
  setupImagePreview("coverImage", "coverPreview");
  for(let i=1; i<=4; i++) setupImagePreview(`highlight${i}`, `highlight${i}Preview`);

  // View Switcher
  const lvBtn = document.getElementById('listViewBtn');
  const cvBtn = document.getElementById('cardViewBtn');
  const tDisp = document.getElementById('toursDisplay');
  if (lvBtn && cvBtn && tDisp) {
      const setView = (v) => {
          tDisp.classList.toggle('list-view', v === 'list');
          tDisp.classList.toggle('card-view', v === 'card');
          lvBtn.classList.toggle('active', v === 'list');
          cvBtn.classList.toggle('active', v === 'card');
          localStorage.setItem('tourViewPreference', v);
      };
      lvBtn.onclick = () => setView('list');
      cvBtn.onclick = () => setView('card');
      setView(localStorage.getItem('tourViewPreference') || 'list');
  }

  // Form Submission
  tourForm.addEventListener("submit", function (e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('.submit-btn');
    const originalBtnHtml = submitBtn.innerHTML;
    
    // Disable button and show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

    const formData = new FormData(this);

    // Manual collection
    const activities = Array.from(document.querySelectorAll('.day-container')).map((d, i) => ({
        day_number: i + 1,
        title: d.querySelector('.activity-title')?.value || d.querySelector('input[name$="Title"]')?.value,
        description: d.querySelector('.activity-desc')?.value || d.querySelector('textarea[name$="Desc"]')?.value
    })).filter(a => a.title);
    formData.append("activities", JSON.stringify(activities));

    const getList = (sel) => Array.from(document.querySelectorAll(sel)).map(i => i.value).filter(Boolean);
    formData.append("includedItems", JSON.stringify(getList('#includedList input')));
    formData.append("excludedItems", JSON.stringify(getList('#excludedList input')));
    formData.append("toBringItems", JSON.stringify(getList('#bringList input')));
    
    const tiers = Array.from(document.querySelectorAll('#pricingTiersList .pricing-tier')).map(t => ({
        group_size: t.querySelector('.tier-group').value,
        price_per_person: t.querySelector('.tier-price').value
    })).filter(t => t.group_size && t.price_per_person);
    formData.append("pricingTiers", JSON.stringify(tiers));

    const notes = getList('#pricingNotesList .pricing-note');
    formData.append("pricingNotes", JSON.stringify(notes));

    fetch(this.action || window.location.href, { method: 'POST', body: formData })
    .then(r => r.json())
    .then(d => {
        if (d.success) { 
            alert(d.message); 
            window.location.reload(); 
        } else { 
            alert('Error: ' + d.message); 
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
        }
    })
    .catch(err => { 
        console.error(err); 
        alert('An error occurred'); 
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnHtml;
    });
  });
});
