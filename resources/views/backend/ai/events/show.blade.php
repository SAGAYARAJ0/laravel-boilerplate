@extends('backend.layouts.app')

@section('title', __('Event Details'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            <div class="d-flex justify-content-between">
                <div>@lang('Event Details')</div>
                <div>
                    <a href="{{ route('admin.ai.events.edit', $event->id) }}" class="btn btn-warning btn-sm mr-2">
                        <i class="fas fa-edit mr-1"></i> @lang('Edit Event')
                    </a>
                    <a href="{{ route('admin.ai.events.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> @lang('Back to Events')
                    </a>
                </div>
            </div>
        </x-slot>

        <x-slot name="body">
            <div class="row">
                <div class="col-md-12">
                    <h3>{{ $event->event_name }}</h3>
                    
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                                    <h6 class="card-title">@lang('Date')</h6>
                                    <p class="card-text">
                                        @if($event->date)
                                            {{ \Carbon\Carbon::parse($event->date)->format('F d, Y') }}
                                        @else
                                            @lang('Not specified')
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-clock fa-2x text-success mb-2"></i>
                                    <h6 class="card-title">@lang('Time')</h6>
                                    <p class="card-text">{{ $event->time ?? __('Not specified') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-map-marker-alt fa-2x text-danger mb-2"></i>
                                    <h6 class="card-title">@lang('Venue')</h6>
                                    <p class="card-text">{{ $event->venue ?? __('Not specified') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-user fa-2x text-info mb-2"></i>
                                    <h6 class="card-title">@lang('Organizer')</h6>
                                    <p class="card-text">{{ $event->organizer ?? __('Not specified') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php $parsed = $event->parsed_description; @endphp

                    @if($parsed['programme'])
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-clipboard-list mr-2"></i>@lang('Programme')</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $parsed['programme'] }}</p>
                            </div>
                        </div>
                    @endif

                    @if($parsed['cpd_points'])
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-certificate mr-2"></i>@lang('CPD Points')</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $parsed['cpd_points'] }}</p>
                            </div>
                        </div>
                    @endif

                    @if(!empty($parsed['speakers']))
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-users mr-2"></i>@lang('Speakers') ({{ count($parsed['speakers']) }})</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($parsed['speakers'] as $speaker)
                                        <div class="col-md-6 mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-tie text-muted mr-2"></i>
                                                <span>{{ $speaker }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!empty($parsed['agenda']))
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="fas fa-list-ol mr-2"></i>@lang('Agenda')</h5>
                            </div>
                            <div class="card-body">
                                <ol class="mb-0">
                                    @foreach($parsed['agenda'] as $agendaItem)
                                        <li class="mb-2">{{ $agendaItem }}</li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    @endif

                    @if($event->description && empty($parsed['programme']) && empty($parsed['speakers']) && empty($parsed['agenda']))
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i>@lang('Description')</h5>
                            </div>
                            <div class="card-body">
                                {!! nl2br(e($event->description)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </x-slot>
    </x-backend.card>
@endsection
