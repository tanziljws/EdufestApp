<?php

// Test export API
$eventId = 28; // Ganti dengan ID event yang mau di-test
$url = "http://localhost:8000/api/admin/events/{$eventId}/export-participants?format=csv";

echo "Testing export for Event ID: {$eventId}\n";
echo "URL: {$url}\n\n";

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: text/csv',
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status: {$httpCode}\n\n";

if ($httpCode === 200) {
    // Save to file
    $filename = "export_event_{$eventId}_" . date('Y-m-d_His') . ".csv";
    file_put_contents($filename, $response);
    
    echo "✓ Export successful!\n";
    echo "File saved: {$filename}\n\n";
    
    // Show preview
    echo "=== Preview (first 20 lines) ===\n";
    $lines = explode("\n", $response);
    $preview = array_slice($lines, 0, 20);
    echo implode("\n", $preview);
    
    echo "\n\n=== Summary (last 10 lines) ===\n";
    $summary = array_slice($lines, -10);
    echo implode("\n", $summary);
    
} else {
    echo "✗ Export failed!\n";
    echo "Response: {$response}\n";
}
