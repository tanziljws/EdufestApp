# 📜 PANDUAN ADMIN - SISTEM SERTIFIKAT EDUFEST

## 🎯 OVERVIEW

Sistem sertifikat EduFest memungkinkan admin untuk:
1. Upload custom template sertifikat per event
2. Auto-generate sertifikat untuk peserta yang hadir
3. Download dan kelola sertifikat

## 📋 CARA KERJA SISTEM

### Flow Lengkap:
```
1. Admin membuat event → Upload template sertifikat (opsional)
2. User mendaftar event → Registration created
3. User hadir ke event → Attendance marked as "present"
4. System auto-generate → Certificate created dengan PDF
5. User download → PDF tersedia di halaman Sertifikat
```

## 🖼️ UPLOAD TEMPLATE SERTIFIKAT

### Langkah-langkah:

#### 1. **Saat Membuat Event Baru**
- Buka halaman Admin → Events → Create New Event
- Isi semua field event (title, date, location, dll)
- Di bagian **Certificate Template**, klik "Choose File"
- Upload file template sertifikat

#### 2. **Saat Edit Event**
- Buka halaman Admin → Events → Edit Event
- Scroll ke bagian **Certificate Template**
- Upload file template baru (akan replace template lama)

### Spesifikasi Template:

**Format File:**
- ✅ JPG, JPEG, PNG, GIF
- ✅ PDF (untuk template yang sudah jadi)
- ❌ Max size: 2MB

**Dimensi Rekomendasi:**
- Landscape A4: 297mm x 210mm (3508 x 2480 pixels @ 300 DPI)
- Atau: 1920 x 1357 pixels (untuk web)

**Design Guidelines:**
1. **Background:** Buat design background sertifikat lengkap
2. **Nama Area:** Sisakan area kosong di tengah untuk nama peserta
3. **Text Overlay:** System akan menambahkan:
   - Nama peserta (font besar, bold, di tengah)
   - Deskripsi event (font sedang, di bawah nama)

**Posisi Text Overlay (Default):**
- Nama: 95mm dari atas, center horizontal
- Deskripsi: 125mm dari atas, center horizontal (margin 50mm kiri-kanan)

### Contoh Template Design:

```
┌─────────────────────────────────────────────────┐
│                                                 │
│         [LOGO SEKOLAH]    [LOGO EVENT]         │
│                                                 │
│              SERTIFIKAT PENGHARGAAN            │
│                                                 │
│                Diberikan kepada:               │
│                                                 │
│              [NAMA PESERTA DISINI]             │ ← System overlay
│                                                 │
│         [DESKRIPSI EVENT AKAN DISINI]          │ ← System overlay
│                                                 │
│                                                 │
│    [TTD KEPALA SEKOLAH]    [TTD KOORDINATOR]   │
│                                                 │
│              [TANGGAL & NOMOR SERI]            │
│                                                 │
└─────────────────────────────────────────────────┘
```

## 🔧 BACKEND CONFIGURATION

### File Upload Handler:
File: `app/Http/Controllers/Api/EventController.php`

```php
// Saat create event
if ($request->hasFile('certificate_template')) {
    $certPath = $request->file('certificate_template')->store('cert_templates', 'public');
    $data['certificate_template_path'] = $certPath;
}

// Saat update event
if ($request->hasFile('certificate_template')) {
    $certPath = $request->file('certificate_template')->store('cert_templates', 'public');
    $data['certificate_template_path'] = $certPath;
}
```

### Storage Location:
```
storage/
└── app/
    └── public/
        ├── cert_templates/          ← Template sertifikat dari admin
        │   ├── template1.jpg
        │   ├── template2.png
        │   └── ...
        └── certificates/            ← PDF sertifikat yang di-generate
            ├── CERT-2025-XXXXX.pdf
            └── ...
```

### Database Schema:
```sql
events table:
- certificate_template_path (string, nullable)
  → Menyimpan path ke template: "cert_templates/template1.jpg"
```

## 📝 GENERATE SERTIFIKAT

### Otomatis (Recommended):
Sertifikat akan di-generate otomatis ketika:
1. User mark attendance dengan status "present"
2. System trigger job `GenerateCertificatePdfJob`
3. Certificate record dibuat di database
4. PDF file di-generate dan disimpan

### Manual (Via Script):
Jika perlu generate manual untuk user tertentu:

```bash
# Generate untuk registration tertentu
php generate_certificate_manual.php

# Generate untuk semua eligible users
php generate_all_eligible_certificates.php
```

### Via API:
```bash
POST /api/registrations/{registration_id}/generate-certificate
Content-Type: application/json
Authorization: Bearer {token}

{
  "template": "random"  // atau "modern", "classic", "default"
}
```

## 🎨 TEMPLATE CUSTOMIZATION

### Jika Menggunakan Custom Template:

File: `app/Http/Controllers/Api/CertificateController.php`

