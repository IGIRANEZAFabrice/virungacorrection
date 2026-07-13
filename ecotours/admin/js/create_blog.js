document.addEventListener("DOMContentLoaded", () => {
  let blockCounter = 0;
  const contentBlocks = document.getElementById("contentBlocks");

  // Event delegation for Add Block buttons
  document.body.addEventListener("click", (e) => {
    if (e.target.closest(".add-block-btn")) {
      const btn = e.target.closest(".add-block-btn");
      const blockType = btn.getAttribute("data-block-type");
      addContentBlock(blockType);
    }
  });

  // Event delegation for Remove Block buttons
  document.body.addEventListener("click", (e) => {
    if (e.target.closest(".remove-block")) {
      const block = e.target.closest(".content-block");
      if (block && confirm("Remove this content block?")) {
        block.remove();
        updateBlockNumbers();
      }
    }
  });

  // Add a new content block
  function addContentBlock(type) {
    blockCounter++;
    const blockNum =
      contentBlocks.querySelectorAll(".content-block").length + 1;
    const block = document.createElement("div");
    block.className = "content-block";
    block.setAttribute("data-block-type", type);

    let html = `
      <div class="block-header">
        <span class="block-title">${capitalize(type)} Block ${blockNum}</span>
        <button type="button" class="remove-block"><i class="fas fa-times"></i></button>
      </div>
    `;

    if (type === "text") {
      html += `
        <div class="form-group">
          <label for="blockTitle${blockCounter}">Section Title</label>
          <input type="text" id="blockTitle${blockCounter}" name="blockTitle${blockCounter}" placeholder="Enter section title"/>
        </div>
        <div class="form-group">
          <label for="blockDescription${blockCounter}">Content</label>
          <textarea id="blockDescription${blockCounter}" name="blockDescription${blockCounter}" placeholder="Write your content here"></textarea>
        </div>
      `;
    } else if (type === "image") {
      html += `
        <div class="form-group">
          <label for="blockImageCaption${blockCounter}">Image Caption</label>
          <input type="text" id="blockImageCaption${blockCounter}" name="blockImageCaption${blockCounter}" placeholder="Enter image caption"/>
        </div>
        <div class="form-group">
          <label for="blockImage${blockCounter}">Image</label>
          <input type="file" id="blockImage${blockCounter}" name="blockImage${blockCounter}" accept="image/*" data-block-id="${blockCounter}" class="block-image-input"/>
          <div class="image-preview" id="imagePreview${blockCounter}"></div>
        </div>
      `;
    }

    block.innerHTML = html;
    contentBlocks.appendChild(block);

    // Initialize rich-text editor for textareas in the new block
    block.querySelectorAll("textarea").forEach((ta) => {
      enablePlainPaste(ta);
      initializeRichTextEditor(ta);
    });

    // Initialize image preview
    block.querySelectorAll('input[type="file"]').forEach((fileInput) => {
      fileInput.addEventListener("change", (e) => handleImagePreview(e.target));
    });
  }

  // Initialize rich-text editor for existing textareas (like Introduction)
  document.querySelectorAll("#bigDescription").forEach((ta) => {
    enablePlainPaste(ta);
    initializeRichTextEditor(ta);
  });

  // Handle image preview
  function handleImagePreview(input) {
    const preview = input.parentElement.querySelector(".image-preview");
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = (e) => {
        preview.innerHTML = `<img src="${e.target.result}" style="max-width:80%; height:auto; border-radius:10px; margin-top:10px;" />`;
      };
      reader.readAsDataURL(input.files[0]);
    } else {
      preview.innerHTML = "";
    }
  }

  // Enable plain text paste
  function enablePlainPaste(el) {
    el.addEventListener("paste", (e) => {
      e.preventDefault();
      const text = (e.clipboardData || window.clipboardData).getData(
        "text/plain"
      );
      document.execCommand("insertText", false, text);
    });
  }

  // Update block numbers after removal
  function updateBlockNumbers() {
    contentBlocks.querySelectorAll(".content-block").forEach((block, i) => {
      const type = block.getAttribute("data-block-type");
      const title = block.querySelector(".block-title");
      if (title) title.textContent = `${capitalize(type)} Block ${i + 1}`;
    });
  }

  // Capitalize helper
  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  // Initialize simple rich text editor
  function initializeRichTextEditor(textarea) {
    const wrapper = document.createElement("div");
    wrapper.className = "rich-text-wrapper";

    const toolbar = document.createElement("div");
    toolbar.className = "rich-text-toolbar";
    toolbar.innerHTML = `
      <button type="button" onclick="execCommand('bold')" title="Bold"><i class="fas fa-bold"></i></button>
      <button type="button" onclick="execCommand('italic')" title="Italic"><i class="fas fa-italic"></i></button>
      <button type="button" onclick="execCommand('underline')" title="Underline"><i class="fas fa-underline"></i></button>
      <button type="button" onclick="execCommand('insertUnorderedList')" title="Bullet List"><i class="fas fa-list-ul"></i></button>
      <button type="button" onclick="execCommand('insertOrderedList')" title="Numbered List"><i class="fas fa-list-ol"></i></button>
      <span class="toolbar-divider">|</span>
      <button type="button" onclick="insertLink()" title="Insert Link"><i class="fas fa-link"></i></button>
      <button type="button" onclick="execCommand('unlink')" title="Remove Link"><i class="fas fa-unlink"></i></button>
      <span class="toolbar-divider">|</span>
      <button type="button" onclick="insertQuote()" title="Insert Quote"><i class="fas fa-quote-left"></i></button>
    `;

    const editor = document.createElement("div");
    editor.className = "rich-text-editor";
    editor.contentEditable = true;
    editor.innerHTML = textarea.value;

    wrapper.appendChild(toolbar);
    wrapper.appendChild(editor);
    textarea.parentNode.insertBefore(wrapper, textarea);
    wrapper.appendChild(textarea);

    // Hide original textarea
    textarea.style.display = "none";

    // Sync content using innerHTML to preserve formatting and links
    editor.addEventListener("input", () => {
      textarea.value = editor.innerHTML;
    });
  }

  // Global functions for toolbar
  window.execCommand = (cmd) => document.execCommand(cmd, false, null);

  window.insertLink = () => {
    const selection = window.getSelection();
    if (!selection.rangeCount) return alert("Place your cursor first.");
    let text = selection.toString() || prompt("Enter link text:");
    let url = prompt("Enter URL:", "https://");
    if (!text || !url) return;
    document.execCommand(
      "insertHTML",
      false,
      `<a href="${url}" target="_blank">${text}</a>`
    );
  };

  window.insertQuote = () => {
    const selection = window.getSelection();
    if (!selection.rangeCount) return alert("Place your cursor first.");
    let text = selection.toString() || prompt("Enter quote text:");
    if (!text) return;
    const blockquote = document.createElement("blockquote");
    blockquote.textContent = text;
    blockquote.style.cssText =
      "border-left:4px solid #2e7d32;margin:10px 0;padding:10px;background:#f8f9fa;font-style:italic;";
    const range = selection.getRangeAt(0);
    range.deleteContents();
    range.insertNode(blockquote);
  };

  // CSS for text area and toolbar
  const style = document.createElement("style");
  style.textContent = `
    .rich-text-editor {
      border: 1px solid #ddd;
      border-radius: 0 0 4px 4px;
      padding: 12px;
      min-height: 120px;
      width: 100%;
      background: #fff;
      overflow-wrap: break-word;
    }
    .rich-text-editor:focus {
      outline: none;
      border-color: #2e7d32;
      box-shadow: 0 0 0 2px rgba(46,125,50,0.2);
    }
    .rich-text-toolbar {
      border: 1px solid #ddd;
      border-bottom: none;
      padding: 8px;
      background: #f8f9fa;
      border-radius: 4px 4px 0 0;
      display: flex;
      gap: 4px;
    }
    .rich-text-toolbar button {
      background: none;
      border: 1px solid transparent;
      padding: 5px 8px;
      border-radius: 3px;
      cursor: pointer;
    }
    .rich-text-toolbar button:hover {
      background: #e9ecef;
      border-color: #adb5bd;
    }
    .toolbar-divider {
      margin: 0 6px;
      color: #dee2e6;
      user-select: none;
    }
  `;
  document.head.appendChild(style);

  console.log("Create Blog JS fully initialized");
});
