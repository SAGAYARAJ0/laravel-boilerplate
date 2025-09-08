@extends('backend.layouts.app')

@section('title', __('Edit Certificate Template'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    {{ __('Edit Certificate Template') }}
                    <small class="text-muted">{{ $certificate->name }}</small>
                </h4>
            </div><!--col-->

            <div class="col-sm-7">
                <div class="btn-toolbar float-right" role="toolbar">
                    <a href="{{ route('admin.ai.certificates.show', $certificate) }}" class="btn btn-secondary ml-1">
                        <i class="fas fa-arrow-left"></i> @lang('Back to Template')
                    </a>
                </div><!--btn-toolbar-->
            </div><!--col-->
        </div><!--row-->

        <div class="row mt-4">
            <div class="col">
                <form id="certificateForm" method="POST" action="{{ route('admin.ai.certificates.update', $certificate) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">@lang('Template Name') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $certificate->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="description">@lang('Description')</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $certificate->description) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="background_image">@lang('Background Image')</label>
                                @if($certificate->background_image)
                                    <div class="mb-2">
                                        <img src="{{ $certificate->background_image_url }}" alt="Current background" class="img-thumbnail" style="max-width: 200px;">
                                        <p class="small text-muted">@lang('Current background image')</p>
                                    </div>
                                @endif
                                <input type="file" class="form-control-file" id="background_image" name="background_image" accept="image/*">
                                <small class="form-text text-muted">@lang('Leave empty to keep current image')</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="width">@lang('Width (px)')</label>
                                        <input type="number" class="form-control" id="width" name="width" value="{{ old('width', $certificate->width) }}" min="100" max="5000">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="height">@lang('Height (px)')</label>
                                        <input type="number" class="form-control" id="height" name="height" value="{{ old('height', $certificate->height) }}" min="100" max="5000">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="orientation">@lang('Orientation')</label>
                                <select class="form-control" id="orientation" name="orientation">
                                    <option value="landscape" {{ old('orientation', $certificate->orientation) == 'landscape' ? 'selected' : '' }}>@lang('Landscape')</option>
                                    <option value="portrait" {{ old('orientation', $certificate->orientation) == 'portrait' ? 'selected' : '' }}>@lang('Portrait')</option>
                                </select>
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
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ old('is_active', $certificate->is_active) ? 'checked' : '' }}>
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
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-0 clearfix">
                                <div class="float-right">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> @lang('Update Template')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card>
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
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
    }
    .toolbar-section {
        margin-bottom: 15px;
    }
    .toolbar-section:last-child {
        margin-bottom: 0;
    }
    .toolbar-label {
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }
</style>
@endpush

