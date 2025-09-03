<?php

namespace App\Domains\Ai\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Traits\LogsActivity;
use Exception;

class EventProcessingService
{
    protected $n8nEndpoint = 'http://redmindgpt.redmindtechnologies.com:5678/form/0ff339d6-477a-4e42-82ef-299b9d8d3455';

    /**
     * Process the uploaded PDF file through n8n
     *
     * @param UploadedFile $file
     * @return array
     * @throws Exception
     */
    public function processPdf(UploadedFile $file)
    {
        try {
            Log::info('Starting PDF processing through n8n', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);

            $response = Http::attach(
                'pdf', 
                file_get_contents($file->path()), 
                $file->getClientOriginalName()
            )->post($this->n8nEndpoint);

            if (!$response->successful()) {
                Log::error('n8n processing failed', [
                    'status_code' => $response->status(),
                    'response' => $response->body(),
                    'filename' => $file->getClientOriginalName()
                ]);
                throw new Exception('Failed to process PDF through n8n: ' . $response->status());
            }

            $responseData = $response->json();
            Log::info('PDF successfully processed through n8n', [
                'filename' => $file->getClientOriginalName(),
                'response_data' => $this->sanitizeLogData($responseData)
            ]);

            return $responseData;

        } catch (Exception $e) {
            Log::error('Error processing PDF through n8n', [
                'error' => $e->getMessage(),
                'filename' => $file->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Extract event details from n8n response
     *
     * @param array $data
     * @return array
     */
    public function extractEventDetails(array $data)
    {
        try {
            Log::info('Extracting event details from n8n response', [
                'raw_data' => $this->sanitizeLogData($data)
            ]);

            $eventData = [
                'event_name' => $data['event_name'] ?? null,
                'date' => $data['date'] ?? null,
                'time' => $data['time'] ?? null,
                'venue' => $data['venue'] ?? null,
                'organizer' => $data['organizer'] ?? null,
                'description' => $data['description'] ?? null,
            ];

            // Log missing fields
            $missingFields = array_filter($eventData, function ($value) {
                return $value === null;
            });

            if (!empty($missingFields)) {
                Log::warning('Some event fields are missing from n8n response', [
                    'missing_fields' => array_keys($missingFields)
                ]);
            }

            Log::info('Event details extracted successfully', [
                'extracted_data' => $this->sanitizeLogData($eventData)
            ]);

            return $eventData;

        } catch (Exception $e) {
            Log::error('Error extracting event details', [
                'error' => $e->getMessage(),
                'raw_data' => $this->sanitizeLogData($data),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Sanitize sensitive data before logging
     *
     * @param array $data
     * @return array
     */
    protected function sanitizeLogData(array $data): array
    {
        // Remove or mask any sensitive information if needed
        return $data;
    }
}
