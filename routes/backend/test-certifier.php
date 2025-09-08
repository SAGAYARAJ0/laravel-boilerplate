<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

/**
 * Test Routes for Certifier Integration Debugging
 * 
 * This file contains test routes to help debug and verify the Certifier API integration.
 * These routes are temporary and should be removed in production.
 * 
 * Usage:
 * - Access /admin/test-certifier-route to verify route functionality
 * - Check logs for detailed debugging information
 * - Verify all Certifier-related routes are properly registered
 */

/**
 * Test route to debug Certifier import page access
 * 
 * This route helps verify that:
 * 1. The routing system is working correctly
 * 2. All Certifier routes are properly registered
 * 3. Authentication and middleware are functioning
 * 4. Route names are correctly defined
 * 
 * @return \Illuminate\Http\JsonResponse
 */
Route::get('/test-certifier-route', function() {
    // Log the access attempt for debugging
    Log::info('Test Certifier route accessed', [
        'timestamp' => now(),
        'user_id' => auth()->id() ?? 'guest',
        'url' => request()->fullUrl(),
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent()
    ]);
    
    // Test route generation and return diagnostic information
    try {
        $routes = [
            'certifier_import' => route('admin.ai.certificates.certifier-import'),
            'certificates_index' => route('admin.ai.certificates.index'),
            'test_connection' => route('admin.ai.certificates.certifier.test-connection'),
            'get_designs' => route('admin.ai.certificates.certifier.designs'),
        ];
        
        return response()->json([
            'success' => true,
            'message' => 'Test route working - all Certifier routes accessible',
            'timestamp' => now(),
            'user' => auth()->user() ? auth()->user()->name : 'Guest',
            'routes' => $routes,
            'config' => [
                'certifier_url' => config('services.certifier.base_url'),
                'has_token' => !empty(config('services.certifier.token')),
                'version' => config('services.certifier.version')
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Test route failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Test route failed: ' . $e->getMessage(),
            'timestamp' => now()
        ], 500);
    }
})->name('test-certifier');
