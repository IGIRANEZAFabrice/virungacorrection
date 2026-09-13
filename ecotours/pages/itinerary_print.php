<?php
require_once './itenaryopenhandler.php';

$countryName = ucfirst(trim($tour['country']));
if (strtolower($countryName) === 'congo' || stripos($countryName, 'congo') !== false) {
    $countryName = 'DR Congo';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($tour['title']); ?> - Official Itinerary Dossier</title>
  <link rel="shortcut icon" href="../images/logos/icon.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --pdf-green: #122a1f;
      --pdf-green-light: #1b3a2b;
      --pdf-gold: #c9a24b;
      --pdf-gold-dark: #8e681c;
      --pdf-bg-warm: #fbfaf7;
      --pdf-border: #e6e2d8;
      --pdf-text-dark: #202924;
      --pdf-text-muted: #607066;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Jost', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
      color: var(--pdf-text-dark);
      background: #f0eee9;
      line-height: 1.5;
      font-size: 13px;
      padding: 30px 15px;
    }

    .dossier-wrapper {
      max-width: 820px;
      margin: 0 auto;
      background: #ffffff;
      padding: 35px 40px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      border-radius: 4px;
    }

    .print-actions-bar {
      max-width: 820px;
      margin: 0 auto 20px auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 15px;
    }

    .print-btn-primary {
      background: linear-gradient(135deg, #1b3a2b 0%, #122a1f 100%);
      color: #ffffff;
      border: 1px solid #c9a24b;
      padding: 10px 22px;
      border-radius: 6px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .print-btn-primary:hover {
      background: #c9a24b;
      color: #122a1f;
    }

    .back-link {
      color: #1b3a2b;
      text-decoration: none;
      font-weight: 600;
      font-size: 13px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    /* PDF Layout Elements */
    .header-table {
      width: 100%;
      border-collapse: collapse;
      border-bottom: 2px solid var(--pdf-gold);
      padding-bottom: 15px;
      margin-bottom: 22px;
    }

    .brand-title {
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-size: 26px;
      font-weight: 700;
      letter-spacing: 2px;
      color: var(--pdf-green);
      text-transform: uppercase;
      line-height: 1;
    }

    .brand-subtitle {
      font-size: 10.5px;
      color: var(--pdf-text-muted);
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin-top: 5px;
      font-weight: 500;
    }

    .header-contacts {
      text-align: right;
      font-size: 11px;
      color: #3b4740;
      line-height: 1.45;
    }

    .header-contacts strong {
      color: var(--pdf-gold-dark);
      font-size: 11.5px;
    }

    .journey-badge {
      display: inline-block;
      background: #f4ede0;
      color: var(--pdf-gold-dark);
      font-weight: 700;
      font-size: 10px;
      padding: 4px 10px;
      border-radius: 4px;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 8px;
    }

    .journey-title {
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-size: 28px;
      color: var(--pdf-green);
      font-weight: 700;
      line-height: 1.15;
      margin-bottom: 14px;
    }

    .metrics-table {
      width: 100%;
      border-collapse: collapse;
      background: var(--pdf-bg-warm);
      border: 1px solid var(--pdf-border);
      border-radius: 6px;
      margin-bottom: 20px;
      overflow: hidden;
    }

    .metrics-table td {
      width: 25%;
      padding: 10px 14px;
      border-right: 1px solid var(--pdf-border);
      text-align: center;
    }

    .metrics-table td:last-child {
      border-right: none;
    }

    .metric-label {
      font-size: 9.5px;
      text-transform: uppercase;
      color: var(--pdf-text-muted);
      letter-spacing: 0.5px;
      font-weight: 600;
    }

    .metric-value {
      font-size: 14px;
      font-weight: 700;
      color: var(--pdf-green);
      margin-top: 2px;
    }

    .photo-grid-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    .photo-grid-table td {
      width: 50%;
    }

    .photo-grid-table img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 6px;
      display: block;
      border: 1px solid #dcd7cc;
    }

    .overview-box {
      background: #fdfbf7;
      border-left: 4px solid var(--pdf-gold);
      border-top: 1px solid #efeae0;
      border-right: 1px solid #efeae0;
      border-bottom: 1px solid #efeae0;
      border-radius: 0 6px 6px 0;
      padding: 14px 18px;
      margin-bottom: 24px;
    }

    .overview-label {
      font-size: 10.5px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--pdf-gold-dark);
      font-weight: 700;
      margin-bottom: 4px;
    }

    .overview-text {
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-size: 15px;
      line-height: 1.55;
      font-style: italic;
      color: #26312a;
    }

    .section-title-bar {
      border-bottom: 2px solid var(--pdf-green);
      padding-bottom: 6px;
      margin: 26px 0 16px 0;
    }

    .section-title-text {
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-size: 19px;
      color: var(--pdf-green);
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .day-card {
      page-break-inside: avoid;
      break-inside: avoid;
      background: #ffffff;
      border: 1px solid var(--pdf-border);
      border-radius: 6px;
      padding: 13px 16px;
      margin-bottom: 11px;
    }

    .day-badge {
      background: var(--pdf-green-light);
      color: #f4ede0;
      font-weight: 700;
      font-size: 10px;
      padding: 4px 8px;
      border-radius: 4px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      display: inline-block;
    }

    .day-title {
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-size: 16px;
      color: var(--pdf-green);
      font-weight: 700;
      margin: 0 0 6px 0;
    }

    .day-desc {
      font-size: 12px;
      line-height: 1.6;
      color: #3b4740;
    }

    .why-attend-box {
      page-break-inside: avoid;
      break-inside: avoid;
      background: #f5f9f6;
      border: 1px solid #cfe2d5;
      border-radius: 6px;
      padding: 15px 18px;
      margin-bottom: 20px;
    }

    .why-attend-title {
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-size: 16px;
      font-weight: 700;
      color: var(--pdf-green-light);
      text-transform: uppercase;
      margin-bottom: 6px;
    }

    .inclusions-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
      page-break-inside: avoid;
      break-inside: avoid;
    }

    .inclusions-box {
      background: #f8faf8;
      border: 1px solid #d3e4d7;
      border-radius: 6px;
      padding: 14px 16px;
      height: 100%;
    }

    .inclusions-box h4 {
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-size: 15px;
      font-weight: 700;
      color: #174223;
      border-bottom: 1px solid #d3e4d7;
      padding-bottom: 5px;
      margin-bottom: 8px;
    }

    .exclusions-box {
      background: #fdfafb;
      border: 1px solid #edd5d5;
      border-radius: 6px;
      padding: 14px 16px;
      height: 100%;
    }

    .exclusions-box h4 {
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-size: 15px;
      font-weight: 700;
      color: #782626;
      border-bottom: 1px solid #edd5d5;
      padding-bottom: 5px;
      margin-bottom: 8px;
    }

    .inclusions-box ul, .exclusions-box ul {
      padding-left: 16px;
      font-size: 11.5px;
      line-height: 1.55;
    }

    .inclusions-box li {
      color: #2c3d32;
      margin-bottom: 4px;
    }

    .exclusions-box li {
      color: #4f3333;
      margin-bottom: 4px;
    }

    .packing-box {
      page-break-inside: avoid;
      break-inside: avoid;
      background: var(--pdf-bg-warm);
      border: 1px solid var(--pdf-border);
      border-radius: 6px;
      padding: 12px 16px;
      margin-bottom: 20px;
      font-size: 11.5px;
      color: #404d45;
    }

    .packing-box strong {
      color: var(--pdf-green);
      font-size: 12.5px;
      font-family: 'Cormorant Garamond', Georgia, serif;
    }

    .footer-booking-box {
      page-break-inside: avoid;
      break-inside: avoid;
      background: var(--pdf-green);
      color: #f4ede0;
      border-radius: 8px;
      padding: 20px 24px;
      text-align: center;
      margin-top: 24px;
    }

    .footer-booking-title {
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-size: 19px;
      font-weight: 700;
      color: var(--pdf-gold);
      margin-bottom: 5px;
    }

    .footer-booking-text {
      font-size: 12px;
      color: #dbe4dc;
      line-height: 1.5;
      margin-bottom: 12px;
    }

    .footer-contacts-line {
      font-size: 12.5px;
      font-weight: 600;
      color: #ffffff;
    }

    .footer-contacts-line a {
      color: #ffffff;
      text-decoration: none;
    }

    .footer-subtext {
      font-size: 10.5px;
      color: #9cb1a3;
      margin-top: 8px;
    }

    /* Print Styles */
    @media print {
      body {
        background: #ffffff;
        padding: 0;
        margin: 0;
      }

      .dossier-wrapper {
        box-shadow: none;
        padding: 0;
        max-width: 100%;
      }

      .print-actions-bar {
        display: none !important;
      }

      @page {
        margin: 12mm 12mm 15mm 12mm;
        size: A4 portrait;
      }
    }
  </style>
</head>
<body>

  <div class="print-actions-bar">
    <a href="itenaryopen.php?id=<?php echo $tour_id; ?>" class="back-link">
      <i class="fas fa-arrow-left"></i> Back to Interactive Tour Page
    </a>
    <button onclick="window.print()" class="print-btn-primary">
      <i class="fas fa-print"></i> Print / Save as PDF
    </button>
  </div>

  <div class="dossier-wrapper" id="pdfDossierContent">

    <!-- HEADER -->
    <table class="header-table">
      <tr>
        <td style="vertical-align: middle;">
          <img src="../../img/logo.png" alt="Virunga Journeys" style="height: 48px; width: auto; max-width: 220px; object-fit: contain; display: block;">
        </td>
        <td class="header-contacts">
          <strong>www.virungajourneys.com</strong><br>
          WhatsApp: +250 784 513 435<br>
          info@virungajourneys.com
        </td>
      </tr>
    </table>

    <!-- TITLE & METRICS -->
    <div>
      <div class="journey-badge">
        <?php echo htmlspecialchars(strtoupper($tour['category'])); ?> · <?php echo htmlspecialchars(strtoupper($countryName)); ?>
      </div>
      <h1 class="journey-title"><?php echo htmlspecialchars($tour['title']); ?></h1>

      <table class="metrics-table">
        <tr>
          <td>
            <div class="metric-label">Duration</div>
            <div class="metric-value"><?php echo (int)$tour['days_count']; ?> Day<?php echo $tour['days_count'] > 1 ? 's' : ''; ?></div>
          </td>
          <td>
            <div class="metric-label">Destination</div>
            <div class="metric-value"><?php echo htmlspecialchars($countryName); ?></div>
          </td>
          <td>
            <div class="metric-label">Category</div>
            <div class="metric-value"><?php echo htmlspecialchars($tour['category']); ?></div>
          </td>
          <td>
            <div class="metric-label">Format</div>
            <div class="metric-value">Private & Guided</div>
          </td>
        </tr>
      </table>
    </div>

    <!-- COVER PHOTO (FULL WIDTH TOP) -->
    <div style="margin-bottom: 20px; border-radius: 6px; overflow: hidden; border: 1px solid #dcd7cc;">
      <img src="../<?php echo htmlspecialchars($tour['cover_image_path']); ?>" alt="Cover Photo" style="width: 100%; height: 260px; object-fit: cover; display: block;">
    </div>

    <!-- EXECUTIVE OVERVIEW -->
    <div class="overview-box">
      <div class="overview-label">Executive Journey Overview</div>
      <div class="overview-text">"<?php echo htmlspecialchars($tour['short_description']); ?>"</div>
    </div>

    <!-- DAY-BY-DAY ITINERARY -->
    <div class="section-title-bar">
      <div class="section-title-text">Detailed Day-by-Day Itinerary</div>
    </div>

    <?php foreach ($days as $day): ?>
      <div class="day-card">
        <table style="width: 100%; border-collapse: collapse;">
          <tr>
            <td style="width: 68px; vertical-align: top;">
              <span class="day-badge">DAY <?php echo sprintf('%02d', $day['day_number']); ?></span>
            </td>
            <td style="vertical-align: top; padding-left: 8px;">
              <div class="day-title"><?php echo htmlspecialchars($day['day_title']); ?></div>
              <div class="day-desc"><?php echo nl2br(htmlspecialchars($day['day_description'])); ?></div>
            </td>
          </tr>
        </table>
      </div>
    <?php endforeach; ?>

    <?php if (!empty($tour['why_attend'])): ?>
      <!-- WHY CHOOSE THIS JOURNEY -->
      <div class="why-attend-box">
        <div class="why-attend-title">✦ Why Choose This Experience</div>
        <div class="day-desc" style="color: #24352b;"><?php echo nl2br(htmlspecialchars($tour['why_attend'])); ?></div>
      </div>
    <?php endif; ?>

    <!-- INCLUSIONS & EXCLUSIONS -->
    <table class="inclusions-table">
      <tr>
        <td style="width: 50%; vertical-align: top; padding-right: 7px;">
          <div class="inclusions-box">
            <h4>✔ What is Included</h4>
            <ul>
              <?php if (!empty($included)): ?>
                <?php foreach ($included as $inc): ?>
                  <li><?php echo htmlspecialchars($inc['item_description']); ?></li>
                <?php endforeach; ?>
              <?php else: ?>
                <li>Dedicated safari vehicle & expert tour guide</li>
                <li>All scheduled itinerary activities</li>
                <li>Selected accommodation & breakfast</li>
              <?php endif; ?>
            </ul>
          </div>
        </td>
        <td style="width: 50%; vertical-align: top; padding-left: 7px;">
          <div class="exclusions-box">
            <h4>✖ What is Not Included</h4>
            <ul>
              <?php if (!empty($excluded)): ?>
                <?php foreach ($excluded as $exc): ?>
                  <li><?php echo htmlspecialchars($exc['item_description']); ?></li>
                <?php endforeach; ?>
              <?php else: ?>
                <li>International flights and entry visas</li>
                <li>Personal expenses, tips & gratuities</li>
                <li>Unlisted drinks and activities</li>
              <?php endif; ?>
            </ul>
          </div>
        </td>
      </tr>
    </table>

    <?php if (!empty($toBring)): ?>
      <!-- WHAT TO BRING -->
      <div class="packing-box">
        <strong>Recommended Packing & Preparation:</strong> 
        <?php 
        $tbList = [];
        foreach ($toBring as $tb) {
            $tbList[] = htmlspecialchars($tb['item_description']);
        }
        echo implode(' · ', $tbList);
        ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($pricingTiers)): ?>
      <!-- PRICING TIERS -->
      <div style="margin-bottom: 20px; page-break-inside: avoid; break-inside: avoid;">
        <div class="section-title-bar" style="margin-top: 15px;">
          <div class="section-title-text">Standard Rates (<?php echo htmlspecialchars($pricingYear ?? date('Y')); ?>)</div>
        </div>
        <table style="width: 100%; border-collapse: collapse; border: 1px solid var(--pdf-border); font-size: 12px;">
          <thead>
            <tr style="background: var(--pdf-bg-warm);">
              <th style="padding: 8px 12px; border: 1px solid var(--pdf-border); text-align: left;">Group Size</th>
              <th style="padding: 8px 12px; border: 1px solid var(--pdf-border); text-align: right;">Price per Person (USD)</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pricingTiers as $tier): ?>
              <tr>
                <td style="padding: 8px 12px; border: 1px solid var(--pdf-border);"><?php echo htmlspecialchars($tier['group_size']); ?></td>
                <td style="padding: 8px 12px; border: 1px solid var(--pdf-border); text-align: right; font-weight: 700; color: var(--pdf-green);">$<?php echo number_format((float)$tier['price_per_person'], 2); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php if (!empty($pricingNotes)): ?>
          <div style="font-size: 11px; color: #607066; margin-top: 6px;">
            <?php foreach ($pricingNotes as $n): ?>
              <div>• <?php echo htmlspecialchars($n['note']); ?></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($highlights)): ?>
      <!-- JOURNEY HIGHLIGHTS GALLERY (BOTTOM - 4 PER ROW) -->
      <div style="margin-bottom: 22px; page-break-inside: avoid; break-inside: avoid;">
        <div class="section-title-bar" style="margin-bottom: 12px;">
          <div class="section-title-text">Journey Photo Highlights</div>
        </div>
        <?php 
          $highlightRows = array_chunk($highlights, 4);
          foreach ($highlightRows as $rIdx => $row):
            $isLastRow = ($rIdx === count($highlightRows) - 1);
        ?>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: <?php echo $isLastRow ? '0' : '10px'; ?>;">
          <tr>
            <?php for ($i = 0; $i < 4; $i++): 
              $shot = $row[$i] ?? null;
              $padRight = ($i < 3) ? '6px' : '0';
              $padLeft = ($i > 0) ? '6px' : '0';
            ?>
            <td style="width: 25%; padding-right: <?php echo $padRight; ?>; padding-left: <?php echo $padLeft; ?>; vertical-align: top;">
              <?php if ($shot): ?>
                <img src="../<?php echo htmlspecialchars($shot['image_path']); ?>"
                     style="width: 100%; height: 125px; object-fit: cover; display: block; border-radius: 4px; border: 1px solid #dcd7cc;" alt="Highlight">
              <?php endif; ?>
            </td>
            <?php endfor; ?>
          </tr>
        </table>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- PEACE OF MIND & ETHICAL TOURISM -->
    <div style="background: var(--pdf-bg-warm); border-left: 3px solid #7d8e83; padding: 10px 14px; margin-bottom: 20px; font-size: 11px; color: #4e5e54; line-height: 1.5; page-break-inside: avoid;">
      <strong>Guest Safety & Ethics:</strong> Standard emergency medical & evacuation coverage is included on all expeditions. We uphold ethical fair wages, local community reinvestment, and conservation stewardship on every journey.
    </div>

    <!-- BOOKING FOOTER -->
    <div class="footer-booking-box">
      <div class="footer-booking-title">Ready to Plan or Customize This Journey?</div>
      <div class="footer-booking-text">
        Our dedicated journey planners are available to adapt dates, accommodation levels, and private activities to your exact travel wishes.
      </div>
      <div class="footer-contacts-line">
        <span>WhatsApp: +250 784 513 435</span>
        <span style="margin: 0 8px; color: var(--pdf-gold);">·</span>
        <span>Email: info@virungajourneys.com</span>
        <span style="margin: 0 8px; color: var(--pdf-gold);">·</span>
        <span>www.virungajourneys.com</span>
      </div>
      <div class="footer-subtext">
        Virunga Journeys · Musanze, Northern Province, Rwanda · East Africa
      </div>
    </div>
  </div>

  <script>
    window.addEventListener('DOMContentLoaded', () => {
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.get('autoprint') === '1') {
        setTimeout(() => {
          window.print();
        }, 600);
      }
    });
  </script>
</body>
</html>
