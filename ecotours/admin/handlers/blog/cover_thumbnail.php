<?php
// Cached card previews keep the article's original cover untouched.
$name = basename((string) ($_GET['file'] ?? ''));
$source = __DIR__ . '/../../images/blog/covers/' . $name;
if ($name === '' || !is_file($source)) {
    http_response_code(404);
    exit;
}
$info = @getimagesize($source);
if (!$info) {
    http_response_code(404);
    exit;
}
$cacheDir = __DIR__ . '/../../images/blog/thumbnails';
$cache = $cacheDir . '/' . hash('sha256', $name . filemtime($source) . '-800-v1') . '.jpg';
if (!is_file($cache) && function_exists('imagecreatetruecolor')) {
    if (is_dir($cacheDir) || @mkdir($cacheDir, 0755, true)) {
        $loader = [IMAGETYPE_JPEG => 'imagecreatefromjpeg', IMAGETYPE_PNG => 'imagecreatefrompng', IMAGETYPE_GIF => 'imagecreatefromgif', IMAGETYPE_WEBP => 'imagecreatefromwebp'][$info[2]] ?? null;
        // Avoid decoding enormous uploads beyond the available PHP memory.
        $limit = ini_get('memory_limit');
        $bytes = (int) $limit;
        if (stripos($limit, 'G') !== false) $bytes *= 1024 * 1024 * 1024;
        elseif (stripos($limit, 'M') !== false) $bytes *= 1024 * 1024;
        elseif (stripos($limit, 'K') !== false) $bytes *= 1024;
        $estimated = $info[0] * $info[1] * 8 + 16 * 1024 * 1024;
        if ($loader && function_exists($loader) && ($bytes < 0 || memory_get_usage(true) + $estimated < $bytes)) {
            $original = @$loader($source);
            if ($original) {
                $width = min(800, $info[0]);
                $height = max(1, (int) round($info[1] * $width / $info[0]));
                $preview = imagecreatetruecolor($width, $height);
                imagefill($preview, 0, 0, imagecolorallocate($preview, 255, 255, 255));
                imagecopyresampled($preview, $original, 0, 0, 0, 0, $width, $height, $info[0], $info[1]);
                $temp = tempnam($cacheDir, 'preview-');
                if ($temp !== false) {
                    if (imagejpeg($preview, $temp, 78)) @rename($temp, $cache);
                    if (is_file($temp)) @unlink($temp);
                }
                imagedestroy($preview);
                imagedestroy($original);
            }
        }
    }
}
$file = is_file($cache) ? $cache : $source;
header('Content-Type: ' . ($file === $cache ? 'image/jpeg' : $info['mime']));
header('Cache-Control: public, max-age=86400');
header('Content-Length: ' . filesize($file));
readfile($file);
