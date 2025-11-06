# Fitur Ubah Kata Sandi (Change Password) - EduFest

## Deskripsi
Fitur untuk mengubah password user yang sudah login melalui halaman Profile. User harus memasukkan password lama dan password baru untuk keamanan.

## Fitur yang Diimplementasikan

### Backend (Laravel)

1. **ChangePasswordController**
   - File: `app/Http/Controllers/ChangePasswordController.php`
   - Method: `changePassword()`
   - Validasi:
     - Password lama harus benar
     - Password baru minimal 8 karakter
     - Password baru harus dikonfirmasi
     - Password baru harus berbeda dari password lama

2. **API Route**
   - Endpoint: `POST /api/auth/change-password`
   - Middleware: `auth:sanctum` (harus login)
   - Request Body:
     ```json
     {
       "current_password": "password_lama",
       "new_password": "password_baru",
       "new_password_confirmation": "password_baru"
     }
     ```

### Frontend (React)

1. **ChangePassword Component**
   - File: `src/components/ChangePassword.js`
   - Fitur:
     - Form input password lama, baru, dan konfirmasi
     - Toggle show/hide password untuk setiap field
     - Real-time validation feedback
     - Password strength indicator
     - Success/error message
     - Loading state saat submit

2. **Profile Integration**
   - File: `src/pages/Profile.js`
   - Menu "Atur Kata Sandi" di sidebar profile
   - Route: `/profile?section=password`

## Cara Menggunakan

### Untuk User:

1. **Login** ke aplikasi
2. Klik **Profile** di navbar
3. Pilih menu **"Atur Kata Sandi"** di sidebar
4. Isi form:
   - **Password Lama**: Password yang sedang digunakan
   - **Password Baru**: Password baru (min. 8 karakter)
   - **Konfirmasi Password Baru**: Ketik ulang password baru
5. Klik **"Simpan Password"**
6. Jika berhasil, akan muncul pesan sukses

## Validasi

### Frontend Validation:
- ✅ Password lama harus diisi
- ✅ Password baru harus diisi
- ✅ Password baru minimal 8 karakter
- ✅ Konfirmasi password harus cocok
- ✅ Password baru harus berbeda dari password lama

### Backend Validation:
- ✅ Password lama harus sesuai dengan database
- ✅ Password baru minimal 8 karakter
- ✅ Password baru harus dikonfirmasi
- ✅ Password baru tidak boleh sama dengan password lama

## Fitur Keamanan

1. **Authentication Required**
   - Hanya user yang sudah login bisa mengubah password
   - Menggunakan Bearer token authentication

2. **Current Password Verification**
   - User harus memasukkan password lama yang benar
   - Mencegah orang lain mengubah password jika device tidak terkunci

3. **Password Hashing**
   - Password di-hash menggunakan bcrypt
   - Password lama diverifikasi dengan `Hash::check()`
   - Password baru di-hash dengan `Hash::make()`

4. **Password Confirmation**
   - User harus mengetik password baru 2 kali
   - Mencegah typo saat input password baru

## UI/UX Features

### Password Visibility Toggle
- Icon mata untuk show/hide password
- Tersedia di semua field password
- Memudahkan user melihat password yang diketik

### Real-time Validation Feedback
- Indikator visual untuk setiap requirement:
  - ✓ Hijau = requirement terpenuhi
  - ○ Abu-abu = requirement belum terpenuhi
- Requirements yang ditampilkan:
  - Minimal 8 karakter
  - Password dan konfirmasi cocok
  - Berbeda dari password lama

### Success/Error Messages
- Alert box dengan icon
- Warna hijau untuk success
- Warna merah untuk error
- Pesan yang jelas dan informatif

### Loading State
- Button disabled saat loading
- Spinner animation
- Text berubah jadi "Menyimpan..."

## API Endpoint

### POST /api/auth/change-password

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "current_password": "password123",
  "new_password": "newpassword123",
  "new_password_confirmation": "newpassword123"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Password berhasil diubah"
}
```

**Error Responses:**

**1. Validation Error (422):**
```json
{
  "success": false,
  "message": "Password baru minimal 8 karakter",
  "errors": {
    "new_password": ["Password baru minimal 8 karakter"]
  }
}
```

**2. Wrong Current Password (400):**
```json
{
  "success": false,
  "message": "Password lama tidak sesuai"
}
```

**3. Same Password (400):**
```json
{
  "success": false,
  "message": "Password baru harus berbeda dari password lama"
}
```

**4. Unauthorized (401):**
```json
{
  "message": "Unauthenticated."
}
```

## Testing

### Manual Testing Checklist:

- [ ] User bisa akses halaman "Atur Kata Sandi" dari Profile
- [ ] Form menampilkan 3 field password
- [ ] Toggle show/hide password berfungsi
- [ ] Validation feedback muncul real-time
- [ ] Error muncul jika password lama salah
- [ ] Error muncul jika password baru < 8 karakter
- [ ] Error muncul jika konfirmasi tidak cocok
- [ ] Error muncul jika password baru sama dengan lama
- [ ] Success message muncul jika berhasil
- [ ] Form di-reset setelah berhasil
- [ ] Loading state muncul saat submit
- [ ] User bisa login dengan password baru

### Test Cases:

**Test 1: Success Case**
```
Input:
- Current Password: password123 (benar)
- New Password: newpassword123
- Confirmation: newpassword123

