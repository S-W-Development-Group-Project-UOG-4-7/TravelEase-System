<?php
/**
 * chat_api.php
 * TravelEase AI Chatbot router (Q&A + Actions)
 *
 * Frontend calls this endpoint with JSON: {"message": "..."}
 *
 * Requires:
 * - config/secrets.php with OPENAI_API_KEY
 * - db.php (PDO $pdo)
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

// ----------------------
// Load secrets
// ----------------------
$secrets = @require __DIR__ . '/config/secrets.php';
$OPENAI_KEY = $secrets['OPENAI_API_KEY'] ?? '';
if (!$OPENAI_KEY || $OPENAI_KEY === 'PASTE_YOUR_OPENAI_KEY_HERE') {
  http_response_code(500);
  echo json_encode(['error' => 'Server not configured: missing OPENAI_API_KEY in config/secrets.php']);
  exit;
}

require_once __DIR__ . '/db.php'; // provides $pdo (PDO)

// ----------------------
// Read input
// ----------------------
$raw = file_get_contents('php://input');
$input = json_decode($raw ?: '{}', true);
if (!is_array($input)) $input = [];

$message = trim((string)($input['message'] ?? ''));
if ($message === '') {
  http_response_code(400);
  echo json_encode(['error' => 'Message is required']);
  exit;
}

// Basic guardrails
if (mb_strlen($message) > 1200) {
  http_response_code(400);
  echo json_encode(['error' => 'Message is too long']);
  exit;
}

$userId = $_SESSION['user_id'] ?? null;
$userRole = $_SESSION['role'] ?? 'guest';
$userEmail = $_SESSION['email'] ?? null;

// ----------------------
// Helpers
// ----------------------
function respond_json(int $status, array $payload): void {
  http_response_code($status);
  echo json_encode($payload, JSON_UNESCAPED_UNICODE);
  exit;
}

function openai_responses_api(string $apiKey, array $payload): array {
  $ch = curl_init('https://api.openai.com/v1/responses');
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
      'Authorization: Bearer ' . $apiKey,
      'Content-Type: application/json',
    ],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_TIMEOUT => 30,
  ]);

  $res = curl_exec($ch);
  $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $err = curl_error($ch);
  // PHP 8.4+ deprecates curl_close(); let GC release the handle.
  $ch = null;

  return [$http, $res, $err];
}

/**
 * Correct extractor for Responses API.
 * Handles:
 * - output_text shortcut
 * - output[].type=message with content[] having text
 */
function extract_text(array $data): string {
  if (isset($data['output_text']) && is_string($data['output_text']) && trim($data['output_text']) !== '') {
    return trim($data['output_text']);
  }

  $out = $data['output'] ?? [];
  if (!is_array($out)) return '';

  foreach ($out as $o) {
    if (!is_array($o)) continue;
    if (($o['type'] ?? '') !== 'message') continue;

    $content = $o['content'] ?? [];
    if (!is_array($content)) continue;

    foreach ($content as $c) {
      if (!is_array($c)) continue;

      // Most common: { type: "output_text", text: "..." }
      if (isset($c['text']) && is_string($c['text']) && trim($c['text']) !== '') {
        return trim($c['text']);
      }
      if (($c['type'] ?? '') === 'output_text' && isset($c['text']) && is_string($c['text'])) {
        return trim($c['text']);
      }
    }
  }

  return '';
}

