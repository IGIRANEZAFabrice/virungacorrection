<?php
/**
 * Virunga Collective — Signature Reusable CTA Component
 * 
 * Configurable variables:
 * - $cta_id             (string) Section HTML ID (default: 'planner')
 * - $cta_eyebrow        (string) Top eyebrow badge (default: 'VIRUNGA COLLECTIVE')
 * - $cta_title          (string) Main headline (default: 'PLAN YOUR JOURNEY')
 * - $cta_lead           (string) Descriptive subtext (default: 'Tell us what you are curious about. We will help you discover the right way to experience the Virunga.')
 * - $cta_primary_text   (string) Primary button label (default: 'PLAN YOUR JOURNEY')
 * - $cta_primary_url    (string) Primary button URL (default: WhatsApp enquiry or build URL)
 * - $cta_primary_icon   (string) Primary button FontAwesome icon (default: 'fab fa-whatsapp')
 * - $cta_secondary_text (string) Secondary button label (default: 'DISCOVER VIRUNGA HOUSE')
 * - $cta_secondary_url  (string) Secondary button URL (default: link to homestays)
 * - $cta_secondary_icon (string) Secondary button FontAwesome icon (default: 'fas fa-arrow-right')
 * - $cta_show_secondary (bool)   Whether to show secondary button (default: true)
 * - $cta_bg_image       (string) Background image URL (default: img/bg.jpg)
 */

if (!isset($baseLink) || !is_callable($baseLink)) {
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $basePath = rtrim(dirname($scriptName), '/\\');
    if (preg_match('#/(pages|ecotours|homestay)(/.*)?$#', $basePath)) {
        $basePath = preg_replace('#/(pages|ecotours|homestay)(/.*)?$#', '', $basePath);
    }
    $basePath = $basePath === '/' ? '' : $basePath;
    $baseLink = static function (string $target = '') use ($basePath): string {
        $target = ltrim($target, '/');
        return ($basePath === '' ? '' : $basePath) . '/' . $target;
    };
}

// Set default props if not already defined
$cta_id = $cta_id ?? 'planner';
$cta_eyebrow = $cta_eyebrow ?? 'VIRUNGA COLLECTIVE';
$cta_title = $cta_title ?? 'PLAN YOUR JOURNEY';
$cta_lead = $cta_lead ?? 'Tell us what you are curious about. We will help you discover the right way to experience the Virunga.';
$cta_primary_text = $cta_primary_text ?? 'PLAN YOUR JOURNEY';
$cta_primary_url = $cta_primary_url ?? 'https://wa.me/250784513435?text=' . urlencode('Hello Virunga Collective, I would like to plan my journey.');
$cta_primary_icon = $cta_primary_icon ?? 'fab fa-whatsapp';
$cta_secondary_text = $cta_secondary_text ?? 'DISCOVER VIRUNGA HOUSE';
$cta_secondary_url = $cta_secondary_url ?? $baseLink('homestays');
$cta_secondary_icon = $cta_secondary_icon ?? 'fas fa-arrow-right';
$cta_show_secondary = $cta_show_secondary ?? true;
$cta_bg_image = $cta_bg_image ?? $baseLink('img/bg.jpg');

$is_external_primary = (strpos($cta_primary_url, 'http://') === 0 || strpos($cta_primary_url, 'https://') === 0 || strpos($cta_primary_url, 'wa.me') !== false);
$is_external_secondary = (strpos($cta_secondary_url, 'http://') === 0 || strpos($cta_secondary_url, 'https://') === 0);
?>