@push('after-scripts')
<!-- Load Fabric.js for canvas functionality -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let canvas;
    
    // Initialize Fabric.js canvas
    function initializeCanvas() {
        const width = {{ $certificate->width }};
        const height = {{ $certificate->height }};
        
        canvas = new fabric.Canvas('certificate-canvas', {
            width: width,
            height: height,
            backgroundColor: '#ffffff'
        });
        
        // Set canvas size
        canvas.setWidth(width);
        canvas.setHeight(height);
        
        // Load existing template data
        const templateData = @json($certificate->template_data ?? null);
        if (templateData && typeof templateData === 'object') {
            try {
                canvas.loadFromJSON(templateData, function() {
                    canvas.renderAll();
                    console.log('Template data loaded successfully');
                });
            } catch (error) {
                console.error('Error loading template data:', error);
                // Load default template if existing data is invalid
                loadDefaultTemplate();
            }
        } else {
            // Load default template if no data exists
            loadDefaultTemplate();
        }
        
        // Setup toolbar handlers
        setupToolbarHandlers();
        
        // Setup form submission
        setupFormSubmission();
        
        // Setup template selection
        setupTemplateSelection();
        
        console.log('Canvas initialized successfully for editing');
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
                
                // Confirm before changing template
                if (confirm('This will replace the current design. Are you sure?')) {
                    loadTemplate(templateType);
                }
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
                loadDefaultTemplate();
        }
        
        canvas.renderAll();
    }

    // Setup toolbar handlers
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
                    duplicateBtn.disabled = true;
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
        document.getElementById('add-text').addEventListener('click', function() {
            const text = new fabric.Text('Click to edit text', {
                left: 100,
                top: 100,
                fill: '#000000',
                fontFamily: 'Arial',
                fontSize: 24
            });
            canvas.add(text);
            canvas.setActiveObject(text);
        });

        document.getElementById('add-rectangle').addEventListener('click', function() {
            const rect = new fabric.Rect({
                left: 100,
                top: 100,
                width: 200,
                height: 100,
                fill: 'rgba(0, 0, 255, 0.2)',
                stroke: '#0000ff',
                strokeWidth: 2
            });
            canvas.add(rect);
            canvas.setActiveObject(rect);
        });

        document.getElementById('add-line').addEventListener('click', function() {
            const line = new fabric.Line([100, 100, 300, 100], {
                stroke: '#000000',
                strokeWidth: 2
            });
            canvas.add(line);
            canvas.setActiveObject(line);
        });

        document.getElementById('add-image').addEventListener('click', function() {
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
                                scaleY: 0.5
                            });
                            canvas.add(img);
                            canvas.setActiveObject(img);
                        });
                    };
                    reader.readAsDataURL(file);
                }
            };
            input.click();
        });

        // Add circle button
        document.getElementById('add-circle').addEventListener('click', function() {
            const circle = new fabric.Circle({
                left: 100,
                top: 100,
                radius: 50,
                fill: 'rgba(255, 0, 0, 0.2)',
                stroke: '#ff0000',
                strokeWidth: 2,
                selectable: true
            });
            canvas.add(circle);
            canvas.setActiveObject(circle);
            canvas.renderAll();
        });

        // Add triangle button
        document.getElementById('add-triangle').addEventListener('click', function() {
            const triangle = new fabric.Triangle({
                left: 100,
                top: 100,
                width: 100,
                height: 100,
                fill: 'rgba(0, 255, 0, 0.2)',
                stroke: '#00ff00',
                strokeWidth: 2,
                selectable: true
            });
            canvas.add(triangle);
            canvas.setActiveObject(triangle);
            canvas.renderAll();
        });

        // Background color button
        document.getElementById('background-color').addEventListener('click', function() {
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
        document.getElementById('grid-toggle').addEventListener('click', function() {
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
        document.getElementById('clear-all').addEventListener('click', function() {
            if (confirm('Are you sure you want to clear all elements?')) {
                canvas.clear();
                canvas.backgroundColor = '#ffffff';
                gridVisible = false;
                document.getElementById('grid-toggle').classList.remove('active');
                loadTemplate('empty');
                deleteBtn.disabled = true;
            }
        });
    }

    // Setup form submission
    function setupFormSubmission() {
        document.getElementById('certificateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            try {
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
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect || "{{ route('admin.ai.certificates.show', $certificate) }}";
                    } else {
                        alert('Error updating template: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating template: ' + error.message);
                });
            } catch (error) {
                console.error('Error processing form:', error);
                alert('Error processing form: ' + error.message);
            }
        });
    }

    // Initialize canvas when the page loads
    if (typeof fabric !== 'undefined') {
        initializeCanvas();
    } else {
        // Retry if Fabric.js isn't loaded yet
        setTimeout(initializeCanvas, 100);
    }
});

