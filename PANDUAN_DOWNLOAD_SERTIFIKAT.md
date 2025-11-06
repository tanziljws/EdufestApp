# 📜 PANDUAN LENGKAP DOWNLOAD SERTIFIKAT - EDUFEST

## 🎯 CARA KERJA SISTEM SERTIFIKAT

### **Alur Lengkap:**
```
1. User Daftar Event
   ↓
2. User Hadir & Absen (dengan token)
   ↓
3. Sistem Auto-Generate Sertifikat PDF (30-60 detik)
   ↓
4. User Download Sertifikat
```

---

## ✅ LANGKAH-LANGKAH UNTUK MENDAPATKAN SERTIFIKAT

### **STEP 1: Daftar Event**
1. Login ke sistem
2. Buka halaman **Events** (`/events`)
3. Pilih event yang ingin diikuti
4. Klik **"Daftar"**
5. Isi form pendaftaran
6. Submit form
7. **Token Absensi** akan dikirim ke email Anda

### **STEP 2: Hadiri Event**
1. Datang ke lokasi event sesuai jadwal
2. Catat **Token Absensi** dari email

### **STEP 3: Absensi**
1. Pada hari H event, setelah jam mulai
2. Buka halaman detail event
3. Klik tombol **"Absensi"**
4. Masukkan **Token Absensi**
5. Klik **"Submit"**
6. Tunggu pesan sukses: "Absensi berhasil! Sertifikat sedang dibuat..."

### **STEP 4: Download Sertifikat**
1. Tunggu **30-60 detik** (sertifikat sedang di-generate)
2. Buka halaman **"Sertifikat Saya"** (`/profile?section=certificates`)
3. Lihat daftar sertifikat Anda
4. Klik tombol **"Download"** pada sertifikat yang diinginkan
5. File PDF akan terdownload dengan nama: `Sertifikat_CERT-2025-XXXXXXXX.pdf`

---

## 🔧 BACKEND API ENDPOINTS

### **1. Get My Certificates (User Login Required)**
```
GET /api/me/certificates
Headers: Authorization: Bearer {token}

Response:
[
  {
    "id": 1,
    "registration_id": 5,
    "serial_number": "CERT-2025-ABC12345",
    "file_path": "certificates/CERT-2025-ABC12345.pdf",
    "issued_at": "2025-01-20T10:30:00.000000Z",
    "registration": {
      "event": {
        "title": "Programming Competition 2025"
      }
    }
  }
]
```

### **2. Download Certificate (Public)**
```
GET /api/certificates/{certificate_id}/download

Response: PDF File Download
Filename: Sertifikat_CERT-2025-XXXXXXXX.pdf
```

### **3. Search Certificate (Public)**
```
GET /api/certificates/search?q=CERT-2025-ABC12345

Response:
{
  "message": "Search completed",
  "data": [...],
  "count": 1
}
```

---

## 📂 STRUKTUR FILE SERTIFIKAT

### **Lokasi Penyimpanan:**
```
laravel-event-app/
└── storage/
    └── app/
        └── public/
            └── certificates/
                ├── CERT-2025-ABC12345.pdf
                ├── CERT-2025-XYZ67890.pdf
                └── ...
```

### **Akses Public:**
```
http://127.0.0.1:8000/storage/certificates/CERT-2025-ABC12345.pdf
```

---

## 🎨 TEMPLATE SERTIFIKAT

### **Template yang Tersedia:**
1. **certificate.blade.php** (Default) - Design dengan ribbon emas, medali, ornamen
2. **certificate_modern.blade.php** (Modern) - Design modern minimalis
3. **certificate_classic.blade.php** (Classic) - Design klasik formal

### **Template Default (certificate.blade.php):**
- ✅ Ribbon emas di pojok kiri
- ✅ Medali emas dengan bintang
- ✅ Ornamen gelombang biru-emas di bawah
- ✅ Layout A4 Landscape
- ✅ Tanda tangan 2 kolom (Kepala Sekolah & Wakil)
- ✅ Serial number unik
- ✅ Tanggal terbit

---

## 🧪 CARA TEST SISTEM

### **Test 1: Generate Sertifikat Manual**
Jalankan script test:
```bash
cd c:\xampp\htdocs\EduFest
php test_pdf_generation.php
```

Output yang diharapkan:
```
=== TEST PDF GENERATION ===
✓ DomPDF Package: INSTALLED
✓ PDF Facade: AVAILABLE
✓ PDF View Loaded Successfully
✓ PDF Generated Successfully
  PDF Size: 3.62 KB
✓ PDF Saved to: storage/app/public/certificates/test-certificate.pdf
=== SUCCESS ===
```

### **Test 2: Test dengan Data Real**

#### **A. Buat Event Test (via Admin)**
1. Login sebagai admin
2. Buat event baru dengan tanggal hari ini
3. Catat ID event

#### **B. Daftar Event (via User)**
1. Login sebagai user
2. Daftar ke event yang dibuat
3. Catat token absensi dari email/database

#### **C. Absensi**
1. Buka halaman event detail
2. Klik "Absensi"
3. Masukkan token
4. Submit

#### **D. Cek Sertifikat**
1. Tunggu 30-60 detik
2. Buka `/profile?section=certificates`
3. Lihat sertifikat muncul
4. Klik "Download"
5. Buka PDF yang terdownload

---

## 🔍 TROUBLESHOOTING

### **Problem 1: Sertifikat Tidak Muncul**

