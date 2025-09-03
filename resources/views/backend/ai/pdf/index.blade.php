@extends('backend.layouts.app')

@section('title', __('PDF Upload & Analysis'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('PDF Upload & Analysis')
        </x-slot>

        <x-slot name="body">
            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('admin.ai.pdf.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="pdf_file" class="form-label">@lang('Select PDF File')</label>
                            <input type="file" 
                                   class="form-control" 
                                   id="pdf_file" 
                                   name="pdf_file" 
                                   accept=".pdf"
                                   required>
                            <small class="form-text text-muted">@lang('Maximum file size: 10MB')</small>
                        </div>
                        <button type="submit" class="btn btn-primary">@lang('Upload PDF')</button>
                    </form>
                </div>
            </div>

            @if(session('uploaded_pdfs'))
                <div class="row mt-4">
                    <div class="col-sm-12">
                        <h5>@lang('Recently Uploaded PDFs')</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('File Name')</th>
                                        <th>@lang('Upload Date')</th>
                                        <th>@lang('Actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(session('uploaded_pdfs') as $pdf)
                                        <tr>
                                            <td>{{ $pdf['original_name'] }}</td>
                                            <td>{{ \Carbon\Carbon::parse($pdf['created_at'])->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ route('admin.ai.pdf.view', $pdf['id']) }}" 
                                                   class="btn btn-info btn-sm" 
                                                   target="_blank">
                                                    @lang('View')
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </x-slot>
    </x-backend.card>
@endsection
