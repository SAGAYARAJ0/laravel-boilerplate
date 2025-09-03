<?php

namespace App\Domains\Ai\Tests;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class N8nConnectionTest
{
    protected $n8nFormEndpoint = 'http://redmindgpt.redmindtechnologies.com:5678/webhook-test/form';
    /**
     * Run the n8n connection test
     *
     * @return array
     */
    public function runTest()
    {
        try {
            Log::info('Starting n8n connection test');
        
            // Test the form submission
            return $this->testFormSubmission();

        } catch (\Exception $e) {
            Log::error('Error during n8n connection test', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Error during n8n connection test',
                'error' => $e->getMessage()
            ];
        }
    }


    /**
     * Test the form submission endpoint
     */
    protected function testFormSubmission()
    {
        // Create a sample PDF content
        $samplePdfPath = storage_path('app/test.pdf');
        if (!file_exists($samplePdfPath)) {
            $this->createSamplePdf($samplePdfPath);
        }

        // Test the form submission with sample PDF
        $response = Http::attach(
            'pdf',
            file_get_contents($samplePdfPath),
            'test.pdf'
        )->post($this->n8nFormEndpoint);

        if ($response->successful()) {
            Log::info('n8n form submission test successful', [
                'status_code' => $response->status(),
                'response' => $response->json()
            ]);

            return [
                'success' => true,
                'message' => 'n8n form submission test successful',
                'status_code' => $response->status(),
                'response' => $response->json()
            ];
        } 

        Log::error('n8n form submission test failed', [
            'status_code' => $response->status(),
            'response' => $response->body()
        ]);

        return [
            'success' => false,
            'message' => 'n8n form submission test failed',
            'status_code' => $response->status(),
            'error' => $response->body(),
            'suggestions' => [
                'Ensure the form endpoint URL is correct',
                'Check if the workflow is activated in n8n',
                'Verify the workflow accepts PDF file uploads',
                'Make sure both webhook and form nodes are properly configured'
            ]
        ];
    }


    /**
     * Create a sample PDF file for testing
     */
    protected function createSamplePdf($filePath)
    {
        require_once base_path('vendor/setasign/fpdf/fpdf.php');
        
        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, 'This is a test PDF file for n8n connection testing');
        $pdf->Output('F', $filePath);
    }
}
