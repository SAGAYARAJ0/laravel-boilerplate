<?php

namespace App\Domains\Ai\Http\Controllers\Backend;

use App\Domains\Ai\Services\EventProcessingService;
use App\Domains\Ai\Services\EventMasterService;
use App\Domains\Ai\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $eventProcessingService;
    protected $eventMasterService;

    public function __construct(
        EventProcessingService $eventProcessingService,
        EventMasterService $eventMasterService
    ) {
        $this->eventProcessingService = $eventProcessingService;
        $this->eventMasterService = $eventMasterService;
    }

    /**
     * Display a listing of events.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $events = Event::orderBy('date', 'asc')->get();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'events' => $events
                ]);
            }

            return view('backend.ai.events.index', compact('events'));
        } catch (\Exception $e) {
            $events = collect([]); // Empty collection if table doesn't exist
            return view('backend.ai.events.index', compact('events'));
        }
    }

    /**
     * Display the specified event.
     *
     * @param Event $event
     * @return \Illuminate\View\View
     */
    public function show(Event $event)
    {
        return view('backend.ai.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     *
     * @param Event $event
     * @return \Illuminate\View\View
     */
    public function edit(Event $event)
    {
        return view('backend.ai.events.edit', compact('event'));
    }

    /**
     * Update the specified event in storage.
     *
     * @param Request $request
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'date' => 'nullable|date',
            'time' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $event->update($request->all());

        activity('event_update')
            ->performedOn($event)
            ->withProperties([
                'updated_fields' => $request->all(),
                'user_id' => auth()->id()
            ])
            ->log('Event updated manually');

        return redirect()->route('admin.ai.events.index')
            ->withFlash('success', __('Event updated successfully.'));
    }

    /**
     * Remove the specified event from storage.
     *
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Event $event)
    {
        $eventName = $event->event_name;

        activity('event_deletion')
            ->performedOn($event)
            ->withProperties([
                'deleted_event' => $event->toArray(),
                'user_id' => auth()->id()
            ])
            ->log('Event deleted manually');

        $event->delete();

        return redirect()->route('admin.ai.events.index')
            ->withFlash('success', __('Event "' . $eventName . '" deleted successfully.'));
    }

    /**
     * Handle PDF upload and processing
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadPdf(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        try {
            $file = $request->file('pdf_file');
            
            // Log the start of PDF upload process
            activity('pdf_upload')
                ->withProperties([
                    'filename' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'user_id' => auth()->id()
                ])
                ->log('Started PDF upload and processing');

            // Process PDF through n8n
            $n8nResponse = $this->eventProcessingService->processPdf($file);

            // Log successful n8n processing
            activity('n8n_processing')
                ->withProperties([
                    'filename' => $file->getClientOriginalName(),
                    'status' => 'success',
                    'user_id' => auth()->id()
                ])
                ->log('PDF successfully processed through n8n');

            // Extract event details
            $eventData = $this->eventProcessingService->extractEventDetails($n8nResponse);

            // Create or update event
            $event = $this->eventMasterService->createOrUpdateEvent($eventData);

            // Log successful event creation
            activity('event_creation')
                ->performedOn($event)
                ->withProperties([
                    'event_name' => $event->event_name,
                    'venue' => $event->venue,
                    'date' => $event->date,
                    'user_id' => auth()->id(),
                    'source_file' => $file->getClientOriginalName()
                ])
                ->log('Event successfully created from PDF');

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Event processed successfully',
                    'data' => $event
                ]);
            }

            return redirect()->route('admin.ai.events.index')
                ->withFlash('success', __('Event created successfully.'));

        } catch (\Exception $e) {
            // Log the error
            activity('pdf_processing_error')
                ->withProperties([
                    'filename' => $request->file('pdf_file')->getClientOriginalName(),
                    'error_message' => $e->getMessage(),
                    'user_id' => auth()->id(),
                    'error_trace' => $e->getTraceAsString()
                ])
                ->log('Error processing PDF file');

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process event PDF: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withFlash('error', __('Failed to process event PDF: ') . $e->getMessage());
        }
    }
}
