<?php
/**
 * Quick fix script to create database tables and test event creation
 * Run this with: php fix_event_master.php
 */

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Domains\Ai\Models\Event;

echo "=== Event Master Database Fix ===\n\n";

try {
    // Check if events table exists
    if (!Schema::hasTable('events')) {
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
        echo "✅ Events table created successfully!\n";
    } else {
        echo "✅ Events table already exists\n";
    }

    // Check if pdf_processing table exists
    if (!Schema::hasTable('pdf_processing')) {
        echo "Creating pdf_processing table...\n";
        DB::statement("
            CREATE TABLE `pdf_processing` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `file_id` varchar(255) NOT NULL,
                `original_name` varchar(255) NOT NULL,
                `stored_name` varchar(255) NOT NULL,
                `status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
                `extracted_text` longtext DEFAULT NULL,
                `n8n_response` json DEFAULT NULL,
                `processing_result` json DEFAULT NULL,
                `error_message` text DEFAULT NULL,
                `sent_to_n8n_at` timestamp NULL DEFAULT NULL,
                `response_received_at` timestamp NULL DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `pdf_processing_file_id_unique` (`file_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "✅ PDF processing table created successfully!\n";
    } else {
        echo "✅ PDF processing table already exists\n";
    }

    // Create sample event from N8N data
    $eventExists = Event::where('event_name', 'MASTERCLASS ON INTERVENTIONAL PAIN MANAGEMENT')->exists();
    
    if (!$eventExists) {
        echo "Creating sample event from N8N data...\n";
        
        $event = Event::create([
            'event_name' => 'MASTERCLASS ON INTERVENTIONAL PAIN MANAGEMENT',
            'date' => '2023-05-24',
            'venue' => 'TARABICHI HEALTH CARE – EDUCATION CENTRE, DUBAI',
            'organizer' => 'Auto-generated from PDF',
            'description' => "Programme: Comprehensive Interventional Pain Management Workshop\n\nSpeakers: Dr Rahul Bhansali, Dr Prit Anand Singh, Dr Najam Mian, Dr Mohamed Elfekky, Dr Sharmila Tulpule, Dr Mosbah Salem El Khodary, Dr Haytham Elkhatib, Dr Shravan Tirunagari, Dr Wiquar Ahmed, Dr Sadiq Bhayani, Dr Tushar Munnoli, Dr Naren Raj, Dr Amit Verma, Dr Ravi Kare, Dr Jaya Batra, Dr Anand Rajasekaran, Dr Mohamed Elahl, Dr Jatin Dedhia, Dr Manesh Matthews, Dr Syed Abu Sayeed\n\nCPD Points: 14\n\nAgenda: 08:00 - 08:30 Registration & Refreshments\n08:30 - 09:00 Introduction\n09:00 - 09:30 Upper Limb MSK pain procedures\n10:00 - 10:30 Tea/Coffee Break\n10:30 - 11:00 Cervical Spine Procedures\n11:00 - 11:15 Thoracic Spine procedures\n11:15 - 11:30 Headache and Facial Pain Management\n12:30 - 13:00 Regenerative Medicine\n13:00 - 14:00 Lunch Break\n14:00 - 15:30 Ultrasound Live Demonstration\n15:30 - 16:00 Radiofrequency & Cryoneurolysis"
        ]);
        
        echo "✅ Sample event created with ID: {$event->id}\n";
    } else {
        echo "✅ Sample event already exists\n";
    }

    // Test event retrieval
    $eventCount = Event::count();
    echo "\n📊 Total events in database: {$eventCount}\n";

    if ($eventCount > 0) {
        echo "\n🎉 SUCCESS: Event Master should now display events!\n";
        echo "Visit the Event Master page to see your events.\n";
    } else {
        echo "\n❌ No events found. Something went wrong.\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Fix Complete ===\n";
