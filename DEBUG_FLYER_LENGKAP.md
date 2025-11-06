# 🔍 DEBUG FLYER TIDAK MUNCUL - PANDUAN LENGKAP

## Perbaikan yang Sudah Dilakukan

### ✅ Backend (Laravel)
1. **Event.php Model** - Accessor URL sudah diperbaiki
2. **EventController.php** - Upload flyer sudah benar
3. **Storage link** - Sudah ada

### ✅ Frontend (React)
1. **Events.js** - Prioritas `flyer_url` langsung tanpa `resolveMediaUrl`
2. **EventDetail.js** - Prioritas `flyer_url` langsung
3. **Console.log debugging** - Ditambahkan untuk trace URL

## Cara Debug Step-by-Step

### STEP 1: Cek Browser Console

1. **Buka aplikasi** di browser (http://localhost:3000)
2. **Tekan F12** untuk buka Developer Tools
3. **Pilih tab Console**
4. **Buka halaman Events**
5. **Lihat log** yang muncul:

```
📋 Events fetched: [...]
Event "Workshop React": {
  flyer_url: "http://localhost:8000/storage/flyers/abc123.jpg",
  flyer_path: "flyers/abc123.jpg",
  ...
}
```

**Analisa:**
- ✅ Jika `flyer_url` ada dan lengkap → Backend OK
- ❌ Jika `flyer_url` null → Backend tidak generate URL
- ❌ Jika `flyer_url` undefined → Field tidak ada di response

### STEP 2: Cek Network Tab

1. **Tekan F12** → Tab **Network**
2. **Refresh halaman** Events
3. **Cari request** `events?` atau `/api/events`
4. **Klik request** tersebut
5. **Pilih tab Response**
6. **Lihat JSON response**

**Contoh response yang benar:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Workshop React",
      "flyer_path": "flyers/abc123.jpg",
      "flyer_url": "http://localhost:8000/storage/flyers/abc123.jpg",
      ...
    }
  ]
}
```

**Jika `flyer_url` tidak ada:**
- Backend tidak menambahkan accessor
- Cek `Event.php` model, pastikan `flyer_url` ada di `$appends`

### STEP 3: Cek Image Load Error

Di Console, lihat apakah ada error:
```
Image failed to load: http://localhost:8000/storage/flyers/abc123.jpg
GET http://localhost:8000/storage/flyers/abc123.jpg 404 (Not Found)
```

**Jika ada error 404:**
- File tidak ada di storage
- Storage link rusak
- Path salah

### STEP 4: Cek File Fisik

Buka folder:
```
C:\xampp\htdocs\EduFest\laravel-event-app\storage\app\public\flyers\
```

**Cek:**
- ✅ Apakah folder `flyers` ada?
- ✅ Apakah ada file gambar di dalamnya?
- ✅ Catat nama file (contoh: `abc123.jpg`)

### STEP 5: Cek Storage Link

Buka folder:
```
C:\xampp\htdocs\EduFest\laravel-event-app\public\storage\
```

**Cek:**
- ✅ Apakah folder `storage` ada?
- ✅ Apakah ini symbolic link? (icon berbeda)
- ✅ Apakah bisa akses `public\storage\flyers\`?

**Jika folder tidak ada atau bukan symbolic link:**
```bash
cd C:\xampp\htdocs\EduFest\laravel-event-app

# Hapus folder storage jika ada
rmdir public\storage

# Buat link baru
php artisan storage:link
```

### STEP 6: Test URL Langsung

Buka browser, akses langsung:
```
http://localhost:8000/storage/flyers/abc123.jpg
```

Ganti `abc123.jpg` dengan nama file yang ada di folder.

**Hasil:**
- ✅ Gambar muncul → Storage link OK, masalah di frontend
- ❌ 404 Not Found → Storage link rusak atau file tidak ada
- ❌ 403 Forbidden → Permission issue

### STEP 7: Cek Database

Buka phpMyAdmin atau MySQL:
```sql
SELECT id, title, flyer_path FROM events WHERE flyer_path IS NOT NULL;
```

**Contoh hasil yang benar:**
```
id | title              | flyer_path
1  | Workshop React     | flyers/abc123.jpg
2  | Seminar Laravel    | flyers/def456.png
```

**SALAH jika:**
```
flyer_path: public/flyers/abc123.jpg  ❌ (ada prefix 'public/')
flyer_path: /flyers/abc123.jpg        ❌ (ada leading slash)
flyer_path: storage/flyers/abc123.jpg ❌ (ada prefix 'storage/')
```

## Kemungkinan Masalah & Solusi

### Masalah 1: flyer_url null di Response

**Penyebab:** Accessor tidak berjalan atau flyer_path kosong

**Solusi:**
1. Cek `Event.php`, pastikan ada:
   ```php
   protected $appends = ['flyer_url', 'image_url', 'certificate_template_url'];
   ```

2. Clear cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

### Masalah 2: URL Double Prefix

**Contoh:** `http://localhost:8000/storage/storage/flyers/abc.jpg`

