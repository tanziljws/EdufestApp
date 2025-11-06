<?php
require_once __DIR__ . '/laravel-event-app/vendor/autoload.php';

$app = require_once __DIR__ . '/laravel-event-app/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Event;
use App\Models\User;
use App\Models\Registration;

echo "=== TEST PDF GENERATION ===\n\n";

// Check if dompdf is installed
if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
    echo "✓ DomPDF Package: INSTALLED\n";
} else {
    echo "✗ DomPDF Package: NOT FOUND\n";
    exit(1);
}

// Check if PDF facade is available
try {
    $pdf = \PDF::getFacadeRoot();
    echo "✓ PDF Facade: AVAILABLE\n";
} catch (\Exception $e) {
    echo "✗ PDF Facade: NOT AVAILABLE\n";
    echo "  Error: " . $e->getMessage() . "\n";
}

// Try to generate a simple PDF
try {
    $testData = [
        'name' => 'Test User',
        'event' => (object)[
            'title' => 'Test Event',
            'event_date' => now()->toDateString()
        ],
        'serial' => 'CERT-TEST-12345678',
        'date' => now()->toDateString()
    ];
    
    echo "\n=== TESTING PDF GENERATION ===\n";
    echo "Creating test PDF with data:\n";
    echo "  Name: {$testData['name']}\n";
    echo "  Event: {$testData['event']->title}\n";
    echo "  Serial: {$testData['serial']}\n";
    
    $pdf = \PDF::loadView('pdf.certificate', $testData);
    echo "✓ PDF View Loaded Successfully\n";
    
    // Try to output PDF
    $output = $pdf->output();
    echo "✓ PDF Generated Successfully\n";
    echo "  PDF Size: " . number_format(strlen($output) / 1024, 2) . " KB\n";
    
    // Try to save PDF
    $testPath = storage_path('app/public/certificates/test-certificate.pdf');
    $dir = dirname($testPath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "✓ Created certificates directory\n";
    }
    
    file_put_contents($testPath, $output);
    echo "✓ PDF Saved to: $testPath\n";
    
    if (file_exists($testPath)) {
        echo "✓ File exists and size: " . number_format(filesize($testPath) / 1024, 2) . " KB\n";
    }
    
    echo "\n=== SUCCESS ===\n";
    echo "PDF generation is working correctly!\n";
    echo "You can now generate certificates automatically.\n";
    
} catch (\Exception $e) {
    echo "\n✗ PDF Generation FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
