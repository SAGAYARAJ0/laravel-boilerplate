<?php

use App\Http\Controllers\Backend\AiController;
use App\Domains\Ai\Http\Controllers\Backend\EventController;
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
