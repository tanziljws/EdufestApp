# Perbaikan Flyer Tidak Muncul di Public/User

## Masalah
Flyer yang diupload oleh admin tidak muncul di sisi public/user.

## Penyebab Masalah

### 1. **Accessor URL Salah**
Di `Event.php` model, accessor `getFlyerUrlAttribute()` menggunakan:
```php
return url(Storage::url($path));
```

Ini menyebabkan URL menjadi double-prefix, contoh:
```
http://localhost:8000/storage/storage/flyers/abc.jpg  ❌ (salah)
```

Seharusnya:
```
http://localhost:8000/storage/flyers/abc.jpg  ✅ (benar)
```

### 2. **Storage Link Belum Dibuat**
Laravel menyimpan file di `storage/app/public/`, tapi file harus bisa diakses via `public/storage/`. Ini memerlukan symbolic link.

## Solusi yang Sudah Diterapkan

### 1. **Perbaiki Accessor di Event.php**

**SEBELUM:**
```php
public function getFlyerUrlAttribute(): ?string
{
    if (empty($this->flyer_path)) {
        return null;
    }
    $path = ltrim(str_replace(['\\', 'public/'], ['/', ''], $this->flyer_path), '/');
    return url(Storage::url($path));  // ❌ Double prefix
}
```

**SESUDAH:**
```php
public function getFlyerUrlAttribute(): ?string
{
    if (empty($this->flyer_path)) {
        return null;
    }
    // If path starts with 'http', return as-is (already absolute URL)
    if (str_starts_with($this->flyer_path, 'http')) {
        return $this->flyer_path;
    }
    // Remove 'public/' prefix if exists
    $path = str_replace('public/', '', $this->flyer_path);
    // Return full URL using asset helper
    return asset('storage/' . $path);  // ✅ Correct
}
```

### 2. **Perbaiki Accessor untuk Image dan Certificate**

Accessor `getImageUrlAttribute()` dan `getCertificateTemplateUrlAttribute()` juga diperbaiki dengan cara yang sama.

## Langkah yang Harus Dilakukan

### STEP 1: Buat Storage Link (WAJIB!)

Jalankan command ini di terminal:

```bash
cd C:\xampp\htdocs\EduFest\laravel-event-app
php artisan storage:link
```

**Output yang diharapkan:**
```
The [C:\xampp\htdocs\EduFest\laravel-event-app\public\storage] link has been connected to [C:\xampp\htdocs\EduFest\laravel-event-app\storage\app\public].
The links have been created.
```

**Jika muncul error "link already exists":**
```bash
# Hapus link lama
rmdir public\storage

# Buat link baru
php artisan storage:link
```

### STEP 2: Cek Struktur Folder

Setelah storage link dibuat, struktur folder harus seperti ini:

```
laravel-event-app/
├── storage/
│   └── app/
│       └── public/
│           ├── flyers/           ← File flyer disimpan di sini
│           │   ├── abc123.jpg
│           │   └── def456.png
│           ├── cert_templates/   ← File certificate template
│           └── images/           ← File images lainnya
│
└── public/
    └── storage/                  ← Symbolic link ke storage/app/public
        ├── flyers/               ← Accessible via URL
        ├── cert_templates/
        └── images/
```

### STEP 3: Test Upload Flyer

1. Login sebagai **admin**
2. Buka **Admin > Events**
3. Klik **"Tambah Event"** atau **Edit event**
4. Upload flyer (JPG/PNG, max 2MB)
5. Klik **"Simpan"**

### STEP 4: Cek di Public/User

1. Logout dari admin
2. Login sebagai **user biasa** (atau tanpa login)
3. Buka halaman **Events**
4. Flyer harus muncul di card event

## Cara Kerja Storage di Laravel

### 1. **Upload File**
```php
// EventController.php
if ($request->hasFile('flyer')) {
    $data['flyer_path'] = $request->file('flyer')->store('flyers', 'public');
}
```

Ini menyimpan file di: `storage/app/public/flyers/abc123.jpg`

Dan menyimpan path di database: `flyers/abc123.jpg`

### 2. **Generate URL**
```php
// Event.php model
public function getFlyerUrlAttribute(): ?string
{
    $path = str_replace('public/', '', $this->flyer_path);
    return asset('storage/' . $path);
}
```

Ini menghasilkan URL: `http://localhost:8000/storage/flyers/abc123.jpg`

### 3. **Akses File**
Browser mengakses: `http://localhost:8000/storage/flyers/abc123.jpg`

Laravel routing:
- `public/storage/` (symbolic link)
- → `storage/app/public/`
- → `storage/app/public/flyers/abc123.jpg` ✅

