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
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            width: {{ $template->width ?? 800 }}px;
            height: {{ $template->height ?? 600 }}px;
            position: relative;
            background-color: {{ $processedTemplate['background'] ?? $processedTemplate['backgroundColor'] ?? $template->background_color ?? '#8B4513' }};
            overflow: hidden;
        }
        
        .certificate-container {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
            box-sizing: border-box;
            background-color: inherit;
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
            overflow: visible;
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
    </style>
</head>
<body>
    <div class="certificate-container">
        @php
            // Extract background color from Fabric.js canvas data
            $bgColor = '#ffffff'; // Default
            
            // Check multiple possible sources for background color
            if (isset($processedTemplate['background'])) {
                $bgColor = $processedTemplate['background'];
            } elseif (isset($processedTemplate['backgroundColor'])) {
                $bgColor = $processedTemplate['backgroundColor'];
            } elseif (isset($template->template_data['background'])) {
                $bgColor = $template->template_data['background'];
            } elseif (isset($template->template_data['backgroundColor'])) {
                $bgColor = $template->template_data['backgroundColor'];
            } elseif ($template->background_color) {
                $bgColor = $template->background_color;
            }
            
            \Log::info('PNG Template Background Color Debug', [
                'final_bg_color' => $bgColor,
                'template_bg_color' => $template->background_color ?? 'null',
                'template_data_bg' => $template->template_data['background'] ?? 'null',
                'processed_bg' => $processedTemplate['background'] ?? 'null'
            ]);
        @endphp
        
        <!-- Apply background color directly to container -->
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: {{ $bgColor }}; z-index: -2;"></div>
        
        @if($template->background_image && file_exists(public_path('storage/' . $template->background_image)))
            <img src="{{ asset('storage/' . $template->background_image) }}" 
                 style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; object-fit: cover;">
        @endif
        
        @if(isset($processedTemplate['objects']) && count($processedTemplate['objects']) > 0)
            @foreach($processedTemplate['objects'] as $object)
                @php
                    // Use original coordinates without scaling for HTML display
                    $left = $object['left'] ?? 0;
                    $top = $object['top'] ?? 0;
                    $width = $object['width'] ?? null;
                    $height = $object['height'] ?? null;
                    $fontSize = $object['fontSize'] ?? 16;
                    $strokeWidth = $object['strokeWidth'] ?? 1;
                    $radius = $object['radius'] ?? null;
                @endphp
                
                @if($object['type'] === 'text' || $object['type'] === 'i-text')
                    @php
                        $textAlign = $object['textAlign'] ?? 'left';
                        
                        // Use original positioning for HTML display
                        $adjustedLeft = $left;
                        
                        // Calculate text width
                        if (isset($width) && $width > 0) {
                            $textWidth = $width;
                        } else {
                            // Estimate width based on text length and font size
                            $textLength = strlen($object['text'] ?? '');
                            $textWidth = $textLength * ($fontSize * 0.6);
                            $textWidth = max($textWidth, 50);
                        }
                        
                        // Adjust left position based on text alignment to match Fabric.js
                        if ($textAlign === 'center') {
                            $adjustedLeft = $left - ($textWidth / 2);
                        } elseif ($textAlign === 'right') {
                            $adjustedLeft = $left - $textWidth;
                        }
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
                        font-family: '{{ $object['fontFamily'] ?? 'Arial' }}', sans-serif;
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
                            border-radius: {{ $object['rx'] }}px;
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
                        $x1 = $object['x1'] ?? 0;
                        $y1 = $object['y1'] ?? 0;
                        $x2 = $object['x2'] ?? 0;
                        $y2 = $object['y2'] ?? 0;
                        
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
            <div style="padding: 40px; text-align: center; border: 20px solid #DAA520; box-sizing: border-box; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                <h1 style="color: #1a237e; font-size: 36px; font-weight: bold; margin-bottom: 30px;">CERTIFICATE OF ACHIEVEMENT</h1>
                <p style="font-size: 20px; margin-bottom: 20px;">This is to certify that</p>
                <h2 style="font-size: 32px; font-weight: bold; margin: 20px 0; color: #000;">{{ $data['recipient_name'] ?? 'Recipient Name' }}</h2>
                <p style="font-size: 20px; margin-bottom: 20px;">has successfully completed</p>
                <h3 style="font-size: 24px; color: #1a237e; margin: 15px 0;">{{ $data['course_name'] ?? 'Course Name' }}</h3>
                <p style="font-size: 20px;">on {{ isset($data['completion_date']) ? \Carbon\Carbon::parse($data['completion_date'])->format('F j, Y') : date('F j, Y') }} with distinction.</p>
                
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

    <!-- JavaScript for PNG conversion -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        // Auto-convert to PNG when page loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                html2canvas(document.querySelector('.certificate-container'), {
                    useCORS: true,
                    allowTaint: true,
                    scale: 2,
                    width: {{ $template->width ?? 800 }},
                    height: {{ $template->height ?? 600 }}
                }).then(function(canvas) {
                    // Convert canvas to PNG
                    const link = document.createElement('a');
                    link.download = 'certificate-{{ $data["recipient_name"] ?? "certificate" }}.png';
                    link.href = canvas.toDataURL('image/png');
                    
                    // Auto-download
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
            }, 1000); // Wait 1 second for fonts to load
        });
    </script>
</body>
</html>
