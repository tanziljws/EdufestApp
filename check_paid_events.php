<?php

require 'laravel-event-app/vendor/autoload.php';
$app = require_once 'laravel-event-app/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Event Berbayar ===\n\n";

$events = App\Models\Event::where('is_free', false)
    ->where('price', '>', 0)
    ->get(['id', 'title', 'price']);

if ($events->isEmpty()) {
    echo "Tidak ada event berbayar.\n";
} else {
    foreach($events as $e) {
        echo "ID: {$e->id} | {$e->title} | Rp " . number_format($e->price, 0, ',', '.') . "\n";
    }
}