## Troubleshooting

### Error 1: Flyer Masih Tidak Muncul

**Cek 1: Apakah storage link sudah dibuat?**
```bash
# Windows
dir public\storage

# Jika tidak ada, jalankan:
php artisan storage:link
```

**Cek 2: Apakah file benar-benar ada?**
```bash
dir storage\app\public\flyers
```

**Cek 3: Apakah URL benar?**
Buka browser console (F12), lihat URL gambar:
- ✅ Benar: `http://localhost:8000/storage/flyers/abc.jpg`
- ❌ Salah: `http://localhost:8000/storage/storage/flyers/abc.jpg`

### Error 2: 404 Not Found

**Penyebab:** Storage link belum dibuat atau rusak

**Solusi:**
```bash
# Hapus link lama (jika ada)
rmdir public\storage

# Buat link baru
php artisan storage:link
```

### Error 3: Permission Denied

**Penyebab:** Folder storage tidak punya permission write

**Solusi (Windows):**
1. Klik kanan folder `storage`
2. Properties > Security
3. Edit > Add > Everyone
4. Allow: Full Control

### Error 4: Image Broken (Icon Gambar Rusak)

**Penyebab:** File tidak ada atau URL salah

**Cek di Browser Console:**
```
Failed to load resource: net::ERR_FILE_NOT_FOUND
http://localhost:8000/storage/flyers/abc.jpg
```

**Solusi:**
1. Cek apakah file ada di `storage/app/public/flyers/`
2. Cek apakah storage link sudah dibuat
3. Cek URL di database (tabel `events`, kolom `flyer_path`)

## Testing Checklist

- [ ] Storage link sudah dibuat (`php artisan storage:link`)
- [ ] Folder `public/storage` ada dan merupakan symbolic link
- [ ] Admin bisa upload flyer tanpa error
- [ ] File tersimpan di `storage/app/public/flyers/`
- [ ] Path tersimpan di database (kolom `flyer_path`)
- [ ] Flyer muncul di halaman Events (public)
- [ ] Flyer muncul di halaman Event Detail
- [ ] Flyer muncul di Wishlist
- [ ] URL flyer benar (tidak double `/storage/storage/`)
- [ ] Klik flyer bisa membuka gambar full size

## File yang Dimodifikasi

### Backend:
- ✅ `app/Models/Event.php` - Perbaiki accessor `getFlyerUrlAttribute()`, `getImageUrlAttribute()`, `getCertificateTemplateUrlAttribute()`

### Frontend:
- Tidak ada perubahan (sudah benar menggunakan `flyer_url`, `image_url`)

## Catatan Penting

1. **Storage link hanya perlu dibuat 1 kali**
   - Setelah dibuat, tidak perlu dijalankan lagi
   - Kecuali jika folder `public/storage` terhapus

2. **Path di database tidak boleh ada prefix `public/`**
   - ✅ Benar: `flyers/abc.jpg`
   - ❌ Salah: `public/flyers/abc.jpg`

3. **URL harus menggunakan `asset()` helper**
   - ✅ Benar: `asset('storage/' . $path)`
   - ❌ Salah: `url(Storage::url($path))`

4. **Frontend sudah benar**
   - Menggunakan `flyer_url` dari backend
   - Menggunakan `resolveMediaUrl()` utility
   - Fallback ke placeholder jika tidak ada flyer

## Cara Verifikasi

### 1. Cek Database
```sql
SELECT id, title, flyer_path FROM events WHERE flyer_path IS NOT NULL;
```

Contoh hasil:
```
id | title              | flyer_path
1  | Workshop React     | flyers/abc123.jpg
2  | Seminar Laravel    | flyers/def456.png
```

### 2. Cek API Response
Buka browser, akses:
```
http://localhost:8000/api/events/1
```

Response harus include:
```json
{
  "id": 1,
  "title": "Workshop React",
  "flyer_path": "flyers/abc123.jpg",
  "flyer_url": "http://localhost:8000/storage/flyers/abc123.jpg",
  ...
}
```

### 3. Cek File Fisik
```bash
# Cek apakah file ada
dir storage\app\public\flyers\abc123.jpg

# Cek apakah bisa diakses via public
# Buka browser: http://localhost:8000/storage/flyers/abc123.jpg
```

## Summary

✅ **Masalah:** Accessor URL menggunakan double prefix  
✅ **Solusi:** Perbaiki accessor menggunakan `asset('storage/' . $path)`  
✅ **Wajib:** Jalankan `php artisan storage:link`  
✅ **Status:** Siap digunakan  

Setelah menjalankan `php artisan storage:link`, flyer yang diupload admin akan langsung muncul di sisi public/user! 🎉
