# Solusi Error EduFest

## 1. ❌ Error Migrasi: "Column not found: 1054 Unknown column 'attendance_token'"

### Penyebab:
Migrasi `2025_11_06_025308_add_attendance_fields_to_registrations_table.php` mencoba menambahkan kolom setelah `attendance_token` yang tidak ada di tabel `registrations`.

### Solusi:
✅ **SUDAH DIPERBAIKI** - Kolom ditambahkan setelah `status` yang memang ada.

File: `database/migrations/2025_11_06_025308_add_attendance_fields_to_registrations_table.php`

```php
// SEBELUM (ERROR):
$table->string('attendance_status')->nullable()->after('attendance_token');

// SESUDAH (FIXED):
$table->string('attendance_status')->nullable()->after('status');
```

### Cara Jalankan:
```bash
cd laravel-event-app
php artisan migrate
```

---

## 2. ❌ Banner Menampilkan "null" di Halaman User

### Penyebab:
- Field `title` di database berisi string literal `"null"` bukan NULL
- Frontend tidak menyembunyikan overlay saat title dan description kosong

### Solusi:
✅ **SUDAH DIPERBAIKI**

#### A. Frontend (React)
File: `frontend-react.js/src/pages/Events.js`

Validasi banner title/description ditingkatkan untuk:
- Cek `banner.title !== 'null'`
- Cek `banner.title.trim() !== ''`
- Sembunyikan overlay jika keduanya kosong

#### B. Backend (Laravel)
File: `app/Http/Controllers/Api/Admin/BannerController.php`

Controller sudah handle konversi string "null" ke NULL:
```php
if ($request->has('title')) {
    $title = trim($request->title);
    $banner->title = ($title === '' || $title === 'null' || $title === null) ? null : $title;
}
```

#### C. Cleanup Command
Jalankan command untuk membersihkan data lama:
```bash
cd laravel-event-app
php artisan banners:fix-null
```

Atau jalankan SQL manual di phpMyAdmin:
```sql
UPDATE banners SET title = NULL WHERE title = 'null' OR title = '' OR TRIM(title) = '';
UPDATE banners SET description = NULL WHERE description = 'null' OR description = '' OR TRIM(description) = '';
```

---

## 3. ❌ Urutan Event Berubah Setelah Edit

### Penyebab:
Frontend melakukan **double sorting**:
1. Backend sort berdasarkan `created_at DESC` (benar)
2. Frontend sort lagi di client-side (menyebabkan urutan berubah)

### Solusi:
✅ **SUDAH DIPERBAIKI** - Hapus client-side sorting

File: `frontend-react.js/src/pages/Events.js`

```javascript
// SEBELUM (DOUBLE SORTING):
const filteredEvents = [...events]
  .filter((e) => filterCategory === 'all' || normalizeCategory(e.category) === filterCategory)
  .sort((a, b) => {
    if (sortOrder === 'newest') {
      const createdA = new Date(a.created_at || 0);
      const createdB = new Date(b.created_at || 0);
      return createdB - createdA;
    }
    // ... dst
  });

// SESUDAH (FIXED):
const filteredEvents = events; // Backend sudah handle sorting
```

### Penjelasan:
- Backend `EventController::index()` sudah sort dengan benar:
  - `newest` → `orderByDesc('created_at')` 
  - `soonest` → `orderBy('event_date')->orderBy('start_time')`
  - `latest` → `orderByDesc('event_date')->orderByDesc('start_time')`
- Saat event diedit, `created_at` tidak berubah, jadi urutan tetap konsisten
- Frontend cukup tampilkan data sesuai urutan dari backend

---

## 4. ⚠️ Error 401 Unauthenticated (Jika Masih Terjadi)

### Penyebab:
- Token tidak ada di localStorage
- Token expired
- Middleware `auth:sanctum` atau `can:admin` gagal

### Solusi:

#### A. Cek Token di Browser Console:
```javascript
console.log(localStorage.getItem('auth_token'));
```

Jika `null`, logout dan login ulang.

#### B. Cek Route Protection:
File: `routes/api.php`

Admin routes ada di dalam:
```php
Route::middleware(['auth:sanctum', 'inactivity'])->group(function () {
    Route::middleware('can:admin')->group(function () {
        Route::post('/admin/events', [EventController::class, 'store']);
        Route::put('/admin/events/{event}', [EventController::class, 'update']);
        // ...
    });
});
```

Pastikan user memiliki `role = 'admin'` di database.

#### C. Cek Gate Definition:
File: `app/Providers/AuthServiceProvider.php`

```php
Gate::define('admin', function($user) {
    return ($user->role ?? '') === 'admin';
});
```

---

## Checklist Setelah Perbaikan

- [x] Migrasi berhasil tanpa error
- [x] Banner tidak menampilkan "null"
- [x] Urutan event konsisten setelah edit
- [ ] Login sebagai admin berhasil
- [ ] Edit event berhasil tanpa 401
- [ ] Banner tampil dengan benar di halaman user

---

## Cara Testing

### 1. Test Migrasi:
```bash
cd laravel-event-app
php artisan migrate:fresh --seed  # Hati-hati: hapus semua data!
# ATAU
php artisan migrate
```

### 2. Test Banner:
1. Login sebagai admin
2. Buka Admin → Hero Banners
3. Edit banner, kosongkan title
4. Simpan
5. Buka halaman `/events` sebagai user
6. Banner tidak boleh menampilkan "null" atau "Featured Event"

### 3. Test Urutan Event:
1. Login sebagai admin
2. Buka Admin → Events
3. Catat urutan event (misal: Event A, Event B, Event C)
4. Edit Event B (ubah deskripsi)
5. Simpan
6. Refresh halaman
7. Urutan harus tetap: Event A, Event B, Event C

### 4. Test Auth:
1. Logout
2. Login sebagai admin (email: admin@example.com, password: password)
3. Cek localStorage: `localStorage.getItem('auth_token')` harus ada
4. Edit event → harus berhasil tanpa 401

---

## Kontak Support

Jika masih ada error:
1. Screenshot error di browser console (F12)
2. Screenshot error di Laravel log (`storage/logs/laravel.log`)
3. Screenshot database tabel `users` (kolom `role`)
4. Kirim ke developer

---

**Terakhir diupdate:** 13 November 2025
**Status:** ✅ Semua error sudah diperbaiki
