<?php
// Simple test to verify web server is working
echo json_encode([
    'success' => true,
    'message' => 'Web server is working',
    'timestamp' => date('Y-m-d H:i:s'),
    'file_updated' => filemtime(__FILE__)
]);
?>