```php
// Check if event has custom template
if ($registration->event->certificate_template_path) {
    $templatePath = storage_path('app/public/' . $registration->event->certificate_template_path);
    
    // Generate PDF dengan template custom
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4-L',
        'margin_left' => 0,
        'margin_right' => 0,
        'margin_top' => 0,
        'margin_bottom' => 0,
    ]);
    
    // HTML dengan background image dan text overlay
    $html = '
    <style>
        .certificate-container {
            background-image: url(\'file://' . $templatePath . '\');
            background-size: cover;
        }
        .name-text {
            position: absolute;
            top: 95mm;
            text-align: center;
            font-size: 32pt;
            font-weight: bold;
        }
    </style>
    <div class="certificate-container">
        <div class="name-text">' . $userName . '</div>
    </div>';
    
    $mpdf->WriteHTML($html);
}
```

### Jika Tidak Ada Custom Template:

System akan menggunakan default blade template:
- File: `resources/views/pdf/certificate_modern.blade.php`
- Design: Template modern dengan gradient background

## 📊 MONITORING & MANAGEMENT

### Check Certificate Status:

```bash
# Check semua sertifikat di database
php check_certificates.php
```

Output:
```
=== CHECKING CERTIFICATES DATA ===

Total Certificates: 3

Certificate ID: 1
Serial Number: CERT-2025-A4BEE4E6
User: Meitanti (ID: 3)
Event: Seminar Kewirausahaan Digital
File Path: certificates/CERT-2025-A4BEE4E6.pdf
Issued At: 2025-09-10 13:18:17
File Exists: YES
File Size: 193,633 bytes
```

### Test Certificate Download:

```bash
# Test download certificate
php test_certificate_download.php
```

### Test API Endpoint:

```bash
# Test API untuk user tertentu
php test_certificate_api.php
```

## 🚨 TROUBLESHOOTING

### Issue 1: Template tidak muncul di PDF
**Penyebab:** Path template salah atau file tidak ada
**Solusi:**
```bash
# Check apakah file exists
ls -la storage/app/public/cert_templates/

# Check permission
chmod -R 775 storage/

# Recreate symlink
php artisan storage:link
```

### Issue 2: PDF tidak ter-generate
**Penyebab:** mPDF error atau memory limit
**Solusi:**
```bash
# Check mPDF installed
composer show mpdf/mpdf

# Increase memory limit di php.ini
memory_limit = 256M

# Check Laravel logs
tail -f storage/logs/laravel.log
```

### Issue 3: Download gagal
**Penyebab:** File permission atau CORS
**Solusi:**
```bash
# Fix permission
chmod -R 775 storage/app/public/certificates/

# Check .env
FILESYSTEM_DISK=public

# Clear cache
php artisan cache:clear
php artisan config:clear
```

## 📱 FRONTEND ADMIN PANEL

### Upload Template via Admin Panel:

**Form Fields:**
```javascript
<input 
  type="file" 
  name="certificate_template"
  accept="image/jpeg,image/png,image/gif,application/pdf"
/>
```

**API Call:**
```javascript
const formData = new FormData();
formData.append('certificate_template', file);
formData.append('title', eventTitle);
// ... other fields

await adminService.createEvent(formData);
```

### Preview Template:
Setelah upload, admin bisa preview template di:
- Event detail page
- Certificate management page

## 🎓 BEST PRACTICES

### 1. **Design Template:**
- Gunakan high-resolution image (min 1920x1357)
- Sisakan area kosong untuk text overlay
- Gunakan warna kontras untuk readability
- Test print untuk memastikan kualitas

### 2. **File Management:**
- Beri nama file yang deskriptif: `template_seminar_2025.jpg`
- Compress image untuk reduce file size (max 2MB)
- Backup template di folder terpisah

### 3. **Testing:**
- Test generate certificate sebelum event
- Verify PDF quality dan layout
- Test download di berbagai browser

### 4. **Security:**
- Jangan upload file executable
- Validate file type di backend
- Set proper file permissions

## 📈 STATISTICS & REPORTS

### Certificate Analytics:

```sql
-- Total certificates issued
SELECT COUNT(*) FROM certificates;

-- Certificates per event
SELECT e.title, COUNT(c.id) as total_certs
FROM events e
LEFT JOIN registrations r ON r.event_id = e.id
LEFT JOIN certificates c ON c.registration_id = r.id
GROUP BY e.id
ORDER BY total_certs DESC;

-- Users with most certificates
SELECT u.name, COUNT(c.id) as total_certs
FROM users u
LEFT JOIN registrations r ON r.user_id = u.id
LEFT JOIN certificates c ON c.registration_id = r.id
GROUP BY u.id
ORDER BY total_certs DESC
LIMIT 10;
```

## 🔐 SECURITY CONSIDERATIONS

1. **File Upload Validation:**
   - Max size: 2MB
   - Allowed types: JPG, PNG, GIF, PDF
   - Scan for malware (recommended)

2. **Access Control:**
   - Only admin can upload templates
   - Only authenticated users can download their certificates
   - Certificate download requires ownership verification

3. **Storage Security:**
   - Store templates in private storage
   - Serve via Laravel controller (not direct URL)
   - Use signed URLs for temporary access

## 📞 SUPPORT

Jika ada pertanyaan atau masalah:
1. Check documentation ini
2. Review Laravel logs: `storage/logs/laravel.log`
3. Test dengan scripts yang disediakan
4. Contact developer team

---

**Last Updated:** November 5, 2025
**Version:** 1.0
**Status:** Production Ready ✅
