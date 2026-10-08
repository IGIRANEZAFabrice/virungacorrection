<?php
/** Apply current brand labels to public output, including existing CMS content. */
function virunga_current_brand_html(string $html): string {
    $html = preg_replace('/<p\b[^>]*data-brand-history[^>]*>.*?<\/p>/is', '', $html);
    $html = preg_replace('/Virunga\s+(?:Journeys|Ecotours)\s+was\s+formerly\s+known\s+as\s+Virunga\s+Ecotours\.?/i', '', $html);
    $html = preg_replace('/Formerly\s+Virunga\s+Ecotours\s+and\s+Virunga\s+Homestays?\.?/i', '', $html);
    $html = preg_replace('/Virunga\s+Ecotours\s+Training\s+Institute/i', 'Virunga Academy', $html);
    $html = preg_replace('/Virunga\s+Ecotours/i', 'Virunga Journeys', $html);
    $html = preg_replace('/Virunga\s+Homestays?\b/i', 'Virunga House', $html);
    return $html;
}
ob_start('virunga_current_brand_html');