// Load default template if no template data exists
function loadDefaultTemplate() {
    // Add outer border
    const outerBorder = new fabric.Rect({
        left: 50,
        top: 50,
        width: canvas.width - 100,
        height: canvas.height - 100,
        fill: 'transparent',
        stroke: '#DAA520',
        strokeWidth: 8,
        selectable: false,
        evented: false
    });
    canvas.add(outerBorder);
    
    // Add inner border
    const innerBorder = new fabric.Rect({
        left: 75,
        top: 75,
        width: canvas.width - 150,
        height: canvas.height - 150,
        fill: 'transparent',
        stroke: '#DAA520',
        strokeWidth: 2,
        selectable: false,
        evented: false
    });
    canvas.add(innerBorder);
    
    // Add title
    const title = new fabric.Text('CERTIFICATE OF ACHIEVEMENT', {
        left: canvas.width / 2,
        top: 120,
        fill: '#1a237e',
        fontFamily: 'Arial',
        fontSize: 36,
        fontWeight: 'bold',
        textAlign: 'center',
        originX: 'center'
    });
    canvas.add(title);
    
    // Add "This is to certify that" text
    const certifyText = new fabric.Text('This is to certify that', {
        left: 150,
        top: 200,
        fill: '#000000',
        fontFamily: 'Arial',
        fontSize: 20
    });
    canvas.add(certifyText);
    
    // Add recipient name placeholder
    const recipientName = new fabric.Text('@{{recipient_name}}', {
        left: canvas.width / 2,
        top: 250,
        fill: '#000000',
        fontFamily: 'Arial',
        fontSize: 28,
        fontWeight: 'bold',
        textAlign: 'center',
        originX: 'center'
    });
    canvas.add(recipientName);
    
    // Add completion text
    const completionText = new fabric.Text('has successfully completed the', {
        left: 150,
        top: 300,
        fill: '#000000',
        fontFamily: 'Arial',
        fontSize: 20
    });
    canvas.add(completionText);
    
    // Add course name placeholder
    const courseName = new fabric.Text('@{{course_name}}', {
        left: canvas.width / 2,
        top: 350,
        fill: '#1a237e',
        fontFamily: 'Arial',
        fontSize: 24,
        fontWeight: 'bold',
        textAlign: 'center',
        originX: 'center'
    });
    canvas.add(courseName);
    
    // Add date text
    const dateText = new fabric.Text('on @{{completion_date}} with distinction.', {
        left: 150,
        top: 400,
        fill: '#000000',
        fontFamily: 'Arial',
        fontSize: 18
    });
    canvas.add(dateText);
    
    // Add signature lines
    const signatureLine1 = new fabric.Line([200, canvas.height - 120, 400, canvas.height - 120], {
        stroke: '#000000',
        strokeWidth: 1,
        selectable: false
    });
    canvas.add(signatureLine1);
    
    const facilitatorText = new fabric.Text('Course Facilitator', {
        left: 300,
        top: canvas.height - 105,
        fill: '#666666',
        fontFamily: 'Arial',
        fontSize: 12,
        textAlign: 'center',
        originX: 'center'
    });
    canvas.add(facilitatorText);
    
    const signatureLine2 = new fabric.Line([canvas.width - 400, canvas.height - 120, canvas.width - 200, canvas.height - 120], {
        stroke: '#000000',
        strokeWidth: 1,
        selectable: false
    });
    canvas.add(signatureLine2);
    
    const coordinatorText = new fabric.Text('Program Coordinator', {
        left: canvas.width - 300,
        top: canvas.height - 105,
        fill: '#666666',
        fontFamily: 'Arial',
        fontSize: 12,
        textAlign: 'center',
        originX: 'center'
    });
    canvas.add(coordinatorText);
    
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
        const title = new fabric.Text('CERTIFICATE OF ACHIEVEMENT', {
            left: canvas.width / 2,
            top: 100,
            fill: '#1a237e',
            fontFamily: 'Times New Roman',
            fontSize: 42,
            fontWeight: 'bold',
            textAlign: 'center',
            originX: 'center',
            shadow: 'rgba(0,0,0,0.3) 2px 2px 4px',
            selectable: true
        });
        canvas.add(title);
        
        // Add "This is to certify that" text
        const certifyText = new fabric.Text('This is to certify that', {
            left: canvas.width / 2,
            top: 200,
            fill: '#2c3e50',
            fontFamily: 'Arial',
            fontSize: 22,
            fontStyle: 'italic',
            textAlign: 'center',
            originX: 'center',
            selectable: true
        });
        canvas.add(certifyText);
        
        // Add recipient name placeholder
        const recipientName = new fabric.Text('@{{recipient_name}}', {
            left: canvas.width / 2,
            top: 250,
            fill: '#000000',
            fontFamily: 'Georgia',
            fontSize: 32,
            fontWeight: 'bold',
            textAlign: 'center',
            originX: 'center',
            selectable: true
        });
        canvas.add(recipientName);
        
        // Add completion text
        const completionText = new fabric.Text('has successfully completed the', {
            left: canvas.width / 2,
            top: 310,
            fill: '#2c3e50',
            fontFamily: 'Arial',
            fontSize: 20,
            textAlign: 'center',
            originX: 'center',
            selectable: true
        });
        canvas.add(completionText);
        
        // Add course name placeholder
        const courseName = new fabric.Text('@{{course_name}}', {
            left: canvas.width / 2,
            top: 350,
            fill: '#1a237e',
            fontFamily: 'Arial',
            fontSize: 28,
            fontWeight: 'bold',
            textAlign: 'center',
            originX: 'center',
            selectable: true
        });
        canvas.add(courseName);
        
        // Add date text
        const dateText = new fabric.Text('on @{{completion_date}} with distinction.', {
            left: canvas.width / 2,
            top: 410,
            fill: '#2c3e50',
            fontFamily: 'Arial',
            fontSize: 18,
            textAlign: 'center',
            originX: 'center',
            selectable: true
        });
        canvas.add(dateText);
        
        // Add signature areas
        addSignatureAreas();
        
    } catch (error) {
        console.error('Error loading achievement template:', error);
    }
}

