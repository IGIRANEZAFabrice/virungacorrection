<?php
/** Canonical URLs must retain parameters that identify distinct public content. */
function virunga_canonical_url(string $path): string {
    $query = [];
    $name = pathinfo($path, PATHINFO_FILENAME);
    $parameters = [
        'blogdetails' => ['slug'], 'activitydetails' => ['id'],
        'blogopen' => ['id'], 'itenaryopen' => ['id'],
        'itinerary_print' => ['id'], 'attraction' => ['id'],
        'activity-detail' => ['id'], 'styleguideopen' => ['id'],
        'itenary' => ['country', 'type', 'category', 'page'],
        'travelmonth' => ['month'], 'blog' => ['page', 'category']
    ];
    foreach ($parameters[$name] ?? [] as $key) {
        if (isset($_GET[$key]) && is_scalar($_GET[$key]) && (string) $_GET[$key] !== '') {
            $query[$key] = (string) $_GET[$key];
        }
    }
    ksort($query);
    return 'https://virungajourneys.com/' . ltrim($path, '/')
        . ($query ? '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986) : '');
}
function virunga_canonical_tag(string $path): void {
    echo '<link rel="canonical" href="' . htmlspecialchars(virunga_canonical_url($path), ENT_QUOTES, 'UTF-8') . '">' . "\n";
}
