@extends('backend.layouts.app')

@section('title', __('Event Master'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            <div class="d-flex justify-content-between">
                <div>@lang('Event Master')</div>
                <div>
                    <a href="{{ route('admin.ai.pdf.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-upload mr-1"></i> @lang('Upload Event PDF')
                    </a>
                </div>
            </div>
        </x-slot>

        <x-slot name="body">
            <!-- Recently Uploaded PDFs Section -->
            @if(isset($recentPdfs) && $recentPdfs->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-file-pdf mr-2"></i>Recently Uploaded PDFs</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Upload Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPdfs as $pdf)
                                        <tr>
                                            <td>
                                                <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                {{ $pdf->original_name }}
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $pdf->created_at->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($pdf->status === 'completed')
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check mr-1"></i>Completed
                                                    </span>
                                                @elseif($pdf->status === 'processing')
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-spinner mr-1"></i>Processing
                                                    </span>
                                                @elseif($pdf->status === 'failed')
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times mr-1"></i>Failed
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">
                                                        <i class="fas fa-clock mr-1"></i>Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.ai.events.reprocess', $pdf->file_id) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn p-0 border-0 bg-transparent" title="Re-upload and reprocess PDF">
                                                        <span class="badge badge-pill badge-success p-2">
                                                            <i class="fas fa-redo"></i>
                                                        </span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Events Section -->
            <div class="row" id="events-container">
                @forelse($events as $event)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $event->event_name }}</h5>
                                <p class="card-text text-muted">{{ $event->summary }}</p>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-calendar-alt mr-2"></i> 
                                    @if($event->date)
                                        {{ \Carbon\Carbon::parse($event->date)->format('F d, Y') }}
                                    @else
                                        Date not specified
                                    @endif
                                </p>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-clock mr-2"></i> {{ $event->time ?? 'Time not specified' }}
                                </p>
                                <p class="text-muted mb-3">
                                    <i class="fas fa-map-marker-alt mr-2"></i> {{ $event->venue ?? 'Venue not specified' }}
                                </p>
                                <p class="text-muted mb-3">
                                    <i class="fas fa-user mr-2"></i> {{ $event->organizer ?? 'Organizer not specified' }}
                                </p>
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('admin.ai.events.show', $event->id) }}" class="text-decoration-none mr-2" title="View Details">
                                        <span class="badge badge-pill badge-info p-2">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </a>
                                    <a href="{{ route('admin.ai.events.edit', $event->id) }}" class="text-decoration-none mr-2" title="Edit Event">
                                        <span class="badge badge-pill badge-secondary p-2">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.ai.events.destroy', $event->id) }}" style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this event?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn p-0 border-0 bg-transparent" title="Delete Event">
                                            <span class="badge badge-pill badge-danger p-2">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            @lang('No events found. Upload a PDF to create events automatically.')
                        </div>
                    </div>
                @endforelse
            </div>
        </x-slot>
    </x-backend.card>

    @push('after-scripts')
    <script>
        // Listen for new event notifications
        window.addEventListener('DOMContentLoaded', (event) => {
            // Function to refresh events when new ones are added
            window.refreshEvents = function() {
                fetch('{{ route("admin.ai.events.index") }}')
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newEventsContainer = doc.getElementById('events-container');
                        document.getElementById('events-container').innerHTML = newEventsContainer.innerHTML;
                    });
            };
        });
    </script>
    @endpush
@endsection
