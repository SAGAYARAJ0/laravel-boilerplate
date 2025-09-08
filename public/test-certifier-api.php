<?php
/**
 * Simple test to check if Certifier API is working
 * Access via: http://localhost/test-certifier-api.php
 */

// Bootstrap Laravel
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: application/json');

try {
    // Test Certifier API connection
    $certifierClient = new App\Services\CertifierClient();
    
    echo json_encode([
        'success' => true,
        'message' => 'Certifier API test',
        'timestamp' => date('Y-m-d H:i:s'),
        'config' => [
            'api_url' => config('services.certifier.base_url'),
            'has_token' => !empty(config('services.certifier.token')),
            'version' => config('services.certifier.version')
        ],
        'connection_test' => $certifierClient->testConnection(),
        'designs_test' => 'Testing designs...'
    ], JSON_PRETTY_PRINT);
    
    // Try to get designs
    try {
        $designs = $certifierClient->getDesigns(['limit' => 3]);
        echo "\n\nDesigns Response:\n";
        echo json_encode($designs, JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        echo "\n\nDesigns Error: " . $e->getMessage();
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT);
}
?>
