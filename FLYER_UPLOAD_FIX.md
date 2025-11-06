# Perbaikan Upload Flyer Event - EduFest

## Masalah yang Diperbaiki
Admin tidak bisa mengupload flyer saat membuat event baru, dan flyer tidak muncul di halaman publik/user.

## Perbaikan yang Dilakukan

### 1. Backend (Laravel) - EventController.php
✅ **Method `store()`**:
- Menambahkan validasi file: `'flyer' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'`
- Menambahkan logging untuk tracking upload
- Menghapus field validasi dari data sebelum insert ke database
- Menambahkan `flyer_url` ke response

✅ **Method `update()`**:
- Menambahkan validasi file yang sama
- Menambahkan logging untuk tracking upload
- Menghapus field validasi dari data sebelum update
- Menambahkan `flyer_url` ke response

✅ **Method `index()` dan `show()`**:
- Menambahkan `flyer_url` ke setiap event yang dikembalikan
- URL format: `http://127.0.0.1:8000/storage/flyers/namafile.jpg`

### 2. Frontend (React) - adminService.js
✅ **Method `createEvent()`**:
- Memperbaiki penanganan File object
- File object sekarang langsung di-append ke FormData tanpa dikonversi ke string
- Menggunakan `instanceof File` untuk deteksi file

✅ **Method `updateEvent()`**:
- Perbaikan yang sama dengan `createEvent()`
- File object ditangani dengan benar

### 3. Frontend (React) - Events.js, EventDetail.js, Home.js
✅ **Prioritas tampilan gambar**:
1. `flyer_url` dari backend (URL lengkap)
2. `flyer_path` dengan `resolveMediaUrl()`
3. Placeholder berdasarkan kategori

✅ **Penanganan nilai NULL atau '0'**:
- Fungsi `resolveMediaUrl()` mendeteksi dan mengembalikan string kosong
- Komponen menampilkan placeholder jika tidak ada flyer

## Cara Kerja Sistem Sekarang

### Upload Flyer (Admin)
1. Admin membuka form "Buat Event Baru"
2. Mengisi semua field wajib
3. **Upload file flyer** melalui input file
4. Klik "Simpan Event"
5. File flyer diupload ke `storage/app/public/flyers/`
6. Path disimpan di database: `flyers/namafile.jpg`
7. Backend mengembalikan `flyer_url` lengkap

### Tampilan Flyer (User/Public)
1. Frontend memanggil API `/api/events`
2. Backend mengembalikan event dengan `flyer_url`
3. Frontend memprioritaskan `flyer_url` untuk ditampilkan
4. Jika tidak ada flyer, tampilkan placeholder berdasarkan kategori

## Testing

### 1. Test Upload Flyer Baru
```bash
# Buka halaman admin
http://localhost:3000/admin/events

# Klik "Buat Event Baru"
# Isi semua field
# Upload gambar flyer (JPG/PNG, max 2MB)
# Klik "Simpan Event"
```

### 2. Verifikasi di Database
```bash
cd laravel-event-app
php check_flyer_status.php
```

### 3. Verifikasi di Halaman Public
```bash
# Buka halaman events
http://localhost:3000/events

# Flyer harus muncul untuk event yang baru dibuat
```

### 4. Check Laravel Logs
```bash
# Lihat log upload
tail -f storage/logs/laravel.log
```

## File yang Dimodifikasi

### Backend
- `app/Http/Controllers/Api/EventController.php`
  - Method `store()` - line 107-185
  - Method `update()` - line 204-259
  - Method `index()` - line 56-87
  - Method `show()` - line 187-201

### Frontend
- `src/services/adminService.js`
  - Method `createEvent()` - line 84-113
  - Method `updateEvent()` - line 115-147

- `src/pages/Events.js`
  - Function `getEventImageUrl()` - line 298-312

- `src/pages/EventDetail.js`
  - Computed `heroUrl` - line 22-48

- `src/pages/Home.js`
  - Event image rendering - line 535-542

- `src/utils/media.js`
  - Function `resolveMediaUrl()` - line 29-67

## Struktur File Flyer

```
laravel-event-app/
├── storage/
│   └── app/
│       └── public/
│           └── flyers/
│               ├── 9B9HtcSSMPghK3vGWQgj0W5RdvX9uOHyNdFhF3QY.jpg
│               ├── bKyxW3oWFOkdio9vPCjstdnAzkIbsHfUKsnJPeb8.jpg
│               └── Gnmic9tgBIWnFAtGwd3CPJm9x3sGwQaRd66jqqpv.png
└── public/
    └── storage/ -> ../storage/app/public (symlink)
```

## URL Akses Flyer

Format URL: `http://127.0.0.1:8000/storage/flyers/namafile.jpg`

Contoh:
- `http://127.0.0.1:8000/storage/flyers/9B9HtcSSMPghK3vGWQgj0W5RdvX9uOHyNdFhF3QY.jpg`
- `http://127.0.0.1:8000/storage/flyers/Gnmic9tgBIWnFAtGwd3CPJm9x3sGwQaRd66jqqpv.png`

## Troubleshooting

### Flyer tidak muncul setelah upload
1. Check Laravel logs: `tail -f storage/logs/laravel.log`
2. Verifikasi file ada di `storage/app/public/flyers/`
3. Verifikasi symlink: `ls -la public/storage`
4. Check database: `php check_flyer_status.php`

### Error "The flyer must be an image"
- Pastikan file yang diupload adalah gambar (JPG, PNG, GIF)
- Ukuran file maksimal 2MB

### Error "Event must be created at least H-3"
- Event harus dibuat minimal 3 hari sebelum tanggal pelaksanaan

## Status Akhir

✅ Upload flyer berfungsi dengan baik
✅ Flyer muncul di halaman publik/user
✅ Placeholder muncul jika tidak ada flyer
✅ Logging untuk debugging
✅ Validasi file yang proper
✅ Response API mengembalikan flyer_url

## Catatan Penting

1. **Symlink harus ada**: Pastikan `php artisan storage:link` sudah dijalankan
2. **Permission folder**: `storage/app/public` harus writable (775)
3. **Max upload size**: Default 2MB, bisa diubah di validasi
4. **Format file**: JPG, PNG, GIF untuk flyer
5. **Placeholder**: Event tanpa flyer akan menampilkan gambar placeholder sesuai kategori
