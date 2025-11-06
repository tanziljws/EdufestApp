# 🎓 CARA UJI COBA SERTIFIKAT - EDUFEST

## ✅ EVENT TEST SUDAH DIBUAT!

Event test sudah berhasil dibuat dan siap untuk dicoba.

---

## 📋 INFORMASI LOGIN

```
Email    : user.test@edufest.com
Password : password
```

---

## 🎯 LANGKAH-LANGKAH UJI COBA

### **STEP 1: LOGIN**

1. Buka browser (Chrome/Firefox/Edge)
2. Buka URL: `http://localhost:3000/login`
3. Masukkan:
   - Email: `user.test@edufest.com`
   - Password: `password`
4. Klik **"Login"**

---

### **STEP 2: BUKA HALAMAN EVENT**

**Cara 1: Langsung ke Event**
- Buka URL: `http://localhost:3000/events/50`

**Cara 2: Cari dari Halaman Events**
- Klik menu **"Events"** di navbar
- Cari event: **"Workshop Sertifikat Test"**
- Klik event tersebut

---

### **STEP 3: ABSENSI**

1. Di halaman detail event, scroll ke bawah
2. Klik tombol **"Absensi"** (warna biru/hijau)
3. Akan muncul form input token
4. Masukkan token: **`CERTS4U4EP`**
5. Klik **"Submit"** atau **"Kirim"**
6. Tunggu pesan sukses: **"Absensi berhasil! Sertifikat sedang dibuat..."**

---

### **STEP 4: TUNGGU SERTIFIKAT DI-GENERATE**

⏱️ **Tunggu 30-60 detik**

Sertifikat sedang di-generate otomatis oleh sistem.

**Catatan:**
- Jika queue worker running → Sertifikat langsung jadi
- Jika queue worker belum running → Perlu jalankan dulu

---

### **STEP 5: DOWNLOAD SERTIFIKAT**

**Cara 1: Via Profile**
1. Klik foto profile di pojok kanan atas
2. Klik **"Profile"** atau **"Dashboard"**
3. Klik menu **"Sertifikat Saya"** di sidebar kiri
4. Atau langsung buka: `http://localhost:3000/profile?section=certificates`

**Cara 2: Via URL Langsung**
- Buka: `http://localhost:3000/profile?section=certificates`

**Download:**
1. Lihat daftar sertifikat Anda
2. Klik tombol **"Download"** (icon download)
3. File PDF akan terdownload
4. Buka file PDF untuk melihat sertifikat

---

## 🔧 TROUBLESHOOTING

### **Problem 1: Tombol Absensi Tidak Aktif**

**Penyebab:**
- Event belum dimulai
- Bukan hari H event

**Solusi:**
✅ Event test sudah dibuat dengan waktu 1 jam yang lalu, jadi tombol **SUDAH AKTIF**

---

### **Problem 2: Token Tidak Valid**

**Penyebab:**
- Token salah
- Token sudah digunakan

**Solusi:**
- Pastikan token: **`CERTS4U4EP`**
- Copy-paste token (jangan ketik manual)
- Token case-sensitive (huruf besar semua)

---

### **Problem 3: Sertifikat Tidak Muncul**

**Penyebab:**
- Sertifikat belum selesai di-generate
- Queue worker belum running

**Solusi:**

**Cek 1: Tunggu Lebih Lama**
- Tunggu 1-2 menit
- Refresh halaman sertifikat

**Cek 2: Jalankan Queue Worker**
Buka terminal baru dan jalankan:
```bash
cd c:\xampp\htdocs\EduFest\laravel-event-app
php artisan queue:work
```

Biarkan terminal tetap terbuka!

**Cek 3: Cek Database**
Buka phpMyAdmin dan cek tabel `certificates`:
```sql
SELECT * FROM certificates WHERE registration_id = 12;
```

Jika ada record, berarti sertifikat sudah dibuat.

---

### **Problem 4: Download Error**

**Penyebab:**
- File PDF tidak ada
- Permission error

**Solusi:**

**Cek File:**
```
Lokasi: c:\xampp\htdocs\EduFest\laravel-event-app\storage\app\public\certificates\
```

Lihat apakah ada file PDF dengan nama `CERT-2025-XXXXXXXX.pdf`

**Buat Symlink (jika belum):**
```bash
cd c:\xampp\htdocs\EduFest\laravel-event-app
php artisan storage:link
```

---

## 📊 INFORMASI TEKNIS

### **Event Details:**
```
Event ID    : 50
Event Title : Workshop Sertifikat Test - 24 Oct 2025 06:22
Event Date  : 2025-10-24 (HARI INI)
Start Time  : 05:22:29 (SUDAH DIMULAI)
End Time    : 08:22:29
Status      : PUBLISHED
```

### **Registration Details:**
```
Registration ID : 12
User ID         : 10
Token Absensi   : CERTS4U4EP
Status          : REGISTERED
```

### **Queue Configuration:**
```
QUEUE_CONNECTION: database
```

**PENTING:** Karena queue mode = `database`, perlu jalankan:
```bash
php artisan queue:work
```

Biarkan terminal tetap terbuka selama testing!

---

## 🎯 CHECKLIST UJI COBA

Centang setiap langkah yang sudah berhasil:

- [ ] Login berhasil dengan email `user.test@edufest.com`
- [ ] Bisa buka halaman event ID 50
- [ ] Tombol "Absensi" terlihat dan aktif
- [ ] Bisa input token `CERTS4U4EP`
- [ ] Absensi berhasil (ada pesan sukses)
- [ ] Queue worker running (terminal terbuka)
- [ ] Tunggu 30-60 detik
- [ ] Buka halaman "Sertifikat Saya"
- [ ] Sertifikat muncul di list
- [ ] Klik tombol "Download"
- [ ] File PDF terdownload
- [ ] Buka PDF dan lihat sertifikat

---

## 🚀 QUICK START (RINGKASAN)

```
1. Login: user.test@edufest.com / password
2. Buka: http://localhost:3000/events/50
3. Klik "Absensi"
4. Token: CERTS4U4EP
5. Submit
6. Tunggu 30-60 detik
7. Buka: http://localhost:3000/profile?section=certificates
8. Download sertifikat
```

---

## 📞 JIKA MASIH ERROR

1. **Cek Laravel Log:**
   ```
   c:\xampp\htdocs\EduFest\laravel-event-app\storage\logs\laravel.log
   ```

2. **Cek Browser Console:**
   - Tekan F12
   - Lihat tab "Console" untuk error JavaScript
   - Lihat tab "Network" untuk error API

3. **Cek Database:**
   - Buka phpMyAdmin
   - Cek tabel: `events`, `registrations`, `attendances`, `certificates`

4. **Restart Services:**
   ```bash
   # Stop semua
   # Restart Apache & MySQL di XAMPP
   # Restart React: npm start
   # Restart Queue: php artisan queue:work
   ```

---

## ✅ SELESAI!

Jika semua langkah berhasil, Anda akan mendapatkan file PDF sertifikat dengan design profesional yang sudah dibuat sebelumnya (ribbon emas, medali, ornamen biru-emas).

**Good luck! 🎉**
