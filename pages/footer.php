<?php
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

$footerCssHref = htmlspecialchars($baseLink('pages/footer.css'), ENT_QUOTES, 'UTF-8');
$footerUrl = static function (string $target = '') use ($baseLink): string {
    return htmlspecialchars($baseLink($target), ENT_QUOTES, 'UTF-8');
};
?>
<link rel="stylesheet" href="<?php echo $footerCssHref; ?>">

<footer class="vc-footer">
  <div class="vc-footer__inner">
    <div class="vc-footer__top">
      <section class="vc-footer__brand" aria-label="Virunga Collective">
        <h2>Virunga Collective</h2>
        <p>Meaningful journeys shaped by wildlife, landscapes, people, culture and place.</p>
      </section>

      <nav class="vc-footer__groups" aria-label="Footer navigation">
        <section class="vc-footer__group">
          <h3>Discover</h3>
          <ul>
            <li><a href="<?php echo $footerUrl('experiences'); ?>">Experiences</a></li>
            <li><a href="<?php echo $footerUrl('homestays'); ?>">Stay</a></li>
            <li><a href="<?php echo $footerUrl('about'); ?>">Our story</a></li>
            <li><a href="<?php echo $footerUrl('ecotours/pages/blog.php'); ?>">The Virunga Journal</a></li>
            <li><a href="<?php echo $footerUrl('impact'); ?>">Virunga impact</a></li>
          </ul>
        </section>

        <section class="vc-footer__group">
          <h3>Plan</h3>
          <ul>
            <li><a href="<?php echo $footerUrl('ecotours/pages/build.php'); ?>">Plan your journey</a></li>
            <li><a href="<?php echo $footerUrl('ecotours/pages/travelmonth.php'); ?>">Getting here</a></li>
            <li><a href="<?php echo $footerUrl('ecotours/pages/travelmonth.php'); ?>">When to visit</a></li>
            <li><a href="<?php echo $footerUrl('faq'); ?>">FAQs</a></li>
            <li><a href="<?php echo $footerUrl('contact-us'); ?>">Contact</a></li>
          </ul>
        </section>

        <section class="vc-footer__group vc-footer__connect">
          <h3>Connect</h3>
          <ul>
            <li><a href="mailto:info@virungajourneys.com">info@virungajourneys.com</a></li>
          </ul>

          <div class="vc-footer__contact-icons">
            <a href="https://wa.me/250784513435" target="_blank" rel="noopener" aria-label="WhatsApp">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 12a8 8 0 1 1-3.6-6.66"/><path d="M20 4l-4.5 8L12 9"/></svg>
            </a>
            <a href="tel:+250784513435" aria-label="Call">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L14 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 2 6a2 2 0 0 1 2-2z"/></svg>
            </a>
          </div>

          <a class="vc-footer__cta" href="<?php echo $footerUrl('ecotours/pages/build.php'); ?>">
            PLAN YOUR JOURNEY
            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
          </a>
        </section>
      </nav>
    </div>

    <div class="vc-footer__bottom">
      <div style="display:flex; gap:8px;" aria-label="Social media">
        <a href="https://www.instagram.com/virunga_ecotours?igsh=YWtnY3FmZjcwdzFl&utm_source=qr" target="_blank" rel="noopener" aria-label="Instagram" class="vc-footer__contact-icons" style="margin:0;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
        </a>
        <a href="https://www.facebook.com/VirungaPrograms?mibextid=LQQJ4d" target="_blank" rel="noopener" aria-label="Facebook" class="vc-footer__contact-icons" style="margin:0;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M15 8h-2a2 2 0 0 0-2 2v10M9 13h4"/><path d="M15 4H8a4 4 0 0 0-4 4v12a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4V8a4 4 0 0 0-4-4z"/></svg>
        </a>
        <a href="https://www.linkedin.com/in/virunga-ecotours-863a221b1?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app" target="_blank" rel="noopener" aria-label="LinkedIn" class="vc-footer__contact-icons" style="margin:0;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M7 10v6M7 7v.01M12 16v-3.5a2 2 0 0 1 4 0V16M12 12.5V16"/></svg>
        </a>
      </div>

      <nav class="vc-footer__legal" aria-label="Legal links">
        <a href="<?php echo $footerUrl('rules'); ?>">Privacy policy</a>
        <a href="<?php echo $footerUrl('rules'); ?>">Terms &amp; conditions</a>
        <a href="<?php echo $footerUrl('cancellation-policy'); ?>">Cancellation policy</a>
        <a href="<?php echo $footerUrl('ecotours/pages/sustainability.php'); ?>">Responsible travel</a>
      </nav>

      <p class="vc-footer__copyright">&copy; 2026 Virunga Collective</p>
    </div>
  </div>
</footer>