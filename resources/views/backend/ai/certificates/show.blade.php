@extends('backend.layouts.app')

@section('title', __('Certificate Template'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    {{ $certificate->name }}
                    <small class="text-muted">{{ __('Template Details') }}</small>
                </h4>
            </div><!--col-->

            <div class="col-sm-7">
                <div class="btn-toolbar float-right" role="toolbar">
                    <a href="{{ route('admin.ai.certificates.index') }}" class="btn btn-secondary ml-1">
                        <i class="fas fa-arrow-left"></i> @lang('Back to List')
                    </a>
                    <a href="{{ route('admin.ai.certificates.edit', $certificate) }}" class="btn btn-primary ml-1">
                        <i class="fas fa-edit"></i> @lang('Edit')
                    </a>
                    <button class="btn btn-success ml-1" id="generateCertificate">
                        <i class="fas fa-certificate"></i> @lang('Generate Certificate')
                    </button>
                </div><!--btn-toolbar-->
            </div><!--col-->
        </div><!--row-->

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">@lang('Template Information')</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">@lang('Name'):</dt>
                            <dd class="col-sm-8">{{ $certificate->name }}</dd>

                            <dt class="col-sm-4">@lang('Description'):</dt>
                            <dd class="col-sm-8">{{ $certificate->description ?: __('No description') }}</dd>

                            <dt class="col-sm-4">@lang('Status'):</dt>
                            <dd class="col-sm-8">
                                @if($certificate->is_active)
                                    <span class="badge badge-success">@lang('Active')</span>
                                @else
                                    <span class="badge badge-danger">@lang('Inactive')</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">@lang('Dimensions'):</dt>
                            <dd class="col-sm-8">{{ $certificate->width }} x {{ $certificate->height }}px</dd>

                            <dt class="col-sm-4">@lang('Orientation'):</dt>
                            <dd class="col-sm-8">{{ ucfirst($certificate->orientation) }}</dd>

                            <dt class="col-sm-4">@lang('Created'):</dt>
                            <dd class="col-sm-8">{{ $certificate->created_at->format('M d, Y H:i') }}</dd>

                            <dt class="col-sm-4">@lang('Updated'):</dt>
                            <dd class="col-sm-8">{{ $certificate->updated_at->format('M d, Y H:i') }}</dd>
                        </dl>

                        @if($certificate->template_variables)
                            <div class="mt-3">
                                <h6>@lang('Template Variables')</h6>
                                <div class="d-flex flex-wrap">
                                    @foreach($certificate->template_variables as $variable)
                                        <span class="badge badge-info mr-1 mb-1">{{ $variable }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if($certificate->generatedCertificates->count() > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">@lang('Generated Certificates') ({{ $certificate->generatedCertificates->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($certificate->generatedCertificates->take(5) as $generated)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $generated->recipient_name }}</strong><br>
                                        <small class="text-muted">{{ $generated->course_name }}</small>
                                    </div>
                                    @if($generated->file_url)
                                        <a href="{{ $generated->file_url }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if($certificate->generatedCertificates->count() > 5)
                            <div class="text-center mt-2">
                                <small class="text-muted">@lang('And :count more...', ['count' => $certificate->generatedCertificates->count() - 5])</small>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">@lang('Certificate Preview')</h5>
                    </div>
                    <div class="card-body">
                        <div id="certificate-preview" style="width: 100%; height: 600px; border: 1px solid #ddd; background: #f8f9fa; overflow: auto;">
                            <canvas id="preview-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->

<!-- Generate Certificate Modal -->
<div class="modal fade" id="generateCertificateModal" tabindex="-1" role="dialog" aria-labelledby="generateCertificateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateCertificateModalLabel">@lang('Generate Certificate')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="generateCertificateForm">
                @csrf
                <div class="modal-body">
                    <div id="formErrors" class="alert alert-danger" style="display: none;"></div>
                    
                    <div class="form-group">
                        <label for="recipient_name">@lang('Recipient Name') <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="recipient_name" name="recipient_name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="recipient_email">@lang('Recipient Email')</label>
                        <input type="email" class="form-control" id="recipient_email" name="recipient_email">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="course_name">@lang('Course Name') <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="course_name" name="course_name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="completion_date">@lang('Completion Date')</label>
                        <input type="date" class="form-control" id="completion_date" name="completion_date" value="{{ date('Y-m-d') }}">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label for="instructor_name">@lang('Instructor Name')</label>
                        <input type="text" class="form-control" id="instructor_name" name="instructor_name">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn-primary" id="generateBtn">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                        @lang('Generate Certificate')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('after-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('after-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Fabric.js canvas for preview
    function initializePreviewCanvas() {
        const container = $('#certificate-preview');
        const canvasElement = $('#preview-canvas');
        
        if (container.length && canvasElement.length) {
            // Set canvas dimensions
            canvasElement.attr({
                width: {{ $certificate->width }},
                height: {{ $certificate->height }}
            });
            
            // Initialize Fabric.js canvas
            const canvas = new fabric.Canvas('preview-canvas', {
                width: {{ $certificate->width }},
                height: {{ $certificate->height }},
                backgroundColor: '#ffffff',
                selection: false
            });
            
            // Load the template data
            const templateData = @json($certificate->template_data ?? null);
            
            if (templateData && typeof templateData === 'object') {
                try {
                    canvas.loadFromJSON(templateData, function() {
                        canvas.renderAll();
                        // Disable interactions for all loaded objects
                        canvas.forEachObject(function(obj) {
                            obj.selectable = false;
                            obj.evented = false;
                        });
                    });
                } catch (error) {
                    console.error('Error loading template data:', error);
                    showPreviewError(canvas, 'Preview not available - Invalid template data');
                }
            } else {
                showPreviewError(canvas, 'No template data available');
            }
            
            // Center the canvas in the container
            container.scrollLeft((canvas.width - container.width()) / 2);
            container.scrollTop((canvas.height - container.height()) / 2);
        }
    }
    
    function showPreviewError(canvas, message) {
        const errorText = new fabric.Text(message, {
            left: canvas.width / 2,
            top: canvas.height / 2,
            fill: '#666666',
            fontFamily: 'Arial',
            fontSize: 16,
            textAlign: 'center',
            originX: 'center',
            originY: 'center',
            selectable: false,
            evented: false
        });
        canvas.add(errorText);
        canvas.renderAll();
    }

    // Initialize preview
    initializePreviewCanvas();

    // Generate certificate button
    $('#generateCertificate').click(function() {
        $('#generateCertificateModal').modal('show');
    });

    // Generate certificate form submission
    $('#generateCertificateForm').on('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        $('#generateBtn').prop('disabled', true);
        $('#generateBtn .spinner-border').show();
        $('#generateBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...');
        
        // Clear previous errors
        $('.invalid-feedback').html('').hide();
        $('.form-control').removeClass('is-invalid');
        $('#formErrors').hide();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: "{{ route('admin.ai.certificates.generate-single', $certificate) }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('Certificate generation response:', response);
                
                if (response.success) {
                    $('#generateCertificateModal').modal('hide');
                    
                    // Immediate download attempt
                    if (response.download_url) {
                        // Create a temporary download link
                        const downloadLink = document.createElement('a');
                        downloadLink.href = response.download_url;
                        downloadLink.download = `certificate-${response.certificate_id || 'generated'}.pdf`;
                        downloadLink.target = '_blank';
                        downloadLink.rel = 'noopener noreferrer';
                        
                        // Add to DOM, click, and remove
                        document.body.appendChild(downloadLink);
                        downloadLink.click();
                        document.body.removeChild(downloadLink);
                        
                        console.log('Download initiated for:', response.download_url);
                    }
                    
                    // Show success message with manual download option
                    Swal.fire({
                        icon: 'success',
                        title: 'Certificate Generated!',
                        html: `
                            <p>Your certificate has been generated successfully!</p>
                            ${response.download_url ? 
                                `<p><a href="${response.download_url}" target="_blank" class="btn btn-primary" download="certificate-${response.certificate_id || 'generated'}.pdf">
                                    <i class="fas fa-download"></i> Download Certificate
                                </a></p>` : 
                                '<p>Download link not available.</p>'
                            }
                        `,
                        showConfirmButton: true,
                        confirmButtonText: 'Close'
                    });
                    
                    // Refresh page after a delay to show new certificate in the list
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to generate certificate.'
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Failed to generate certificate. Please try again.';
                
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        const input = $(`#${field}`);
                        const feedback = input.next('.invalid-feedback');
                        
                        input.addClass('is-invalid');
                        feedback.html(messages.join('<br>')).show();
                    });
                    
                    errorMessage = 'Please correct the errors in the form.';
                    $('#formErrors').html(errorMessage).show();
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                    $('#formErrors').html(errorMessage).show();
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            },
            complete: function() {
                // Reset button state
                $('#generateBtn').prop('disabled', false);
                $('#generateBtn .spinner-border').hide();
                $('#generateBtn').html('Generate Certificate');
            }
        });
    });

    // Close modal handler
    $('#generateCertificateModal').on('hidden.bs.modal', function() {
        // Reset form
        $('#generateCertificateForm')[0].reset();
        $('.invalid-feedback').html('').hide();
        $('.form-control').removeClass('is-invalid');
        $('#formErrors').hide();
    });
});
</script>
@endpush