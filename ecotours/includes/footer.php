<?php
$baseLink = static function (string $target = ''): string {
    return '../' . ltrim($target, '/');
};

include __DIR__ . '/../../pages/footer.php';
?>
