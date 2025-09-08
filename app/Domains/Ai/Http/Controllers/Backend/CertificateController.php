<?php

namespace App\Domains\Ai\Http\Controllers\Backend;

use App\Domains\Ai\Models\CertificateTemplate;
use App\Domains\Ai\Models\GeneratedCertificate;
use App\Domains\Ai\Models\CertifierCredential;
use App\Domains\Ai\Services\CertificateGeneratorService;
use App\Services\CertifierClient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CertificateController extends Controller
{
    protected $certificateGenerator;

    public function __construct(CertificateGeneratorService $certificateGenerator)
    {
        $this->certificateGenerator = $certificateGenerator;
    }

    /**
     * Display a listing of certificate templates
     */
    public function index()
    {
        $templates = CertificateTemplate::latest()->get();
        return view('backend.ai.certificates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new certificate template
     */
    public function create()
    {
        return view('backend.ai.certificates.create');
    }

    /**
     * Store a newly created certificate template
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'template_data' => 'required|json',
            'background_image' => 'nullable|image|max:2048',
            'width' => 'nullable|integer|min:100|max:5000',
            'height' => 'nullable|integer|min:100|max:5000',
            'orientation' => 'nullable|in:portrait,landscape'
        ]);

        $template = CertificateTemplate::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'template_data' => json_decode($validated['template_data'], true),
            'width' => $validated['width'] ?? 1200,
            'height' => $validated['height'] ?? 800,
            'orientation' => $validated['orientation'] ?? 'landscape',
            'is_active' => true
        ]);

        if ($request->hasFile('background_image')) {
            $template->background_image = $request->file('background_image')
                ->store('certificate-templates', 'public');
            $template->save();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Certificate template created successfully',
                'redirect' => route('admin.ai.certificates.show', $template)
            ]);
        }

        return redirect()->route('admin.ai.certificates.show', $template)
            ->with('success', 'Certificate template created successfully');
    }

    /**
     * Display the specified certificate template
     */
    public function show(CertificateTemplate $certificate)
    {
        $certificate->load('generatedCertificates');
        return view('backend.ai.certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified certificate template
     */
    public function edit(CertificateTemplate $certificate)
    {
        return view('backend.ai.certificates.edit', compact('certificate'));
    }

    /**
     * Update the specified certificate template
     */
    public function update(Request $request, CertificateTemplate $certificate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'template_data' => 'required|json',
            'background_image' => 'nullable|image|max:2048',
            'width' => 'nullable|integer|min:100|max:5000',
            'height' => 'nullable|integer|min:100|max:5000',
            'orientation' => 'nullable|in:portrait,landscape',
            'is_active' => 'boolean'
        ]);

        $certificate->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'template_data' => json_decode($validated['template_data'], true),
            'width' => $validated['width'] ?? $certificate->width,
            'height' => $validated['height'] ?? $certificate->height,
            'orientation' => $validated['orientation'] ?? $certificate->orientation,
            'is_active' => $validated['is_active'] ?? $certificate->is_active
        ]);

        if ($request->hasFile('background_image')) {
            // Delete old background image
            if ($certificate->background_image) {
                Storage::disk('public')->delete($certificate->background_image);
            }
            
            $certificate->background_image = $request->file('background_image')
                ->store('certificate-templates', 'public');
            $certificate->save();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Certificate template updated successfully'
            ]);
        }

        return redirect()->route('admin.ai.certificates.show', $certificate)
            ->with('success', 'Certificate template updated successfully');
    }

    /**
     * Remove the specified certificate template
     */
    public function destroy(CertificateTemplate $certificate)
    {
        // Delete background image
        if ($certificate->background_image) {
            Storage::disk('public')->delete($certificate->background_image);
        }

        $certificate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Certificate template deleted successfully'
        ]);
    }

    /**
     * Generate a single certificate
     */
    public function generateSingle(Request $request, CertificateTemplate $certificate)
    {
        try {
            $validated = $request->validate([
                'recipient_name' => 'required|string|max:255',
                'recipient_email' => 'nullable|email',
                'course_name' => 'required|string|max:255',
                'completion_date' => 'nullable|date',
                'instructor_name' => 'nullable|string|max:255',
                'organization' => 'nullable|string|max:255'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $generatedCertificate = $this->certificateGenerator->generateSingle($certificate, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Certificate generated successfully',
                'download_url' => route('admin.ai.certificates.download', $generatedCertificate),
                'certificate_id' => $generatedCertificate->certificate_id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate certificate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download a generated certificate directly
     */
    public function downloadCertificate(\App\Domains\Ai\Models\GeneratedCertificate $generatedCertificate)
    {
        if (!$generatedCertificate->file_path || !Storage::disk('public')->exists($generatedCertificate->file_path)) {
            abort(404, 'Certificate file not found');
        }

        $filePath = Storage::disk('public')->path($generatedCertificate->file_path);
        $fileExtension = pathinfo($generatedCertificate->file_path, PATHINFO_EXTENSION);
        $fileName = 'certificate-' . $generatedCertificate->certificate_id . '.' . $fileExtension;

        $contentType = match($fileExtension) {
            'png' => 'image/png',
            'html' => 'text/html',
            default => 'application/pdf'
        };

        return response()->download($filePath, $fileName, [
            'Content-Type' => $contentType,
        ]);
    }

    /**
     * Generate multiple certificates
     */
    public function generateBulk(Request $request, CertificateTemplate $certificate)
    {
        $validated = $request->validate([
            'recipients' => 'required|array',
            'recipients.*.recipient_name' => 'required|string|max:255',
            'recipients.*.recipient_email' => 'nullable|email',
            'recipients.*.course_name' => 'required|string|max:255',
            'recipients.*.completion_date' => 'nullable|date',
            'recipients.*.instructor_name' => 'nullable|string|max:255',
            'recipients.*.organization' => 'nullable|string|max:255'
        ]);

        try {
            $result = $this->certificateGenerator->generateBulk($certificate, $validated['recipients']);

            return response()->json([
                'success' => true,
                'message' => 'Bulk certificate generation completed',
                'summary' => $result['summary'],
                'certificates' => $result['certificates']->map(function ($cert) {
                    return [
                        'id' => $cert->id,
                        'certificate_id' => $cert->certificate_id,
                        'recipient_name' => $cert->recipient_name,
                        'download_url' => $cert->file_url
                    ];
                }),
                'errors' => $result['errors']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate certificates: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview certificate
     */
    public function preview(Request $request, CertificateTemplate $certificate)
    {
        $validated = $request->validate([
            'recipient_name' => 'nullable|string|max:255',
            'course_name' => 'nullable|string|max:255',
            'completion_date' => 'nullable|date',
            'instructor_name' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255'
        ]);

        // Use sample data if not provided
        $previewData = array_merge([
            'recipient_name' => 'John Doe',
            'course_name' => 'Sample Course',
            'completion_date' => now(),
            'instructor_name' => 'Jane Smith',
            'organization' => config('app.name')
        ], $validated);

        try {
            $previewHtml = $this->certificateGenerator->previewCertificate($certificate, $previewData);

            return response()->json([
                'success' => true,
                'preview_html' => $previewHtml
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate preview: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get template data for editor
     */
    public function getTemplateData(CertificateTemplate $certificate)
    {
        return response()->json([
            'success' => true,
            'template_data' => $certificate->template_data,
            'variables' => $certificate->template_variables
        ]);
    }

    /**
     * Get Certifier designs
     */
    public function getCertifierDesigns(Request $request)
    {
        Log::info('Certifier designs request started', [
            'request_params' => $request->all(),
            'user_id' => auth()->id()
        ]);

        try {
            $certifierClient = new CertifierClient();
            $query = $request->only(['limit', 'offset', 'search']);
            
            Log::info('Calling Certifier API for designs', ['query' => $query]);
            
            $designs = $certifierClient->getDesigns($query);
            
            Log::info('Certifier designs fetched successfully', [
                'designs_count' => isset($designs['data']) ? count($designs['data']) : 0
            ]);
            
            return response()->json([
                'success' => true,
                'designs' => $designs
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch Certifier designs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch designs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import a Certifier design as a template
     */
    public function importCertifierDesign(Request $request, $designId)
    {
        try {
            $certifierClient = new CertifierClient();
            $design = $certifierClient->getDesign($designId);
            
            // Create group for this design
            $groupPayload = [
                'design_id' => $designId,
                'name' => $request->input('name', 'Imported Template - ' . $design['name']),
                'description' => $request->input('description', 'Imported from Certifier')
            ];
            
            $group = $certifierClient->createGroup($groupPayload);
            
            // Create certificate template
            $template = CertificateTemplate::create([
                'name' => $request->input('name', 'Imported Template - ' . $design['name']),
                'description' => $request->input('description', 'Imported from Certifier'),
                'template_source' => 'certifier',
                'certifier_design_id' => $designId,
                'certifier_group_id' => $group['id'],
                'certifier_metadata' => $design,
                'is_active' => true,
                'width' => 1200,
                'height' => 800
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Design imported successfully',
                'template' => $template
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to import Certifier design: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to import design: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a Certifier credential
     */
    public function createCertifierCredential(Request $request, CertificateTemplate $certificate)
    {
        if (!$certificate->isCertifierTemplate()) {
            return response()->json([
                'success' => false,
                'message' => 'This template is not a Certifier template'
            ], 400);
        }

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'required|email',
            'credential_data' => 'required|array'
        ]);

        try {
            $certifierClient = new CertifierClient();
            
            $credentialPayload = [
                'group_id' => $certificate->certifier_group_id,
                'recipient' => [
                    'name' => $validated['recipient_name'],
                    'email' => $validated['recipient_email']
                ],
                'credential_data' => $validated['credential_data']
            ];
            
            $credential = $certifierClient->createCredential($credentialPayload);
            
            // Store in our database
            $certifierCredential = CertifierCredential::create([
                'certificate_template_id' => $certificate->id,
                'certifier_credential_id' => $credential['id'],
                'recipient_name' => $validated['recipient_name'],
                'recipient_email' => $validated['recipient_email'],
                'credential_data' => $credential,
                'status' => 'created'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Credential created successfully',
                'credential' => $certifierCredential
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create Certifier credential: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create credential: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Issue a Certifier credential
     */
    public function issueCertifierCredential(CertificateTemplate $certificate, $credentialId)
    {
        $certifierCredential = CertifierCredential::where('certifier_credential_id', $credentialId)
            ->where('certificate_template_id', $certificate->id)
            ->firstOrFail();

        try {
            $certifierClient = new CertifierClient();
            $result = $certifierClient->issueCredential($credentialId);
            
            $certifierCredential->update([
                'status' => 'issued',
                'issued_at' => now(),
                'certifier_url' => $result['url'] ?? null,
                'credential_data' => array_merge($certifierCredential->credential_data, $result)
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Credential issued successfully',
                'credential' => $certifierCredential->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to issue Certifier credential: ' . $e->getMessage());
            
            $certifierCredential->update([
                'status' => 'failed',
                'error_details' => ['message' => $e->getMessage()]
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to issue credential: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send a Certifier credential
     */
    public function sendCertifierCredential(Request $request, CertificateTemplate $certificate, $credentialId)
    {
        $certifierCredential = CertifierCredential::where('certifier_credential_id', $credentialId)
            ->where('certificate_template_id', $certificate->id)
            ->firstOrFail();

        try {
            $certifierClient = new CertifierClient();
            $payload = $request->only(['message', 'subject']);
            
            $result = $certifierClient->sendCredential($credentialId, $payload);
            
            $certifierCredential->update([
                'status' => 'sent',
                'sent_at' => now(),
                'credential_data' => array_merge($certifierCredential->credential_data, $result)
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Credential sent successfully',
                'credential' => $certifierCredential->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send Certifier credential: ' . $e->getMessage());
            
            $certifierCredential->update([
                'status' => 'failed',
                'error_details' => ['message' => $e->getMessage()]
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send credential: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create, issue, and send credential in one call
     */
    public function createIssueSendCredential(Request $request, CertificateTemplate $certificate)
    {
        if (!$certificate->isCertifierTemplate()) {
            return response()->json([
                'success' => false,
                'message' => 'This template is not a Certifier template'
            ], 400);
        }

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'required|email',
            'credential_data' => 'required|array',
            'message' => 'nullable|string',
            'subject' => 'nullable|string'
        ]);

        try {
            $certifierClient = new CertifierClient();
            
            $payload = [
                'group_id' => $certificate->certifier_group_id,
                'recipient' => [
                    'name' => $validated['recipient_name'],
                    'email' => $validated['recipient_email']
                ],
                'credential_data' => $validated['credential_data'],
                'send_options' => [
                    'message' => $validated['message'] ?? null,
                    'subject' => $validated['subject'] ?? null
                ]
            ];
            
            $result = $certifierClient->createIssueSend($payload);
            
            // Store in our database
            $certifierCredential = CertifierCredential::create([
                'certificate_template_id' => $certificate->id,
                'certifier_credential_id' => $result['id'],
                'recipient_name' => $validated['recipient_name'],
                'recipient_email' => $validated['recipient_email'],
                'credential_data' => $result,
                'status' => 'sent',
                'issued_at' => now(),
                'sent_at' => now(),
                'certifier_url' => $result['url'] ?? null
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Credential created, issued, and sent successfully',
                'credential' => $certifierCredential
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create, issue, and send Certifier credential: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process credential: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test Certifier API connection
     */
    public function testCertifierConnection()
    {
        Log::info('Certifier connection test started', [
            'user_id' => auth()->id(),
            'timestamp' => now()
        ]);

        try {
            $certifierClient = new CertifierClient();
            
            Log::info('Testing Certifier API connection', [
                'api_url' => config('services.certifier.base_url'),
                'has_token' => !empty(config('services.certifier.token'))
            ]);
            
            $isConnected = $certifierClient->testConnection();
            
            Log::info('Certifier connection test completed', [
                'success' => $isConnected
            ]);
            
            return response()->json([
                'success' => $isConnected,
                'message' => $isConnected ? 'Connection successful' : 'Connection failed'
            ]);
        } catch (\Exception $e) {
            Log::error('Certifier connection test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'config' => [
                    'api_url' => config('services.certifier.base_url'),
                    'has_token' => !empty(config('services.certifier.token')),
                    'version' => config('services.certifier.version')
                ]
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Log Certifier button click for debugging
     */
    public function logCertifierClick(Request $request)
    {
        Log::info('Certifier Import button clicked', [
            'user_id' => auth()->id(),
            'target_url' => $request->input('target_url'),
            'current_url' => $request->input('current_url'),
            'timestamp' => $request->input('timestamp'),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
            'session_id' => session()->getId(),
            'route_exists' => \Route::has('admin.ai.certificates.certifier-import'),
            'generated_route' => route('admin.ai.certificates.certifier-import')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Click logged successfully'
        ]);
    }
}