**Penyebab:** Frontend menggunakan `resolveMediaUrl()` pada `flyer_url` yang sudah full URL

**Solusi:** Sudah diperbaiki di Events.js dan EventDetail.js
```jsx
// Prioritas flyer_url langsung (sudah full URL)
src={event.flyer_url || event.image_url || resolveMediaUrl(event.flyer_path)}
```

### Masalah 3: 404 Not Found

**Penyebab:** File tidak ada atau storage link rusak

**Solusi:**
```bash
# Cek file ada
dir storage\app\public\flyers

# Recreate storage link
rmdir public\storage
php artisan storage:link
```

### Masalah 4: Image Placeholder Muncul

**Penyebab:** `onError` handler mengganti dengan placeholder

**Cek Console:** Lihat error message
```
Image failed to load: [URL]
```

**Solusi:** Fix URL yang error

### Masalah 5: CORS Error

**Error di Console:**
```
Access to image at 'http://localhost:8000/...' from origin 'http://localhost:3000' 
has been blocked by CORS policy
```

**Solusi:** Tambahkan di `config/cors.php`:
```php
'paths' => ['api/*', 'storage/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['http://localhost:3000', 'http://localhost:5173'],
```

## Testing Checklist

Gunakan checklist ini untuk memastikan semua OK:

### Backend
- [ ] Storage link ada (`public/storage` → `storage/app/public`)
- [ ] File flyer ada di `storage/app/public/flyers/`
- [ ] Database `flyer_path` format benar (`flyers/abc.jpg`)
- [ ] Event model punya `$appends = ['flyer_url']`
- [ ] Accessor `getFlyerUrlAttribute()` return full URL
- [ ] API response include `flyer_url` field
- [ ] URL bisa diakses langsung di browser

### Frontend
- [ ] Console log menampilkan `flyer_url`
- [ ] `flyer_url` adalah full URL (bukan path)
- [ ] Tidak ada error "Image failed to load"
- [ ] Tidak ada CORS error
- [ ] Image muncul di halaman Events
- [ ] Image muncul di halaman Event Detail

## Command Debugging

### 1. Test Accessor URL
```bash
cd C:\xampp\htdocs\EduFest\laravel-event-app
php test-flyer.php
```

### 2. Clear All Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 3. Recreate Storage Link
```bash
rmdir public\storage
php artisan storage:link
```

### 4. Check Permissions (Windows)
```powershell
# Klik kanan folder storage → Properties → Security
# Pastikan "Everyone" punya Full Control
```

## Cara Test Upload Baru

1. **Login sebagai admin**
2. **Buka Admin > Events**
3. **Klik "Tambah Event"**
4. **Isi form:**
   - Title: Test Flyer Upload
   - Description: Testing upload flyer
   - Date: Besok
   - Time: 10:00 - 12:00
   - Location: Lab Testing
   - Category: Teknologi
   - **Upload flyer:** Pilih gambar JPG/PNG
5. **Klik "Simpan"**
6. **Buka Console (F12)**
7. **Lihat log upload response**
8. **Logout dari admin**
9. **Buka halaman Events (sebagai user)**
10. **Cari event "Test Flyer Upload"**
11. **Flyer harus muncul!**

## Jika Masih Tidak Muncul

Kirim screenshot dari:

1. **Browser Console** (F12 > Console tab)
   - Screenshot semua log yang muncul
   - Terutama log "Events fetched" dan "Event [title]"

2. **Network Tab** (F12 > Network tab)
   - Screenshot request `/api/events`
   - Screenshot response JSON
   - Screenshot request gambar (jika ada error 404)

3. **Folder Storage**
   - Screenshot isi folder `storage/app/public/flyers/`
   - Screenshot isi folder `public/storage/flyers/`

4. **Database**
   - Screenshot hasil query:
     ```sql
     SELECT id, title, flyer_path FROM events 
     WHERE flyer_path IS NOT NULL 
     ORDER BY id DESC LIMIT 5;
     ```

5. **Test URL Langsung**
   - Screenshot hasil akses: `http://localhost:8000/storage/flyers/[nama-file].jpg`

Dengan informasi ini, saya bisa identifikasi masalah dengan tepat!
