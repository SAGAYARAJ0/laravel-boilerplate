<?php

namespace App\Domains\Ai\Services;

use App\Domains\Ai\Models\Event;
use Illuminate\Support\Facades\Log;

class EventMasterService
{
    /**
     * Create or update event from processed data
     *
     * @param array $eventData
     * @return Event
     */
    public function createOrUpdateEvent(array $eventData)
    {
        try {
            Log::info('Starting event creation/update process', [
                'event_data' => $eventData
            ]);

            // Create unique identifier based on event name, date, and venue
            $uniqueKey = $this->generateEventKey($eventData);
            
            $existingEvent = Event::where('event_name', $eventData['event_name'])
                ->where('date', $eventData['date'] ?? null)
                ->where('venue', $eventData['venue'] ?? null)
                ->first();

            if ($existingEvent) {
                // Update existing event
                $existingEvent->update($eventData);
                $event = $existingEvent;
            } else {
                // Create new event
                $event = Event::create($eventData);
            }

            if ($existingEvent) {
                Log::info('Event updated successfully', [
                    'event_id' => $event->id,
                    'event_name' => $event->event_name,
                    'changes' => $event->getChanges()
                ]);

                activity('event_update')
                    ->performedOn($event)
                    ->withProperties([
                        'old_values' => $existingEvent->toArray(),
                        'new_values' => $event->toArray(),
                        'user_id' => auth()->id()
                    ])
                    ->log('Event updated');
            } else {
                Log::info('New event created successfully', [
                    'event_id' => $event->id,
                    'event_name' => $event->event_name
                ]);

                activity('event_creation')
                    ->performedOn($event)
                    ->withProperties([
                        'event_data' => $event->toArray(),
                        'user_id' => auth()->id()
                    ])
                    ->log('New event created');
            }

            return $event;

        } catch (\Exception $e) {
            Log::error('Error creating/updating event', [
                'error' => $e->getMessage(),
                'event_data' => $eventData,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Generate unique key for event identification
     *
     * @param array $eventData
     * @return string
     */
    private function generateEventKey(array $eventData)
    {
        $key = ($eventData['event_name'] ?? 'unknown') . '|' . 
               ($eventData['date'] ?? 'no-date') . '|' . 
               ($eventData['venue'] ?? 'no-venue');
        
        return md5($key);
    }
}
