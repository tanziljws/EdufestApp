<?php
require_once __DIR__ . '/laravel-event-app/vendor/autoload.php';
$app = require_once __DIR__ . '/laravel-event-app/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$columns = \DB::select("SHOW COLUMNS FROM users WHERE Field = 'role'");
print_r($columns);
