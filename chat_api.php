<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

const FALLBACK_REPLY = 'I can only assist with questions regarding Virunga Collective offerings. For other inquiries or personalized assistance, please contact us directly through our Contact page.';

function send_json(array $payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function load_env_file(string $path): void
{
    if (!is_readable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");

        if ($key !== '' && getenv($key) === false) {
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
        }
    }
}

function env_value(string $key, string $default = ''): string
{
    $value = getenv($key);
    return $value === false ? $default : $value;
}

function connect_pdo(string $prefix): ?PDO
{
    $host = env_value($prefix . '_DB_HOST', 'localhost');
    $name = env_value($prefix . '_DB_NAME');
    $user = env_value($prefix . '_DB_USER', 'root');
    $pass = env_value($prefix . '_DB_PASS', '');

    if ($name === '') {
        return null;
    }

    try {
        return new PDO(
            "mysql:host={$host};dbname={$name};charset=utf8mb4",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    } catch (Throwable $e) {
        error_log("AI chat DB connection failed ({$prefix}): " . $e->getMessage());
        return null;
    }
}

function table_columns(PDO $pdo, string $table): array
{
    static $cache = [];
    $key = spl_object_id($pdo) . ':' . $table;

    if (isset($cache[$key])) {
        return $cache[$key];
    }

    try {
        $stmt = $pdo->prepare('SHOW COLUMNS FROM `' . str_replace('`', '``', $table) . '`');
        $stmt->execute();
        $cache[$key] = array_column($stmt->fetchAll(), 'Field');
        return $cache[$key];
    } catch (Throwable $e) {
        $cache[$key] = [];
        return [];
    }
}

function has_columns(PDO $pdo, string $table, array $required): bool
{
    $columns = table_columns($pdo, $table);
    return $columns !== [] && count(array_intersect($required, $columns)) === count($required);
}

function safe_text(?string $value, int $limit = 900): string
{
    $value = trim(html_entity_decode(strip_tags((string) $value), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $value = preg_replace('/\s+/', ' ', $value) ?? '';
    return mb_strlen($value) > $limit ? mb_substr($value, 0, $limit) . '...' : $value;
}

function fetch_rows(PDO $pdo, string $sql, array $params = []): array
{
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        error_log('AI chat query failed: ' . $e->getMessage());
        return [];
    }
}

function add_section(string &$context, string $title, array $fields): void
{
    $lines = [];
    foreach ($fields as $label => $value) {
        $value = safe_text((string) $value);
        if ($value !== '') {
            $lines[] = "{$label}: {$value}";
        }
    }

    if ($lines !== []) {
        $context .= $title . "\n" . implode("\n", $lines) . "\n\n";
    }
}

function build_context(?PDO $eco, ?PDO $home): string
{
    $context = "=== VIRUNGA COLLECTIVE DATABASE KNOWLEDGE ===\n\n";

    if ($eco) {
        if (has_columns($eco, 'tours', ['tour_id', 'title'])) {
            $tours = fetch_rows($eco, 'SELECT * FROM tours ORDER BY created_at DESC LIMIT 200');
            foreach ($tours as $tour) {
                add_section($context, 'TOUR', [
                    'Title' => $tour['title'] ?? '',
                    'Country' => $tour['country'] ?? '',
                    'Category' => $tour['category'] ?? '',
                    'Days' => $tour['days_count'] ?? '',
                    'Description' => $tour['short_description'] ?? '',
                    'Why attend' => $tour['why_attend'] ?? '',
                ]);

                $tourId = $tour['tour_id'] ?? null;
                if ($tourId && has_columns($eco, 'tour_days', ['tour_id', 'day_number', 'day_title', 'day_description'])) {
                    foreach (fetch_rows($eco, 'SELECT day_number, day_title, day_description FROM tour_days WHERE tour_id = ? ORDER BY day_number', [$tourId]) as $day) {
                        $context .= 'Tour day ' . safe_text((string) $day['day_number'], 20) . ': ' . safe_text($day['day_title'] ?? '') . ' - ' . safe_text($day['day_description'] ?? '') . "\n";
                    }
                }
                if ($tourId && has_columns($eco, 'tour_included', ['tour_id', 'item_description'])) {
                    $included = array_map(fn($row) => safe_text($row['item_description'] ?? '', 180), fetch_rows($eco, 'SELECT item_description FROM tour_included WHERE tour_id = ?', [$tourId]));
                    if ($included !== []) {
                        $context .= 'Included: ' . implode(', ', array_filter($included)) . "\n";
                    }
                }
                if ($tourId && has_columns($eco, 'tour_excluded', ['tour_id', 'item_description'])) {
                    $excluded = array_map(fn($row) => safe_text($row['item_description'] ?? '', 180), fetch_rows($eco, 'SELECT item_description FROM tour_excluded WHERE tour_id = ?', [$tourId]));
                    if ($excluded !== []) {
                        $context .= 'Excluded: ' . implode(', ', array_filter($excluded)) . "\n";
                    }
                }
                $context .= "\n";
            }
        }

        if (has_columns($eco, 'accommodations', ['name'])) {
            foreach (fetch_rows($eco, 'SELECT * FROM accommodations WHERE COALESCE(is_active, 1) = 1 LIMIT 120') as $acc) {
                add_section($context, 'ACCOMMODATION', [
                    'Name' => $acc['name'] ?? '',
                    'Location' => $acc['location'] ?? '',
                    'Type' => $acc['accommodation_type'] ?? '',
                    'Price' => $acc['price_display'] ?? '',
                    'Capacity' => $acc['guest_capacity'] ?? '',
                    'Description' => $acc['short_description'] ?? ($acc['description'] ?? ''),
                    'Includes' => $acc['includes'] ?? '',
                ]);
            }
        }

        if (has_columns($eco, 'destinations', ['name'])) {
            foreach (fetch_rows($eco, 'SELECT * FROM destinations LIMIT 120') as $dest) {
                add_section($context, 'DESTINATION', [
                    'Name' => $dest['name'] ?? '',
                    'Country' => $dest['country_code'] ?? ($dest['country'] ?? ''),
                    'Description' => $dest['description'] ?? '',
                ]);
            }
        }

        if (has_columns($eco, 'blog_posts', ['title'])) {
            foreach (fetch_rows($eco, "SELECT * FROM blog_posts WHERE COALESCE(status, 'published') = 'published' ORDER BY created_at DESC LIMIT 80") as $blog) {
                add_section($context, 'ECOTOURS BLOG', [
                    'Title' => $blog['title'] ?? '',
                    'Summary' => $blog['introduction'] ?? ($blog['excerpt'] ?? ($blog['content'] ?? '')),
                ]);
            }
        }
    }

    if ($home) {
        if (has_columns($home, 'activities', ['title'])) {
            foreach (fetch_rows($home, "SELECT * FROM activities WHERE COALESCE(status, 'active') = 'active' ORDER BY display_order ASC, id DESC LIMIT 120") as $activity) {
                add_section($context, 'HOMESTAY ACTIVITY', [
                    'Title' => $activity['title'] ?? '',
                    'Tag' => $activity['tag'] ?? '',
                    'Duration' => $activity['duration'] ?? '',
                    'Age group' => $activity['age_group'] ?? '',
                    'Group size' => $activity['group_size'] ?? '',
                    'Price' => $activity['price'] ?? '',
                    'Description' => $activity['short_description'] ?? ($activity['long_description'] ?? ''),
                ]);
            }
        }

        if (has_columns($home, 'rooms', ['title'])) {
            foreach (fetch_rows($home, "SELECT * FROM rooms WHERE COALESCE(status, 'active') = 'active' ORDER BY id DESC LIMIT 80") as $room) {
                add_section($context, 'HOMESTAY ROOM', [
                    'Title' => $room['title'] ?? '',
                    'Price' => $room['price'] ?? ($room['price_display'] ?? ''),
                    'Capacity' => $room['capacity'] ?? '',
                    'Description' => $room['description'] ?? '',
                ]);
            }
        }

        if (has_columns($home, 'shop_items', ['title'])) {
            foreach (fetch_rows($home, "SELECT * FROM shop_items WHERE COALESCE(status, 'active') = 'active' ORDER BY id DESC LIMIT 120") as $item) {
                add_section($context, 'SHOP ITEM', [
                    'Title' => $item['title'] ?? '',
                    'Category' => $item['category'] ?? '',
                    'Tag' => $item['tag'] ?? '',
                    'Price' => $item['price'] ?? '',
                    'Description' => $item['description'] ?? '',
                ]);
            }
        }

        if (has_columns($home, 'cars', ['title'])) {
            foreach (fetch_rows($home, "SELECT * FROM cars WHERE COALESCE(status, 'active') = 'active' ORDER BY display_order ASC, id DESC LIMIT 80") as $car) {
                add_section($context, 'CAR RENTAL', [
                    'Title' => $car['title'] ?? '',
                    'Price' => $car['price'] ?? '',
                    'Description' => $car['description'] ?? '',
                ]);
            }
        }

        if (has_columns($home, 'blogs', ['title'])) {
            foreach (fetch_rows($home, "SELECT * FROM blogs WHERE COALESCE(status, 'published') = 'published' ORDER BY created_at DESC LIMIT 80") as $blog) {
                add_section($context, 'HOMESTAY BLOG', [
                    'Title' => $blog['title'] ?? '',
                    'Summary' => $blog['sub_title'] ?? ($blog['content'] ?? ''),
                ]);
            }
        }
    }

    return $context;
}

load_env_file(__DIR__ . '/.env');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    send_json(['response' => 'Please send a chat message using the website assistant.'], 405);
}

$input = json_decode(file_get_contents('php://input') ?: '', true);
$userMessage = trim((string) ($input['message'] ?? ''));

if ($userMessage === '') {
    send_json(['response' => 'Please ask a question.']);
}

if (mb_strlen($userMessage) > 1200) {
    send_json(['response' => 'Please keep your question a little shorter so I can help clearly.'], 422);
}

$apiKey = env_value('GEMINI_API_KEY');
if ($apiKey === '') {
    send_json(['response' => 'The AI assistant is not configured yet. Please contact us directly through our Contact page.'], 500);
}

$context = build_context(connect_pdo('ECOTOURS'), connect_pdo('HOMESTAY'));
$systemInstruction = "You are the official Virunga Collective AI Assistant. Answer using ONLY the provided database context. If the answer is not in the context, reply exactly: \"" . FALLBACK_REPLY . "\" Be concise, warm, and accurate. Do not invent prices, dates, availability, policies, or facts.";

$payload = [
    'contents' => [
        [
            'role' => 'user',
            'parts' => [
                [
                    'text' => $systemInstruction . "\n\n" . $context . "\n\nUSER QUESTION: " . $userMessage,
                ],
            ],
        ],
    ],
    'generationConfig' => [
        'temperature' => 0.2,
        'maxOutputTokens' => 650,
    ],
];

$model = rawurlencode(env_value('GEMINI_MODEL', 'gemini-flash-latest'));
$url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'X-goog-api-key: ' . $apiKey,
    ],
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 35,
]);

$response = curl_exec($ch);
$curlError = curl_error($ch);
$statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false || $statusCode >= 400) {
    error_log('Gemini API error: HTTP ' . $statusCode . ' ' . $curlError . ' ' . (string) $response);
    send_json(['response' => "I couldn't process your request right now. Please contact us directly through our Contact page."], 502);
}

$data = json_decode($response, true);
$reply = trim((string) ($data['candidates'][0]['content']['parts'][0]['text'] ?? ''));

send_json(['response' => $reply !== '' ? $reply : FALLBACK_REPLY]);
