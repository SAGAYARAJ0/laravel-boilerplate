<?php
/**
 * Test script to manually create an event and verify Event Master displays it
 */

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\Ai\Models\Event;
use Illuminate\Support\Facades\Schema;

echo "=== Testing Event Master Database ===\n\n";

try {
    // Check if events table exists
    if (!Schema::hasTable('events')) {
        echo "❌ Events table does NOT exist\n";
        echo "Creating events table...\n";
        
        DB::statement("
            CREATE TABLE `events` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `event_name` varchar(255) NOT NULL,
                `date` date DEFAULT NULL,
                `time` varchar(255) DEFAULT NULL,
                `venue` varchar(255) DEFAULT NULL,
                `organizer` varchar(255) DEFAULT NULL,
                `description` text DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        echo "✅ Events table created successfully\n\n";
    } else {
        echo "✅ Events table exists\n\n";
    }

    // Check current events count
    $currentCount = Event::count();
    echo "📊 Current events in database: {$currentCount}\n\n";

    // Create a test event
    echo "Creating test event...\n";
    $testEvent = Event::create([
        'event_name' => 'Test Medical Conference 2025',
        'date' => '2025-06-15',
        'time' => '09:00 AM - 05:00 PM',
        'venue' => 'Medical Center, Test City',
        'organizer' => 'Test Script',
        'description' => "Programme: Test Medical Conference\n\nSpeakers: Dr. Test Speaker, Prof. Example Doctor\n\nCPD Points: 10 CPD\n\nAgenda: Morning sessions, Lunch break, Afternoon workshops"
    ]);

    echo "✅ Test event created with ID: {$testEvent->id}\n";
    echo "Event Name: {$testEvent->event_name}\n";
    echo "Date: {$testEvent->date}\n";
    echo "Venue: {$testEvent->venue}\n\n";

    // Verify total count
    $newCount = Event::count();
    echo "📊 Total events after creation: {$newCount}\n\n";

    if ($newCount > $currentCount) {
        echo "🎉 SUCCESS: Event created and stored in database!\n";
        echo "Event Master should now display this event.\n\n";
        
        // List all events
        echo "=== All Events in Database ===\n";
        $allEvents = Event::orderBy('created_at', 'desc')->get();
        foreach ($allEvents as $event) {
            echo "- {$event->event_name} (ID: {$event->id}) - {$event->date}\n";
        }
    } else {
        echo "❌ Issue: Event count did not increase\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";
