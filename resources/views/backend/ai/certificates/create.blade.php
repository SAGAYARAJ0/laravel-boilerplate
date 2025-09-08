@extends('backend.layouts.app')

@section('title', __('Create Certificate Template'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    {{ __('Create Certificate Template') }}
                </h4>
            </div><!--col-->

            <div class="col-sm-7">
                <div class="btn-toolbar float-right" role="toolbar">
                    <a href="{{ route('admin.ai.certificates.index') }}" class="btn btn-secondary ml-1">
                        <i class="fas fa-arrow-left"></i> @lang('Back to List')
                    </a>
                </div><!--btn-toolbar-->
            </div><!--col-->
        </div><!--row-->

        <div class="row mt-4">
            <div class="col">
                <form id="certificateForm" method="POST" action="{{ route('admin.ai.certificates.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">@lang('Template Name') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="form-group">
                                <label for="description">@lang('Description')</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>

                            <!-- Template Selection -->
                            <div class="form-group">
                                <label for="template-type">@lang('Choose Template Design')</label>
                                <div class="template-selector">
                                    <div class="template-option active" data-template="achievement" data-description="🏆 Classic Achievement Design">
                                        <div class="template-preview" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 10px; border: 2px solid #4e73df; border-radius: 5px;">
                                            <div style="text-align: center; font-family: 'Times New Roman', serif;">
                                                <div style="font-size: 16px; font-weight: bold; color: #2e59d9;">CERTIFICATE OF ACHIEVEMENT</div>
                                                <div style="font-size: 10px; margin-top: 5px;">Classic achievement design</div>
                                            </div>
                                        </div>
                                        <small>Achievement</small>
                                    </div>
                                    <div class="template-option" data-template="participation" data-description="🎯 Elegant Participation Design">
                                        <div class="template-preview" style="background: linear-gradient(135deg, #fdfcfb 0%, #e2d1c3 100%); padding: 10px; border: 2px solid #e9b949; border-radius: 5px;">
                                            <div style="text-align: center; font-family: 'Georgia', serif;">
                                                <div style="font-size: 14px; font-weight: bold; color: #856404;">CERTIFICATE OF PARTICIPATION</div>
                                                <div style="font-size: 9px; margin-top: 5px;">Elegant participation design</div>
                                            </div>
                                        </div>
                                        <small>Participation</small>
                                    </div>
                                    <div class="template-option" data-template="completion" data-description="✅ Modern Completion Design">
                                        <div class="template-preview" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); padding: 10px; border: 2px solid #2196f3; border-radius: 5px;">
                                            <div style="text-align: center; font-family: 'Arial', sans-serif;">
                                                <div style="font-size: 15px; font-weight: bold; color: #0d47a1;">CERTIFICATE OF COMPLETION</div>
                                                <div style="font-size: 9px; margin-top: 5px;">Modern completion design</div>
                                            </div>
                                        </div>
                                        <small>Completion</small>
                                    </div>
                                    <div class="template-option" data-template="excellence" data-description="⭐ Premium Excellence Design">
                                        <div class="template-preview" style="background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%); padding: 10px; border: 2px solid #e53e3e; border-radius: 5px;">
                                            <div style="text-align: center; font-family: 'Georgia', serif;">
                                                <div style="font-size: 14px; font-weight: bold; color: #c53030;">CERTIFICATE OF EXCELLENCE</div>
                                                <div style="font-size: 9px; margin-top: 5px;">Premium excellence design</div>
                                            </div>
                                        </div>
                                        <small>Excellence</small>
                                    </div>
                                    <div class="template-option" data-template="training" data-description="📚 Professional Training Design">
                                        <div class="template-preview" style="background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%); padding: 10px; border: 2px solid #38a169; border-radius: 5px;">
                                            <div style="text-align: center; font-family: 'Arial', sans-serif;">
                                                <div style="font-size: 14px; font-weight: bold; color: #2f855a;">TRAINING CERTIFICATE</div>
                                                <div style="font-size: 9px; margin-top: 5px;">Professional training design</div>
                                            </div>
                                        </div>
                                        <small>Training</small>
                                    </div>
                                    <div class="template-option" data-template="appreciation" data-description="💝 Warm Appreciation Design">
                                        <div class="template-preview" style="background: linear-gradient(135deg, #fffaf0 0%, #fbd38d 100%); padding: 10px; border: 2px solid #ed8936; border-radius: 5px;">
                                            <div style="text-align: center; font-family: 'Times New Roman', serif;">
                                                <div style="font-size: 13px; font-weight: bold; color: #c05621;">CERTIFICATE OF APPRECIATION</div>
                                                <div style="font-size: 9px; margin-top: 5px;">Warm appreciation design</div>
                                            </div>
                                        </div>
                                        <small>Appreciation</small>
                                    </div>
                                    <div class="template-option" data-template="recognition" data-description="🏅 Corporate Recognition Design">
                                        <div class="template-preview" style="background: linear-gradient(135deg, #f7fafc 0%, #e2e8f0 100%); padding: 10px; border: 2px solid #4a5568; border-radius: 5px;">
                                            <div style="text-align: center; font-family: 'Arial', sans-serif;">
                                                <div style="font-size: 14px; font-weight: bold; color: #2d3748;">CERTIFICATE OF RECOGNITION</div>
                                                <div style="font-size: 9px; margin-top: 5px;">Corporate recognition design</div>
                                            </div>
                                        </div>
                                        <small>Recognition</small>
                                    </div>
                                    <div class="template-option" data-template="honor" data-description="👑 Distinguished Honor Design">
                                        <div class="template-preview" style="background: linear-gradient(135deg, #faf5ff 0%, #e9d8fd 100%); padding: 10px; border: 2px solid #805ad5; border-radius: 5px;">
                                            <div style="text-align: center; font-family: 'Georgia', serif;">
                                                <div style="font-size: 14px; font-weight: bold; color: #553c9a;">CERTIFICATE OF HONOR</div>
                                                <div style="font-size: 9px; margin-top: 5px;">Distinguished honor design</div>
                                            </div>
                                        </div>
                                        <small>Honor</small>
                                    </div>
                                    <div class="template-option" data-template="graduation" data-description="🎓 Academic Graduation Design">
                                        <div class="template-preview" style="background: linear-gradient(135deg, #f0f9ff 0%, #bfdbfe 100%); padding: 10px; border: 2px solid #3b82f6; border-radius: 5px;">
                                            <div style="text-align: center; font-family: 'Times New Roman', serif;">
                                                <div style="font-size: 14px; font-weight: bold; color: #1d4ed8;">GRADUATION CERTIFICATE</div>
                                                <div style="font-size: 9px; margin-top: 5px;">Academic graduation design</div>
                                            </div>
                                        </div>
                                        <small>Graduation</small>
                                    </div>
                                    <div class="template-option" data-template="empty" data-description="🎨 Start from Scratch">
                                        <div class="template-preview" style="background: #ffffff; padding: 10px; border: 2px dashed #cccccc; border-radius: 5px;">
                                            <div style="text-align: center; font-family: 'Arial', sans-serif;">
                                                <div style="font-size: 14px; font-weight: bold; color: #666666;">BLANK TEMPLATE</div>
                                                <div style="font-size: 9px; margin-top: 5px;">Start from scratch</div>
                                            </div>
                                        </div>
                                        <small>Empty</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="background_image">@lang('Background Image')</label>
                                <input type="file" class="form-control-file" id="background_image" name="background_image" accept="image/*">
                                <small class="form-text text-muted">@lang('Optional background image for the certificate')</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="width">@lang('Width (px)')</label>
                                        <input type="number" class="form-control" id="width" name="width" value="1200" min="100" max="5000">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="height">@lang('Height (px)')</label>
                                        <input type="number" class="form-control" id="height" name="height" value="800" min="100" max="5000">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="orientation">@lang('Orientation')</label>
                                <select class="form-control" id="orientation" name="orientation">
                                    <option value="landscape">@lang('Landscape')</option>
                                    <option value="portrait">@lang('Portrait')</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                                    <label class="custom-control-label" for="is_active">@lang('Active')</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label>@lang('Certificate Design')</label>
                                <div class="toolbar" style="background: #2c3e50; padding: 8px; border-radius: 6px; margin-bottom: 15px; box-shadow: 0 1px 5px rgba(0,0,0,0.1);">
                                    <div class="toolbar-section" style="margin-bottom: 6px;">
                                        <label class="toolbar-label" style="font-size: 11px; margin-bottom: 4px;">ELEMENTS</label>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-info btn-xs" id="add-text" title="Add Text" style="padding: 4px 8px; font-size: 11px;"><i class="fas fa-font"></i></button>
                                            <button type="button" class="btn btn-outline-info btn-xs" id="add-rectangle" title="Add Rectangle" style="padding: 4px 8px; font-size: 11px;"><i class="fas fa-square"></i></button>
                                            <button type="button" class="btn btn-outline-info btn-xs" id="add-circle" title="Add Circle" style="padding: 4px 8px; font-size: 11px;"><i class="fas fa-circle"></i></button>
                                            <button type="button" class="btn btn-outline-info btn-xs" id="add-triangle" title="Add Triangle" style="padding: 4px 8px; font-size: 11px;"><i class="fas fa-play"></i></button>
                                            <button type="button" class="btn btn-outline-warning btn-xs" id="add-line" title="Add Line" style="padding: 4px 8px; font-size: 11px;"><i class="fas fa-minus"></i></button>
                                            <button type="button" class="btn btn-outline-success btn-xs" id="add-image" title="Add Image" style="padding: 4px 8px; font-size: 11px;"><i class="fas fa-image"></i></button>
                                        </div>
                                    </div>
                                    
                                    <div class="toolbar-section" style="margin-bottom: 6px;">
                                        <label class="toolbar-label" style="font-size: 11px; margin-bottom: 4px;">DESIGN</label>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-secondary btn-xs" id="background-color" title="Background Color" style="padding: 4px 8px; font-size: 11px;"><i class="fas fa-palette"></i></button>
                                            <button type="button" class="btn btn-outline-secondary btn-xs" id="grid-toggle" title="Toggle Grid" style="padding: 4px 8px; font-size: 11px;"><i class="fas fa-th"></i></button>
                                            <button type="button" class="btn btn-outline-info btn-xs" id="add-frame" title="Add Frame" style="padding: 4px 8px; font-size: 11px;"><i class="fas fa-border-style"></i></button>
                                        </div>
                                    </div>
                                    
                                    <div class="toolbar-section" style="margin-bottom: 6px;">
                                        <label class="toolbar-label" style="font-size: 11px; margin-bottom: 4px;">PROPERTIES</label>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <input type="color" class="form-control form-control-sm color-picker" id="element-color" title="Element Color" style="width: 28px; height: 24px; padding: 1px; border-radius: 3px;">
                                            <select class="form-control form-control-sm" id="font-family" style="width: 80px; height: 24px; font-size: 11px; padding: 2px;">
                                                <option value="Arial">Arial</option>
                                                <option value="Times New Roman">Times</option>
                                                <option value="Helvetica">Helvetica</option>
                                                <option value="Georgia">Georgia</option>
                                                <option value="Verdana">Verdana</option>
                                            </select>
                                            <input type="number" class="form-control form-control-sm" id="font-size" placeholder="Size" min="8" max="200" value="16" style="width: 45px; height: 24px; font-size: 11px; padding: 2px;">
                                        </div>
                                    </div>
                                    
                                    <div class="toolbar-section">
                                        <label class="toolbar-label" style="font-size: 11px; margin-bottom: 4px;">ACTIONS</label>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-success btn-xs" id="duplicate-selected" title="Duplicate Selected (Ctrl+D)" disabled style="padding: 4px 8px; font-size: 11px;"><i class="fas fa-copy"></i></button>
                                            <button type="button" class="btn btn-outline-danger btn-xs" id="delete-selected" title="Delete Selected" disabled style="padding: 4px 8px; font-size: 11px;"><i class="fas fa-trash"></i></button>
                                            <button type="button" class="btn btn-outline-danger btn-xs" id="clear-all" title="Clear All" style="padding: 4px 8px; font-size: 11px;"><i class="fas fa-broom"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div id="canvas-container" style="width: 100%; height: 600px; overflow: auto; border: 2px solid #ddd; background: #f8f9fa;">
                                    <canvas id="certificate-canvas"></canvas>
                                </div>
                                <input type="hidden" name="template_data" id="template-data">
                                <input type="hidden" name="template_type" id="template-type" value="achievement">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-0 clearfix">
                                <div class="float-right">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> @lang('Save Template')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->
@endsection

@push('after-styles')
<style>
    .template-selector {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 20px;
        max-height: 500px;
        overflow-y: auto;
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }
    .template-option {
        cursor: pointer;
        text-align: center;
        padding: 0;
        border: 3px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        position: relative;
    }
    .template-option::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(78, 115, 223, 0.05) 0%, rgba(78, 115, 223, 0.1) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1;
    }
    .template-option::after {
        content: attr(data-description);
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(78, 115, 223, 0.95);
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 3;
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    .template-option:hover {
        border-color: #4e73df;
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(78, 115, 223, 0.2);
    }
    .template-option:hover::before {
        opacity: 1;
    }
    .template-option:hover::after {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1.05);
    }
    .template-option.active {
        border-color: #4e73df;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fe 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(78, 115, 223, 0.25);
    }
    .template-option.active::before {
        opacity: 0.7;
    }
    .template-preview {
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        padding: 15px;
        position: relative;
        z-index: 2;
    }
    .template-option small {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        padding: 12px 15px;
        background: rgba(248, 249, 250, 0.8);
        border-top: 1px solid #e9ecef;
        display: block;
        position: relative;
        z-index: 2;
    }
    .template-option:hover small {
        color: #4e73df;
        background: rgba(78, 115, 223, 0.1);
    }
    .template-option.active small {
        color: #4e73df;
        background: rgba(78, 115, 223, 0.15);
        font-weight: 700;
    }
    #canvas-container {
        position: relative;
        margin: 20px 0;
    }
    .toolbar {
        margin-bottom: 15px;
    }
    .toolbar button {
        margin-right: 5px;
        margin-bottom: 5px;
    }
    .toolbar-container {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        border: 1px solid #34495e;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        margin-bottom: 20px;
        color: white;
    }
    
    .toolbar-section {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #4a5568;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .toolbar-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .toolbar-label {
        font-weight: 700;
        color: #e2e8f0;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        display: block;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }

    .btn-group .btn {
        margin: 0 2px;
        border-radius: 6px !important;
        font-weight: 500;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-group .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-outline-primary {
        border-color: #3498db;
        color: #3498db;
        background: rgba(52, 152, 219, 0.1);
    }

    .btn-outline-primary:hover {
        background: #3498db;
        border-color: #3498db;
        color: white;
    }

    .btn-outline-info {
        border-color: #17a2b8;
        color: #17a2b8;
        background: rgba(23, 162, 184, 0.1);
    }

    .btn-outline-info:hover {
        background: #17a2b8;
        border-color: #17a2b8;
        color: white;
    }

    .btn-outline-warning {
        border-color: #f39c12;
        color: #f39c12;
        background: rgba(243, 156, 18, 0.1);
    }

    .btn-outline-warning:hover {
        background: #f39c12;
        border-color: #f39c12;
        color: white;
    }

    .btn-outline-success {
        border-color: #27ae60;
        color: #27ae60;
        background: rgba(39, 174, 96, 0.1);
    }

    .btn-outline-success:hover {
        background: #27ae60;
        border-color: #27ae60;
        color: white;
    }

    .btn-outline-secondary {
        border-color: #95a5a6;
        color: #95a5a6;
        background: rgba(149, 165, 166, 0.1);
    }

    .btn-outline-secondary:hover {
        background: #95a5a6;
        border-color: #95a5a6;
        color: white;
    }

    .btn-outline-dark {
        border-color: #7f8c8d;
        color: #7f8c8d;
        background: rgba(127, 140, 141, 0.1);
    }

    .btn-outline-dark:hover,
    .btn-outline-dark.active {
        background: #7f8c8d;
        border-color: #7f8c8d;
        color: white;
    }

    .color-picker {
        border: 2px solid #4a5568;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .color-picker:hover {
        border-color: #3498db;
        transform: scale(1.05);
    }

    .form-control-sm {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid #4a5568;
        color: white;
        border-radius: 6px;
    }

    .form-control-sm:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: #3498db;
        color: white;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }

    .form-control-sm option {
        background: #2c3e50;
        color: white;
    }

    #canvas-container {
        border: 3px solid #34495e;
        border-radius: 12px;
        background: linear-gradient(45deg, #ecf0f1 25%, transparent 25%), 
                    linear-gradient(-45deg, #ecf0f1 25%, transparent 25%), 
                    linear-gradient(45deg, transparent 75%, #ecf0f1 75%), 
                    linear-gradient(-45deg, transparent 75%, #ecf0f1 75%);
        background-size: 20px 20px;
        background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@push('after-scripts')
<!-- Load Fabric.js for canvas functionality -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let canvas;
    let currentTemplate = 'achievement';
    
    // Initialize Fabric.js canvas
    function initializeCanvas() {
        const width = parseInt(document.getElementById('width').value) || 1200;
        const height = parseInt(document.getElementById('height').value) || 800;
        
        canvas = new fabric.Canvas('certificate-canvas', {
            width: width,
            height: height,
            backgroundColor: '#ffffff'
        });
        
        // Set canvas size
        canvas.setWidth(width);
        canvas.setHeight(height);
        
        // Load default template
        loadTemplate(currentTemplate);
        
        // Setup toolbar handlers
        setupToolbarHandlers();
        
        // Setup form submission
        setupFormSubmission();
        
        // Setup template selection
        setupTemplateSelection();
        
        // Listen for dimension changes
        document.getElementById('width').addEventListener('change', updateCanvasSize);
        document.getElementById('height').addEventListener('change', updateCanvasSize);
        
        console.log('Canvas initialized successfully');
    }
    
    // Update canvas size when dimensions change
    function updateCanvasSize() {
        const width = parseInt(document.getElementById('width').value) || 1200;
        const height = parseInt(document.getElementById('height').value) || 800;
        
        canvas.setWidth(width);
        canvas.setHeight(height);
        canvas.setDimensions({width: width, height: height});
        canvas.renderAll();
    }
    
    // Setup template selection
    function setupTemplateSelection() {
        const templateOptions = document.querySelectorAll('.template-option');
        
        templateOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove active class from all options
                templateOptions.forEach(opt => opt.classList.remove('active'));
                
                // Add active class to clicked option
                this.classList.add('active');
                
                // Get template type
                const templateType = this.getAttribute('data-template');
                currentTemplate = templateType;
                document.getElementById('template-type').value = templateType;
                
                // Load the selected template
                loadTemplate(templateType);
            });
        });
    }
    
    // Load template based on type
    function loadTemplate(templateType) {
        canvas.clear();
        canvas.backgroundColor = '#ffffff';
        
        switch(templateType) {
            case 'achievement':
                loadAchievementTemplate();
                break;
            case 'participation':
                loadParticipationTemplate();
                break;
            case 'completion':
                loadCompletionTemplate();
                break;
            case 'excellence':
                loadExcellenceTemplate();
                break;
            case 'training':
                loadTrainingTemplate();
                break;
            case 'appreciation':
                loadAppreciationTemplate();
                break;
            case 'recognition':
                loadRecognitionTemplate();
                break;
            case 'honor':
                loadHonorTemplate();
                break;
            case 'graduation':
                loadGraduationTemplate();
                break;
            case 'empty':
                loadEmptyTemplate();
                break;
            default:
                loadAchievementTemplate();
        }
        
        canvas.renderAll();
    }
    
    // Achievement Certificate Template
    function loadAchievementTemplate() {
        try {
            // Add decorative border
            const border = new fabric.Rect({
                left: 40,
                top: 40,
                width: canvas.width - 80,
                height: canvas.height - 80,
                fill: 'transparent',
                stroke: '#DAA520',
                strokeWidth: 8,
                strokeDashArray: [5, 5],
                selectable: false,
                evented: false
            });
            canvas.add(border);
            
            // Add inner border
            const innerBorder = new fabric.Rect({
                left: 60,
                top: 60,
                width: canvas.width - 120,
                height: canvas.height - 120,
                fill: 'transparent',
                stroke: '#DAA520',
                strokeWidth: 2,
                selectable: false,
                evented: false
            });
            canvas.add(innerBorder);
            
            // Add title
            const title = new fabric.IText('CERTIFICATE OF ACHIEVEMENT', {
                left: canvas.width / 2,
                top: 100,
                fill: '#1a237e',
                fontFamily: 'Times New Roman',
                fontSize: 42,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                shadow: 'rgba(0,0,0,0.3) 2px 2px 4px',
                selectable: true,
                editable: true
            });
            canvas.add(title);
            
            // Add decorative element
            const decoration = new fabric.Triangle({
                left: canvas.width / 2,
                top: 160,
                width: 30,
                height: 30,
                fill: '#DAA520',
                angle: 180,
                originX: 'center',
                originY: 'center',
                selectable: false,
                evented: false
            });
            canvas.add(decoration);
            
            // Add "This is to certify that" text
            const certifyText = new fabric.IText('This is to certify that', {
                left: canvas.width / 2,
                top: 200,
                fill: '#2c3e50',
                fontFamily: 'Arial',
                fontSize: 22,
                fontStyle: 'italic',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(certifyText);
            
            // Add recipient name placeholder
            const recipientName = new fabric.IText('@{{recipient_name}}', {
                left: canvas.width / 2,
                top: 250,
                fill: '#000000',
                fontFamily: 'Georgia',
                fontSize: 32,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(recipientName);
            
            // Add completion text
            const completionText = new fabric.IText('has successfully completed the', {
                left: canvas.width / 2,
                top: 310,
                fill: '#2c3e50',
                fontFamily: 'Arial',
                fontSize: 20,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(completionText);
            
            // Add course name placeholder
            const courseName = new fabric.IText('@{{course_name}}', {
                left: canvas.width / 2,
                top: 350,
                fill: '#1a237e',
                fontFamily: 'Arial',
                fontSize: 28,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(courseName);
            
            // Add date text
            const dateText = new fabric.IText('on @{{completion_date}} with distinction.', {
                left: canvas.width / 2,
                top: 410,
                fill: '#2c3e50',
                fontFamily: 'Arial',
                fontSize: 18,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(dateText);
            
            // Add signature areas
            addSignatureAreas();
            
        } catch (error) {
            console.error('Error loading achievement template:', error);
        }
    }
    
    // Participation Certificate Template (based on the second image)
    function loadParticipationTemplate() {
        try {
            // Background with light gradient
            canvas.backgroundColor = '#fdfcfb';
            
            // Main border
            const mainBorder = new fabric.Rect({
                left: 30,
                top: 30,
                width: canvas.width - 60,
                height: canvas.height - 60,
                fill: 'transparent',
                stroke: '#e9b949',
                strokeWidth: 15,
                rx: 10,
                ry: 10,
                selectable: false,
                evented: false
            });
            canvas.add(mainBorder);
            
            // Inner border
            const innerBorder = new fabric.Rect({
                left: 50,
                top: 50,
                width: canvas.width - 100,
                height: canvas.height - 100,
                fill: 'transparent',
                stroke: '#d4af37',
                strokeWidth: 3,
                rx: 5,
                ry: 5,
                selectable: false,
                evented: false
            });
            canvas.add(innerBorder);
            
            // Title
            const title = new fabric.IText('CERTIFICATE OF PARTICIPATION', {
                left: canvas.width / 2,
                top: 100,
                fill: '#856404',
                fontFamily: 'Georgia',
                fontSize: 36,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                shadow: 'rgba(0,0,0,0.1) 2px 2px 4px',
                selectable: true,
                editable: true
            });
            canvas.add(title);
            
            // Main content
            const presentedTo = new fabric.IText('This Certificate is presented to', {
                left: canvas.width / 2,
                top: 180,
                fill: '#495057',
                fontFamily: 'Arial',
                fontSize: 18,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(presentedTo);
            
            // Recipient name
            const recipientName = new fabric.IText('@{{recipient_name}}', {
                left: canvas.width / 2,
                top: 230,
                fill: '#000000',
                fontFamily: 'Georgia',
                fontSize: 32,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(recipientName);
            
            // For text
            const forText = new fabric.IText('for participation in the workshop', {
                left: canvas.width / 2,
                top: 290,
                fill: '#495057',
                fontFamily: 'Arial',
                fontSize: 18,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(forText);
            
            // Workshop name
            const workshopName = new fabric.IText('@{{course_name}}', {
                left: canvas.width / 2,
                top: 330,
                fill: '#856404',
                fontFamily: 'Arial',
                fontSize: 24,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(workshopName);
            
            // Date
            const dateText = new fabric.IText('@{{completion_date}}', {
                left: canvas.width / 2,
                top: 380,
                fill: '#6c757d',
                fontFamily: 'Arial',
                fontSize: 16,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(dateText);
            
            // Signatures for participation template
            const signatureLeft = new fabric.IText('Ketut Susilo', {
                left: canvas.width * 0.3,
                top: canvas.height - 100,
                fill: '#000000',
                fontFamily: 'Arial',
                fontSize: 14,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(signatureLeft);
            
            const signatureRight = new fabric.IText('Drew Feig', {
                left: canvas.width * 0.7,
                top: canvas.height - 100,
                fill: '#000000',
                fontFamily: 'Arial',
                fontSize: 14,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(signatureRight);
            
            // Signature lines
            const lineLeft = new fabric.Line([canvas.width * 0.3 - 60, canvas.height - 120, canvas.width * 0.3 + 60, canvas.height - 120], {
                stroke: '#000000',
                strokeWidth: 1,
                selectable: false
            });
            canvas.add(lineLeft);
            
            const lineRight = new fabric.Line([canvas.width * 0.7 - 60, canvas.height - 120, canvas.width * 0.7 + 60, canvas.height - 120], {
                stroke: '#000000',
                strokeWidth: 1,
                selectable: false
            });
            canvas.add(lineRight);
            
        } catch (error) {
            console.error('Error loading participation template:', error);
        }
    }
    
    // Completion Certificate Template
    function loadCompletionTemplate() {
        try {
            // Clean modern design
            canvas.backgroundColor = '#ffffff';
            
            // Top decorative line
            const topLine = new fabric.Line([100, 80, canvas.width - 100, 80], {
                stroke: '#2196f3',
                strokeWidth: 3,
                selectable: false,
                evented: false
            });
            canvas.add(topLine);
            
            // Bottom decorative line
            const bottomLine = new fabric.Line([100, canvas.height - 80, canvas.width - 100, canvas.height - 80], {
                stroke: '#2196f3',
                strokeWidth: 3,
                selectable: false,
                evented: false
            });
            canvas.add(bottomLine);
            
            // Title
            const title = new fabric.IText('CERTIFICATE OF COMPLETION', {
                left: canvas.width / 2,
                top: 120,
                fill: '#0d47a1',
                fontFamily: 'Arial',
                fontSize: 38,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(title);
            
            // This certifies text
            const certifiesText = new fabric.IText('This certifies that', {
                left: canvas.width / 2,
                top: 200,
                fill: '#455a64',
                fontFamily: 'Arial',
                fontSize: 20,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(certifiesText);
            
            // Recipient name
            const recipientName = new fabric.IText('@{{recipient_name}}', {
                left: canvas.width / 2,
                top: 250,
                fill: '#000000',
                fontFamily: 'Georgia',
                fontSize: 36,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(recipientName);
            
            // Has successfully completed
            const completedText = new fabric.IText('has successfully completed', {
                left: canvas.width / 2,
                top: 320,
                fill: '#455a64',
                fontFamily: 'Arial',
                fontSize: 20,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(completedText);
            
            // Course name
            const courseName = new fabric.IText('@{{course_name}}', {
                left: canvas.width / 2,
                top: 360,
                fill: '#0d47a1',
                fontFamily: 'Arial',
                fontSize: 28,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(courseName);
            
            // Duration and mode
            const durationText = new fabric.IText('Program Duration: @{{program_duration}} | Mode of Learning: @{{learning_mode}}', {
                left: canvas.width / 2,
                top: 420,
                fill: '#607d8b',
                fontFamily: 'Arial',
                fontSize: 16,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(durationText);
            
            // Date issued
            const dateText = new fabric.IText('Issued on: @{{completion_date}}', {
                left: canvas.width / 2,
                top: 460,
                fill: '#607d8b',
                fontFamily: 'Arial',
                fontSize: 16,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(dateText);
            
            // Issued by
            const issuedBy = new fabric.IText('Issued by: @{{issued_by}}', {
                left: canvas.width / 2,
                top: 500,
                fill: '#455a64',
                fontFamily: 'Arial',
                fontSize: 16,
                fontStyle: 'italic',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(issuedBy);
            
        } catch (error) {
            console.error('Error loading completion template:', error);
        }
    }
    
    // Excellence Certificate Template
    function loadExcellenceTemplate() {
        try {
            canvas.backgroundColor = '#fff5f5';
            
            // Elegant border with corner decorations
            const mainBorder = new fabric.Rect({
                left: 25,
                top: 25,
                width: canvas.width - 50,
                height: canvas.height - 50,
                fill: 'transparent',
                stroke: '#e53e3e',
                strokeWidth: 6,
                rx: 15,
                ry: 15,
                selectable: false,
                evented: false
            });
            canvas.add(mainBorder);
            
            // Inner decorative border
            const innerBorder = new fabric.Rect({
                left: 45,
                top: 45,
                width: canvas.width - 90,
                height: canvas.height - 90,
                fill: 'transparent',
                stroke: '#c53030',
                strokeWidth: 2,
                strokeDashArray: [10, 5],
                selectable: false,
                evented: false
            });
            canvas.add(innerBorder);
            
            // Excellence badge/seal
            const seal = new fabric.Circle({
                left: canvas.width - 150,
                top: 80,
                radius: 40,
                fill: '#e53e3e',
                stroke: '#c53030',
                strokeWidth: 3,
                selectable: false,
                evented: false
            });
            canvas.add(seal);
            
            const sealText = new fabric.IText('EXCELLENCE', {
                left: canvas.width - 150,
                top: 80,
                fill: '#ffffff',
                fontFamily: 'Arial',
                fontSize: 10,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                originY: 'center',
                selectable: false,
                evented: false
            });
            canvas.add(sealText);
            
            // Title
            const title = new fabric.IText('CERTIFICATE OF EXCELLENCE', {
                left: canvas.width / 2,
                top: 110,
                fill: '#c53030',
                fontFamily: 'Georgia',
                fontSize: 40,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                shadow: 'rgba(0,0,0,0.2) 3px 3px 6px',
                selectable: true,
                editable: true
            });
            canvas.add(title);
            
            // Subtitle
            const subtitle = new fabric.IText('In Recognition of Outstanding Performance', {
                left: canvas.width / 2,
                top: 170,
                fill: '#744210',
                fontFamily: 'Arial',
                fontSize: 18,
                fontStyle: 'italic',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(subtitle);
            
            // Presented to
            const presentedTo = new fabric.IText('This certificate is proudly presented to', {
                left: canvas.width / 2,
                top: 220,
                fill: '#2d3748',
                fontFamily: 'Arial',
                fontSize: 20,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(presentedTo);
            
            // Recipient name
            const recipientName = new fabric.IText('@{{recipient_name}}', {
                left: canvas.width / 2,
                top: 270,
                fill: '#000000',
                fontFamily: 'Georgia',
                fontSize: 36,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(recipientName);
            
            // Achievement text
            const achievementText = new fabric.IText('for demonstrating excellence in', {
                left: canvas.width / 2,
                top: 330,
                fill: '#2d3748',
                fontFamily: 'Arial',
                fontSize: 20,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(achievementText);
            
            // Course name
            const courseName = new fabric.IText('@{{course_name}}', {
                left: canvas.width / 2,
                top: 370,
                fill: '#c53030',
                fontFamily: 'Arial',
                fontSize: 26,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(courseName);
            
            // Date
            const dateText = new fabric.IText('Awarded on @{{completion_date}}', {
                left: canvas.width / 2,
                top: 420,
                fill: '#6c757d',
                fontFamily: 'Arial',
                fontSize: 16,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(dateText);
            
            addSignatureAreas();
            
        } catch (error) {
            console.error('Error loading excellence template:', error);
        }
    }
    
    // Training Certificate Template
    function loadTrainingTemplate() {
        try {
            canvas.backgroundColor = '#f0fff4';
            
            // Professional border
            const border = new fabric.Rect({
                left: 30,
                top: 30,
                width: canvas.width - 60,
                height: canvas.height - 60,
                fill: 'transparent',
                stroke: '#38a169',
                strokeWidth: 8,
                selectable: false,
                evented: false
            });
            canvas.add(border);
            
            // Header section
            const headerRect = new fabric.Rect({
                left: 50,
                top: 50,
                width: canvas.width - 100,
                height: 80,
                fill: '#38a169',
                selectable: false,
                evented: false
            });
            canvas.add(headerRect);
            
            // Title
            const title = new fabric.IText('TRAINING CERTIFICATE', {
                left: canvas.width / 2,
                top: 90,
                fill: '#ffffff',
                fontFamily: 'Arial',
                fontSize: 36,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                originY: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(title);
            
            // Certificate text
            const certText = new fabric.IText('This is to certify that', {
                left: canvas.width / 2,
                top: 180,
                fill: '#2f855a',
                fontFamily: 'Arial',
                fontSize: 22,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(certText);
            
            // Recipient name
            const recipientName = new fabric.IText('@{{recipient_name}}', {
                left: canvas.width / 2,
                top: 230,
                fill: '#000000',
                fontFamily: 'Arial',
                fontSize: 32,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(recipientName);
            
            // Training completion text
            const completionText = new fabric.IText('has successfully completed the training program', {
                left: canvas.width / 2,
                top: 280,
                fill: '#2f855a',
                fontFamily: 'Arial',
                fontSize: 20,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(completionText);
            
            // Course name
            const courseName = new fabric.IText('@{{course_name}}', {
                left: canvas.width / 2,
                top: 320,
                fill: '#38a169',
                fontFamily: 'Arial',
                fontSize: 28,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(courseName);
            
            // Duration and date
            const durationText = new fabric.IText('Duration: @{{program_duration}} hours | Completed on: @{{completion_date}}', {
                left: canvas.width / 2,
                top: 370,
                fill: '#6c757d',
                fontFamily: 'Arial',
                fontSize: 16,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(durationText);
            
            addSignatureAreas();
            
        } catch (error) {
            console.error('Error loading training template:', error);
        }
    }
    
    // Appreciation Certificate Template
    function loadAppreciationTemplate() {
        try {
            canvas.backgroundColor = '#fffaf0';
            
            // Warm decorative border
            const outerBorder = new fabric.Rect({
                left: 20,
                top: 20,
                width: canvas.width - 40,
                height: canvas.height - 40,
                fill: 'transparent',
                stroke: '#ed8936',
                strokeWidth: 10,
                rx: 20,
                ry: 20,
                selectable: false,
                evented: false
            });
            canvas.add(outerBorder);
            
            // Inner border with pattern
            const innerBorder = new fabric.Rect({
                left: 40,
                top: 40,
                width: canvas.width - 80,
                height: canvas.height - 80,
                fill: 'transparent',
                stroke: '#c05621',
                strokeWidth: 3,
                strokeDashArray: [15, 5, 5, 5],
                rx: 10,
                ry: 10,
                selectable: false,
                evented: false
            });
            canvas.add(innerBorder);
            
            // Decorative elements
            const leftDecor = new fabric.Triangle({
                left: 80,
                top: 100,
                width: 20,
                height: 20,
                fill: '#ed8936',
                angle: 30,
                selectable: false,
                evented: false
            });
            canvas.add(leftDecor);
            
            const rightDecor = new fabric.Triangle({
                left: canvas.width - 100,
                top: 100,
                width: 20,
                height: 20,
                fill: '#ed8936',
                angle: -30,
                selectable: false,
                evented: false
            });
            canvas.add(rightDecor);
            
            // Title
            const title = new fabric.IText('CERTIFICATE OF APPRECIATION', {
                left: canvas.width / 2,
                top: 120,
                fill: '#c05621',
                fontFamily: 'Times New Roman',
                fontSize: 36,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(title);
            
            // Appreciation text
            const appreciationText = new fabric.IText('In grateful recognition and sincere appreciation', {
                left: canvas.width / 2,
                top: 180,
                fill: '#744210',
                fontFamily: 'Georgia',
                fontSize: 18,
                fontStyle: 'italic',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(appreciationText);
            
            // Presented to
            const presentedTo = new fabric.IText('This certificate is presented to', {
                left: canvas.width / 2,
                top: 220,
                fill: '#2d3748',
                fontFamily: 'Arial',
                fontSize: 20,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(presentedTo);
            
            // Recipient name
            const recipientName = new fabric.IText('@{{recipient_name}}', {
                left: canvas.width / 2,
                top: 270,
                fill: '#000000',
                fontFamily: 'Georgia',
                fontSize: 34,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(recipientName);
            
            // For contribution
            const contributionText = new fabric.IText('for valuable contribution and dedication to', {
                left: canvas.width / 2,
                top: 320,
                fill: '#2d3748',
                fontFamily: 'Arial',
                fontSize: 20,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(contributionText);
            
            // Course/Program name
            const courseName = new fabric.IText('@{{course_name}}', {
                left: canvas.width / 2,
                top: 360,
                fill: '#c05621',
                fontFamily: 'Times New Roman',
                fontSize: 26,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(courseName);
            
            // Date
            const dateText = new fabric.IText('Presented on @{{completion_date}}', {
                left: canvas.width / 2,
                top: 410,
                fill: '#6c757d',
                fontFamily: 'Arial',
                fontSize: 16,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(dateText);
            
            addSignatureAreas();
            
        } catch (error) {
            console.error('Error loading appreciation template:', error);
        }
    }
    
    // Recognition Certificate Template
    function loadRecognitionTemplate() {
        try {
            canvas.backgroundColor = '#f7fafc';
            
            // Corporate style border
            const border = new fabric.Rect({
                left: 35,
                top: 35,
                width: canvas.width - 70,
                height: canvas.height - 70,
                fill: 'transparent',
                stroke: '#4a5568',
                strokeWidth: 6,
                selectable: false,
                evented: false
            });
            canvas.add(border);
            
            // Header bar
            const headerBar = new fabric.Rect({
                left: 55,
                top: 55,
                width: canvas.width - 110,
                height: 60,
                fill: '#4a5568',
                selectable: false,
                evented: false
            });
            canvas.add(headerBar);
            
            // Title
            const title = new fabric.IText('CERTIFICATE OF RECOGNITION', {
                left: canvas.width / 2,
                top: 85,
                fill: '#ffffff',
                fontFamily: 'Arial',
                fontSize: 32,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                originY: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(title);
            
            // Recognition text
            const recognitionText = new fabric.IText('This certificate recognizes', {
                left: canvas.width / 2,
                top: 160,
                fill: '#2d3748',
                fontFamily: 'Arial',
                fontSize: 22,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(recognitionText);
            
            // Recipient name
            const recipientName = new fabric.IText('@{{recipient_name}}', {
                left: canvas.width / 2,
                top: 210,
                fill: '#000000',
                fontFamily: 'Arial',
                fontSize: 36,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(recipientName);
            
            // Achievement description
            const achievementText = new fabric.IText('for outstanding achievement and professional excellence in', {
                left: canvas.width / 2,
                top: 260,
                fill: '#2d3748',
                fontFamily: 'Arial',
                fontSize: 20,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(achievementText);
            
            // Course name
            const courseName = new fabric.IText('@{{course_name}}', {
                left: canvas.width / 2,
                top: 300,
                fill: '#4a5568',
                fontFamily: 'Arial',
                fontSize: 28,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(courseName);
            
            // Performance metrics
            const metricsText = new fabric.IText('Performance Rating: Excellent | Completion Date: @{{completion_date}}', {
                left: canvas.width / 2,
                top: 350,
                fill: '#718096',
                fontFamily: 'Arial',
                fontSize: 16,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(metricsText);
            
            // Organization
            const orgText = new fabric.IText('Recognized by @{{organization}}', {
                left: canvas.width / 2,
                top: 380,
                fill: '#4a5568',
                fontFamily: 'Arial',
                fontSize: 18,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(orgText);
            
            addSignatureAreas();
            
        } catch (error) {
            console.error('Error loading recognition template:', error);
        }
    }
    
    // Honor Certificate Template
    function loadHonorTemplate() {
        try {
            canvas.backgroundColor = '#faf5ff';
            
            // Elegant purple border
            const outerBorder = new fabric.Rect({
                left: 25,
                top: 25,
                width: canvas.width - 50,
                height: canvas.height - 50,
                fill: 'transparent',
                stroke: '#805ad5',
                strokeWidth: 8,
                rx: 25,
                ry: 25,
                selectable: false,
                evented: false
            });
            canvas.add(outerBorder);
            
            // Inner decorative border
            const innerBorder = new fabric.Rect({
                left: 45,
                top: 45,
                width: canvas.width - 90,
                height: canvas.height - 90,
                fill: 'transparent',
                stroke: '#553c9a',
                strokeWidth: 3,
                strokeDashArray: [20, 10],
                rx: 15,
                ry: 15,
                selectable: false,
                evented: false
            });
            canvas.add(innerBorder);
            
            // Honor seal
            const seal = new fabric.Polygon([
                {x: 0, y: -50}, {x: 14, y: -15}, {x: 47, y: -15}, {x: 23, y: 7},
                {x: 29, y: 40}, {x: 0, y: 23}, {x: -29, y: 40}, {x: -23, y: 7},
                {x: -47, y: -15}, {x: -14, y: -15}
            ], {
                left: canvas.width - 120,
                top: 120,
                fill: '#805ad5',
                stroke: '#553c9a',
                strokeWidth: 2,
                scaleX: 0.8,
                scaleY: 0.8,
                selectable: false,
                evented: false
            });
            canvas.add(seal);
            
            const sealText = new fabric.IText('HONOR', {
                left: canvas.width - 120,
                top: 120,
                fill: '#ffffff',
                fontFamily: 'Arial',
                fontSize: 12,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                originY: 'center',
                selectable: false,
                evented: false
            });
            canvas.add(sealText);
            
            // Title
            const title = new fabric.IText('CERTIFICATE OF HONOR', {
                left: canvas.width / 2,
                top: 110,
                fill: '#553c9a',
                fontFamily: 'Georgia',
                fontSize: 42,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                shadow: 'rgba(0,0,0,0.3) 2px 2px 4px',
                selectable: true,
                editable: true
            });
            canvas.add(title);
            
            // Honor text
            const honorText = new fabric.IText('Bestowed upon', {
                left: canvas.width / 2,
                top: 170,
                fill: '#6b46c1',
                fontFamily: 'Georgia',
                fontSize: 20,
                fontStyle: 'italic',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(honorText);
            
            // Recipient name
            const recipientName = new fabric.IText('@{{recipient_name}}', {
                left: canvas.width / 2,
                top: 220,
                fill: '#000000',
                fontFamily: 'Georgia',
                fontSize: 38,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(recipientName);
            
            // Honor description
            const descriptionText = new fabric.IText('in recognition of distinguished service and exceptional dedication to', {
                left: canvas.width / 2,
                top: 280,
                fill: '#4c1d95',
                fontFamily: 'Arial',
                fontSize: 18,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(descriptionText);
            
            // Course name
            const courseName = new fabric.IText('@{{course_name}}', {
                left: canvas.width / 2,
                top: 320,
                fill: '#553c9a',
                fontFamily: 'Georgia',
                fontSize: 26,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(courseName);
            
            // Honor date
            const dateText = new fabric.IText('Conferred this @{{completion_date}}', {
                left: canvas.width / 2,
                top: 370,
                fill: '#6b46c1',
                fontFamily: 'Georgia',
                fontSize: 16,
                fontStyle: 'italic',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(dateText);
            
            addSignatureAreas();
            
        } catch (error) {
            console.error('Error loading honor template:', error);
        }
    }
    
    // Graduation Certificate Template
    function loadGraduationTemplate() {
        try {
            canvas.backgroundColor = '#f0f9ff';
            
            // Academic border
            const border = new fabric.Rect({
                left: 30,
                top: 30,
                width: canvas.width - 60,
                height: canvas.height - 60,
                fill: 'transparent',
                stroke: '#3b82f6',
                strokeWidth: 10,
                selectable: false,
                evented: false
            });
            canvas.add(border);
            
            // Academic seal/crest
            const crest = new fabric.Circle({
                left: canvas.width / 2,
                top: 80,
                radius: 35,
                fill: '#1d4ed8',
                stroke: '#1e40af',
                strokeWidth: 3,
                selectable: false,
                evented: false
            });
            canvas.add(crest);
            
            const crestText = new fabric.IText('GRAD', {
                left: canvas.width / 2,
                top: 80,
                fill: '#ffffff',
                fontFamily: 'Arial',
                fontSize: 14,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                originY: 'center',
                selectable: false,
                evented: false
            });
            canvas.add(crestText);
            
            // Title
            const title = new fabric.IText('GRADUATION CERTIFICATE', {
                left: canvas.width / 2,
                top: 140,
                fill: '#1d4ed8',
                fontFamily: 'Times New Roman',
                fontSize: 38,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(title);
            
            // Institution line
            const institutionText = new fabric.IText('@{{organization}} hereby certifies that', {
                left: canvas.width / 2,
                top: 190,
                fill: '#1e40af',
                fontFamily: 'Arial',
                fontSize: 18,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(institutionText);
            
            // Recipient name
            const recipientName = new fabric.IText('@{{recipient_name}}', {
                left: canvas.width / 2,
                top: 240,
                fill: '#000000',
                fontFamily: 'Times New Roman',
                fontSize: 36,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(recipientName);
            
            // Graduation text
            const graduationText = new fabric.IText('has successfully completed all requirements for graduation from', {
                left: canvas.width / 2,
                top: 290,
                fill: '#1e40af',
                fontFamily: 'Arial',
                fontSize: 20,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(graduationText);
            
            // Program name
            const programName = new fabric.IText('@{{course_name}}', {
                left: canvas.width / 2,
                top: 330,
                fill: '#1d4ed8',
                fontFamily: 'Times New Roman',
                fontSize: 28,
                fontWeight: 'bold',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(programName);
            
            // Degree conferment
            const degreeText = new fabric.IText('and is hereby granted all rights and privileges pertaining thereto', {
                left: canvas.width / 2,
                top: 370,
                fill: '#1e40af',
                fontFamily: 'Arial',
                fontSize: 16,
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(degreeText);
            
            // Graduation date
            const dateText = new fabric.IText('Given this @{{completion_date}}', {
                left: canvas.width / 2,
                top: 400,
                fill: '#6b7280',
                fontFamily: 'Times New Roman',
                fontSize: 16,
                fontStyle: 'italic',
                textAlign: 'center',
                originX: 'center',
                selectable: true,
                editable: true
            });
            canvas.add(dateText);
            
            addSignatureAreas();
            
        } catch (error) {
            console.error('Error loading graduation template:', error);
        }
    }
    
    // Add signature areas to template
    function addSignatureAreas() {
        // Signature line 1
        const line1 = new fabric.Line([200, canvas.height - 120, 400, canvas.height - 120], {
            stroke: '#000000',
            strokeWidth: 1,
            selectable: false
        });
        canvas.add(line1);
        
        const facilitator = new fabric.IText('Course Facilitator', {
            left: 300,
            top: canvas.height - 105,
            fill: '#666666',
            fontFamily: 'Arial',
            fontSize: 12,
            textAlign: 'center',
            originX: 'center',
            selectable: false
        });
        canvas.add(facilitator);
        
        // Signature line 2
        const line2 = new fabric.Line([canvas.width - 400, canvas.height - 120, canvas.width - 200, canvas.height - 120], {
            stroke: '#000000',
            strokeWidth: 1,
            selectable: false
        });
        canvas.add(line2);
        
        const coordinator = new fabric.IText('Program Coordinator', {
            left: canvas.width - 300,
            top: canvas.height - 105,
            fill: '#666666',
            fontFamily: 'Arial',
            fontSize: 12,
            textAlign: 'center',
            originX: 'center',
            selectable: false
        });
        canvas.add(coordinator);
    }

    // Add professional frame to certificate
    function addProfessionalFrame() {
        const frameOptions = [
            { name: 'Classic Border', type: 'classic' },
            { name: 'Elegant Frame', type: 'elegant' },
            { name: 'Modern Frame', type: 'modern' },
            { name: 'Ornate Frame', type: 'ornate' }
        ];
        
        // Create frame selection modal
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select Frame Style</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            ${frameOptions.map(frame => `
                                <div class="col-6 mb-3">
                                    <button class="btn btn-outline-primary btn-block frame-option" data-type="${frame.type}">
                                        ${frame.name}
                                    </button>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        $(modal).modal('show');
        
        // Handle frame selection
        modal.querySelectorAll('.frame-option').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const frameType = e.target.dataset.type;
                createFrame(frameType);
                $(modal).modal('hide');
                document.body.removeChild(modal);
            });
        });
    }
    
    // Create different frame styles
    function createFrame(type) {
        const padding = 20;
        const width = canvas.width - (padding * 2);
        const height = canvas.height - (padding * 2);
        
        switch(type) {
            case 'classic':
                // Simple border frame
                const classicFrame = new fabric.Rect({
                    left: padding,
                    top: padding,
                    width: width,
                    height: height,
                    fill: 'transparent',
                    stroke: '#8B4513',
                    strokeWidth: 8,
                    selectable: true,
                    evented: true
                });
                canvas.add(classicFrame);
                break;
                
            case 'elegant':
                // Double border frame
                const outerFrame = new fabric.Rect({
                    left: padding,
                    top: padding,
                    width: width,
                    height: height,
                    fill: 'transparent',
                    stroke: '#DAA520',
                    strokeWidth: 6,
                    selectable: true
                });
                const innerFrame = new fabric.Rect({
                    left: padding + 15,
                    top: padding + 15,
                    width: width - 30,
                    height: height - 30,
                    fill: 'transparent',
                    stroke: '#DAA520',
                    strokeWidth: 2,
                    selectable: true
                });
                canvas.add(outerFrame);
                canvas.add(innerFrame);
                break;
                
            case 'modern':
                // Corner accent frame
                const corners = [
                    { x: padding, y: padding },
                    { x: canvas.width - padding - 40, y: padding },
                    { x: padding, y: canvas.height - padding - 40 },
                    { x: canvas.width - padding - 40, y: canvas.height - padding - 40 }
                ];
                
                corners.forEach(corner => {
                    const cornerAccent = new fabric.Rect({
                        left: corner.x,
                        top: corner.y,
                        width: 40,
                        height: 40,
                        fill: 'transparent',
                        stroke: '#4A90E2',
                        strokeWidth: 3,
                        selectable: true
                    });
                    canvas.add(cornerAccent);
                });
                break;
                
            case 'ornate':
                // Decorative frame with pattern
                const ornateFrame = new fabric.Rect({
                    left: padding,
                    top: padding,
                    width: width,
                    height: height,
                    fill: 'transparent',
                    stroke: '#B8860B',
                    strokeWidth: 12,
                    strokeDashArray: [20, 10],
                    selectable: true
                });
                canvas.add(ornateFrame);
                break;
        }
        
        canvas.renderAll();
    }
    
    // Update property controls based on selected object
    function updatePropertyControls(obj) {
        if (!obj) return;
        
        const colorPicker = document.getElementById('element-color');
        const fontFamily = document.getElementById('font-family');
        const fontSize = document.getElementById('font-size');
        const boldBtn = document.getElementById('font-bold');
        const italicBtn = document.getElementById('font-italic');
        
        // Update color picker
        if (obj.fill && typeof obj.fill === 'string') {
            colorPicker.value = obj.fill;
        }
        
        // Update text properties if it's a text object
        if (obj.type === 'i-text' || obj.type === 'text') {
            fontFamily.value = obj.fontFamily || 'Arial';
            fontSize.value = obj.fontSize || 16;
            
            // Update button states
            boldBtn.classList.toggle('active', obj.fontWeight === 'bold');
            italicBtn.classList.toggle('active', obj.fontStyle === 'italic');
        }
    }
    
    // Reset property controls when no object is selected
    function resetPropertyControls() {
        const colorPicker = document.getElementById('element-color');
        const fontFamily = document.getElementById('font-family');
        const fontSize = document.getElementById('font-size');
        const boldBtn = document.getElementById('font-bold');
        const italicBtn = document.getElementById('font-italic');
        
        colorPicker.value = '#000000';
        fontFamily.value = 'Arial';
        fontSize.value = '16';
        boldBtn.classList.remove('active');
        italicBtn.classList.remove('active');
    }
    
    // Setup toolbar handlers for Fabric.js
    function setupToolbarHandlers() {
        // Get delete button reference
        const deleteBtn = document.getElementById('delete-selected');
        
        // Function to handle element deletion
        function deleteSelectedElements() {
            const activeObjects = canvas.getActiveObjects();
            if (activeObjects && activeObjects.length > 0) {
                if (confirm('Are you sure you want to delete the selected element(s)?')) {
                    activeObjects.forEach(object => {
                        canvas.remove(object);
                    });
                    canvas.discardActiveObject().renderAll();
                    deleteBtn.disabled = true;
                }
            }
        }
        
        // Function to handle element duplication
        function duplicateSelectedElements() {
            const activeObjects = canvas.getActiveObjects();
            if (activeObjects && activeObjects.length > 0) {
                activeObjects.forEach(object => {
                    object.clone(function(cloned) {
                        // Offset the cloned object position
                        cloned.set({
                            left: cloned.left + 20,
                            top: cloned.top + 20,
                            evented: true,
                        });
                        
                        // Handle different object types
                        if (cloned.type === 'activeSelection') {
                            // Multiple objects selected
                            cloned.canvas = canvas;
                            cloned.forEachObject(function(obj) {
                                canvas.add(obj);
                            });
                            cloned.setCoords();
                        } else {
                            // Single object
                            canvas.add(cloned);
                        }
                        
                        canvas.setActiveObject(cloned);
                        canvas.renderAll();
                    });
                });
            }
        }
        
        // Get duplicate button reference
        const duplicateBtn = document.getElementById('duplicate-selected');
        
        // Update button states based on selection
        canvas.on('selection:created', function() {
            deleteBtn.disabled = false;
            duplicateBtn.disabled = false;
        });
        
        canvas.on('selection:cleared', function() {
            deleteBtn.disabled = true;
            duplicateBtn.disabled = true;
        });
        
        // Button click handlers
        deleteBtn.addEventListener('click', deleteSelectedElements);
        duplicateBtn.addEventListener('click', duplicateSelectedElements);
        
        // Keyboard shortcuts support
        document.addEventListener('keydown', function(e) {
            // Check if user is currently editing text
            const activeObject = canvas.getActiveObject();
            const isEditingText = activeObject && (activeObject.type === 'i-text' || activeObject.type === 'text') && activeObject.isEditing;
            
            // Only process shortcuts if NOT editing text
            if (!isEditingText) {
                // Delete/Backspace - Delete selected elements
                if ((e.key === 'Delete' || e.key === 'Backspace') && canvas.getActiveObjects().length > 0) {
                    deleteSelectedElements();
                    e.preventDefault();
                }
                
                // Ctrl+D - Duplicate selected elements
                if (e.ctrlKey && e.key.toLowerCase() === 'd' && canvas.getActiveObjects().length > 0) {
                    duplicateSelectedElements();
                    e.preventDefault();
                }
            }
        });
        // Add text button
        document.getElementById('add-text').addEventListener('click', () => {
            const text = new fabric.IText('Click to edit text', {
                left: 100,
                top: 100,
                fill: '#000000',
                fontFamily: 'Arial',
                fontSize: 24,
                selectable: true,
                editable: true
            });
            canvas.add(text);
            canvas.setActiveObject(text);
            canvas.renderAll();
        });

        // Add rectangle button
        document.getElementById('add-rectangle').addEventListener('click', () => {
            const rect = new fabric.Rect({
                left: 100,
                top: 100,
                width: 200,
                height: 100,
                fill: 'rgba(0, 0, 255, 0.2)',
                stroke: '#0000ff',
                strokeWidth: 2,
                selectable: true,
                editable: true
            });
            canvas.add(rect);
            canvas.setActiveObject(rect);
            canvas.renderAll();
        });

        // Add line button
        document.getElementById('add-line').addEventListener('click', () => {
            const line = new fabric.Line([100, 100, 300, 100], {
                stroke: '#000000',
                strokeWidth: 2,
                selectable: true,
                editable: true
            });
            canvas.add(line);
            canvas.setActiveObject(line);
            canvas.renderAll();
        });

        // Add image button
        document.getElementById('add-image').addEventListener('click', () => {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.onchange = function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        fabric.Image.fromURL(event.target.result, function(img) {
                            img.set({
                                left: 100,
                                top: 100,
                                scaleX: 0.5,
                                scaleY: 0.5,
                                selectable: true
                            });
                            canvas.add(img);
                            canvas.setActiveObject(img);
                            canvas.renderAll();
                        });
                    };
                    reader.readAsDataURL(file);
                }
            };
            input.click();
        });

        // Add circle button
        document.getElementById('add-circle').addEventListener('click', () => {
            const circle = new fabric.Circle({
                left: 100,
                top: 100,
                radius: 50,
                fill: 'rgba(255, 0, 0, 0.2)',
                stroke: '#ff0000',
                strokeWidth: 2,
                selectable: true,
                editable: true
            });
            canvas.add(circle);
            canvas.setActiveObject(circle);
            canvas.renderAll();
        });

        // Add triangle button
        document.getElementById('add-triangle').addEventListener('click', () => {
            const triangle = new fabric.Triangle({
                left: 100,
                top: 100,
                width: 100,
                height: 100,
                fill: 'rgba(0, 255, 0, 0.2)',
                stroke: '#00ff00',
                strokeWidth: 2,
                selectable: true,
                editable: true
            });
            canvas.add(triangle);
            canvas.setActiveObject(triangle);
            canvas.renderAll();
        });

        // Add frame button
        document.getElementById('add-frame').addEventListener('click', () => {
            addProfessionalFrame();
        });

        // Color picker for elements
        document.getElementById('element-color').addEventListener('change', (e) => {
            const activeObject = canvas.getActiveObject();
            if (activeObject) {
                if (activeObject.type === 'i-text' || activeObject.type === 'text') {
                    activeObject.set('fill', e.target.value);
                } else {
                    activeObject.set('fill', e.target.value);
                }
                canvas.renderAll();
            }
        });

        // Font family change
        document.getElementById('font-family').addEventListener('change', (e) => {
            const activeObject = canvas.getActiveObject();
            if (activeObject && (activeObject.type === 'i-text' || activeObject.type === 'text')) {
                activeObject.set('fontFamily', e.target.value);
                canvas.renderAll();
            }
        });

        // Font size change
        document.getElementById('font-size').addEventListener('change', (e) => {
            const activeObject = canvas.getActiveObject();
            if (activeObject && (activeObject.type === 'i-text' || activeObject.type === 'text')) {
                activeObject.set('fontSize', parseInt(e.target.value));
                canvas.renderAll();
            }
        });

        // Bold toggle
        document.getElementById('font-bold').addEventListener('click', () => {
            const activeObject = canvas.getActiveObject();
            if (activeObject && (activeObject.type === 'i-text' || activeObject.type === 'text')) {
                const currentWeight = activeObject.fontWeight;
                activeObject.set('fontWeight', currentWeight === 'bold' ? 'normal' : 'bold');
                canvas.renderAll();
            }
        });

        // Italic toggle
        document.getElementById('font-italic').addEventListener('click', () => {
            const activeObject = canvas.getActiveObject();
            if (activeObject && (activeObject.type === 'i-text' || activeObject.type === 'text')) {
                const currentStyle = activeObject.fontStyle;
                activeObject.set('fontStyle', currentStyle === 'italic' ? 'normal' : 'italic');
                canvas.renderAll();
            }
        });

        // Update property controls when object is selected
        canvas.on('selection:created', function(e) {
            updatePropertyControls(e.selected[0]);
        });

        canvas.on('selection:updated', function(e) {
            updatePropertyControls(e.selected[0]);
        });

        canvas.on('selection:cleared', function() {
            resetPropertyControls();
        });

        // Background color button
        document.getElementById('background-color').addEventListener('click', () => {
            const input = document.createElement('input');
            input.type = 'color';
            input.value = canvas.backgroundColor || '#ffffff';
            input.onchange = function(e) {
                canvas.backgroundColor = e.target.value;
                canvas.renderAll();
            };
            input.click();
        });

        // Grid toggle button
        let gridVisible = false;
        document.getElementById('grid-toggle').addEventListener('click', () => {
            if (gridVisible) {
                // Remove grid
                const gridObjects = canvas.getObjects().filter(obj => obj.id === 'grid-line');
                gridObjects.forEach(obj => canvas.remove(obj));
                gridVisible = false;
                document.getElementById('grid-toggle').classList.remove('active');
            } else {
                // Add grid
                const gridSize = 50;
                for (let i = 0; i < canvas.width; i += gridSize) {
                    const line = new fabric.Line([i, 0, i, canvas.height], {
                        stroke: '#e0e0e0',
                        strokeWidth: 1,
                        selectable: false,
                        evented: false,
                        id: 'grid-line'
                    });
                    canvas.add(line);
                    canvas.sendToBack(line);
                }
                for (let i = 0; i < canvas.height; i += gridSize) {
                    const line = new fabric.Line([0, i, canvas.width, i], {
                        stroke: '#e0e0e0',
                        strokeWidth: 1,
                        selectable: false,
                        evented: false,
                        id: 'grid-line'
                    });
                    canvas.add(line);
                    canvas.sendToBack(line);
                }
                gridVisible = true;
                document.getElementById('grid-toggle').classList.add('active');
                canvas.renderAll();
            }
        });

        // Clear all button
        document.getElementById('clear-all').addEventListener('click', () => {
            if (confirm('Are you sure you want to clear all elements?')) {
                canvas.clear();
                canvas.backgroundColor = '#ffffff';
                gridVisible = false;
                document.getElementById('grid-toggle').classList.remove('active');
                loadTemplate(currentTemplate);
                deleteBtn.disabled = true;
            }
        });
    }

    // Setup form submission
    function setupFormSubmission() {
        document.getElementById('certificateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Convert canvas to JSON
            const canvasData = JSON.stringify(canvas.toJSON());
            document.getElementById('template-data').value = canvasData;
            
            // Submit form via AJAX
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Template saved successfully!');
                    window.location.href = data.redirect || '{{ route("admin.ai.certificates.index") }}';
                } else {
                    alert('Error saving template: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving template: ' + error.message);
            });
        });
    }

    // Empty Template - Just blank canvas
    function loadEmptyTemplate() {
        try {
            // Clear canvas and set white background
            canvas.clear();
            canvas.backgroundColor = '#ffffff';
            canvas.renderAll();
            console.log('Empty template loaded');
        } catch (error) {
            console.error('Error loading empty template:', error);
        }
    }

    // Initialize canvas when the page loads
    if (typeof fabric !== 'undefined') {
        initializeCanvas();
    } else {
        // Retry if Fabric.js isn't loaded yet
        setTimeout(initializeCanvas, 100);
    }
});
</script>
@endpush