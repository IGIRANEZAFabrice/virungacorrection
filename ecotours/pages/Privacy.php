<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <title>Privacy Policy - Virunga Ecotours</title>
    <link rel="stylesheet" href="../css/earthy-theme.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/privacy.css" />
  </head>
  <body>
    <?php
// Database connection for privacy policy
$servername = "localhost";
$username = "dmxewbmy_homestay";
$password = "Igiraneza@11823";
$dbname = "dmxewbmy_ecodatabase";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch privacy policy content from database
    $stmt = $pdo->prepare("SELECT content, last_updated FROM privacy_policy WHERE id = 1");
    $stmt->execute();
    $privacy_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($privacy_data) {
        $privacy_content = $privacy_data['content'];
        $last_updated = date('F j, Y', strtotime($privacy_data['last_updated']));
    } else {
        // Fallback content if database is empty
        $privacy_content = "Privacy policy content is currently being updated. Please check back soon.";
        $last_updated = "April 12, 2025";
    }
} catch (PDOException $e) {
    // Fallback in case of database connection issues
    $privacy_content = "Privacy policy content is temporarily unavailable. Please contact us directly for privacy-related inquiries.";
    $last_updated = "April 12, 2025";
    error_log("Privacy Policy Database Error: " . $e->getMessage());
}

include './includes/header.php';
?>


    <div class="hero">
      <div class="hero-background"></div>
      <div class="hero-content">
        <h1>Privacy Policy</h1>
        <p>
          At Virunga Ecotours, we're committed to protecting your personal
          information and being transparent about how we use it.
        </p>
        <div class="last-updated">Last Updated: <?php echo htmlspecialchars($last_updated); ?></div>
      </div>
    </div>

    <main>
      <!-- Privacy Policy Content from Database -->
      <div class="privacy-content" style="background-color: white;">
        <?php
        // Function to convert markdown-like content to HTML
        function convertMarkdownToHtml($content) {
            // Escape HTML first to prevent XSS
            $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

            // Convert headers with section icons
            $content = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $content);
            $content = preg_replace('/^## (\d+)\. (.+)$/m', '<h2><span class="section-icon">$1</span> $2</h2>', $content);
            $content = preg_replace('/^## (.+)$/m', '<h2><span class="section-icon">📋</span> $1</h2>', $content);
            $content = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $content);

            // Convert bold text
            $content = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $content);

            // Convert bullet points
            $content = preg_replace('/^- (.+)$/m', '<li>$1</li>', $content);

            // Wrap consecutive list items in ul tags
            $content = preg_replace('/(<li>.*?<\/li>)(\s*<li>.*?<\/li>)*/s', '<ul>$0</ul>', $content);

            // Convert line breaks to paragraphs
            $paragraphs = explode("\n\n", $content);
            $content = '';
            foreach ($paragraphs as $paragraph) {
                $paragraph = trim($paragraph);
                if (!empty($paragraph)) {
                    // Don't wrap headers and lists in paragraph tags
                    if (!preg_match('/^<(h[1-6]|ul|li)/', $paragraph)) {
                        $content .= '<p>' . $paragraph . '</p>';
                    } else {
                        $content .= $paragraph;
                    }
                }
            }

            // Clean up formatting
            $content = preg_replace('/<p>(<h[1-6]>.*?<\/h[1-6]>)<\/p>/', '$1', $content);
            $content = preg_replace('/<p>(<ul>.*?<\/ul>)<\/p>/s', '$1', $content);

            return $content;
        }

        // Display the privacy policy content
        if (!empty($privacy_content)) {
            echo '<div class="policy-content">';
            echo convertMarkdownToHtml($privacy_content);
            echo '</div>';
        } else {
            echo '<div class="policy-content">';
            echo '<p>Privacy policy content is currently being updated. Please contact us directly for any privacy-related inquiries.</p>';
            echo '</div>';
        }
        ?>
      </div>

      <!-- Contact Section for Privacy Inquiries -->
      <section class="privacy-contact-section">
        <div class="contact-container">
          <h2><i class="fas fa-shield-alt"></i> Privacy Contact Information</h2>
          <p>If you have any questions about this Privacy Policy or wish to exercise your privacy rights, please contact us:</p>

          <div class="contact-methods">
            <div class="contact-card" style="background-color: #f2e8dc;">
              <div class="contact-icon"><i class="fas fa-envelope"></i></div>
              <h4>Email</h4>
              <p style="color: #000000;">info@virungaecotours.com</p>
            </div>
            <div class="contact-card" style="background-color: #f2e8dc;">
              <div class="contact-icon"><i class="fas fa-phone"></i></div>
              <h4>Phone</h4>
              <p style="color: #000000;">+250 788 123 456</p>
            </div>
            <div class="contact-card" style="background-color: #f2e8dc;">
              <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
              <h4>Address</h4>
              <p style="color: #000000;">Virunga Ecotours<br/>P.O. Box 6754<br/>Kigali, Rwanda</p>
            </div>
          </div>
        </div>
      </section>
    </main>

      <?php include './includes/footer.php'; ?>
    <script src="../js/header.js" defer></script>
  </body>
</html>