**Kemungkinan Penyebab:**
- Belum absen
- Queue belum running (jika pakai queue database)
- PDF generation gagal

**Solusi:**
1. Cek apakah sudah absen:
```sql
SELECT * FROM attendances WHERE user_id = {your_user_id};
```

2. Cek apakah sertifikat sudah dibuat:
```sql
SELECT * FROM certificates WHERE registration_id = {your_registration_id};
```

3. Cek log Laravel:
```bash
tail -f storage/logs/laravel.log
```

4. Jika pakai queue database, jalankan:
```bash
php artisan queue:work
```

### **Problem 2: Download Error**

**Kemungkinan Penyebab:**
- File PDF tidak ada
- Permission error
- Symlink belum dibuat

**Solusi:**
1. Cek file exists:
```bash
ls storage/app/public/certificates/
```

2. Buat symlink:
```bash
php artisan storage:link
```

3. Set permission:
```bash
chmod -R 775 storage/app/public/certificates/
```

### **Problem 3: PDF Kosong atau Error**

**Kemungkinan Penyebab:**
- Template blade error
- Data tidak lengkap
- DomPDF error

**Solusi:**
1. Test template manual:
```bash
php artisan tinker
```
```php
$pdf = \PDF::loadView('pdf.certificate', [
    'name' => 'Test User',
    'event' => (object)['title' => 'Test Event', 'event_date' => now()],
    'serial' => 'TEST-123',
    'date' => now()->toDateString()
]);
$pdf->save(storage_path('app/public/certificates/test.pdf'));
```

2. Cek error di log

---

## ⚙️ KONFIGURASI

### **1. Queue Configuration**

**Development (Sync - Langsung):**
```env
QUEUE_CONNECTION=sync
```

**Production (Database - Background):**
```env
QUEUE_CONNECTION=database
```

Jika pakai database queue, jalankan:
```bash
php artisan queue:table
php artisan migrate
php artisan queue:work
```

### **2. Storage Configuration**

Pastikan symlink sudah dibuat:
```bash
php artisan storage:link
```

Cek permission:
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### **3. DomPDF Configuration**

Package sudah terinstall:
```json
"barryvdh/laravel-dompdf": "*"
```

Tidak perlu konfigurasi tambahan (auto-discovery).

---

## 📊 DATABASE STRUCTURE

### **Table: certificates**
```sql
CREATE TABLE certificates (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    registration_id BIGINT NOT NULL,
    serial_number VARCHAR(255) UNIQUE NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    issued_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE
);
```

### **Relasi:**
- `certificates` → `registrations` (belongsTo)
- `registrations` → `certificates` (hasOne)
- `registrations` → `attendances` (hasOne)
- `registrations` → `users` (belongsTo)
- `registrations` → `events` (belongsTo)

---

## 🎯 CHECKLIST SEBELUM TEST

- [ ] DomPDF package terinstall (`composer require barryvdh/laravel-dompdf`)
- [ ] Storage symlink dibuat (`php artisan storage:link`)
- [ ] Folder certificates exists (`storage/app/public/certificates/`)
- [ ] Permission correct (775)
- [ ] Queue configuration set (sync atau database)
- [ ] Template sertifikat exists (`resources/views/pdf/certificate.blade.php`)
- [ ] Routes registered (`routes/api.php`)
- [ ] Controller exists (`CertificateController.php`)
- [ ] Job exists (`GenerateCertificatePdfJob.php`)
- [ ] Frontend page exists (`Certificates.js`)

---

## 🚀 QUICK START TEST

### **Cara Tercepat untuk Test:**

1. **Buat Test User & Event:**
```bash
php artisan tinker
```
```php
// Create test user
$user = \App\Models\User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'role' => 'user'
]);

// Create test event (hari ini)
$event = \App\Models\Event::create([
    'title' => 'Test Event for Certificate',
    'event_date' => now()->toDateString(),
    'start_time' => '09:00:00',
    'end_time' => '12:00:00',
    'location' => 'Test Location',
    'category' => 'teknologi',
    'description' => 'Test event',
    'is_free' => true
]);

// Create registration
$reg = \App\Models\Registration::create([
    'user_id' => $user->id,
    'event_id' => $event->id,
    'status' => 'approved',
    'attendance_token' => 'TEST123'
]);

// Create attendance
$att = \App\Models\Attendance::create([
    'registration_id' => $reg->id,
    'event_id' => $event->id,
    'user_id' => $user->id,
    'status' => 'present',
    'attendance_time' => now()
]);

// Generate certificate
\App\Jobs\GenerateCertificatePdfJob::dispatch($reg, 'random');

echo "Test data created! Registration ID: " . $reg->id;
```

2. **Tunggu 30 detik**

3. **Cek sertifikat:**
```php
$cert = \App\Models\Certificate::where('registration_id', $reg->id)->first();
echo "Certificate: " . $cert->serial_number;
echo "\nFile: " . $cert->file_path;
```

4. **Download via browser:**
```
http://127.0.0.1:8000/api/certificates/{certificate_id}/download
```

---

## 📞 SUPPORT

Jika ada masalah:
1. Cek log: `storage/logs/laravel.log`
2. Cek queue jobs: `SELECT * FROM jobs;` (jika pakai database queue)
3. Test PDF generation manual dengan script test
4. Pastikan semua checklist terpenuhi

---

**Status:** ✅ Sistem Sertifikat READY
**Last Updated:** 24 Oktober 2025
