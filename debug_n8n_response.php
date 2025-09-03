<?php
/**
 * Debug script to test N8N response parsing and event creation
 */

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\Ai\Models\Event;
use App\Http\Controllers\Backend\AiController;

echo "=== N8N Response Parsing Debug ===\n\n";

// Simulate the exact N8N response from your logs
$n8nResponse = [
    [
        "output" => [
            "Event_tittle" => "MASTERCLASS ON INTERVENTIONAL PAIN MANAGEMENT",
            "Dates_Details" => "24th – 25th May 2023",
            "programme" => "Hands-on Ultrasound–Guided MSK, Spine & Joint Injections, Denervation & Regenerative Medicine",
            "Venues_Details" => "TARABICHI HEALTH CARE – EDUCATION CENTRE, DUBAI",
            "Speakers_Details" => "Dr Rahul Bhansali, Dr Prit Anand Singh, Dr Najam Mian, Dr Mohamed Elfekky, Dr Sharmila Tulpule, Dr Mosbah Salem El Khodary, Dr Haytham Elkhatib, Dr Shravan Tirunagari, Dr Wiquar Ahmed, Dr Sadiq Bhayani, Dr Tushar Munnoli, Dr Naren Raj, Dr Amit Verma, Dr Ravi Kare, Dr Jaya Batra, Dr Anand Rajasekaran, Dr Mohamed Elahl, Dr Jatin Dedhia, Dr Manesh Matthews, Dr Syed Abu Sayeed",
            "cpd Details" => "14 CME Credits (Approval Sought)",
            "agenda_details" => "Day 1: 08:00 - 08:30 Registration & Refreshments...",
            "program" => "The program includes a comprehensive interventional pain management workshop covering joint injections, spinal injections, denervation techniques, regenerative medicine, and ultrasound-guided procedures."
        ]
    ]
];

echo "1. Testing N8N Response Structure:\n";
echo "Response Keys: " . implode(', ', array_keys($n8nResponse)) . "\n";
echo "First Item Keys: " . implode(', ', array_keys($n8nResponse[0])) . "\n";
echo "Output Keys: " . implode(', ', array_keys($n8nResponse[0]['output'])) . "\n\n";

// Test the extractEventDetails method logic
echo "2. Testing Event Data Extraction:\n";

// Simulate the extraction logic
if (is_array($n8nResponse) && isset($n8nResponse[0]['output'])) {
    $eventDetails = $n8nResponse[0]['output'];
    echo "✅ Found event data in responseData[0][output]\n";
    
    echo "Event Title: " . ($eventDetails['Event_tittle'] ?? 'NOT FOUND') . "\n";
    echo "Date Details: " . ($eventDetails['Dates_Details'] ?? 'NOT FOUND') . "\n";
    echo "Venue Details: " . ($eventDetails['Venues_Details'] ?? 'NOT FOUND') . "\n";
    echo "Programme: " . ($eventDetails['programme'] ?? 'NOT FOUND') . "\n";
    echo "CPD Details: " . ($eventDetails['cpd Details'] ?? 'NOT FOUND') . "\n\n";
} else {
    echo "❌ Could not extract event data\n\n";
}

// Test date parsing
echo "3. Testing Date Parsing:\n";
$dateString = "24th – 25th May 2023";
echo "Original: {$dateString}\n";

try {
    // Test the date parsing logic from AiController
    if (preg_match('/(\d{1,2})(?:st|nd|rd|th)?\s*[-–]\s*(\d{1,2})(?:st|nd|rd|th)?\s+([A-Za-z]+),?\s+(\d{4})/', $dateString, $matches)) {
        $day = $matches[1];
        $month = $matches[3];
        $year = $matches[4];
        $parsedDate = \Carbon\Carbon::createFromFormat('j F Y', "$day $month $year")->format('Y-m-d');
        echo "✅ Parsed Date: {$parsedDate}\n";
    } else {
        echo "❌ Date parsing failed\n";
    }
} catch (Exception $e) {
    echo "❌ Date parsing error: " . $e->getMessage() . "\n";
}

echo "\n4. Testing Event Creation with Correct Data:\n";

try {
    // Create event with the extracted data
    $eventData = [
        'event_name' => $eventDetails['Event_tittle'] ?? 'Untitled Event',
        'date' => '2023-05-24', // Manually parsed for test
        'venue' => $eventDetails['Venues_Details'] ?? null,
        'organizer' => 'Auto-generated from PDF',
        'description' => "Programme: " . ($eventDetails['programme'] ?? '') . "\n\n" .
                        "CPD Points: " . ($eventDetails['cpd Details'] ?? '') . "\n\n" .
                        "Speakers: " . ($eventDetails['Speakers_Details'] ?? '')
    ];
    
    echo "Event Data to be created:\n";
    foreach ($eventData as $key => $value) {
        echo "- {$key}: " . (strlen($value ?? '') > 50 ? substr($value, 0, 50) . '...' : ($value ?? 'NULL')) . "\n";
    }
    
    // Create the event
    $event = Event::create($eventData);
    
    echo "\n✅ Event created successfully!\n";
    echo "Event ID: {$event->id}\n";
    echo "Event Name: {$event->event_name}\n";
    echo "Date: {$event->date}\n";
    echo "Venue: {$event->venue}\n";
    
} catch (Exception $e) {
    echo "❌ Event creation failed: " . $e->getMessage() . "\n";
}

echo "\n=== Debug Complete ===\n";
