<?php

/**
 * Simple route debug test - run this to check if routes are working
 */

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Route Debug Test ===\n";
echo "Timestamp: " . now() . "\n\n";

// Test route generation
try {
    $routes = [
        'certificates.index' => route('admin.ai.certificates.index'),
        'certifier.import' => route('admin.ai.certificates.certifier-import'),
    ];
    
    echo "Routes generated successfully:\n";
    foreach ($routes as $name => $url) {
        echo "  {$name}: {$url}\n";
    }
    
    // Write a test log entry
    \Illuminate\Support\Facades\Log::info('Debug route test executed', [
        'timestamp' => now(),
        'routes' => $routes
    ]);
    
    echo "\nTest log written to storage/logs/laravel-" . date('Y-m-d') . ".log\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\nTest completed.\n";
