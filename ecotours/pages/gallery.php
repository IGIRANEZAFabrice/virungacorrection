<?php
require_once '../admin/config/database.php';

try {
    $query = "SELECT * FROM gallery_items ORDER BY display_order ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $galleryItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Gallery Error: " . $e->getMessage());
    $galleryItems = [];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Uganda Gorilla Tracking | Bwindi Forest Adventures | Virunga Ecotours</title>
<meta name="description" content="Explore Uganda's Bwindi Impenetrable Forest for mountain gorilla tracking. Community tourism experiences connecting you with local cultures and conservation efforts.">
<meta name="keywords" content="Uganda gorilla tracking, Bwindi Impenetrable Forest, Uganda wildlife tours, mountain gorilla Uganda, community tourism Uganda">
    <title>Modern Animated Gallery</title>
    <link
      rel="shortcut icon"
      href="../images/logos/icon.png"
      type="image/x-icon"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/earthy-theme.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/gallery.css">
  </head>
  <body>
    <?php include('./includes/header.php'); ?>
    <div class="progress-container">
      <div class="progress-bar" id="progressBar"></div>
    </div>

    <div class="gallery-container" id="gallery">
      <?php if (empty($galleryItems)): ?>
        <p class="error-message">No gallery items available at the moment.</p>
      <?php else: ?>
        <?php foreach ($galleryItems as $index => $item): ?>
          <div class="gallery-item" data-index="<?php echo $index; ?>">
            <img src="../<?php echo htmlspecialchars($item['image_path']); ?>" 
                 alt="<?php echo htmlspecialchars($item['alt_text']); ?>" ondragstart="return false;" oncontextmenu="return false;"/>
            <div class="overlay">
              <h3><?php echo htmlspecialchars($item['title']); ?></h3>
              <p><?php echo htmlspecialchars($item['description']); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="modal" id="imageModal">
      <button class="close-btn" id="closeModal" title="Close (Esc)">
        &times;
      </button>
      <button class="nav-btn prev-btn" id="prevBtn">&#10094;</button>
      <div class="modal-content">
        <div class="loader"></div>
        <img src="" alt="" class="modal-img" id="modalImage" ondragstart="return false;" oncontextmenu="return false;"/>
        <div class="counter" id="imageCounter"></div>
        <div class="image-caption" id="imageCaption"></div>
      </div>
      <button class="nav-btn next-btn" id="nextBtn">&#10095;</button>
    </div>
    <?php include('./includes/footer.php'); ?>
    <script src="../js/header.js" defer></script>
    <script>
      const modal = document.getElementById("imageModal");
      const modalImage = document.getElementById("modalImage");
      const closeModal = document.getElementById("closeModal");
      const prevBtn = document.getElementById("prevBtn");
      const nextBtn = document.getElementById("nextBtn");
      const imageCaption = document.getElementById("imageCaption");
      const imageCounter = document.getElementById("imageCounter");
      const galleryItems = document.querySelectorAll(".gallery-item");
      const progressBar = document.getElementById("progressBar");

      let currentIndex = 0;
      const totalImages = galleryItems.length;

      function updateProgressBar() {
        const progress = ((currentIndex + 1) / totalImages) * 100;
        progressBar.style.width = `${progress}%`;
      }

      function showImage(index) {
        if (index < 0) index = totalImages - 1;
        if (index >= totalImages) index = 0;

        currentIndex = index;

        // Loading state
        modal.classList.add("loading");

        // Update progress bar
        updateProgressBar();

        const currentItem = galleryItems[index];
        const img = currentItem.querySelector("img");
        const title = currentItem.querySelector("h3").textContent;
        const description = currentItem.querySelector("p").textContent;

        // Set new image
        modalImage.src = img.src;
        modalImage.alt = img.alt;
        imageCaption.textContent = `${title} - ${description}`;
        imageCounter.textContent = `${index + 1} / ${totalImages}`;

        // Remove loading when image is loaded
        modalImage.onload = () => {
          modal.classList.remove("loading");
        };
      }

      // Event Listeners
      galleryItems.forEach((item) => {
        item.addEventListener("click", function () {
          const index = parseInt(this.dataset.index);
          currentIndex = index;
          showImage(index);
          modal.classList.add("active");
          document.body.style.overflow = "hidden";
        });
      });

      closeModal.addEventListener("click", () => {
        modal.classList.remove("active");
        document.body.style.overflow = "auto";
        progressBar.style.width = "0";
      });

      prevBtn.addEventListener("click", () => {
        showImage(currentIndex - 1);
      });

      nextBtn.addEventListener("click", () => {
        showImage(currentIndex + 1);
      });

      // Keyboard navigation
      document.addEventListener("keydown", (e) => {
        if (!modal.classList.contains("active")) return;

        if (e.key === "Escape") {
          modal.classList.remove("active");
          document.body.style.overflow = "auto";
          progressBar.style.width = "0";
        }
        if (e.key === "ArrowLeft") showImage(currentIndex - 1);
        if (e.key === "ArrowRight") showImage(currentIndex + 1);
      });

      // Close modal when clicking outside
      modal.addEventListener("click", (e) => {
        if (e.target === modal) {
          modal.classList.remove("active");
          document.body.style.overflow = "auto";
          progressBar.style.width = "0";
        }
      });

      // Simple swipe support
      let touchStartX = 0;
      let touchEndX = 0;

      const handleSwipe = () => {
        const swipeDistance = touchEndX - touchStartX;
        if (Math.abs(swipeDistance) > 50) {
          showImage(currentIndex + (swipeDistance > 0 ? -1 : 1));
        }
      };

      modal.addEventListener(
        "touchstart",
        (e) => (touchStartX = e.changedTouches[0].screenX)
      );
      modal.addEventListener("touchend", (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
      });
    </script>
  </body>
</html>