// Participation Certificate Template
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
        
        // Title
        const title = new fabric.Text('CERTIFICATE OF PARTICIPATION', {
            left: canvas.width / 2,
            top: 100,
            fill: '#856404',
            fontFamily: 'Georgia',
            fontSize: 36,
            fontWeight: 'bold',
            textAlign: 'center',
            originX: 'center',
            shadow: 'rgba(0,0,0,0.1) 2px 2px 4px',
            selectable: true
        });
        canvas.add(title);
        
        // Main content
        const presentedTo = new fabric.Text('This Certificate is presented to', {
            left: canvas.width / 2,
            top: 180,
            fill: '#495057',
            fontFamily: 'Arial',
            fontSize: 18,
            textAlign: 'center',
            originX: 'center',
            selectable: true
        });
        canvas.add(presentedTo);
        
        // Recipient name
        const recipientName = new fabric.Text('@{{recipient_name}}', {
            left: canvas.width / 2,
            top: 230,
            fill: '#000000',
            fontFamily: 'Georgia',
            fontSize: 32,
            fontWeight: 'bold',
            textAlign: 'center',
            originX: 'center',
            selectable: true
        });
        canvas.add(recipientName);
        
        // Course name
        const workshopName = new fabric.Text('@{{course_name}}', {
            left: canvas.width / 2,
            top: 330,
            fill: '#856404',
            fontFamily: 'Arial',
            fontSize: 24,
            fontWeight: 'bold',
            textAlign: 'center',
            originX: 'center',
            selectable: true
        });
        canvas.add(workshopName);
        
        // Date
        const dateText = new fabric.Text('@{{completion_date}}', {
            left: canvas.width / 2,
            top: 380,
            fill: '#6c757d',
            fontFamily: 'Arial',
            fontSize: 16,
            textAlign: 'center',
            originX: 'center',
            selectable: true
        });
        canvas.add(dateText);
        
    } catch (error) {
        console.error('Error loading participation template:', error);
    }
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

// Completion Template
function loadCompletionTemplate() {
    loadAchievementTemplate(); // Use achievement as base for now
}

// Excellence Template
function loadExcellenceTemplate() {
    loadAchievementTemplate(); // Use achievement as base for now
}

// Training Template
function loadTrainingTemplate() {
    loadAchievementTemplate(); // Use achievement as base for now
}

// Appreciation Template
function loadAppreciationTemplate() {
    loadAchievementTemplate(); // Use achievement as base for now
}

// Recognition Template
function loadRecognitionTemplate() {
    loadAchievementTemplate(); // Use achievement as base for now
}

// Honor Template
function loadHonorTemplate() {
    loadAchievementTemplate(); // Use achievement as base for now
}

// Graduation Template
function loadGraduationTemplate() {
    loadAchievementTemplate(); // Use achievement as base for now
}

// Add signature areas helper function
function addSignatureAreas() {
    // Add signature lines
    const signatureLine1 = new fabric.Line([canvas.width * 0.3 - 60, canvas.height - 120, canvas.width * 0.3 + 60, canvas.height - 120], {
        stroke: '#000000',
        strokeWidth: 1,
        selectable: false
    });
    canvas.add(signatureLine1);
    
    const facilitatorText = new fabric.Text('Course Facilitator', {
        left: canvas.width * 0.3,
        top: canvas.height - 100,
        fill: '#666666',
        fontFamily: 'Arial',
        fontSize: 12,
        textAlign: 'center',
        originX: 'center'
    });
    canvas.add(facilitatorText);
    
    const signatureLine2 = new fabric.Line([canvas.width * 0.7 - 60, canvas.height - 120, canvas.width * 0.7 + 60, canvas.height - 120], {
        stroke: '#000000',
        strokeWidth: 1,
        selectable: false
    });
    canvas.add(signatureLine2);
    
    const coordinatorText = new fabric.Text('Program Coordinator', {
        left: canvas.width * 0.7,
        top: canvas.height - 100,
        fill: '#666666',
        fontFamily: 'Arial',
        fontSize: 12,
        textAlign: 'center',
        originX: 'center'
    });
    canvas.add(coordinatorText);
}
</script>
@endpush