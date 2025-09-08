<?php

namespace App\Domains\Ai\Services;

use App\Domains\Ai\Models\CertificateTemplate;
use App\Domains\Ai\Models\GeneratedCertificate;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CertificateGeneratorService
{
    /**
     * Generate a single certificate
     *
     * @param CertificateTemplate $template
     * @param array $data
     * @return GeneratedCertificate
     */
    public function generateSingle(CertificateTemplate $template, array $data)
    {
        try {
            Log::info('Starting single certificate generation', [
                'template_id' => $template->id,
                'recipient' => $data['recipient_name'] ?? 'Unknown'
            ]);

            // Create generated certificate record
            $certificate = GeneratedCertificate::create([
                'certificate_template_id' => $template->id,
                'recipient_name' => $data['recipient_name'] ?? '',
                'recipient_email' => $data['recipient_email'] ?? '',
                'course_name' => $data['course_name'] ?? '',
                'completion_date' => $data['completion_date'] ?? now(),
                'certificate_data' => $data,
                'status' => 'pending',
                'generated_by' => auth()->id()
            ]);

            // Generate PNG
            $pngPath = $this->generatePng($template, $data, $certificate->id);

            // Update certificate record
            $certificate->update([
                'file_path' => $pngPath,
                'file_type' => 'html',
                'status' => 'completed'
            ]);

            Log::info('Certificate generated successfully', [
                'certificate_id' => $certificate->id,
                'file_path' => $pngPath
            ]);

            return $certificate;

        } catch (\Exception $e) {
            Log::error('Error generating certificate', [
                'template_id' => $template->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Generate multiple certificates
     *
     * @param CertificateTemplate $template
     * @param array $recipients
     * @return array
     */
    public function generateBulk(CertificateTemplate $template, array $recipients)
    {
        $certificates = [];
        $errors = [];

        foreach ($recipients as $index => $recipientData) {
            try {
                $certificate = $this->generateSingle($template, $recipientData);
                $certificates[] = $certificate;
            } catch (\Exception $e) {
                $errors[] = [
                    'index' => $index,
                    'recipient' => $recipientData['recipient_name'] ?? 'Unknown',
                    'error' => $e->getMessage()
                ];
            }
        }

        Log::info('Bulk certificate generation completed', [
            'template_id' => $template->id,
            'total_recipients' => count($recipients),
            'successful' => count($certificates),
            'failed' => count($errors)
        ]);

        return [
            'certificates' => $certificates,
            'errors' => $errors,
            'summary' => [
                'total' => count($recipients),
                'successful' => count($certificates),
                'failed' => count($errors)
            ]
        ];
    }

    /**
     * Generate PNG from template and data
     *
     * @param CertificateTemplate $template
     * @param array $data
     * @param int $certificateId
     * @return string
     */
    private function generatePng(CertificateTemplate $template, array $data, int $certificateId)
    {
        // Replace template variables with actual data
        $processedTemplate = $this->processTemplateData($template, $data);

        // Generate HTML template for conversion
        $html = view('backend.ai.certificates.png-template', [
            'template' => $template,
            'data' => $data,
            'processedTemplate' => $processedTemplate
        ])->render();

        // Generate filename
        $filename = 'certificate-' . $certificateId . '-' . Str::slug($data['recipient_name'] ?? 'certificate') . '.png';
        $filePath = 'certificates/' . $filename;
        $fullPath = Storage::disk('public')->path($filePath);
        
        // Ensure directory exists
        $directory = dirname($fullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Create a simple HTML file that can be converted to PNG using browser tools
        // For now, we'll create an HTML file and let the frontend handle PNG conversion
        $htmlFilename = 'certificate-' . $certificateId . '-' . Str::slug($data['recipient_name'] ?? 'certificate') . '.html';
        $htmlFilePath = 'certificates/' . $htmlFilename;
        Storage::disk('public')->put($htmlFilePath, $html);

        // Return the HTML file path for now - frontend can convert to PNG
        return $htmlFilePath;
    }

    /**
     * Process template data by replacing variables
     *
     * @param CertificateTemplate $template
     * @param array $data
     * @return array
     */
    private function processTemplateData(CertificateTemplate $template, array $data)
    {
        $templateData = $template->template_data;

        if (!isset($templateData['objects'])) {
            return $templateData;
        }

        foreach ($templateData['objects'] as &$object) {
            if (isset($object['type']) && ($object['type'] === 'text' || $object['type'] === 'i-text') && isset($object['text'])) {
                $object['text'] = $this->replaceVariables($object['text'], $data);
            }
        }

        return $templateData;
    }

    /**
     * Replace variables in text with actual values
     *
     * @param string $text
     * @param array $data
     * @return string
     */
    private function replaceVariables(string $text, array $data)
    {
        $replacements = [
            '@{{recipient_name}}' => $data['recipient_name'] ?? '',
            '@{{course_name}}' => $data['course_name'] ?? '',
            '@{{completion_date}}' => isset($data['completion_date']) ? 
                \Carbon\Carbon::parse($data['completion_date'])->format('F j, Y') : '',
            '@{{date}}' => now()->format('F j, Y'),
            '@{{certificate_id}}' => $data['certificate_id'] ?? '',
            '@{{instructor_name}}' => $data['instructor_name'] ?? '',
            '@{{organization}}' => $data['organization'] ?? config('app.name'),
            '@{{program_duration}}' => $data['program_duration'] ?? '40',
            '@{{learning_mode}}' => $data['learning_mode'] ?? 'Online',
            '@{{issued_by}}' => $data['issued_by'] ?? config('app.name'),
            // Also support old format for backward compatibility
            '{{recipient_name}}' => $data['recipient_name'] ?? '',
            '{{course_name}}' => $data['course_name'] ?? '',
            '{{completion_date}}' => isset($data['completion_date']) ? 
                \Carbon\Carbon::parse($data['completion_date'])->format('F j, Y') : '',
            '{{date}}' => now()->format('F j, Y'),
            '{{certificate_id}}' => $data['certificate_id'] ?? '',
            '{{instructor_name}}' => $data['instructor_name'] ?? '',
            '{{organization}}' => $data['organization'] ?? config('app.name'),
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }

    /**
     * Convert hex color to RGB array
     *
     * @param string $hex
     * @return array
     */
    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2))
        ];
    }

    /**
     * Render Fabric.js object to PNG image
     *
     * @param resource $image
     * @param array $object
     * @param float $scale
     * @return void
     */
    private function renderObjectToPng($image, array $object, float $scale): void
    {
        $type = $object['type'] ?? '';
        
        switch ($type) {
            case 'text':
                $this->renderTextToPng($image, $object, $scale);
                break;
            case 'rect':
                $this->renderRectToPng($image, $object, $scale);
                break;
            case 'circle':
                $this->renderCircleToPng($image, $object, $scale);
                break;
            case 'line':
                $this->renderLineToPng($image, $object, $scale);
                break;
        }
    }

    /**
     * Render text object to PNG
     *
     * @param resource $image
     * @param array $object
     * @param float $scale
     * @return void
     */
    private function renderTextToPng($image, array $object, float $scale): void
    {
        $text = $object['text'] ?? '';
        if (empty($text)) return;

        $left = ($object['left'] ?? 0) * $scale;
        $top = ($object['top'] ?? 0) * $scale;
        $fontSize = ($object['fontSize'] ?? 16) * $scale;
        $fill = $object['fill'] ?? '#000000';
        $textAlign = $object['textAlign'] ?? 'left';
        
        // Convert color
        $rgb = $this->hexToRgb($fill);
        $textColor = imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);
        
        // Use built-in font (can be enhanced with TTF fonts later)
        $font = 5; // Built-in font size 5
        
        // Calculate text dimensions
        $textWidth = imagefontwidth($font) * strlen($text);
        $textHeight = imagefontheight($font);
        
        // Adjust position based on alignment
        if ($textAlign === 'center') {
            $left -= $textWidth / 2;
        } elseif ($textAlign === 'right') {
            $left -= $textWidth;
        }
        
        // Render text
        imagestring($image, $font, $left, $top, $text, $textColor);
    }

    /**
     * Render rectangle to PNG
     *
     * @param resource $image
     * @param array $object
     * @param float $scale
     * @return void
     */
    private function renderRectToPng($image, array $object, float $scale): void
    {
        $left = ($object['left'] ?? 0) * $scale;
        $top = ($object['top'] ?? 0) * $scale;
        $width = ($object['width'] ?? 100) * $scale;
        $height = ($object['height'] ?? 100) * $scale;
        $fill = $object['fill'] ?? 'transparent';
        $stroke = $object['stroke'] ?? null;
        
        // Fill rectangle
        if ($fill !== 'transparent') {
            $rgb = $this->hexToRgb($fill);
            $fillColor = imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);
            imagefilledrectangle($image, $left, $top, $left + $width, $top + $height, $fillColor);
        }
        
        // Draw border
        if ($stroke) {
            $rgb = $this->hexToRgb($stroke);
            $strokeColor = imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);
            imagerectangle($image, $left, $top, $left + $width, $top + $height, $strokeColor);
        }
    }

    /**
     * Render circle to PNG
     *
     * @param resource $image
     * @param array $object
     * @param float $scale
     * @return void
     */
    private function renderCircleToPng($image, array $object, float $scale): void
    {
        $left = ($object['left'] ?? 0) * $scale;
        $top = ($object['top'] ?? 0) * $scale;
        $radius = ($object['radius'] ?? 50) * $scale;
        $fill = $object['fill'] ?? 'transparent';
        $stroke = $object['stroke'] ?? null;
        
        // Fill circle
        if ($fill !== 'transparent') {
            $rgb = $this->hexToRgb($fill);
            $fillColor = imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);
            imagefilledellipse($image, $left, $top, $radius * 2, $radius * 2, $fillColor);
        }
        
        // Draw border
        if ($stroke) {
            $rgb = $this->hexToRgb($stroke);
            $strokeColor = imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);
            imageellipse($image, $left, $top, $radius * 2, $radius * 2, $strokeColor);
        }
    }

    /**
     * Render line to PNG
     *
     * @param resource $image
     * @param array $object
     * @param float $scale
     * @return void
     */
    private function renderLineToPng($image, array $object, float $scale): void
    {
        $x1 = ($object['x1'] ?? 0) * $scale;
        $y1 = ($object['y1'] ?? 0) * $scale;
        $x2 = ($object['x2'] ?? 100) * $scale;
        $y2 = ($object['y2'] ?? 100) * $scale;
        $stroke = $object['stroke'] ?? '#000000';
        
        $rgb = $this->hexToRgb($stroke);
        $strokeColor = imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);
        
        imageline($image, $x1, $y1, $x2, $y2, $strokeColor);
    }

    /**
     * Preview certificate without saving
     *
     * @param CertificateTemplate $template
     * @param array $data
     * @return string
     */
    public function previewCertificate(CertificateTemplate $template, array $data)
    {
        $processedTemplate = $this->processTemplateData($template, $data);

        return view('backend.ai.certificates.preview', [
            'template' => $template,
            'data' => $data,
            'processedTemplate' => $processedTemplate
        ])->render();
    }
}
