<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificate</title>
    <style>
        @page {
            margin: 0;
            size: {{ $template->orientation === 'portrait' ? 'A4 portrait' : 'A4 landscape' }};
        }
        
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            position: relative;
            background-color: {{ $template->background_color ?? '#ffffff' }};
        }
        
        .certificate-container {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
            box-sizing: border-box;
        }
        
        .certificate-element {
            position: absolute;
            box-sizing: border-box;
        }
        
        .text-element {
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.2;
            display: block;
            box-sizing: border-box;
            overflow: hidden;
        }
        
        .image-element {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        
        .rectangle-element {
            border-style: solid;
            box-sizing: border-box;
        }
        
        .line-element {
            position: absolute;
            box-sizing: border-box;
        }
        
        .circle-element {
            border-radius: 50%;
            box-sizing: border-box;
        }
        
        .triangle-element {
            width: 0;
            height: 0;
            position: absolute;
            box-sizing: border-box;
        }

        /* Fallback styles for better PDF rendering */
        .fallback-certificate {
            width: 100%;
            height: 100%;
            padding: 40px;
            text-align: center;
            border: 20px solid #DAA520;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .fallback-title {
            color: #1a237e;
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 30px;
            font-family: DejaVu Sans, Arial, sans-serif;
        }
        
        .fallback-text {
            font-size: 20px;
            margin-bottom: 20px;
            font-family: DejaVu Sans, Arial, sans-serif;
        }
        
        .fallback-name {
            font-size: 32px;
            font-weight: bold;
            margin: 20px 0;
            color: #000;
            font-family: DejaVu Sans, Arial, sans-serif;
        }
        
        .fallback-course {
            font-size: 24px;
            color: #1a237e;
            margin: 15px 0;
            font-family: DejaVu Sans, Arial, sans-serif;
        }
        
        .fallback-date {
            font-size: 16px;
            margin-top: 30px;
            font-family: DejaVu Sans, Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        @if($template->background_image && file_exists(public_path('storage/' . $template->background_image)))
            <img src="{{ public_path('storage/' . $template->background_image) }}" 
                 style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; object-fit: cover;">
        @endif
        
        
        {{-- Debug: Log template processing --}}
        @php
            \Log::info('PDF Template Processing', [
                'template_id' => $template->id,
                'has_processed_template' => isset($processedTemplate['objects']),
                'objects_count' => isset($processedTemplate['objects']) ? count($processedTemplate['objects']) : 0,
                'data_provided' => $data,
                'sample_text_objects' => collect($processedTemplate['objects'] ?? [])->where('type', 'text')->take(3)->toArray()
            ]);
        @endphp
        
        @if(isset($processedTemplate['objects']) && count($processedTemplate['objects']) > 0)
            @php
                // Get template dimensions from Fabric.js canvas
                $canvasWidth = $template->width ?? 800;
                $canvasHeight = $template->height ?? 600;
                
                // PDF page dimensions in points (72 DPI)
                if ($template->orientation === 'portrait') {
                    $pageWidth = 595.28;  // A4 portrait width
                    $pageHeight = 841.89; // A4 portrait height
                } else {
                    $pageWidth = 841.89;  // A4 landscape width
                    $pageHeight = 595.28; // A4 landscape height
                }
                
                // Use 1:1 scale to match Fabric.js coordinates exactly
                // Calculate scale to fit canvas within PDF page
                $scaleX = $pageWidth / $canvasWidth;
                $scaleY = $pageHeight / $canvasHeight;
                $scale = min($scaleX, $scaleY);
                
                // Center the scaled canvas on the page
                $offsetX = ($pageWidth - ($canvasWidth * $scale)) / 2;
                $offsetY = ($pageHeight - ($canvasHeight * $scale)) / 2;
            @endphp
            
            @foreach($processedTemplate['objects'] as $object)
                @php
                    // Apply scaling and offset to dimensions and positions
                    $left = $offsetX + (isset($object['left']) ? $object['left'] * $scale : 0);
                    $top = $offsetY + (isset($object['top']) ? $object['top'] * $scale : 0);
                    $width = isset($object['width']) ? $object['width'] * $scale : null;
                    $height = isset($object['height']) ? $object['height'] * $scale : null;
                    $fontSize = isset($object['fontSize']) ? max($object['fontSize'] * $scale, 8) : 16; // Min font size 8pt
                    $strokeWidth = isset($object['strokeWidth']) ? max($object['strokeWidth'] * $scale, 0.5) : 0;
                    $radius = isset($object['radius']) ? $object['radius'] * $scale : null;
                @endphp
                
                @if($object['type'] === 'text' || $object['type'] === 'i-text')
                    @php
                        $textAlign = $object['textAlign'] ?? 'left';
                        
                        // In Fabric.js, text positioning works differently based on alignment
                        // For PDF, we need to adjust the positioning to match Fabric.js behavior
                        $adjustedLeft = $left;
                        
                        // Calculate text width
                        if (isset($width) && $width > 0) {
                            $textWidth = $width;
                        } else {
                            // Estimate width based on text length and font size
                            $textLength = strlen($object['text'] ?? '');
                            $textWidth = $textLength * ($fontSize * 0.6);
                            $textWidth = max($textWidth, 50); // Minimum width
                        }
                        
                        // Adjust left position based on text alignment to match Fabric.js
                        if ($textAlign === 'center') {
                            // For center alignment, Fabric.js centers the text at the given position
                            $adjustedLeft = $left - ($textWidth / 2);
                        } elseif ($textAlign === 'right') {
                            // For right alignment, Fabric.js aligns text to end at the given position
                            $adjustedLeft = $left - $textWidth;
                        }
                        // For left alignment, use position as-is
                    @endphp
                    <div class="certificate-element text-element" style="
                        position: absolute;
                        left: {{ $adjustedLeft }}px;
                        top: {{ $top }}px;
                        width: {{ $textWidth }}px;
                        @if(isset($height) && $height > 0)
                            height: {{ $height }}px;
                        @else
                            min-height: {{ $fontSize * 1.2 }}px;
                        @endif
                        font-family: DejaVu Sans, '{{ $object['fontFamily'] ?? 'Arial' }}', sans-serif;
                        font-size: {{ $fontSize }}px;
                        font-weight: {{ $object['fontWeight'] ?? 'normal' }};
                        font-style: {{ $object['fontStyle'] ?? 'normal' }};
                        color: {{ $object['fill'] ?? '#000000' }};
                        text-align: left;
                        line-height: {{ isset($object['lineHeight']) ? $object['lineHeight'] : '1.2' }};
                        overflow: visible;
                        word-wrap: break-word;
                        white-space: pre-wrap;
                        display: block;
                        @if(isset($object['angle']) && $object['angle'] != 0)
                            transform: rotate({{ $object['angle'] }}deg);
                            transform-origin: {{ $textAlign === 'center' ? 'center center' : ($textAlign === 'right' ? 'right center' : 'left top') }};
                        @endif
                        @if(isset($object['shadow']))
                            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
                        @endif
                        z-index: {{ $loop->index + 1 }};
                    ">{{ $object['text'] ?? '' }}</div>
                @elseif($object['type'] === 'rect')
                    <div class="certificate-element rectangle-element" style="
                        left: {{ $left }}px;
                        top: {{ $top }}px;
                        width: {{ $width ?? 100 }}px;
                        height: {{ $height ?? 100 }}px;
                        background-color: {{ $object['fill'] ?? 'transparent' }};
                        border-color: {{ $object['stroke'] ?? 'transparent' }};
                        border-width: {{ $strokeWidth }}px;
                        border-style: {{ isset($object['strokeDashArray']) ? 'dashed' : 'solid' }};
                        @if(isset($object['rx']) && $object['rx'] > 0)
                            border-radius: {{ $object['rx'] * $scale }}px;
                        @endif
                        @if(isset($object['angle']) && $object['angle'] != 0)
                            transform: rotate({{ $object['angle'] }}deg);
                            transform-origin: left top;
                        @endif
                        z-index: {{ $loop->index + 1 }};
                    ">
                    </div>
                @elseif($object['type'] === 'circle' && isset($radius))
                    <div class="certificate-element circle-element" style="
                        left: {{ $left - $radius }}px;
                        top: {{ $top - $radius }}px;
                        width: {{ $radius * 2 }}px;
                        height: {{ $radius * 2 }}px;
                        background-color: {{ $object['fill'] ?? 'transparent' }};
                        border-color: {{ $object['stroke'] ?? 'transparent' }};
                        border-width: {{ $strokeWidth }}px;
                        border-style: solid;
                        z-index: {{ $loop->index + 1 }};
                    ">
                    </div>
                @elseif($object['type'] === 'triangle' && isset($width) && isset($height))
                    <div class="certificate-element triangle-element" style="
                        left: {{ $left }}px;
                        top: {{ $top }}px;
                        border-left: {{ $width / 2 }}px solid transparent;
                        border-right: {{ $width / 2 }}px solid transparent;
                        border-bottom: {{ $height }}px solid {{ $object['fill'] ?? '#000000' }};
                        @if(isset($object['angle']) && $object['angle'] != 0)
                            transform: rotate({{ $object['angle'] }}deg);
                            transform-origin: left top;
                        @endif
                        z-index: {{ $loop->index + 1 }};
                    ">
                    </div>
                @elseif($object['type'] === 'line')
                    @php
                        $x1 = $offsetX + (isset($object['x1']) ? $object['x1'] * $scale : 0);
                        $y1 = $offsetY + (isset($object['y1']) ? $object['y1'] * $scale : 0);
                        $x2 = $offsetX + (isset($object['x2']) ? $object['x2'] * $scale : 0);
                        $y2 = $offsetY + (isset($object['y2']) ? $object['y2'] * $scale : 0);
                        
                        $lineWidth = sqrt(pow($x2 - $x1, 2) + pow($y2 - $y1, 2));
                        $lineLeft = $x1;
                        $lineTop = $y1;
                        
                        // Calculate angle for the line
                        $angle = 0;
                        if ($x2 != $x1 || $y2 != $y1) {
                            $angle = rad2deg(atan2($y2 - $y1, $x2 - $x1));
                        }
                    @endphp
                    
                    <div class="certificate-element line-element" style="
                        left: {{ $lineLeft }}px;
                        top: {{ $lineTop }}px;
                        width: {{ $lineWidth }}px;
                        height: {{ $strokeWidth }}px;
                        background-color: {{ $object['stroke'] ?? '#000000' }};
                        transform: rotate({{ $angle }}deg);
                        transform-origin: left top;
                        z-index: {{ $loop->index + 1 }};
                    ">
                    </div>
                @elseif($object['type'] === 'image' && isset($object['src']))
                    <img class="certificate-element image-element" 
                         src="{{ $object['src'] }}" 
                         style="
                            left: {{ $left }}px;
                            top: {{ $top }}px;
                            @if(isset($width))
                                width: {{ $width }}px;
                            @endif
                            @if(isset($height))
                                height: {{ $height }}px;
                            @endif
                            @if(isset($object['angle']) && $object['angle'] != 0)
                                transform: rotate({{ $object['angle'] }}deg);
                                transform-origin: left top;
                            @endif
                            z-index: {{ $loop->index + 1 }};
                         ">
                @endif
            @endforeach
        @else
            <!-- Fallback certificate design -->
            <div class="fallback-certificate">
                <h1 class="fallback-title">CERTIFICATE OF ACHIEVEMENT</h1>
                <p class="fallback-text">This is to certify that</p>
                <h2 class="fallback-name">{{ $data['recipient_name'] ?? 'Recipient Name' }}</h2>
                <p class="fallback-text">has successfully completed</p>
                <h3 class="fallback-course">{{ $data['course_name'] ?? 'Course Name' }}</h3>
                <p class="fallback-text">on {{ isset($data['completion_date']) ? \Carbon\Carbon::parse($data['completion_date'])->format('F j, Y') : date('F j, Y') }} with distinction.</p>
                
                <div style="margin-top: 50px; width: 100%; display: flex; justify-content: space-around;">
                    <div style="text-align: center;">
                        <div style="border-top: 1px solid #000; width: 200px; margin: 0 auto;"></div>
                        <p style="margin-top: 5px;">Course Facilitator</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="border-top: 1px solid #000; width: 200px; margin: 0 auto;"></div>
                        <p style="margin-top: 5px;">Program Coordinator</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>