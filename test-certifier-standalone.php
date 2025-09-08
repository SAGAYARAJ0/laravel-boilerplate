<?php
/**
 * Certifier API Test with File Upload Support
 * Place this file in: /public/certifier-with-files-test.php
 */

header('Content-Type: text/html; charset=utf-8');

// Handle file upload
$uploadedFile = null;
$uploadMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    $uploadDir = __DIR__ . '/uploads/';
    
    // Create uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $uploadedFile = $uploadDir . basename($_FILES['test_file']['name']);
    
    if (move_uploaded_file($_FILES['test_file']['tmp_name'], $uploadedFile)) {
        $uploadMessage = "<div class='alert alert-success'>File uploaded successfully: " . basename($_FILES['test_file']['name']) . "</div>";
    } else {
        $uploadMessage = "<div class='alert alert-danger'>File upload failed!</div>";
    }
}

echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .alert { padding: 10px; margin: 10px 0; border-radius: 5px; }
    .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
    .code-block { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; }
    .file-section { border: 1px solid #ddd; padding: 20px; margin: 20px 0; border-radius: 8px; }
</style>";

echo "<h1>🧪 Certifier API Test with Sample Files</h1>";

// Load environment variables
$envFile = __DIR__ . '/../.env';
$config = [];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $config[trim($key)] = trim($value, '"\'');
        }
    }
}

$token = $config['CERTIFIER_TOKEN'] ?? '';

echo $uploadMessage;

// Sample files section
echo "<div class='file-section'>";
echo "<h2>📁 Sample Test Files</h2>";

echo "<h3>1. Upload Test File</h3>";
echo "<form method='POST' enctype='multipart/form-data' style='margin-bottom: 20px;'>";
echo "<input type='file' name='test_file' accept='.csv,.json,.txt' style='margin-right: 10px;' />";
echo "<button type='submit' style='padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 4px;'>Upload File</button>";
echo "</form>";

// Display uploaded file content
if ($uploadedFile && file_exists($uploadedFile)) {
    echo "<h3>📄 Uploaded File Content:</h3>";
    echo "<div class='code-block'>";
    echo "<pre>" . htmlspecialchars(file_get_contents($uploadedFile)) . "</pre>";
    echo "</div>";
}

echo "<h3>2. Download Sample Files</h3>";
echo "<p>Click the buttons below to download sample files for testing:</p>";

// Sample JSON Design
echo "<button onclick='downloadSampleJSON()' style='padding: 10px 15px; background: #28a745; color: white; border: none; border-radius: 4px; margin: 5px;'>📄 Download Sample Design JSON</button>";

// Sample CSV Recipients
echo "<button onclick='downloadSampleCSV()' style='padding: 10px 15px; background: #17a2b8; color: white; border: none; border-radius: 4px; margin: 5px;'>📊 Download Sample Recipients CSV</button>";

// Sample Test Data
echo "<button onclick='downloadTestData()' style='padding: 10px 15px; background: #6f42c1; color: white; border: none; border-radius: 4px; margin: 5px;'>🧪 Download Test Data JSON</button>";

echo "</div>";

// API Test Section
echo "<div class='file-section'>";
echo "<h2>🔧 API Test with Sample Data</h2>";

if (empty($token)) {
    echo "<div class='alert alert-danger'>❌ CERTIFIER_TOKEN not configured. Please add your token to .env file.</div>";
} else {
    echo "<button onclick='testWithSampleData()' style='padding: 12px 20px; background: #fd7e14; color: white; border: none; border-radius: 4px; font-size: 16px;'>🚀 Test API with Sample Data</button>";
    echo "<div id='testResults' style='margin-top: 20px;'></div>";
}

echo "</div>";