<style>
  /* ---------- Reusable Signature CTA Component Styles ---------- */
  .vc-cta-section {
    position: relative;
    padding: clamp(60px, 8vw, 110px) 0;
    background-color: #08140e;
    background-image: url('<?php echo htmlspecialchars($cta_bg_image, ENT_QUOTES, 'UTF-8'); ?>');
    background-repeat: no-repeat;
    background-position: center center;
    background-size: cover;
    color: #f6f2e9;
    text-align: center;
    overflow: hidden;
  }
  .vc-cta-overlay {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at center, rgba(18, 42, 31, 0.88) 0%, rgba(8, 20, 14, 0.97) 100%);
    z-index: 1;
  }
  .vc-cta-content {
    position: relative;
    z-index: 2;
    max-width: 820px;
    margin: 0 auto;
    padding: 0 20px;
  }
  .vc-cta-eyebrow {
    display: inline-block;
    font-family: "Jost", sans-serif;
    font-size: clamp(0.68rem, 2vw, 0.76rem);
    font-weight: 600;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #c9a24b;
    margin-bottom: 12px;
  }
  .vc-cta-title {
    font-family: "Cormorant Garamond", Georgia, serif;
    font-size: clamp(1.6rem, 4.5vw, 2.8rem);
    font-weight: 500;
    color: #ffffff;
    line-height: 1.18;
    margin-bottom: 14px;
    letter-spacing: 0.01em;
    text-transform: uppercase;
  }
  .vc-cta-lead {
    font-family: "Jost", sans-serif;
    font-size: clamp(0.88rem, 1.3vw, 1.06rem);
    color: rgba(246, 242, 233, 0.9);
    line-height: 1.6;
    margin: 0 auto 28px;
    max-width: 580px;
    font-weight: 300;
  }
  .vc-cta-actions {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    margin: 0 auto;
  }
  .vc-cta-section .btn,
  .vc-cta-btn-primary,
  .vc-cta-btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 4px;
    font-family: "Jost", sans-serif;
    font-size: 0.82rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    box-sizing: border-box;
    cursor: pointer;
  }
  .vc-cta-btn-primary {
    background: #c9a24b;
    color: #0e2118 !important;
    border: 1px solid #c9a24b;
    box-shadow: 0 4px 18px rgba(201, 162, 75, 0.25);
  }
  .vc-cta-btn-primary:hover {
    background: #deb862;
    border-color: #deb862;
    color: #08140e !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(201, 162, 75, 0.4);
  }
  .vc-cta-btn-secondary {
    background: rgba(246, 242, 233, 0.06);
    color: #f6f2e9 !important;
    border: 1px solid rgba(246, 242, 233, 0.35);
    font-weight: 500;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
  }
  .vc-cta-btn-secondary:hover {
    background: rgba(246, 242, 233, 0.14);
    border-color: #c9a24b;
    color: #ffffff !important;
    transform: translateY(-2px);
  }
  @media (max-width: 680px) {
    .vc-cta-section {
      padding: 50px 0;
    }
    .vc-cta-content {
      padding: 0 16px;
    }
    .vc-cta-actions {
      flex-direction: column;
      width: 100%;
      max-width: 320px;
      gap: 10px;
    }
    .vc-cta-section .btn,
    .vc-cta-btn-primary,
    .vc-cta-btn-secondary {
      width: 100%;
      max-width: 100%;
      padding: 11px 16px;
      font-size: 0.78rem;
      letter-spacing: 0.06em;
      min-height: auto;
    }
  }
  @media (max-width: 360px) {
    .vc-cta-section {
      padding: 40px 0;
    }
    .vc-cta-content {
      padding: 0 12px;
    }
    .vc-cta-title {
      font-size: 1.4rem;
      margin-bottom: 10px;
    }
    .vc-cta-lead {
      font-size: 0.82rem;
      margin-bottom: 20px;
      line-height: 1.5;
    }
    .vc-cta-actions {
      max-width: 100%;
      gap: 8px;
    }
    .vc-cta-section .btn,
    .vc-cta-btn-primary,
    .vc-cta-btn-secondary {
      padding: 9px 12px;
      font-size: 0.72rem;
      letter-spacing: 0.04em;
      gap: 6px;
    }
  }
</style>

<section class="vc-cta-section sec-final" id="<?php echo htmlspecialchars($cta_id, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="vc-cta-overlay"></div>
  <div class="vc-cta-content">
    <?php if (!empty($cta_eyebrow)): ?>
      <span class="vc-cta-eyebrow reveal"><?php echo htmlspecialchars($cta_eyebrow, ENT_QUOTES, 'UTF-8'); ?></span>
    <?php endif; ?>
    
    <h2 class="vc-cta-title final-title reveal">
      <?php echo htmlspecialchars($cta_title, ENT_QUOTES, 'UTF-8'); ?>
    </h2>
    
    <p class="vc-cta-lead final-lead reveal" style="--reveal-delay: 0.12s;">
      <?php echo htmlspecialchars($cta_lead, ENT_QUOTES, 'UTF-8'); ?>
    </p>
    
    <div class="vc-cta-actions reveal" style="--reveal-delay: 0.22s;">
      <a 
        href="<?php echo htmlspecialchars($cta_primary_url, ENT_QUOTES, 'UTF-8'); ?>" 
        class="vc-cta-btn-primary btn btn-solid"
        <?php echo $is_external_primary ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
      >
        <?php if (!empty($cta_primary_icon)): ?>
          <i class="<?php echo htmlspecialchars($cta_primary_icon, ENT_QUOTES, 'UTF-8'); ?>" aria-hidden="true"></i>
        <?php endif; ?>
        <span><?php echo htmlspecialchars($cta_primary_text, ENT_QUOTES, 'UTF-8'); ?></span>
      </a>

      <?php if ($cta_show_secondary && !empty($cta_secondary_text)): ?>
        <a 
          href="<?php echo htmlspecialchars($cta_secondary_url, ENT_QUOTES, 'UTF-8'); ?>" 
          class="vc-cta-btn-secondary btn"
          <?php echo $is_external_secondary ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
        >
          <span><?php echo htmlspecialchars($cta_secondary_text, ENT_QUOTES, 'UTF-8'); ?></span>
          <?php if (!empty($cta_secondary_icon)): ?>
            <i class="<?php echo htmlspecialchars($cta_secondary_icon, ENT_QUOTES, 'UTF-8'); ?>" aria-hidden="true"></i>
          <?php endif; ?>
        </a>
      <?php endif; ?>
    </div>
  </div>
</section>
