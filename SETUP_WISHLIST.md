# Setup Fitur Wishlist - PENTING!

## ⚠️ LANGKAH WAJIB SEBELUM MENGGUNAKAN FITUR WISHLIST

### 1. Jalankan Migration Database

Buka terminal/command prompt di folder `laravel-event-app` dan jalankan:

```bash
cd C:\xampp\htdocs\EduFest\laravel-event-app
php artisan migrate
```

**Output yang diharapkan:**
```
Migrating: 2025_10_21_012800_create_wishlists_table
Migrated:  2025_10_21_012800_create_wishlists_table (XX.XXms)
```

Jika sudah pernah dijalankan, akan muncul:
```
Nothing to migrate.
```

### 2. Pastikan Laravel Server Berjalan

```bash
php artisan serve
```

Server harus berjalan di `http://localhost:8000`

### 3. Pastikan React Development Server Berjalan

Buka terminal baru di folder `frontend-react.js`:

```bash
cd C:\xampp\htdocs\EduFest\frontend-react.js
npm run dev
```

## Cara Menggunakan Fitur Wishlist

### A. Menambah Event ke Wishlist

1. **Login** terlebih dahulu
2. Buka halaman **Events** (`/events`)
3. Klik icon **❤️** di pojok kanan atas card event
4. Icon akan berubah menjadi **merah penuh** (filled)
5. Event otomatis masuk ke wishlist

### B. Melihat Wishlist

Ada 3 cara untuk melihat wishlist:

#### Cara 1: Dari Profile
1. Klik **Profile** di navbar
2. Pilih menu **"My Wishlist"** di sidebar
3. Semua event yang disimpan akan ditampilkan

#### Cara 2: Dari Dashboard
1. Buka **Dashboard**
2. Klik tombol **"Wishlist"** (warna pink/rose)
3. Akan redirect ke halaman wishlist

#### Cara 3: Langsung ke URL
- Akses: `http://localhost:5173/wishlist`

### C. Menghapus dari Wishlist

Ada 2 cara untuk menghapus event dari wishlist:

#### Cara 1: Dari Halaman Events
1. Klik lagi icon **❤️ merah** di card event
2. Icon berubah jadi **abu-abu** (outline)
3. Event otomatis terhapus dari wishlist

#### Cara 2: Dari Halaman Wishlist/Profile
1. Buka halaman **Wishlist** atau **Profile > My Wishlist**
2. Klik icon **❤️ merah** di card event
3. Event langsung hilang dari daftar

## Fitur yang Sudah Dibuat

### ✅ Backend (Laravel)
- [x] Tabel `wishlists` di database
- [x] Model `Wishlist` dengan relasi
- [x] `WishlistController` dengan 4 endpoints
- [x] API routes dengan authentication

### ✅ Frontend (React)
- [x] Icon love di halaman Events
- [x] Icon love di halaman EventDetail
- [x] Halaman Wishlist (`/wishlist`)
- [x] Menu "My Wishlist" di Profile sidebar
- [x] Komponen EmbeddedWishlist di Profile
- [x] Link Wishlist di Dashboard
- [x] Service untuk komunikasi API

## Troubleshooting

### Icon Love Tidak Berubah Warna

**Kemungkinan penyebab:**

1. **Migration belum dijalankan**
   - Solusi: Jalankan `php artisan migrate`

2. **User belum login**
   - Solusi: Login terlebih dahulu
   - Icon love hanya muncul jika user sudah login

3. **Backend error**
   - Cek Laravel logs: `laravel-event-app/storage/logs/laravel.log`
   - Pastikan tidak ada error

4. **Frontend tidak terkoneksi ke backend**
   - Cek console browser (F12)
   - Pastikan tidak ada error API
   - Pastikan Laravel server berjalan di port 8000

### Cara Debug

1. **Buka Browser Console** (F12)
2. **Klik icon love**
3. **Lihat Network tab**:
   - Jika berhasil: Status 200/201
   - Jika gagal: Status 400/401/500

4. **Lihat Console tab**:
   - Cek apakah ada error JavaScript
   - Cek log dari `handleWishlistToggle`

### Error "401 Unauthorized"

**Penyebab:** Token authentication tidak valid atau expired

**Solusi:**
1. Logout
2. Login kembali
3. Coba lagi klik icon love

### Error "500 Internal Server Error"

**Penyebab:** Error di backend Laravel

**Solusi:**
1. Cek file: `laravel-event-app/storage/logs/laravel.log`
2. Lihat error terakhir
3. Kemungkinan besar migration belum dijalankan

## API Endpoints

### 1. GET /api/wishlist
Ambil semua wishlist user

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "event": {
        "id": 5,
        "title": "Workshop Testing",
        ...
      }
    }
  ]
}
```

### 2. POST /api/wishlist
Tambah event ke wishlist

**Headers:**
```
Authorization: Bearer {token}
```

**Body:**
```json
{
  "event_id": 5
}
```

### 3. DELETE /api/wishlist/{eventId}
Hapus event dari wishlist

**Headers:**
```
Authorization: Bearer {token}
```

### 4. GET /api/wishlist/check/{eventId}
Cek status wishlist

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "is_wishlisted": true
}
```

## Testing Checklist

Gunakan checklist ini untuk memastikan semua fitur berfungsi:

- [ ] Migration berhasil dijalankan
- [ ] Laravel server berjalan
- [ ] React dev server berjalan
- [ ] User bisa login
- [ ] Icon love muncul di halaman Events (setelah login)
- [ ] Klik icon love → berubah jadi merah
- [ ] Event masuk ke wishlist
- [ ] Menu "My Wishlist" muncul di Profile sidebar
- [ ] Bisa buka halaman Wishlist dari Profile
- [ ] Event yang disimpan muncul di Wishlist
- [ ] Klik icon love lagi → berubah jadi abu-abu
- [ ] Event terhapus dari wishlist
- [ ] Refresh halaman → status wishlist tetap tersimpan

## Catatan Penting

1. **Icon love hanya muncul jika user sudah login**
2. **Warna merah = sudah di wishlist**
3. **Warna abu-abu = belum di wishlist**
4. **Klik sekali = tambah ke wishlist**
5. **Klik lagi = hapus dari wishlist**
6. **Wishlist bersifat private** (setiap user punya wishlist sendiri)

## Kontak Support

Jika masih ada masalah, cek:
1. Browser console (F12)
2. Laravel logs (`storage/logs/laravel.log`)
3. Network tab untuk melihat API response
