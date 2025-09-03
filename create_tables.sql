-- Create events table
CREATE TABLE IF NOT EXISTS `events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date DEFAULT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `venue` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organizer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create pdf_processing table
CREATE TABLE IF NOT EXISTS `pdf_processing` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stored_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','processing','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `extracted_text` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `n8n_response` json DEFAULT NULL,
  `processing_result` json DEFAULT NULL,
  `error_message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_to_n8n_at` timestamp NULL DEFAULT NULL,
  `response_received_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pdf_processing_file_id_unique` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample event from N8N data
INSERT INTO `events` (`event_name`, `date`, `time`, `venue`, `organizer`, `description`, `created_at`, `updated_at`) VALUES
('MASTERCLASS ON INTERVENTIONAL PAIN MANAGEMENT', '2023-05-24', NULL, 'TARABICHI HEALTH CARE – EDUCATION CENTRE, DUBAI', 'Auto-generated from PDF', 
'Programme: Comprehensive Interventional Pain Management Workshop

Speakers: Dr Rahul Bhansali, Dr Prit Anand Singh, Dr Najam Mian, Dr Mohamed Elfekky, Dr Sharmila Tulpule, Dr Mosbah Salem El Khodary, Dr Haytham Elkhatib, Dr Shravan Tirunagari, Dr Wiquar Ahmed, Dr Sadiq Bhayani, Dr Tushar Munnoli, Dr Naren Raj, Dr Amit Verma, Dr Ravi Kare, Dr Jaya Batra, Dr Anand Rajasekaran, Dr Mohamed Elahl, Dr Jatin Dedhia, Dr Manesh Matthews, Dr Syed Abu Sayeed

CPD Points: 14

Agenda: 08:00 - 08:30 Registration & Refreshments
08:30 - 09:00 Introduction
09:00 - 09:30 Upper Limb MSK pain procedures- (Shoulder, Elbow, Hand & wrist pain management blocks)
09:30-10:00 Lower Limb MSK pain procedures (Hip, Knee, Ankle & foot interventional pain procedures)
10:00 - 10:30 Tea/ Coffee Break
10:30 - 11:00 Cervical Spine Procedures using Ultrasound Guidance (Procedures for Neck pain management)
11:00 - 11:15 Thoracic Spine procedures using Ultrasound Guidance (Procedures for Thoracic spine and Chest wall pain management)
11.15 - 11.30 Headache and Facial Pain Interventional Pain Management
11:30 - 11:50 How to manage Chronic Shoulder Joint Pain? Role of Shoulder Joint Denervation (Radio frequency/ Cryoneurolysis)
11:50 - 12:10 How to manage Chronic Knee Joint Pain? Role of Knee Joint Denervation Radio frequency/ Cryoneurolysis)
12:10 - 12:30 How to manage Chronic Hip Joint Pain? Role of Hip Joint Denervation/ Interventions (Radio frequency/ Cryoneurolysis)
12:30 - 13:00 Regenerative Medicine in Pain Management
13:00 - 14:00 Lunch Break
14:00 - 15:30 Ultrasound Live Demonstration of all the blocks (Demonstration only)
15:30 - 16:00 Radiofrequency & Cryoneurolysis in Pain Management...', 
NOW(), NOW());
