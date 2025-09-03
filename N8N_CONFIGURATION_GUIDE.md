# N8N Configuration Guide for Multiple PDF Events

## Problem
Currently, N8N processes PDFs and extracts event data, but events don't appear in Event Master because N8N is not sending responses back to Laravel.

## Solution
Add an HTTP Request node at the end of your N8N workflow to send extracted data back to Laravel.

## N8N Workflow Configuration

### Step 1: Add HTTP Request Node
At the end of your N8N workflow, add an **HTTP Request** node with these settings:

**Basic Settings:**
- **Method**: `POST`
- **URL**: `http://your-laravel-domain.com/api/n8n/response`
- **Authentication**: None (endpoint is public)

### Step 2: Configure Request Body
Set the request body to JSON format with the following structure:

```json
{
  "file_id": "{{ $('PDF Upload').first().json.file_id }}",
  "Event_tittle": "{{ $('Extract Event Data').first().json.event_title }}",
  "Dates_Details": "{{ $('Extract Event Data').first().json.dates }}",
  "programme": "{{ $('Extract Event Data').first().json.programme }}",
  "Venues_Details": "{{ $('Extract Event Data').first().json.venue }}",
  "Speakers_Details": "{{ $('Extract Event Data').first().json.speakers }}",
  "cpd Details": "{{ $('Extract Event Data').first().json.cpd_points }}",
  "agenda_details": "{{ $('Extract Event Data').first().json.agenda }}"
}
```

### Step 3: Headers
Add these headers:
- **Content-Type**: `application/json`
- **Accept**: `application/json`

## Expected Data Format

Laravel expects these specific field names from N8N:

| N8N Field | Laravel Field | Description |
|-----------|---------------|-------------|
| `Event_tittle` | `event_name` | Event title/name |
| `Dates_Details` | `date` | Event date (parsed automatically) |
| `programme` | `description` | Combined into description |
| `Venues_Details` | `venue` | Event venue/location |
| `Speakers_Details` | `description` | Combined into description |
| `cpd Details` | `description` | Combined into description |
| `agenda_details` | `description` | Combined into description |

## Laravel Endpoint Details

**Endpoint**: `/api/n8n/response`
**Method**: POST
**URL**: `http://your-domain.com/api/n8n/response`

The endpoint will:
1. Create database tables if they don't exist
2. Update PDF processing status
3. Parse event date from text format
4. Create unique events (won't overwrite existing events)
5. Combine programme, speakers, CPD, and agenda into description

## Testing N8N Configuration

### Manual Test
You can test the endpoint manually using curl:

```bash
curl -X POST http://your-domain.com/api/n8n/response \
  -H "Content-Type: application/json" \
  -d '{
    "file_id": "test-123",
    "Event_tittle": "Test Medical Conference",
    "Dates_Details": "March 15-16, 2025",
    "programme": "Advanced Medical Training",
    "Venues_Details": "Medical Center, London",
    "Speakers_Details": "Dr. Smith, Dr. Jones",
    "cpd Details": "20 CPD Points",
    "agenda_details": "09:00 Registration, 10:00 Sessions"
  }'
```

### Expected Response
```json
{
  "success": true,
  "message": "Response processed successfully"
}
```

## Multiple Events Handling

The system now creates unique events based on:
- Event name
- Event date  
- Event venue

This means:
- Different events with different names = separate events ✅
- Same event name but different dates = separate events ✅
- Same event name but different venues = separate events ✅
- Exact same name, date, and venue = updates existing event ✅

## Troubleshooting

### Events Not Appearing
1. Check Laravel logs: `storage/logs/laravel-*.log`
2. Look for "Received N8N response" entries
3. Verify N8N is sending POST requests to correct URL
4. Check database tables exist (events, pdf_processing)

### Common Issues
- **Wrong URL**: Ensure N8N sends to `/api/n8n/response`
- **Missing file_id**: Laravel needs file_id to match processing record
- **Wrong field names**: Use exact field names listed above
- **Network issues**: Ensure N8N can reach your Laravel application

## Current Status
- ✅ Laravel webhook endpoint ready
- ✅ Event creation logic fixed for multiple events
- ✅ Database tables auto-created
- ✅ Event Master UI ready
- ❌ N8N needs HTTP Request node configuration

Once N8N is configured to send responses, each PDF upload will automatically create events in Event Master.