// JavaScript for file downloads and API testing
echo "<script>
function downloadSampleJSON() {
    const data = {
        'name': 'Sample Certificate Design',
        'type': 'certificate',
        'format': 'A4',
        'orientation': 'landscape',
        'background_color': '#ffffff',
        'elements': [
            {
                'type': 'text',
                'content': 'Certificate of Achievement',
                'x': 400,
                'y': 150,
                'font_size': 36,
                'font_family': 'Arial',
                'color': '#333333',
                'alignment': 'center'
            },
            {
                'type': 'text',
                'content': 'This is to certify that',
                'x': 400,
                'y': 220,
                'font_size': 16,
                'font_family': 'Arial',
                'color': '#666666',
                'alignment': 'center'
            },
            {
                'type': 'text',
                'content': '{{recipient_name}}',
                'x': 400,
                'y': 280,
                'font_size': 28,
                'font_family': 'Arial',
                'color': '#000000',
                'alignment': 'center',
                'dynamic': true
            }
        ]
    };
    
    downloadFile(JSON.stringify(data, null, 2), 'sample-design.json', 'application/json');
}

function downloadSampleCSV() {
    const csvContent = 'recipient_name,recipient_email,course_name,completion_date,grade\\n' +
        'John Doe,john@example.com,Web Development,2025-09-01,A+\\n' +
        'Jane Smith,jane@example.com,Laravel Framework,2025-09-02,A\\n' +
        'Mike Johnson,mike@example.com,API Integration,2025-09-03,B+\\n' +
        'Sarah Wilson,sarah@example.com,Database Design,2025-09-04,A\\n' +
        'David Brown,david@example.com,Frontend Development,2025-09-05,A-';
    
    downloadFile(csvContent, 'sample-recipients.csv', 'text/csv');
}

function downloadTestData() {
    const testData = {
        'test_credentials': [
            {
                'design_id': 'sample-design-123',
                'recipient': {
                    'name': 'Test User',
                    'email': 'test@example.com'
                },
                'attributes': {
                    'course_name': 'Sample Course',
                    'completion_date': '2025-09-05',
                    'grade': 'A+'
                }
            }
        ],
        'api_endpoints': {
            'designs': '/designs',
            'create_credential': '/credentials',
            'get_credentials': '/credentials'
        }
    };
    
    downloadFile(JSON.stringify(testData, null, 2), 'test-data.json', 'application/json');
}

function downloadFile(content, filename, contentType) {
    const blob = new Blob([content], { type: contentType });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
}

function testWithSampleData() {
    const resultsDiv = document.getElementById('testResults');
    resultsDiv.innerHTML = '<div class=\"alert alert-info\">🔄 Testing API with sample data...</div>';
    
    // This would normally make AJAX calls to your API
    // For demonstration, we'll show what the test would include
    
    setTimeout(() => {
        resultsDiv.innerHTML = `
            <div class=\"alert alert-success\">
                <h4>✅ Sample Data Test Results:</h4>
                <ul>
                    <li><strong>Designs Endpoint:</strong> Would test fetching all designs</li>
                    <li><strong>Search Endpoint:</strong> Would search for 'certificate' designs</li>
                    <li><strong>Create Credential:</strong> Would create certificate for Test User</li>
                    <li><strong>Bulk Processing:</strong> Would process the sample CSV data</li>
                </ul>
                <p><em>Integrate this with your Laravel controller for actual API calls.</em></p>
            </div>
        `;
    }, 2000);
}
</script>";

echo "<hr style='margin: 40px 0;'>";
echo "<h2>🔗 Integration Guide</h2>";
echo "<div class='alert alert-info'>";
echo "<h4>Next Steps:</h4>";
echo "<ol>";
echo "<li><strong>Download sample files</strong> using the buttons above</li>";
echo "<li><strong>Upload test files</strong> to see how file processing works</li>";
echo "<li><strong>Use the sample JSON</strong> to understand design structure</li>";
echo "<li><strong>Use the sample CSV</strong> for bulk certificate testing</li>";
echo "<li><strong>Integrate with your Laravel controller</strong> for actual API calls</li>";
echo "</ol>";
echo "</div>";

?>
