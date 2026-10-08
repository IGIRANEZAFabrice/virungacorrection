<?php
// Older edit saves escaped newlines before binding text to prepared statements.
// Decode those separators without removing legitimate characters or backslashes.
function normalizeStoredBlogText($value) {
    return str_replace(['\\r\\n', '\\n', '\\r'], "\n", (string) $value);
}
