@extends('backend.layouts.app')

@section('title', __('Edit Event'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            <div class="d-flex justify-content-between">
                <div>@lang('Edit Event')</div>
                <div>
                    <a href="{{ route('admin.ai.events.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> @lang('Back to Events')
                    </a>
                </div>
            </div>
        </x-slot>

        <x-slot name="body">
            <form method="POST" action="{{ route('admin.ai.events.update', $event->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="event_name">@lang('Event Name') <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('event_name') is-invalid @enderror" 
                                   id="event_name" 
                                   name="event_name" 
                                   value="{{ old('event_name', $event->event_name) }}" 
                                   required>
                            @error('event_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date">@lang('Event Date')</label>
                            <input type="date" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   id="date" 
                                   name="date" 
                                   value="{{ old('date', $event->date) }}">
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="time">@lang('Event Time')</label>
                            <input type="text" 
                                   class="form-control @error('time') is-invalid @enderror" 
                                   id="time" 
                                   name="time" 
                                   value="{{ old('time', $event->time) }}" 
                                   placeholder="e.g., 9:00 AM - 5:00 PM">
                            @error('time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="venue">@lang('Venue')</label>
                            <input type="text" 
                                   class="form-control @error('venue') is-invalid @enderror" 
                                   id="venue" 
                                   name="venue" 
                                   value="{{ old('venue', $event->venue) }}">
                            @error('venue')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="organizer">@lang('Organizer')</label>
                            <input type="text" 
                                   class="form-control @error('organizer') is-invalid @enderror" 
                                   id="organizer" 
                                   name="organizer" 
                                   value="{{ old('organizer', $event->organizer) }}">
                            @error('organizer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">@lang('Description')</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="8" 
                              placeholder="Event description, programme, speakers, agenda, etc.">{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i> @lang('Update Event')
                    </button>
                    <a href="{{ route('admin.ai.events.index') }}" class="btn btn-secondary ml-2">
                        @lang('Cancel')
                    </a>
                </div>
            </form>
        </x-slot>
    </x-backend.card>
@endsection
