<?php
/**
 * BUAT EVENT TEST UNTUK UJI COBA SERTIFIKAT
 * Event ini bisa langsung dicoba di web browser
 */

require_once __DIR__ . '/laravel-event-app/vendor/autoload.php';
$app = require_once __DIR__ . '/laravel-event-app/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Attendance;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║          BUAT EVENT TEST UNTUK SERTIFIKAT                    ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

try {
    // ============================================
    // 1. BUAT/AMBIL USER TEST
    // ============================================
    echo "📝 Step 1: Membuat User Test...\n";
    echo str_repeat("─", 60) . "\n";
    
    $testUser = User::where('email', 'user.test@edufest.com')->first();
    if (!$testUser) {
        $testUser = User::create([
            'name' => 'User Test Sertifikat',
            'email' => 'user.test@edufest.com',
            'password' => bcrypt('password'),
            'role' => 'participant'
        ]);
        echo "✓ User Test DIBUAT\n";
    } else {
        echo "✓ User Test SUDAH ADA\n";
    }
    
    echo "   Email: {$testUser->email}\n";
    echo "   Password: password\n";
    echo "   ID: {$testUser->id}\n\n";
    
    // ============================================
    // 2. BUAT EVENT HARI INI
    // ============================================
    echo "📅 Step 2: Membuat Event Test (Hari Ini)...\n";
    echo str_repeat("─", 60) . "\n";
    
    // Ambil admin untuk created_by
    $admin = User::where('role', 'admin')->first();
    if (!$admin) {
        echo "✗ TIDAK ADA ADMIN! Membuat admin dulu...\n";
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@edufest.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin'
        ]);
        echo "✓ Admin dibuat\n";
    }
    
    $event = Event::create([
        'title' => 'Workshop Sertifikat Test - ' . now()->format('d M Y H:i'),
        'event_date' => now()->toDateString(), // HARI INI
        'start_time' => now()->subHour()->format('H:i:s'), // 1 JAM YANG LALU (sudah mulai)
        'end_time' => now()->addHours(2)->format('H:i:s'),
        'location' => 'Lab Komputer SMKN 4 Bogor',
        'category' => 'teknologi',
        'description' => 'Event test untuk uji coba download sertifikat. Event ini dibuat khusus untuk testing sistem sertifikat.',
        'is_free' => true,
        'status' => 'published',
        'created_by' => $admin->id
    ]);
    
    echo "✓ Event DIBUAT\n";
    echo "   ID: {$event->id}\n";
    echo "   Judul: {$event->title}\n";
    echo "   Tanggal: {$event->event_date}\n";
    echo "   Waktu: {$event->start_time} - {$event->end_time}\n";
    echo "   Status: Event SUDAH DIMULAI (bisa langsung absen)\n\n";
    
    // ============================================
    // 3. DAFTARKAN USER KE EVENT
    // ============================================
    echo "📋 Step 3: Mendaftarkan User ke Event...\n";
    echo str_repeat("─", 60) . "\n";
    
    $tokenAbsensi = 'CERT' . strtoupper(\Illuminate\Support\Str::random(6));
    
    $registration = Registration::create([
        'user_id' => $testUser->id,
        'event_id' => $event->id,
        'status' => 'registered',  // Changed to 'registered' (valid enum value)
        'attendance_token' => $tokenAbsensi,
        'token_hash' => hash('sha256', $tokenAbsensi),
        'token_plain' => $tokenAbsensi,
        'token_sent_at' => now(),
        'name' => $testUser->name,
        'email' => $testUser->email,
        'phone' => '081234567890',
        'motivation' => 'Ingin belajar dan mendapatkan sertifikat'
    ]);
    
    echo "✓ Registrasi BERHASIL\n";
    echo "   Registration ID: {$registration->id}\n";
    echo "   Token Absensi: {$tokenAbsensi}\n";
    echo "   Status: {$registration->status}\n\n";
    
    // ============================================
    // 4. INFORMASI UNTUK UJI COBA
    // ============================================
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                  CARA UJI COBA SERTIFIKAT                    ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    
    $baseUrl = env('APP_URL', 'http://localhost:3000');
    
    echo "🎯 LANGKAH-LANGKAH:\n\n";
    
    echo "1️⃣  LOGIN KE SISTEM\n";
    echo "    • Buka: {$baseUrl}/login\n";
    echo "    • Email: {$testUser->email}\n";
    echo "    • Password: password\n\n";
    
    echo "2️⃣  BUKA HALAMAN EVENT\n";
    echo "    • Setelah login, buka: {$baseUrl}/events/{$event->id}\n";
    echo "    • Atau cari event: \"{$event->title}\"\n\n";
    
    echo "3️⃣  KLIK TOMBOL ABSENSI\n";
    echo "    • Di halaman event detail, klik tombol \"Absensi\"\n";
    echo "    • Masukkan Token Absensi: {$tokenAbsensi}\n";
    echo "    • Klik Submit\n\n";
    
    echo "4️⃣  TUNGGU SERTIFIKAT DI-GENERATE\n";
    echo "    • Setelah absen sukses, tunggu 30-60 detik\n";
    echo "    • Sertifikat sedang dibuat otomatis\n\n";
    
    echo "5️⃣  DOWNLOAD SERTIFIKAT\n";
    echo "    • Buka: {$baseUrl}/profile?section=certificates\n";
    echo "    • Atau klik menu \"Sertifikat Saya\" di profile\n";
    echo "    • Klik tombol \"Download\" pada sertifikat\n";
    echo "    • File PDF akan terdownload\n\n";
    
    echo "═══════════════════════════════════════════════════════════════\n\n";
    
    echo "📌 INFORMASI PENTING:\n\n";
    echo "✓ Event ID: {$event->id}\n";
    echo "✓ User Email: {$testUser->email}\n";
    echo "✓ User Password: password\n";
    echo "✓ Token Absensi: {$tokenAbsensi}\n";
    echo "✓ Event Status: SUDAH DIMULAI (bisa langsung absen)\n";
    echo "✓ Registrasi Status: CONFIRMED\n\n";
    
    echo "⚠️  CATATAN:\n";
    echo "• Event ini dibuat dengan tanggal HARI INI\n";
    echo "• Waktu mulai: 1 jam yang lalu (sudah bisa absen)\n";
    echo "• Token absensi sudah disiapkan\n";
    echo "• Setelah absen, sertifikat otomatis di-generate\n";
    echo "• Jika queue=sync, sertifikat langsung jadi\n";
    echo "• Jika queue=database, jalankan: php artisan queue:work\n\n";
    
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║                    ✅ SIAP UNTUK DICOBA!                     ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n\n";
    
    // ============================================
    // 5. CEK KONFIGURASI QUEUE
    // ============================================
    echo "🔧 Konfigurasi Queue:\n";
    $queueConnection = env('QUEUE_CONNECTION', 'sync');
    echo "   QUEUE_CONNECTION: {$queueConnection}\n";
    
    if ($queueConnection === 'sync') {
        echo "   ✓ Mode SYNC: Sertifikat langsung di-generate\n";
    } else {
        echo "   ⚠️  Mode {$queueConnection}: Perlu jalankan queue worker\n";
        echo "   Jalankan: php artisan queue:work\n";
    }
    
    echo "\n";
    
    // ============================================
    // 6. SIMPAN INFO KE FILE
    // ============================================
    $infoFile = __DIR__ . '/TEST_SERTIFIKAT_INFO.txt';
    $info = "=== INFO EVENT TEST SERTIFIKAT ===\n\n";
    $info .= "Dibuat pada: " . now()->format('Y-m-d H:i:s') . "\n\n";
    $info .= "LOGIN:\n";
    $info .= "Email: {$testUser->email}\n";
    $info .= "Password: password\n\n";
    $info .= "EVENT:\n";
    $info .= "ID: {$event->id}\n";
    $info .= "Judul: {$event->title}\n";
    $info .= "URL: {$baseUrl}/events/{$event->id}\n\n";
    $info .= "TOKEN ABSENSI:\n";
    $info .= "{$tokenAbsensi}\n\n";
    $info .= "LANGKAH:\n";
    $info .= "1. Login dengan email & password di atas\n";
    $info .= "2. Buka URL event\n";
    $info .= "3. Klik Absensi\n";
    $info .= "4. Masukkan token absensi\n";
    $info .= "5. Tunggu 30-60 detik\n";
    $info .= "6. Buka /profile?section=certificates\n";
    $info .= "7. Download sertifikat\n";
    
    file_put_contents($infoFile, $info);
    echo "📄 Info disimpan ke: TEST_SERTIFIKAT_INFO.txt\n\n";
    
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "Selesai! Silakan buka browser dan mulai testing.\n";
    echo "═══════════════════════════════════════════════════════════════\n";
    
} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    echo "Stack Trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
