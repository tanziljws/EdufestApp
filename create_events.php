<?php
// Simple script to create test events directly in database
require_once 'laravel-event-app/vendor/autoload.php';

// Load Laravel environment
$app = require_once 'laravel-event-app/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

try {
    // Get or create admin user
    $admin = User::where('email', 'admin@smkn4bogor.sch.id')->first();
    if (!$admin) {
        $admin = User::create([
            'name' => 'Admin SMKN 4',
            'email' => 'admin@smkn4bogor.sch.id',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'email_verified_at' => now()
        ]);
        echo "Admin user created!\n";
    }

    // Clear existing events
    Event::truncate();
    echo "Cleared existing events.\n";

    // Create test events
    $events = [
        [
            'title' => 'Programming Competition 2025 - Testing OTP',
            'description' => 'Event khusus untuk testing sistem OTP dan sertifikat. Kompetisi pemrograman dengan sistem absensi digital menggunakan token email.',
            'event_date' => Carbon::today()->format('Y-m-d'),
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'location' => 'Lab Computer SMKN 4 Bogor',
            'price' => 0,
            'is_free' => true,
            'is_published' => true,
            'created_by' => $admin->id,
            'registration_closes_at' => Carbon::tomorrow()
        ],
        [
            'title' => 'Workshop Digital Marketing',
            'description' => 'Pelatihan digital marketing dengan sistem sertifikat otomatis. Event untuk testing flow lengkap dari pendaftaran hingga download sertifikat.',
            'event_date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '15:00:00',
            'location' => 'Ruang Multimedia SMKN 4 Bogor',
            'price' => 25000,
            'is_free' => false,
            'is_published' => true,
            'created_by' => $admin->id,
            'registration_closes_at' => Carbon::now()->addDays(2)
        ],
        [
            'title' => 'Seminar Teknologi AI',
            'description' => 'Seminar tentang perkembangan AI dan machine learning. Cocok untuk testing sistem email OTP dan verifikasi kehadiran.',
            'event_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'start_time' => '13:00:00',
            'end_time' => '17:00:00',
            'location' => 'Auditorium SMKN 4 Bogor',
            'price' => 0,
            'is_free' => true,
            'is_published' => true,
            'created_by' => $admin->id,
            'registration_closes_at' => Carbon::now()->addDays(5)
        ]
    ];

    foreach ($events as $eventData) {
        $event = Event::create($eventData);
        echo "Created event: {$event->title} (ID: {$event->id})\n";
    }

    echo "\n✅ Successfully created " . count($events) . " test events!\n";
    echo "🎯 Event pertama hari ini - siap untuk testing OTP flow!\n";
    echo "📧 Pastikan email SMTP sudah dikonfigurasi di .env\n";
    echo "🔄 Jalankan queue worker: php artisan queue:work\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
