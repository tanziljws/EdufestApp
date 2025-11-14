<?php

// Test dashboard API
$url = "http://localhost:8000/api/admin/dashboard";

echo "Testing Dashboard API\n";
echo "URL: {$url}\n\n";

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status: {$httpCode}\n\n";

if ($httpCode === 200) {
    $data = json_decode($response, true);
    
    echo "=== STATISTICS ===\n";
    echo "Total Events: " . ($data['statistics']['total_events'] ?? 0) . "\n";
    echo "Total Registrations: " . ($data['statistics']['total_registrations'] ?? 0) . "\n";
    echo "Total Attendees: " . ($data['statistics']['total_attendees'] ?? 0) . "\n";
    echo "Attendance Rate: " . ($data['statistics']['attendance_rate'] ?? 0) . "%\n\n";
    
    echo "=== REVENUE ===\n";
    echo "Total Revenue: Rp " . number_format($data['statistics']['total_revenue'] ?? 0, 0, ',', '.') . "\n";
    echo "Admin Revenue: Rp " . number_format($data['statistics']['admin_revenue'] ?? 0, 0, ',', '.') . "\n";
    echo "Panitia Revenue: Rp " . number_format($data['statistics']['panitia_revenue'] ?? 0, 0, ',', '.') . "\n";
    
} else {
    echo "✗ Failed!\n";
    echo "Response: {$response}\n";
}