// ----------------------
// Tools (Actions)
// ----------------------
function tool_search_packages(PDO $pdo, string $query): array {
  $query = trim($query);
  if ($query === '') return ['ok' => false, 'error' => 'MISSING_QUERY'];

  $q = '%' . $query . '%';
  $stmt = $pdo->prepare("SELECT p.id, p.title, p.price, c.name AS country
                         FROM packages p
                         JOIN countries c ON p.country_id = c.id
                         WHERE p.title ILIKE :q OR c.name ILIKE :q
                         ORDER BY p.title
                         LIMIT 8");
  $stmt->execute([':q' => $q]);
  return ['ok' => true, 'packages' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
}

function tool_book_trip(PDO $pdo, $userId, int $package_id, string $start_date, string $end_date): array {
  if (!$userId) return ['ok' => false, 'error' => 'NOT_LOGGED_IN'];
  if ($package_id <= 0) return ['ok' => false, 'error' => 'INVALID_PACKAGE_ID'];
  if (trim($start_date) === '' || trim($end_date) === '') return ['ok' => false, 'error' => 'MISSING_DATES'];

  // Verify package exists
  $stmt = $pdo->prepare("SELECT id, title FROM packages WHERE id = :id LIMIT 1");
  $stmt->execute([':id' => $package_id]);
  $pkg = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$pkg) return ['ok' => false, 'error' => 'PACKAGE_NOT_FOUND'];

  // Postgres-safe insert with RETURNING id
  $stmt = $pdo->prepare("INSERT INTO trips (user_id, package_id, start_date, end_date, status)
                         VALUES (:uid, :pid, :sd, :ed, 'Pending')
                         RETURNING id");
  $stmt->execute([
    ':uid' => $userId,
    ':pid' => $package_id,
    ':sd'  => $start_date,
    ':ed'  => $end_date,
  ]);

  $tripId = (int)$stmt->fetchColumn();
  return ['ok' => true, 'trip_id' => $tripId, 'package' => $pkg];
}

function tool_cancel_trip(PDO $pdo, $userId, int $trip_id): array {
  if (!$userId) return ['ok' => false, 'error' => 'NOT_LOGGED_IN'];
  if ($trip_id <= 0) return ['ok' => false, 'error' => 'INVALID_TRIP_ID'];

  $stmt = $pdo->prepare("UPDATE trips
                         SET status = 'Cancelled'
                         WHERE id = :id AND user_id = :uid
                         RETURNING id");
  $stmt->execute([':id' => $trip_id, ':uid' => $userId]);
  $id = $stmt->fetchColumn();

  if (!$id) return ['ok' => false, 'error' => 'TRIP_NOT_FOUND'];
  return ['ok' => true, 'trip_id' => (int)$id];
}

function tool_get_my_trips(PDO $pdo, $userId): array {
  if (!$userId) return ['ok' => false, 'error' => 'NOT_LOGGED_IN'];

  $stmt = $pdo->prepare("SELECT t.id, t.start_date, t.end_date, t.status,
                                p.title AS package_title, c.name AS country
                         FROM trips t
                         JOIN packages p ON t.package_id = p.id
                         JOIN countries c ON p.country_id = c.id
                         WHERE t.user_id = :uid
                         ORDER BY t.id DESC
                         LIMIT 10");
  $stmt->execute([':uid' => $userId]);

  return ['ok' => true, 'trips' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
}

function tool_send_email(array $secrets, string $to, string $subject, string $body): array {
  $to = trim($to);
  $subject = trim($subject);
  $body = trim($body);

  if ($to === '' || $subject === '' || $body === '') {
    return ['ok' => false, 'error' => 'MISSING_EMAIL_FIELDS'];
  }

  // Recommended: n8n webhook for logs/retries. Fallback: PHP mail()
  $webhook = $secrets['N8N_EMAIL_WEBHOOK'] ?? '';
  $secret = $secrets['N8N_WEBHOOK_SECRET'] ?? '';

  if ($webhook) {
    $payload = ['to' => $to, 'subject' => $subject, 'body' => $body, 'secret' => $secret];

    $ch = curl_init($webhook);
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
      CURLOPT_POSTFIELDS => json_encode($payload),
      CURLOPT_TIMEOUT => 20,
    ]);
    $res = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    // PHP 8.4+ deprecates curl_close(); let GC release the handle.
    $ch = null;

    if ($err) return ['ok' => false, 'error' => 'EMAIL_WEBHOOK_CURL', 'detail' => $err];
    if ($http < 200 || $http >= 300) return ['ok' => false, 'error' => 'EMAIL_WEBHOOK_FAILED', 'http' => $http, 'raw' => $res];

    return ['ok' => true];
  }

  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "Content-type:text/plain;charset=UTF-8\r\n";
  $headers .= "From: TravelEase <no-reply@travelease.local>\r\n";

  $ok = @mail($to, $subject, $body, $headers);
  if (!$ok) return ['ok' => false, 'error' => 'MAIL_FAILED', 'hint' => 'Configure SMTP or use n8n/PHPMailer'];
  return ['ok' => true];
}

function tool_generate_trip_pdf(PDO $pdo, $userId, int $trip_id): array {
  if (!$userId) return ['ok' => false, 'error' => 'NOT_LOGGED_IN'];
  if ($trip_id <= 0) return ['ok' => false, 'error' => 'INVALID_TRIP_ID'];

  // Fetch trip
  $stmt = $pdo->prepare("SELECT t.id, t.start_date, t.end_date, t.status,
                                p.title AS package_title, p.price,
                                c.name AS country
                         FROM trips t
                         JOIN packages p ON t.package_id = p.id
                         JOIN countries c ON p.country_id = c.id
                         WHERE t.id = :tid AND t.user_id = :uid
                         LIMIT 1");
  $stmt->execute([':tid' => $trip_id, ':uid' => $userId]);
  $trip = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$trip) return ['ok' => false, 'error' => 'TRIP_NOT_FOUND'];

  $autoload = __DIR__ . '/vendor/autoload.php';
  if (!file_exists($autoload)) {
    return [
      'ok' => false,
      'error' => 'PDF_NOT_CONFIGURED',
      'hint' => 'Install Dompdf (composer require dompdf/dompdf) and keep vendor/autoload.php in your project.'
    ];
  }

  require_once $autoload;

  $dompdf = new \Dompdf\Dompdf();

  $html = '<h2>TravelEase - Trip Itinerary</h2>'
    . '<p><strong>Trip ID:</strong> ' . htmlspecialchars((string)$trip['id']) . '</p>'
    . '<p><strong>Package:</strong> ' . htmlspecialchars((string)$trip['package_title']) . ' (' . htmlspecialchars((string)$trip['country']) . ')</p>'
    . '<p><strong>Dates:</strong> ' . htmlspecialchars((string)$trip['start_date']) . ' to ' . htmlspecialchars((string)$trip['end_date']) . '</p>'
    . '<p><strong>Status:</strong> ' . htmlspecialchars((string)$trip['status']) . '</p>'
    . '<p><strong>Price:</strong> ' . htmlspecialchars((string)$trip['price']) . '</p>';

  $dompdf->loadHtml($html);
  $dompdf->setPaper('A4', 'portrait');
  $dompdf->render();

  $outDir = __DIR__ . '/downloads';
  if (!is_dir($outDir)) @mkdir($outDir, 0775, true);

  $fileName = 'trip_' . $trip_id . '.pdf';
  $fullPath = $outDir . '/' . $fileName;
  file_put_contents($fullPath, $dompdf->output());

  return ['ok' => true, 'download_url' => 'downloads/' . $fileName];
}

// ----------------------
// AI setup: tool calling
// ----------------------
$system =
"You are TravelEase Assistant.

IMPORTANT TOOL RULES:
- NEVER call book_trip unless you already know a valid package_id.
- If the user mentions a country or destination name (for example: Sri Lanka, India, Japan) but does NOT give a package_id, you MUST call search_packages first.
- After search_packages, you must present the package options and ask the user to choose one.
- Only after the user chooses a package_id may you call book_trip.

AUTH RULES:
- Guests may ask questions.
- Guests must log in to book trips, cancel trips, or generate PDFs.

DATE RULES:
- Dates must be in YYYY-MM-DD format.

Be concise, friendly, and step-by-step.";

$tools = [
  [
    'type' => 'function',
    'name' => 'search_packages',
    'description' => 'Search packages by keyword or destination/country name',
    'parameters' => [
      'type' => 'object',
      'properties' => [
        'query' => ['type' => 'string']
      ],
      'required' => ['query'],
      'additionalProperties' => false
    ]
  ],
  [
    'type' => 'function',
    'name' => 'get_my_trips',
    'description' => 'Get recent trips for the logged-in user',
    'parameters' => [
      'type' => 'object',
      'properties' => new stdClass(),
      'additionalProperties' => false
    ]
  ],
  [
    'type' => 'function',
    'name' => 'book_trip',
    'description' => 'Book a trip for the logged-in user (creates a Pending trip)',
    'parameters' => [
      'type' => 'object',
      'properties' => [
        'package_id' => ['type' => 'integer'],
        'start_date' => ['type' => 'string', 'description' => 'YYYY-MM-DD'],
        'end_date' => ['type' => 'string', 'description' => 'YYYY-MM-DD']
      ],
      'required' => ['package_id', 'start_date', 'end_date'],
      'additionalProperties' => false
    ]
  ],
  [
    'type' => 'function',
    'name' => 'cancel_trip',
    'description' => 'Cancel an existing trip by trip_id for the logged-in user',
    'parameters' => [
      'type' => 'object',
      'properties' => [
        'trip_id' => ['type' => 'integer']
      ],
      'required' => ['trip_id'],
      'additionalProperties' => false
    ]
  ],
  [
    'type' => 'function',
    'name' => 'send_email',
    'description' => 'Send an email (itinerary, confirmation, support). Prefer n8n webhook if configured.',
    'parameters' => [
      'type' => 'object',
      'properties' => [
        'to' => ['type' => 'string'],
        'subject' => ['type' => 'string'],
        'body' => ['type' => 'string']
      ],
      'required' => ['to', 'subject', 'body'],
      'additionalProperties' => false
    ]
  ],
  [
    'type' => 'function',
    'name' => 'generate_trip_pdf',
    'description' => 'Generate a PDF itinerary for a trip_id and return a download URL (requires Dompdf)',
    'parameters' => [
      'type' => 'object',
      'properties' => [
        'trip_id' => ['type' => 'integer']
      ],
      'required' => ['trip_id'],
      'additionalProperties' => false
    ]
  ],
];

$payload = [
  'model' => 'gpt-4.1-mini',
  'input' => [
    ['role' => 'system', 'content' => $system],
    ['role' => 'user', 'content' => "(role={$userRole}) " . $message],
  ],
  'tools' => $tools,
];

[$http, $res, $err] = openai_responses_api($OPENAI_KEY, $payload);

if ($err) {
  respond_json(500, ['error' => 'cURL error calling AI', 'detail' => $err]);
}

if ($http < 200 || $http >= 300) {
  $errData = json_decode($res ?: '', true) ?: [];
  $msg = $errData['error']['message'] ?? 'AI request failed';
  respond_json(500, ['error' => $msg]);
}

$data = json_decode($res, true) ?: [];
$toolCalls = $data['output'] ?? [];
$toolOutputs = [];

if (!is_array($toolCalls)) $toolCalls = [];

foreach ($toolCalls as $item) {
  if (!is_array($item)) continue;
  if (($item['type'] ?? '') !== 'function_call') continue;

  $name = (string)($item['name'] ?? '');
  $args = $item['arguments'] ?? [];
  if (!is_array($args)) $args = [];
  $callId = (string)($item['call_id'] ?? '');

  $result = ['ok' => false, 'error' => 'UNKNOWN_TOOL'];

  try {
    if ($name === 'search_packages') {
      $result = tool_search_packages($pdo, (string)($args['query'] ?? ''));
    } elseif ($name === 'get_my_trips') {
      $result = tool_get_my_trips($pdo, $userId);
    } elseif ($name === 'book_trip') {
      $result = tool_book_trip(
        $pdo,
        $userId,
        (int)($args['package_id'] ?? 0),
        (string)($args['start_date'] ?? ''),
        (string)($args['end_date'] ?? '')
      );
    } elseif ($name === 'cancel_trip') {
      $result = tool_cancel_trip($pdo, $userId, (int)($args['trip_id'] ?? 0));
    } elseif ($name === 'send_email') {
      $result = tool_send_email(
        $secrets,
        (string)($args['to'] ?? ''),
        (string)($args['subject'] ?? ''),
        (string)($args['body'] ?? '')
      );
    } elseif ($name === 'generate_trip_pdf') {
      $result = tool_generate_trip_pdf($pdo, $userId, (int)($args['trip_id'] ?? 0));
    }
  } catch (Throwable $e) {
    $result = ['ok' => false, 'error' => 'TOOL_EXCEPTION', 'detail' => $e->getMessage()];
  }

  $toolOutputs[] = [
    'type' => 'function_call_output',
    'call_id' => $callId,
    'output' => json_encode($result, JSON_UNESCAPED_UNICODE),
  ];
}

// If tools were called, ask AI to craft a final user-facing answer
if (!empty($toolOutputs)) {
  $payload2 = [
  'model' => 'gpt-4.1-mini',
  'input' => array_merge($payload['input'], $toolOutputs),
  'tools' => $tools   // ✅ REQUIRED
];

  [$http2, $res2, $err2] = openai_responses_api($OPENAI_KEY, $payload2);

  if ($err2) {
    respond_json(500, ['error' => 'cURL error calling AI', 'detail' => $err2]);
  }

  if ($http2 < 200 || $http2 >= 300) {
    $errData2 = json_decode($res2 ?: '', true) ?: [];
    $msg2 = $errData2['error']['message'] ?? 'AI request failed';
    respond_json(500, ['error' => $msg2]);
  }

  $data2 = json_decode($res2, true) ?: [];
  $text2 = extract_text($data2);
  respond_json(200, ['reply' => $text2 !== '' ? $text2 : 'Done.']);
}

// No tools => normal answer
$text = extract_text($data);
respond_json(200, ['reply' => $text !== '' ? $text : 'Done.']);