Expected: Success message, form reset
```

**Test 2: Wrong Current Password**
```
Input:
- Current Password: wrongpassword (salah)
- New Password: newpassword123
- Confirmation: newpassword123

Expected: Error "Password lama tidak sesuai"
```

**Test 3: Password Too Short**
```
Input:
- Current Password: password123
- New Password: 123 (< 8 karakter)
- Confirmation: 123

Expected: Error "Password baru minimal 8 karakter"
```

**Test 4: Confirmation Mismatch**
```
Input:
- Current Password: password123
- New Password: newpassword123
- Confirmation: differentpassword

Expected: Error "Konfirmasi password tidak cocok"
```

**Test 5: Same as Current Password**
```
Input:
- Current Password: password123
- New Password: password123 (sama)
- Confirmation: password123

Expected: Error "Password baru harus berbeda dari password lama"
```

## Troubleshooting

### Error: "Password lama tidak sesuai"
**Penyebab:** User memasukkan password lama yang salah

**Solusi:** 
- Pastikan password lama yang dimasukkan benar
- Gunakan toggle show/hide untuk memastikan tidak ada typo
- Jika lupa password lama, gunakan fitur "Lupa Password"

### Error: "Password baru minimal 8 karakter"
**Penyebab:** Password baru kurang dari 8 karakter

**Solusi:** Buat password minimal 8 karakter

### Error: "Konfirmasi password tidak cocok"
**Penyebab:** Password baru dan konfirmasi tidak sama

**Solusi:** 
- Ketik ulang password baru dengan hati-hati
- Gunakan toggle show/hide untuk memastikan

### Error: "401 Unauthorized"
**Penyebab:** Token authentication tidak valid atau expired

**Solusi:**
- Logout dan login kembali
- Coba ubah password lagi

### Form tidak muncul
**Penyebab:** Component tidak di-import atau route salah

**Solusi:**
- Pastikan sudah save semua file
- Refresh browser
- Cek console untuk error

## File yang Dibuat/Dimodifikasi

### Backend:
- ✅ `app/Http/Controllers/ChangePasswordController.php` (NEW)
- ✅ `routes/api.php` (MODIFIED - added change password route)

### Frontend:
- ✅ `src/components/ChangePassword.js` (NEW)
- ✅ `src/pages/Profile.js` (MODIFIED - added password section)

## Perbedaan dengan Reset Password

| Fitur | Change Password | Reset Password |
|-------|----------------|----------------|
| **Akses** | Harus login | Tidak perlu login |
| **Verifikasi** | Password lama | OTP via email |
| **Lokasi** | Profile > Atur Kata Sandi | Login > Lupa Password |
| **Use Case** | User ingat password lama | User lupa password |
| **Endpoint** | `/auth/change-password` | `/auth/reset-password` |

## Best Practices

1. **Gunakan Password yang Kuat**
   - Minimal 8 karakter
   - Kombinasi huruf besar, kecil, angka
   - Hindari password yang mudah ditebak

2. **Jangan Share Password**
   - Jangan berikan password ke orang lain
   - Jangan simpan password di tempat yang mudah diakses

3. **Ubah Password Secara Berkala**
   - Disarankan ubah password setiap 3-6 bulan
   - Ubah segera jika mencurigai akun di-hack

4. **Logout Setelah Ubah Password**
   - Untuk keamanan, logout dari semua device
   - Login kembali dengan password baru

## Status Implementasi

✅ **Backend**: Lengkap dan siap digunakan
✅ **Frontend**: Lengkap dan siap digunakan
✅ **Validation**: Frontend dan backend
✅ **Security**: Password hashing, authentication
✅ **UI/UX**: Modern, user-friendly, responsive

## Catatan Penting

1. **Password di-hash** dengan bcrypt di database
2. **Tidak ada** cara untuk melihat password lama di database
3. **Jika lupa password lama**, gunakan fitur "Lupa Password"
4. **Token authentication** otomatis ditambahkan oleh axios interceptor
5. **Form di-reset** setelah berhasil ubah password

## Future Enhancements (Opsional)

- [ ] Password strength meter (weak/medium/strong)
- [ ] Logout dari semua device setelah ubah password
- [ ] Email notification saat password diubah
- [ ] Password history (prevent reuse of last 5 passwords)
- [ ] Two-factor authentication (2FA)
- [ ] Session management (lihat device yang login)
