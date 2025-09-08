@extends('backend.layouts.app')

@section('title', __('Import Certifier Template'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-cloud-download-alt"></i>
                        Import Template from Certifier
                    </h4>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-primary" id="testConnection">
                            <i class="fas fa-plug"></i> Test Connection
                        </button>
                        <a href="{{ route('admin.ai.certificates.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Templates
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Connection Status -->
                    <div id="connectionStatus" class="alert" style="display: none;"></div>

                    <!-- Search and Filters -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="searchDesigns">Search Designs</label>
                                <input type="text" class="form-control" id="searchDesigns" placeholder="Search by name or description...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="limitDesigns">Results per page</label>
                                <select class="form-control" id="limitDesigns">
                                    <option value="10">10</option>
                                    <option value="25" selected>25</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-primary btn-block" id="loadDesigns">
                                    <i class="fas fa-search"></i> Load Designs
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="loadingIndicator" class="text-center" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2">Loading designs from Certifier...</p>
                    </div>

                    <!-- Designs Grid -->
                    <div id="designsContainer" class="row">
                        <!-- Designs will be loaded here -->
                    </div>

                    <!-- Pagination -->
                    <div id="paginationContainer" class="d-flex justify-content-center mt-4">
                        <!-- Pagination will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Design</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="importForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="templateName">Template Name</label>
                        <input type="text" class="form-control" id="templateName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="templateDescription">Description</label>
                        <textarea class="form-control" id="templateDescription" name="description" rows="3"></textarea>
                    </div>
                    <div id="designPreview" class="text-center mb-3">
                        <!-- Design preview will be shown here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="importBtn">
                        <i class="fas fa-download"></i> Import Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('after-scripts')
<script>
$(document).ready(function() {
    let currentOffset = 0;
    let currentLimit = 25;
    let currentSearch = '';
    let selectedDesign = null;

    // Test connection
    $('#testConnection').click(function() {
        const btn = $(this);
        const originalText = btn.html();
        
        btn.html('<i class="fas fa-spinner fa-spin"></i> Testing...').prop('disabled', true);
        
        $.get('{{ route("admin.ai.certificates.certifier.test-connection") }}')
            .done(function(response) {
                if (response.success) {
                    showAlert('success', 'Connection successful! You can now import designs from Certifier.');
                } else {
                    showAlert('danger', 'Connection failed: ' + response.message);
                }
            })
            .fail(function(xhr) {
                const error = xhr.responseJSON?.message || 'Connection test failed';
                showAlert('danger', 'Connection failed: ' + error);
            })
            .always(function() {
                btn.html(originalText).prop('disabled', false);
            });
    });

    // Load designs
    $('#loadDesigns').click(function() {
        currentOffset = 0;
        currentLimit = parseInt($('#limitDesigns').val());
        currentSearch = $('#searchDesigns').val();
        loadDesigns();
    });

    // Search on enter
    $('#searchDesigns').keypress(function(e) {
        if (e.which === 13) {
            $('#loadDesigns').click();
        }
    });

    // Import form submission
    $('#importForm').submit(function(e) {
        e.preventDefault();
        
        if (!selectedDesign) {
            showAlert('danger', 'No design selected');
            return;
        }

        const btn = $('#importBtn');
        const originalText = btn.html();
        
        btn.html('<i class="fas fa-spinner fa-spin"></i> Importing...').prop('disabled', true);
        
        const formData = {
            name: $('#templateName').val(),
            description: $('#templateDescription').val()
        };

        $.post(`{{ route("admin.ai.certificates.certifier.import-design", ":designId") }}`.replace(':designId', selectedDesign.id), formData)
            .done(function(response) {
                if (response.success) {
                    showAlert('success', 'Template imported successfully!');
                    $('#importModal').modal('hide');
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.ai.certificates.index") }}';
                    }, 1500);
                } else {
                    showAlert('danger', 'Import failed: ' + response.message);
                }
            })
            .fail(function(xhr) {
                const error = xhr.responseJSON?.message || 'Import failed';
                showAlert('danger', 'Import failed: ' + error);
            })
            .always(function() {
                btn.html(originalText).prop('disabled', false);
            });
    });

    function loadDesigns() {
        $('#loadingIndicator').show();
        $('#designsContainer').empty();
        $('#paginationContainer').empty();

        const params = {
            limit: currentLimit,
            offset: currentOffset
        };

        if (currentSearch) {
            params.search = currentSearch;
        }

        $.get('{{ route("admin.ai.certificates.certifier.designs") }}', params)
            .done(function(response) {
                if (response.success && response.designs) {
                    renderDesigns(response.designs);
                } else {
                    showAlert('danger', 'Failed to load designs: ' + (response.message || 'Unknown error'));
                }
            })
            .fail(function(xhr) {
                const error = xhr.responseJSON?.message || 'Failed to load designs';
                showAlert('danger', error);
            })
            .always(function() {
                $('#loadingIndicator').hide();
            });
    }

    function renderDesigns(data) {
        const container = $('#designsContainer');
        
        if (!data.data || data.data.length === 0) {
            container.html(`
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i>
                        No designs found. Try adjusting your search criteria.
                    </div>
                </div>
            `);
            return;
        }

        data.data.forEach(design => {
            const designCard = `
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card design-card" data-design-id="${design.id}">
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            ${design.preview_url ? 
                                `<img src="${design.preview_url}" alt="${design.name}" class="img-fluid" style="max-height: 180px;">` :
                                `<i class="fas fa-certificate fa-4x text-muted"></i>`
                            }
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">${design.name}</h6>
                            <p class="card-text small text-muted">${design.description || 'No description available'}</p>
                            <button type="button" class="btn btn-primary btn-sm btn-block import-design-btn">
                                <i class="fas fa-download"></i> Import
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.append(designCard);
        });

        // Add click handlers for import buttons
        $('.import-design-btn').click(function() {
            const card = $(this).closest('.design-card');
            const designId = card.data('design-id');
            const design = data.data.find(d => d.id === designId);
            
            if (design) {
                selectedDesign = design;
                $('#templateName').val('Imported Template - ' + design.name);
                $('#templateDescription').val('Imported from Certifier: ' + (design.description || design.name));
                
                // Show design preview if available
                const previewHtml = design.preview_url ? 
                    `<img src="${design.preview_url}" alt="${design.name}" class="img-fluid" style="max-height: 200px;">` :
                    `<div class="bg-light p-4 rounded"><i class="fas fa-certificate fa-3x text-muted"></i><br><small class="text-muted">No preview available</small></div>`;
                
                $('#designPreview').html(previewHtml);
                $('#importModal').modal('show');
            }
        });

        // Render pagination
        renderPagination(data);
    }

    function renderPagination(data) {
        const container = $('#paginationContainer');
        
        if (!data.has_more && currentOffset === 0) {
            return; // No pagination needed
        }

        let paginationHtml = '<nav><ul class="pagination">';
        
        // Previous button
        if (currentOffset > 0) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-offset="${currentOffset - currentLimit}">Previous</a></li>`;
        } else {
            paginationHtml += `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;
        }
        
        // Current page info
        const currentPage = Math.floor(currentOffset / currentLimit) + 1;
        paginationHtml += `<li class="page-item active"><span class="page-link">Page ${currentPage}</span></li>`;
        
        // Next button
        if (data.has_more) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-offset="${currentOffset + currentLimit}">Next</a></li>`;
        } else {
            paginationHtml += `<li class="page-item disabled"><span class="page-link">Next</span></li>`;
        }
        
        paginationHtml += '</ul></nav>';
        container.html(paginationHtml);
        
        // Add click handlers for pagination
        $('.page-link[data-offset]').click(function(e) {
            e.preventDefault();
            currentOffset = parseInt($(this).data('offset'));
            loadDesigns();
        });
    }

    function showAlert(type, message) {
        const alert = $('#connectionStatus');
        alert.removeClass('alert-success alert-danger alert-info alert-warning')
             .addClass(`alert-${type}`)
             .html(`<i class="fas fa-${type === 'success' ? 'check' : type === 'danger' ? 'exclamation-triangle' : 'info'}-circle"></i> ${message}`)
             .show();
        
        if (type === 'success') {
            setTimeout(() => alert.fadeOut(), 5000);
        }
    }
});
</script>

<style>
.design-card {
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}

.design-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.design-card .card-img-top {
    border-bottom: 1px solid #dee2e6;
}
</style>
@endpush
