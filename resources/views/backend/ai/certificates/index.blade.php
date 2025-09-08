@extends('backend.layouts.app')

@section('title', __('Certificate Templates'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    {{ __('Certificate Templates') }}
                    <small class="text-muted">{{ __('Management') }}</small>
                </h4>
            </div><!--col-->

            <div class="col-sm-7">
                <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('Toolbar with button groups')">
                    <a href="{{ route('admin.ai.certificates.create') }}" class="btn btn-success ml-1" data-toggle="tooltip" title="@lang('Create Certificate Template')">
                        <i class="fas fa-plus-circle"></i> @lang('Create Template')
                    </a>
                    <a href="{{ route('admin.ai.certificates.certifier-import') }}" class="btn btn-info ml-1" data-toggle="tooltip" title="@lang('Import from Certifier')" id="certifierImportBtn">
                        <i class="fas fa-cloud-download-alt"></i> @lang('Import from Certifier')
                    </a>
                </div><!--btn-toolbar-->
            </div><!--col-->
        </div><!--row-->

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Description')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Created')</th>
                            <th>@lang('Actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td>
                                    <strong>{{ $template->name }}</strong>
                                </td>
                                <td>
                                    {{ Str::limit($template->description, 50) }}
                                </td>
                                <td>
                                    @if($template->is_active)
                                        <span class="badge badge-success">@lang('Active')</span>
                                    @else
                                        <span class="badge badge-danger">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $template->created_at->format('M d, Y') }}
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="@lang('Template Actions')">
                                        <a href="{{ route('admin.ai.certificates.show', $template) }}" class="btn btn-info btn-sm" data-toggle="tooltip" title="@lang('View')">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.ai.certificates.edit', $template) }}" class="btn btn-primary btn-sm" data-toggle="tooltip" title="@lang('Edit')">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-success btn-sm generate-certificate" data-id="{{ $template->id }}" data-toggle="tooltip" title="@lang('Generate Certificate')">
                                            <i class="fas fa-certificate"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm delete-template" data-id="{{ $template->id }}" data-toggle="tooltip" title="@lang('Delete')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <p class="mb-0">@lang('No certificate templates found.')</p>
                                    <div class="card-tools">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.ai.certificates.create') }}" class="btn btn-success">
                                                <i class="fas fa-plus"></i> @lang('Create Template')
                                            </a>
                                            <a href="{{ url('admin/ai/certificates/certifier-import') }}" class="btn btn-info">
                                                <i class="fas fa-cloud-download-alt"></i> Import from Certifier
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->

<!-- Generate Certificate Modal -->
<div class="modal fade" id="generateCertificateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Generate Certificate')</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="generateCertificateForm">
                    <div class="form-group">
                        <label for="recipient_name">@lang('Recipient Name')</label>
                        <input type="text" class="form-control" id="recipient_name" name="recipient_name" required>
                    </div>
                    <div class="form-group">
                        <label for="recipient_email">@lang('Recipient Email')</label>
                        <input type="email" class="form-control" id="recipient_email" name="recipient_email">
                    </div>
                    <div class="form-group">
                        <label for="course_name">@lang('Course Name')</label>
                        <input type="text" class="form-control" id="course_name" name="course_name" required>
                    </div>
                    <div class="form-group">
                        <label for="completion_date">@lang('Completion Date')</label>
                        <input type="date" class="form-control" id="completion_date" name="completion_date">
                    </div>
                    <div class="form-group">
                        <label for="instructor_name">@lang('Instructor Name')</label>
                        <input type="text" class="form-control" id="instructor_name" name="instructor_name">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Cancel')</button>
                <button type="button" class="btn btn-primary" id="generateBtn">@lang('Generate Certificate')</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
<script>
$(document).ready(function() {
    let currentTemplateId = null;

    // Generate certificate button click
    $('.generate-certificate').click(function() {
        currentTemplateId = $(this).data('id');
        $('#generateCertificateModal').modal('show');
    });

    // Generate certificate form submission
    $('#generateBtn').click(function() {
        const formData = new FormData($('#generateCertificateForm')[0]);
        
        // Debug form data
        console.log('Form data being sent:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        
        $.ajax({
            url: `/admin/ai/certificates/${currentTemplateId}/generate-single`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('Certificate generation response:', response);
                if (response.success) {
                    $('#generateCertificateModal').modal('hide');
                    
                    // Show success message first
                    Swal.fire({
                        type: 'success',
                        title: 'Success!',
                        text: 'Certificate generated successfully! Download will start automatically.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Trigger automatic download after a short delay
                    setTimeout(() => {
                        const link = document.createElement('a');
                        link.href = response.download_url;
                        link.download = `certificate-${response.certificate_id}.pdf`;
                        link.target = '_blank';
                        link.rel = 'noopener noreferrer';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        
                        // Also provide a manual download button if auto-download fails
                        Swal.fire({
                            type: 'success',
                            title: 'Certificate Ready!',
                            html: `Certificate generated successfully!<br><br>
                                   <a href="${response.download_url}" target="_blank" class="btn btn-primary" download="certificate-${response.certificate_id}.pdf">
                                       <i class="fas fa-download"></i> Download Certificate
                                   </a>`,
                            showConfirmButton: true,
                            confirmButtonText: 'Close'
                        });
                    }, 1000);
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to generate certificate.'
                    });
                }
            },
            error: function(xhr) {
                console.error('Certificate generation error:', xhr);
                console.error('Response status:', xhr.status);
                console.error('Response text:', xhr.responseText);
                console.error('Response JSON:', xhr.responseJSON);
                
                let errorMessage = 'Failed to generate certificate. Please try again.';
                let detailedErrors = '';
                
                if (xhr.responseJSON) {
                    console.error('Validation errors:', xhr.responseJSON.errors);
                    errorMessage = xhr.responseJSON.message || errorMessage;
                    
                    if (xhr.responseJSON.errors) {
                        detailedErrors = Object.entries(xhr.responseJSON.errors)
                            .map(([field, messages]) => `${field}: ${messages.join(', ')}`)
                            .join('\n');
                        errorMessage += '\n\nValidation errors:\n' + detailedErrors;
                    }
                } else if (xhr.responseText) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        console.error('Parsed response:', response);
                        errorMessage = response.message || errorMessage;
                        
                        if (response.errors) {
                            detailedErrors = Object.entries(response.errors)
                                .map(([field, messages]) => `${field}: ${messages.join(', ')}`)
                                .join('\n');
                            errorMessage += '\n\nValidation errors:\n' + detailedErrors;
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        errorMessage = 'Server error: ' + xhr.status;
                    }
                }
                
                Swal.fire({
                    type: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            }
        });
    });

    // Delete template
    $('.delete-template').click(function() {
        const templateId = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: `/admin/ai/certificates/${templateId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    }
                });
            }
        });

        // Debug logging for Certifier import button
        $('#certifierImportBtn').on('click', function(e) {
            console.log('Certifier Import button clicked');
            console.log('Target URL:', $(this).attr('href'));
            console.log('Current URL:', window.location.href);
            
            // Send AJAX request to log the click
            $.post('/admin/ai/certificates/log-certifier-click', {
                _token: '{{ csrf_token() }}',
                target_url: $(this).attr('href'),
                current_url: window.location.href,
                timestamp: new Date().toISOString()
            }).done(function(response) {
                console.log('Click logged successfully');
            }).fail(function(xhr) {
                console.log('Failed to log click:', xhr.responseText);
            });
        });
    });
});
</script>
@endpush
