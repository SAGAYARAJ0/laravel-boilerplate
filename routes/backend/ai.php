<?php

use App\Http\Controllers\Backend\AiController;
use App\Domains\Ai\Http\Controllers\Backend\EventController;
use App\Domains\Ai\Http\Controllers\Backend\CertificateController;
use Tabuna\Breadcrumbs\Trail;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'ai',
    'as' => 'ai.',
], function () {
    // Events routes
    Route::group([
        'prefix' => 'events',
        'as' => 'events.',
    ], function () {
        Route::get('/', [EventController::class, 'index'])
            ->name('index')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('Event Master'), route('admin.ai.events.index'));
            });
            
        Route::get('/{event}', [EventController::class, 'show'])
            ->name('show')
            ->breadcrumbs(function (Trail $trail, $event) {
                $trail->parent('admin.ai.events.index')
                    ->push(__('Event Details'), route('admin.ai.events.show', $event));
            });

        Route::get('/{event}/edit', [EventController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, $event) {
                $trail->parent('admin.ai.events.show', $event)
                    ->push(__('Edit Event'), route('admin.ai.events.edit', $event));
            });

        Route::put('/{event}', [EventController::class, 'update'])
            ->name('update');

        Route::delete('/{event}', [EventController::class, 'destroy'])
            ->name('destroy');
            
        Route::post('/upload', [EventController::class, 'uploadPdf'])
            ->name('upload');
            
        Route::post('/reprocess/{fileId}', [AiController::class, 'reprocessPdf'])
            ->name('reprocess');
    });

    // Certificate Templates routes
    Route::group([
        'prefix' => 'certificates',
        'as' => 'certificates.',
    ], function () {
        Route::get('/', [CertificateController::class, 'index'])
            ->name('index')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('Certificate Templates'), route('admin.ai.certificates.index'));
            });
            
        Route::get('/create', [CertificateController::class, 'create'])
            ->name('create')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.ai.certificates.index')
                    ->push(__('Create Template'), route('admin.ai.certificates.create'));
            });
            
        Route::post('/', [CertificateController::class, 'store'])
            ->name('store');
            
        Route::get('/{certificate}', [CertificateController::class, 'show'])
            ->name('show')
            ->breadcrumbs(function (Trail $trail, $certificate) {
                $trail->parent('admin.ai.certificates.index')
                    ->push(__('View Template'), route('admin.ai.certificates.show', $certificate));
            });
            
        Route::get('/{certificate}/edit', [CertificateController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, $certificate) {
                $trail->parent('admin.ai.certificates.show', $certificate)
                    ->push(__('Edit Template'), route('admin.ai.certificates.edit', $certificate));
            });
            
        Route::put('/{certificate}', [CertificateController::class, 'update'])
            ->name('update');
            
        Route::delete('/{certificate}', [CertificateController::class, 'destroy'])
            ->name('destroy');
            
        // Certificate generation routes
        Route::post('/{certificate}/generate-single', [CertificateController::class, 'generateSingle'])
            ->name('generate-single');
            
        Route::post('/{certificate}/generate-bulk', [CertificateController::class, 'generateBulk'])
            ->name('generate-bulk');
            
        Route::post('/{certificate}/preview', [CertificateController::class, 'preview'])
            ->name('preview');
            
        Route::get('/{certificate}/data', [CertificateController::class, 'getTemplateData'])
            ->name('data');
            
        // Direct download route for certificates
        Route::get('/download/{generatedCertificate}', [CertificateController::class, 'downloadCertificate'])
            ->name('download');
            
        // Certifier import page
        Route::get('/certifier-import', function() {
            return view('backend.ai.certificates.certifier-import');
        })->name('certifier-import')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.ai.certificates.index')
                ->push(__('Import from Certifier'), route('admin.ai.certificates.certifier-import'));
        });
        
        // Debug route to log button clicks
        Route::post('/log-certifier-click', [CertificateController::class, 'logCertifierClick'])
            ->name('log-certifier-click');
            
        // Certifier integration routes
        Route::group([
            'prefix' => 'certifier',
            'as' => 'certifier.',
        ], function () {
            Route::get('/designs', [CertificateController::class, 'getCertifierDesigns'])
                ->name('designs');
                
            Route::post('/import-design/{designId}', [CertificateController::class, 'importCertifierDesign'])
                ->name('import-design');
                
            Route::post('/{certificate}/create-credential', [CertificateController::class, 'createCertifierCredential'])
                ->name('create-credential');
                
            Route::post('/{certificate}/issue-credential/{credentialId}', [CertificateController::class, 'issueCertifierCredential'])
                ->name('issue-credential');
                
            Route::post('/{certificate}/send-credential/{credentialId}', [CertificateController::class, 'sendCertifierCredential'])
                ->name('send-credential');
                
            Route::post('/{certificate}/create-issue-send', [CertificateController::class, 'createIssueSendCredential'])
                ->name('create-issue-send');
                
            Route::get('/test-connection', [CertificateController::class, 'testCertifierConnection'])
                ->name('test-connection');
        });
    });
    Route::group([
        'prefix' => 'pdf',
        'as' => 'pdf.',
    ], function () {
        Route::get('/', [AiController::class, 'index'])
            ->name('index')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.dashboard')
                    ->push(__('PDF Upload & Analysis'), route('admin.ai.pdf.index'));
            });

        Route::post('/upload', [AiController::class, 'uploadPdf'])
            ->name('upload');
            
        Route::get('/view/{id}', [AiController::class, 'view'])
            ->name('view')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.ai.pdf.index')
                    ->push(__('View PDF'));
            });
            
        // Processing status and results
        Route::get('/status/{fileId}', [AiController::class, 'getProcessingStatus'])
            ->name('status');
            
        Route::get('/records', [AiController::class, 'listProcessingRecords'])
            ->name('records');
    });
});
