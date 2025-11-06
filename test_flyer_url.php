<?php
require_once __DIR__ . '/laravel-event-app/vendor/autoload.php';

$app = require_once __DIR__ . '/laravel-event-app/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Event;

echo "=== TEST FLYER URL ACCESS ===\n\n";

$eventsWithFlyer = Event::whereNotNull('flyer_path')
    ->where('flyer_path', '!=', '')
    ->orderBy('id', 'desc')
    ->limit(5)
    ->get(['id', 'title', 'flyer_path']);

echo "Events dengan Flyer:\n";
echo str_repeat('-', 120) . "\n";

foreach ($eventsWithFlyer as $event) {
    $flyerUrl = url('storage/' . $event->flyer_path);
    $fullPath = __DIR__ . '/laravel-event-app/public/storage/' . $event->flyer_path;
    $fileExists = file_exists($fullPath);
    
    echo "ID: {$event->id}\n";
    echo "Title: {$event->title}\n";
    echo "Flyer Path: {$event->flyer_path}\n";
    echo "Flyer URL: {$flyerUrl}\n";
    echo "File Exists: " . ($fileExists ? "✓ YES" : "✗ NO") . "\n";
    if ($fileExists) {
        echo "File Size: " . number_format(filesize($fullPath) / 1024, 2) . " KB\n";
    }
    echo str_repeat('-', 120) . "\n";
}

echo "\n=== CARA AKSES FLYER ===\n";
echo "1. Pastikan Laravel server running: php artisan serve\n";
echo "2. Akses via browser: http://127.0.0.1:8000/storage/flyers/[filename].jpg\n";
echo "3. Atau via React frontend yang akan fetch dari API\n";
