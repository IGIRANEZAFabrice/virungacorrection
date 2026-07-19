<?php
session_start();

$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$basePath = $basePath === '/' ? '' : $basePath;

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($basePath !== '' && strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}
$slug = trim($path, '/');

if ($slug === '') {
    $slug = 'home';
}

$link = function (string $target = '') use ($basePath): string {
    $target = ltrim($target, '/');
    return ($basePath === '' ? '' : $basePath) . '/' . $target;
};

$pages = [
    'home' => ['file' => __DIR__ . '/pages/home.php', 'cwd' => null, 'assetBase' => $link('')],
    'homestays' => ['file' => __DIR__ . '/homestay/pages/home.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'stays' => ['file' => __DIR__ . '/homestay/pages/home.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'about-us' => ['file' => __DIR__ . '/pages/about.php', 'cwd' => null, 'assetBase' => $link('')],
    'about' => ['file' => __DIR__ . '/pages/about.php', 'cwd' => null, 'assetBase' => $link('')],
    'rooms' => ['file' => __DIR__ . '/homestay/pages/rooms.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'houserules' => ['file' => __DIR__ . '/homestay/pages/houserules.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'shop' => ['file' => __DIR__ . '/homestay/pages/shop.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'contact' => ['file' => __DIR__ . '/homestay/pages/contact.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'contact-us' => ['file' => __DIR__ . '/pages/contact.php', 'cwd' => null, 'assetBase' => $link('')],
    'carrent' => ['file' => __DIR__ . '/homestay/pages/carrent.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'cars' => ['file' => __DIR__ . '/homestay/pages/carrent.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'activity' => ['file' => __DIR__ . '/homestay/pages/activity.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'activities' => ['file' => __DIR__ . '/homestay/pages/activity.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'activitydetails' => ['file' => __DIR__ . '/homestay/pages/activitydetails.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'blog' => ['file' => __DIR__ . '/homestay/pages/blog.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'blogs' => ['file' => __DIR__ . '/homestay/pages/blog.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'blogdetails' => ['file' => __DIR__ . '/homestay/pages/blogdetails.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'bookinginfo' => ['file' => __DIR__ . '/homestay/pages/bookinginfo.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'rules' => ['file' => __DIR__ . '/homestay/pages/houserules.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'impact' => ['file' => __DIR__ . '/homestay/pages/impact.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'safety' => ['file' => __DIR__ . '/homestay/pages/safety.php', 'cwd' => __DIR__ . '/homestay/pages', 'assetBase' => $link('homestay/')],
    'membership' => ['file' => __DIR__ . '/pages/membership.php', 'cwd' => null, 'assetBase' => $link('')],
    'ecotours/community' => ['file' => __DIR__ . '/ecotours/community/index.php', 'cwd' => __DIR__ . '/ecotours/community', 'assetBase' => $link('ecotours/community/')],
    'experiences' => ['file' => __DIR__ . '/ecotours/index.php', 'cwd' => __DIR__ . '/ecotours', 'assetBase' => $link('ecotours/')],
    'journeys' => ['file' => __DIR__ . '/ecotours/index.php', 'cwd' => __DIR__ . '/ecotours', 'assetBase' => $link('ecotours/')],
];

$renderPage = function (array $page, string $currentSlug) use ($link): void {
    $previousDir = null;

    if (!empty($page['cwd'])) {
        $previousDir = getcwd();
        chdir($page['cwd']);
    }

    $baseLink = $link;
    $assetBase = $page['assetBase'] ?? $link('');
    $currentSection = (strpos($currentSlug, 'ecotours') === 0) ? 'ecotours' : (strpos($page['assetBase'] ?? '', 'homestay') !== false ? 'homestay' : 'main');

    include $page['file'];

    if ($previousDir !== null && $previousDir !== false) {
        chdir($previousDir);
    }
};

if (isset($pages[$slug]) && file_exists($pages[$slug]['file'])) {
    $renderPage($pages[$slug], $slug);
    exit;
}

http_response_code(404);
$pageTitle = '404 - Page Not Found';
$baseLink = $link;
$assetBase = $link('');

if (file_exists(__DIR__ . '/pages/404.php')) {
    include __DIR__ . '/pages/404.php';
} else {
    include __DIR__ . '/pages/home.php';
}

exit;
