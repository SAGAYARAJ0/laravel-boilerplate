<?php
/**
 * Certifier API Direct Test
 * Place this file in: /public/check-certifier.php
 * Run in browser: http://localhost/check-certifier.php
 */

header('Content-Type: text/html; charset=utf-8');
echo "<h2>Certifier API Direct Test</h2>";
echo "<p>Timestamp: " . date('Y-m-d H:i:s') . "</p>";

// --- Load .env values manually ---
$envFile = __DIR__ . '/../.env';
$config = [];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $config[trim($key)] = trim($value, '"\'');
        }
    }
}

$apiUrl  = $config['CERTIFIER_API_URL'] ?? 'https://api.certifier.io/v1';
$token   = $config['CERTIFIER_TOKEN'] ?? '';
$version = $config['CERTIFIER_VERSION'] ?? '2022-10-26';

echo "<h3>1. Configuration Check</h3>";
echo "<ul>";
echo "<li>API URL: $apiUrl</li>";
echo "<li>Token: " . (!empty($token) ? "SET (" . substr($token, 0, 10) . "...)" : "<span style='color:red'>NOT SET</span>") . "</li>";
echo "<li>Version: $version</li>";
echo "</ul>";

if (empty($token)) {
    echo "<div style='color:red'>❌ ERROR: CERTIFIER_TOKEN missing in .env</div>";
    exit;
}

// --- Helper function for API requests ---
function callCertifier($endpoint, $token, $version, $apiUrl) {
    $headers = [
        "Authorization: Bearer $token",
        "Certifier-Version: $version",
        "Accept: application/json",
        "Content-Type: application/json"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error    = curl_error($ch);
    curl_close($ch);

    return [$httpCode, $response, $error];
}

// --- Step 2: Test API ---
echo "<h3>2. API Tests</h3>";

$endpoints = [
    "Designs (limit 1)" => "/designs?limit=1",
    "Designs (limit 10)" => "/designs?limit=10",
    "Search Designs" => "/designs?search=certificate&limit=5",
    "Account Info" => "/account"
];

foreach ($endpoints as $label => $endpoint) {
    echo "<div style='border:1px solid #ddd; margin:10px; padding:10px'>";
    echo "<strong>$label</strong><br>";
    echo "Endpoint: <code>$apiUrl$endpoint</code><br>";

    [$code, $resp, $err] = callCertifier($endpoint, $token, $version, $apiUrl);

    echo "HTTP Code: $code<br>";

    if ($err) {
        echo "<span style='color:red'>cURL Error: $err</span><br>";
    }

    if ($code == 200) {
        echo "<span style='color:green'>✅ Success</span><br>";
        $data = json_decode($resp, true);
        echo "<pre style='max-height:200px;overflow-y:auto;background:#f9f9f9;padding:10px'>";
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        echo "</pre>";
    } else {
        echo "<span style='color:red'>❌ Failed</span><br>";
        echo "<pre style='background:#fee;padding:10px'>" . htmlspecialchars(substr($resp, 0, 500)) . "</pre>";
    }

    echo "</div>";
}

// --- Step 3: Laravel Routes (if inside Laravel) ---
echo "<h3>3. Laravel Route Test</h3>";
echo "<p>Try these URLs:</p>";
echo "<ul>";
echo "<li><a href='/admin/ai/certificates'>Certificate Templates</a></li>";
echo "<li><a href='/admin/ai/certificates/certifier-import'>Certifier Import</a></li>";
echo "</ul>";

// --- Step 4: Next Steps ---
echo "<h3>4. Next Steps</h3>";
echo "<div style='border:1px solid green;color:green;padding:10px'>";
echo "✅ Certifier API connectivity confirmed!<br>";
echo "If data = empty, it means you have no designs created in Certifier yet.<br>";
echo "</div>";
