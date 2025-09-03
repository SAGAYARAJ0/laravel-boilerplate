@extends('backend.layouts.app')

@section('title', __('Dashboard'))

@section('content')
    {{-- Welcome Card --}}
    <x-backend.card>
        <x-slot name="header">
            <i class="c-icon cil-people mr-1"></i> @lang('Welcome :Name', ['name' => $logged_in_user->name])
        </x-slot>

        <x-slot name="body">
            <div class="row">
                {{-- Event Statistics --}}
                <div class="col-sm-6 col-lg-3">
                    <div class="card bg-gradient-primary text-white">
                        <div class="card-body">
                            <div class="text-value-lg">{{ $totalEvents ?? 0 }}</div>
                            <div>@lang('Total Events')</div>
                            <div class="progress progress-xs my-2 bg-white bg-opacity-25">
                                <div class="progress-bar bg-white" role="progressbar" style="width: {{ min(100, ($totalEvents ?? 0) * 10) }}%"></div>
                            </div>
                            <small class="text-white">@lang('All Time')</small>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card bg-gradient-info text-white">
                        <div class="card-body">
                            <div class="text-value-lg">{{ $totalPdfs ?? 0 }}</div>
                            <div>@lang('PDF Documents')</div>
                            <div class="progress progress-xs my-2 bg-white bg-opacity-25">
                                <div class="progress-bar bg-white" role="progressbar" style="width: {{ $totalPdfs > 0 ? min(100, ($processedPdfs ?? 0) / $totalPdfs * 100) : 0 }}%"></div>
                            </div>
                            <small class="text-white">{{ $processedPdfs ?? 0 }} @lang('Processed')</small>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card bg-gradient-success text-white">
                        <div class="card-body">
                            <div class="text-value-lg">{{ $upcomingEvents ?? 0 }}</div>
                            <div>@lang('Upcoming Events')</div>
                            <div class="progress progress-xs my-2 bg-white bg-opacity-25">
                                <div class="progress-bar bg-white" role="progressbar" style="width: {{ min(100, ($upcomingEvents ?? 0) * 20) }}%"></div>
                            </div>
                            <small class="text-white">@lang('This Month')</small>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card bg-gradient-warning text-white">
                        <div class="card-body">
                            <div class="text-value-lg">{{ $thisMonthEvents ?? 0 }}</div>
                            <div>@lang('New Events')</div>
                            <div class="progress progress-xs my-2 bg-white bg-opacity-25">
                                <div class="progress-bar bg-white" role="progressbar" style="width: {{ min(100, ($thisMonthEvents ?? 0) * 15) }}%"></div>
                            </div>
                            <small class="text-white">@lang('This Month')</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Activity Section --}}
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="c-icon cil-list mr-1"></i> @lang('Recent PDF Processing')
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @forelse($recentPdfs ?? [] as $pdf)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="c-icon cil-file text-primary mr-2"></i>
                                            {{ Str::limit($pdf->original_name, 30) }}
                                            <small class="text-muted d-block">
                                                @if($pdf->status === 'completed')
                                                    <span class="badge badge-success">@lang('Completed')</span>
                                                @elseif($pdf->status === 'processing')
                                                    <span class="badge badge-warning">@lang('Processing')</span>
                                                @else
                                                    <span class="badge badge-danger">@lang('Failed')</span>
                                                @endif
                                            </small>
                                        </div>
                                        <small class="text-muted">{{ $pdf->created_at->diffForHumans() }}</small>
                                    </div>
                                @empty
                                    <div class="list-group-item text-center text-muted">
                                        @lang('No PDF uploads yet')
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="c-icon cil-calendar mr-1"></i> @lang('Upcoming Events')
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @forelse($nextEvents ?? [] as $event)
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ Str::limit($event->event_name, 40) }}</h6>
                                            <small class="text-primary">
                                                @if($event->date)
                                                    {{ \Carbon\Carbon::parse($event->date)->format('M j, Y') }}
                                                @else
                                                    @lang('Date TBD')
                                                @endif
                                            </small>
                                        </div>
                                        <p class="mb-1 text-muted">
                                            @if($event->venue)
                                                <i class="c-icon cil-location-pin mr-1"></i>{{ Str::limit($event->venue, 50) }}
                                            @else
                                                @lang('Venue not specified')
                                            @endif
                                        </p>
                                        <small>
                                            <i class="c-icon cil-clock mr-1"></i>
                                            {{ $event->time ?? '09:00 AM - 05:00 PM' }}
                                        </small>
                                    </div>
                                @empty
                                    <div class="list-group-item text-center text-muted">
                                        @lang('No upcoming events')
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Events Section --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="c-icon cil-calendar mr-1"></i> @lang('Recent Events')
                            </div>
                            <a href="{{ route('admin.ai.events.index') }}" class="btn btn-sm btn-outline-primary">
                                @lang('View All Events')
                            </a>
                        </div>
                        <div class="card-body">
                            @forelse($recentEvents ?? [] as $event)
                                <div class="row mb-3 p-3 border rounded {{ $loop->last ? '' : 'mb-3' }}" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                    <div class="col-md-8">
                                        <h5 class="mb-2 text-primary">
                                            <i class="c-icon cil-calendar-check mr-2"></i>
                                            {{ $event->event_name }}
                                        </h5>
                                        <div class="row text-muted mb-2">
                                            <div class="col-sm-6">
                                                <i class="c-icon cil-clock mr-1"></i>
                                                @if($event->date)
                                                    {{ \Carbon\Carbon::parse($event->date)->format('M j, Y') }}
                                                @else
                                                    @lang('Date TBD')
                                                @endif
                                                @if($event->time)
                                                    • {{ $event->time }}
                                                @endif
                                            </div>
                                            <div class="col-sm-6">
                                                @if($event->venue)
                                                    <i class="c-icon cil-location-pin mr-1"></i>
                                                    {{ Str::limit($event->venue, 40) }}
                                                @endif
                                            </div>
                                        </div>
                                        @if($event->description)
                                            <p class="text-muted mb-0">
                                                {{ Str::limit(strip_tags($event->description), 120) }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <div class="mb-2">
                                            <span class="badge badge-info">{{ $event->organizer ?? 'Event Master' }}</span>
                                        </div>
                                        <small class="text-muted d-block">
                                            @lang('Created') {{ $event->created_at->diffForHumans() }}
                                        </small>
                                        <div class="mt-2">
                                            <a href="{{ route('admin.ai.events.show', $event->id) }}" class="btn btn-sm btn-outline-primary">
                                                @lang('View Details')
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="c-icon cil-calendar text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted">@lang('No Events Yet')</h5>
                                    <p class="text-muted">@lang('Upload a PDF to automatically create events from medical conference documents')</p>
                                    <a href="{{ route('admin.ai.pdf.index') }}" class="btn btn-primary">
                                        <i class="c-icon cil-cloud-upload mr-1"></i>
                                        @lang('Upload PDF')
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="c-icon cil-lightning mr-1"></i> @lang('Quick Actions')
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <a href="{{ route('admin.ai.pdf.index') }}" class="btn btn-light btn-block py-3 border-primary">
                                        <i class="c-icon cil-cloud-upload text-primary mb-2" style="font-size: 2rem;"></i>
                                        <div class="font-weight-bold">@lang('Upload PDF')</div>
                                        <small class="text-muted">@lang('Auto-create events')</small>
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <a href="{{ route('admin.ai.events.index') }}" class="btn btn-light btn-block py-3 border-success">
                                        <i class="c-icon cil-calendar-check text-success mb-2" style="font-size: 2rem;"></i>
                                        <div class="font-weight-bold">@lang('Event Master')</div>
                                        <small class="text-muted">@lang('Manage events')</small>
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <a href="{{ route('admin.auth.user.index') }}" class="btn btn-light btn-block py-3 border-info">
                                        <i class="c-icon cil-user-follow text-info mb-2" style="font-size: 2rem;"></i>
                                        <div class="font-weight-bold">@lang('Users')</div>
                                        <small class="text-muted">@lang('Manage users')</small>
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <a href="#" class="btn btn-light btn-block py-3 border-warning">
                                        <i class="c-icon cil-chart text-warning mb-2" style="font-size: 2rem;"></i>
                                        <div class="font-weight-bold">@lang('Reports')</div>
                                        <small class="text-muted">@lang('View analytics')</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-backend.card>
@endsection
