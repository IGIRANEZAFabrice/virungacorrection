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

        if ($key !== '') {
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

function env_value(string $key, string $default = ''): string
{
    $val = $_ENV[$key] ?? ($_SERVER[$key] ?? getenv($key));
    if ($val === false || $val === null || $val === '') {
        return $default;
    }
    return (string) $val;
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
            $tours = fetch_rows($eco, 'SELECT * FROM tours ORDER BY CAST(days_count AS UNSIGNED) DESC, tour_id DESC LIMIT 60');
            foreach ($tours as $tour) {
                $daysCount = (int) ($tour['days_count'] ?? 1);
                $context .= "TOUR: " . safe_text($tour['title'] ?? '') . " | Duration: {$daysCount} Days | Country: " . safe_text($tour['country'] ?? '') . " | Category: " . safe_text($tour['category'] ?? '') . "\n";
                if (!empty($tour['short_description'])) {
                    $context .= "Description: " . safe_text($tour['short_description'], 300) . "\n";
                }

                $tourId = $tour['tour_id'] ?? null;
                if ($tourId && has_columns($eco, 'tour_days', ['tour_id', 'day_number', 'day_title', 'day_description'])) {
                    $daysList = [];
                    foreach (fetch_rows($eco, 'SELECT day_number, day_title, day_description FROM tour_days WHERE tour_id = ? ORDER BY day_number', [$tourId]) as $day) {
                        $daysList[] = 'Day ' . $day['day_number'] . ': ' . safe_text($day['day_title'] ?? '', 50) . ' (' . safe_text($day['day_description'] ?? '', 100) . ')';
                    }
                    if ($daysList !== []) {
                        $context .= "Itinerary:\n - " . implode("\n - ", $daysList) . "\n";
                    }
                }
                if ($tourId && has_columns($eco, 'tour_included', ['tour_id', 'item_description'])) {
                    $included = array_map(fn($row) => safe_text($row['item_description'] ?? '', 160), fetch_rows($eco, 'SELECT item_description FROM tour_included WHERE tour_id = ?', [$tourId]));
                    $included = array_filter($included);
                    if ($included !== []) {
                        $context .= "INCLUDED ITEMS:\n - " . implode("\n - ", $included) . "\n";
                    }
                }
                if ($tourId && has_columns($eco, 'tour_excluded', ['tour_id', 'item_description'])) {
                    $excluded = array_map(fn($row) => safe_text($row['item_description'] ?? '', 160), fetch_rows($eco, 'SELECT item_description FROM tour_excluded WHERE tour_id = ?', [$tourId]));
                    $excluded = array_filter($excluded);
                    if ($excluded !== []) {
                        $context .= "EXCLUDED ITEMS:\n - " . implode("\n - ", $excluded) . "\n";
                    }
                }
                $context .= "\n";
            }
        }

        if (has_columns($eco, 'accommodations', ['name'])) {
            foreach (fetch_rows($eco, 'SELECT * FROM accommodations WHERE COALESCE(is_active, 1) = 1 LIMIT 40') as $acc) {
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
            foreach (fetch_rows($eco, 'SELECT * FROM destinations LIMIT 30') as $dest) {
                add_section($context, 'DESTINATION', [
                    'Name' => $dest['name'] ?? '',
                    'Country' => $dest['country_code'] ?? ($dest['country'] ?? ''),
                    'Description' => $dest['description'] ?? '',
                ]);
            }
        }
    }

    if ($home) {
        if (has_columns($home, 'activities', ['title'])) {
            foreach (fetch_rows($home, "SELECT * FROM activities WHERE COALESCE(status, 'active') = 'active' ORDER BY display_order ASC, id DESC LIMIT 40") as $activity) {
                add_section($context, 'HOMESTAY ACTIVITY', [
                    'Title' => $activity['title'] ?? '',
                    'Tag' => $activity['tag'] ?? '',
                    'Duration' => $activity['duration'] ?? '',
                    'Price' => $activity['price'] ?? '',
                    'Description' => $activity['short_description'] ?? ($activity['long_description'] ?? ''),
                ]);
            }
        }

        if (has_columns($home, 'rooms', ['title'])) {
            foreach (fetch_rows($home, "SELECT * FROM rooms WHERE COALESCE(status, 'active') = 'active' ORDER BY id DESC LIMIT 30") as $room) {
                add_section($context, 'HOMESTAY ROOM', [
                    'Title' => $room['title'] ?? '',
                    'Price' => $room['price'] ?? ($room['price_display'] ?? ''),
                    'Capacity' => $room['capacity'] ?? '',
                    'Description' => $room['description'] ?? '',
                ]);
            }
        }

        if (has_columns($home, 'shop_items', ['title'])) {
            foreach (fetch_rows($home, "SELECT * FROM shop_items WHERE COALESCE(status, 'active') = 'active' ORDER BY id DESC LIMIT 30") as $item) {
                add_section($context, 'SHOP ITEM', [
                    'Title' => $item['title'] ?? '',
                    'Category' => $item['category'] ?? '',
                    'Price' => $item['price'] ?? '',
                    'Description' => $item['description'] ?? '',
                ]);
            }
        }

        if (has_columns($home, 'cars', ['title'])) {
            foreach (fetch_rows($home, "SELECT * FROM cars WHERE COALESCE(status, 'active') = 'active' ORDER BY display_order ASC, id DESC LIMIT 20") as $car) {
                add_section($context, 'CAR RENTAL', [
                    'Title' => $car['title'] ?? '',
                    'Price' => $car['price'] ?? '',
                    'Description' => $car['description'] ?? '',
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
$rawHistory = $input['history'] ?? [];

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

$historyText = '';
if (is_array($rawHistory) && $rawHistory !== []) {
    $historyText .= "CONVERSATION HISTORY:\n";
    foreach (array_slice($rawHistory, -6) as $msg) {
        $sender = ($msg['role'] ?? 'user') === 'user' ? 'Guest' : 'Assistant';
        $text = safe_text($msg['text'] ?? '', 400);
        if ($text !== '') {
            $historyText .= "{$sender}: {$text}\n";
        }
    }
    $historyText .= "\n";
}

$systemInstruction = "You are the official Virunga Collective Travel Advisor. You assist guests with authentic travel experiences, gorilla safaris, homestays, volcanic coffee rituals, culinary tours, and activities in Rwanda and the Virunga region.

How to respond:
- Be warm, welcoming, professional, and helpful.
- Refer to the conversation history to understand follow-up questions.
- Present tour itineraries, durations, inclusions, and activities clearly.
- If an exact price is listed in our database (e.g., '$557.2 per Person'), provide it clearly. If no fixed price is listed for a specific tour or activity, inform the guest warmly that pricing depends on group size and customized preferences, and invite them to request a tailored quote via our Contact page or WhatsApp (+250 784 513 435).
- Keep responses clean, elegant, and easy to read with bullet points and paragraphs.";

$payload = [
    'contents' => [
        [
            'role' => 'user',
            'parts' => [
                [
                    'text' => $systemInstruction . "\n\n" . $context . "\n\n" . $historyText . "CURRENT GUEST QUESTION: " . $userMessage,
                ],
            ],
        ],
    ],
    'generationConfig' => [
        'temperature' => 0.2,
        'maxOutputTokens' => 700,
    ],
];

$preferredModel = env_value('GEMINI_MODEL', 'gemini-3.6-flash');
$candidateModels = array_unique([$preferredModel, 'gemini-3.6-flash', 'gemini-3.5-flash']);

$reply = '';
$lastError = '';

foreach ($candidateModels as $model) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/" . rawurlencode($model) . ":generateContent?key=" . urlencode($apiKey);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'X-goog-api-key: ' . $apiKey,
        ],
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT => 25,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $response = curl_exec($ch);
    $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response !== false && $statusCode === 200) {
        $data = json_decode($response, true);
        $reply = trim((string) ($data['candidates'][0]['content']['parts'][0]['text'] ?? ''));
        if ($reply !== '') {
            break;
        }
    } else {
        $lastError = "Model {$model} returned HTTP {$statusCode}: {$response}";
        error_log('Gemini API Error: ' . $lastError);
    }
}

function smart_db_search_fallback(string $message, ?PDO $eco, ?PDO $home): string
{
    $clean = strtolower(trim($message));
    $clean = str_replace(['lave', 'treking', 'itenary', 'itenarary', 'mountin', 'vulcano'], ['lake', 'trekking', 'itinerary', 'itinerary', 'mountain', 'volcano'], $clean);

    preg_match_all('/\b[a-z0-9]{3,}\b/', $clean, $matches);
    $words = array_diff($matches[0] ?? [], ['want', 'visit', 'show', 'tell', 'about', 'this', 'that', 'with', 'from', 'have', 'need', 'like', 'how', 'what', 'would', 'could', 'please']);

    $results = [];

    // Search Tours (Ecotours DB)
    if ($eco && $words !== []) {
        foreach ($words as $w) {
            if (has_columns($eco, 'tours', ['title', 'short_description'])) {
                $stmt = $eco->prepare("SELECT title, days_count, short_description FROM tours WHERE title LIKE ? OR short_description LIKE ? OR category LIKE ? OR why_attend LIKE ? ORDER BY CAST(days_count AS UNSIGNED) DESC LIMIT 4");
                $like = '%' . $w . '%';
                $stmt->execute([$like, $like, $like, $like]);
                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $t) {
                    $results[$t['title']] = [
                        'title' => $t['title'],
                        'duration' => !empty($t['days_count']) ? $t['days_count'] . ' Days' : '',
                        'desc' => $t['short_description'] ?? ''
                    ];
                }
            }
        }
    }

    // Search Activities (Homestay DB)
    if ($home && $words !== []) {
        foreach ($words as $w) {
            if (has_columns($home, 'activities', ['title', 'short_description'])) {
                $stmt = $home->prepare("SELECT title, duration, price, short_description, long_description FROM activities WHERE title LIKE ? OR short_description LIKE ? OR long_description LIKE ? OR tag LIKE ? LIMIT 4");
                $like = '%' . $w . '%';
                $stmt->execute([$like, $like, $like, $like]);
                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $a) {
                    $results[$a['title']] = [
                        'title' => $a['title'],
                        'duration' => $a['duration'] ?? '',
                        'desc' => $a['short_description'] ?? ($a['long_description'] ?? '')
                    ];
                }
            }
        }
    }

    if ($results !== []) {
        $out = "Here are our top Virunga Collective experiences matching your inquiry:\n\n";
        foreach (array_slice($results, 0, 4) as $item) {
            $dur = !empty($item['duration']) ? " ({$item['duration']})" : "";
            $out .= "• **{$item['title']}**{$dur}\n";
        }
        $out .= "\nWould you like the full itinerary or details on any of these? You can also reach our concierge via WhatsApp (+250 784 513 435) or our Contact page!";
        return $out;
    }

    return "Welcome to Virunga Collective! We offer bespoke gorilla trekking, volcano climbing (including Mt. Bisoke & Karisimbi), luxury homestays, and volcanic coffee experiences. Please contact us directly via our Contact page or WhatsApp (+250 784 513 435) to plan your trip!";
}

if ($reply !== '') {
    // Sanitize any accidental prompt leak or internal rule remnants
    $reply = preg_replace('/(?:\)|\]|\"|\')?\s*\*?\s*Rule\s*\d+:.*$/is', '', $reply);
    $reply = trim($reply);
    send_json(['response' => $reply]);
} else {
    $ecoPdo = connect_pdo('ECOTOURS');
    $homePdo = connect_pdo('HOMESTAY');
    $fallbackReply = smart_db_search_fallback($userMessage, $ecoPdo, $homePdo);
    send_json(['response' => $fallbackReply], 200);
}
