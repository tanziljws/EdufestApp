# 🎓 PANDUAN USER - CARA MENDAPATKAN SERTIFIKAT EDUFEST

## 📋 CARA MENDAPATKAN SERTIFIKAT

### Langkah-langkah:

#### 1️⃣ **Daftar Event**
- Buka halaman **Events** atau **Jelajahi Event**
- Pilih event yang ingin diikuti
- Klik tombol **"Daftar Sekarang"**
- Isi form pendaftaran dengan lengkap
- Submit pendaftaran

#### 2️⃣ **Hadiri Event**
- Datang ke lokasi event sesuai jadwal
- Lakukan **check-in** dengan:
  - Scan QR code (jika tersedia)
  - Input token kehadiran yang diberikan panitia
  - Atau konfirmasi kehadiran via sistem

#### 3️⃣ **Tunggu Sertifikat Di-generate**
- Setelah event selesai, sistem akan otomatis generate sertifikat
- Proses biasanya memakan waktu **30-60 detik**
- Sertifikat akan muncul di halaman **"Sertifikat Saya"**

#### 4️⃣ **Download Sertifikat**
- Buka menu **Profile** → **Sertifikat**
- Atau langsung ke: `localhost:3000/profile?section=certificates`
- Klik tombol **"Download"** pada sertifikat yang ingin diunduh
- File PDF akan terdownload otomatis

## 🔍 CARA MENGAKSES HALAMAN SERTIFIKAT

### Via Menu Profile:
1. Login ke akun Anda
2. Klik **icon profile** di navbar (pojok kanan atas)
3. Pilih menu **"Sertifikat"** di sidebar
4. Atau klik tab **"Sertifikat"** di halaman profile

### Via Dashboard:
1. Login ke akun Anda
2. Buka halaman **Dashboard**
3. Klik tombol **"Sertifikat Saya"** di Quick Actions
4. Atau scroll ke section **"Riwayat Event"** dan klik link sertifikat

### Via Direct URL:
- Buka: `http://localhost:3000/profile?section=certificates`

## 📱 FITUR HALAMAN SERTIFIKAT

### 🔎 **Search & Filter**

**Search Box:**
- Cari berdasarkan nama event
- Cari berdasarkan nama peserta
- Cari berdasarkan nomor seri sertifikat
- Contoh: ketik "Seminar" atau "CERT-2025-XXXXX"

**Filter Status:**
- **Semua Status** - Tampilkan semua sertifikat
- **Tersedia** - Sertifikat yang siap didownload
- **Diproses** - Sertifikat yang sedang di-generate
- **Kedaluwarsa** - Sertifikat yang sudah tidak valid

### 📊 **Informasi Sertifikat**

Setiap card sertifikat menampilkan:
- ✅ **Nama Event** - Judul event yang diikuti
- ✅ **Nama Peserta** - Nama Anda di sertifikat
- ✅ **Nomor Seri** - Unique ID sertifikat (CERT-2025-XXXXXXXX)
- ✅ **Tanggal Terbit** - Kapan sertifikat diterbitkan
- ✅ **Kategori Event** - Teknologi, Seni & Budaya, Olahraga, dll
- ✅ **Status** - Badge warna hijau (Tersedia), kuning (Diproses), merah (Kedaluwarsa)

### 🎯 **Aksi yang Tersedia**

**Button "Lihat":**
- Preview sertifikat sebelum download
- Melihat detail lengkap sertifikat

**Button "Download":**
- Download sertifikat dalam format PDF
- File akan tersimpan di folder Downloads
- Nama file: `Sertifikat_CERT-2025-XXXXXXXX.pdf`

**Button "Refresh":**
- Reload data sertifikat terbaru
- Gunakan jika sertifikat baru belum muncul

## 📥 CARA DOWNLOAD SERTIFIKAT

### Langkah Detail:

1. **Buka Halaman Sertifikat**
   - Login → Profile → Sertifikat

2. **Cari Sertifikat yang Diinginkan**
   - Gunakan search box jika punya banyak sertifikat
   - Atau scroll untuk melihat semua

3. **Klik Button "Download"**
   - Button berwarna biru dengan icon download
   - Pastikan status sertifikat "Tersedia" (hijau)

4. **Tunggu Download Selesai**
   - Browser akan otomatis download file PDF
   - Check folder Downloads di komputer Anda

5. **Buka File PDF**
   - Double-click file yang terdownload
   - Atau buka dengan PDF reader (Adobe Reader, Chrome, dll)

### Format File:
- **Type:** PDF (Portable Document Format)
- **Size:** ~200KB - 500KB per file
- **Quality:** High resolution, siap print
- **Orientation:** Landscape (horizontal)
- **Paper Size:** A4 (297mm x 210mm)

## 🖨️ CARA PRINT SERTIFIKAT

### Langkah Print:

1. **Buka File PDF** yang sudah didownload

2. **Klik Print** atau tekan `Ctrl + P`

3. **Setting Print:**
   - **Orientation:** Landscape (horizontal)
   - **Paper Size:** A4
   - **Quality:** Best / High
   - **Color:** Color (jika ada warna di design)
   - **Scale:** Fit to page / 100%

4. **Preview** sebelum print untuk memastikan layout benar

5. **Print** ke printer

### Tips Print:
- ✅ Gunakan kertas berkualitas baik (min 80gsm)
- ✅ Gunakan printer color untuk hasil terbaik
- ✅ Check preview sebelum print untuk avoid waste
- ✅ Print 1 copy dulu untuk test quality
- ❌ Jangan scale/resize, biarkan 100%

## ❓ FAQ (Frequently Asked Questions)

### Q: Kapan sertifikat saya akan tersedia?
**A:** Sertifikat biasanya tersedia **30-60 detik** setelah Anda mark attendance di event. Jika lebih dari 5 menit belum muncul, coba:
1. Klik button "Refresh" di halaman sertifikat
2. Logout dan login kembali
3. Contact admin jika masih belum muncul

### Q: Saya sudah hadir tapi sertifikat belum muncul?
**A:** Pastikan:
1. ✅ Anda sudah **mark attendance** dengan benar (input token/scan QR)
2. ✅ Status attendance Anda adalah **"Present"** (bukan "Absent")
3. ✅ Event sudah selesai (sertifikat di-generate setelah event)
4. ✅ Admin sudah approve attendance Anda

### Q: Sertifikat tidak bisa didownload?
**A:** Coba langkah berikut:
1. Check status sertifikat - harus **"Tersedia"** (hijau)
2. Refresh halaman dengan button "Refresh"
3. Clear browser cache dan cookies
4. Coba browser lain (Chrome, Firefox, Edge)
5. Check koneksi internet Anda
6. Contact admin jika masih error

### Q: File PDF rusak atau tidak bisa dibuka?
**A:** Solusi:
1. Download ulang sertifikat dari website
2. Gunakan PDF reader yang update (Adobe Reader, Chrome)
3. Check apakah file size normal (~200KB-500KB)
4. Jika file size terlalu kecil (<10KB), download ulang
5. Report ke admin jika masalah persist

### Q: Nama saya salah di sertifikat?
**A:** Hubungi admin untuk:
1. Update nama di profile Anda
2. Re-generate sertifikat dengan nama yang benar
3. Admin akan issue sertifikat baru

### Q: Sertifikat hilang dari daftar?
**A:** Kemungkinan:
1. Anda menggunakan akun yang berbeda
2. Sertifikat di-revoke oleh admin
3. Bug sistem - contact admin

### Q: Bisa download sertifikat berkali-kali?
**A:** **Ya!** Anda bisa download sertifikat sebanyak yang Anda mau. File akan selalu tersedia di sistem.

### Q: Sertifikat bisa diverifikasi?
**A:** Ya, setiap sertifikat memiliki:
- **Nomor Seri Unik** (CERT-2025-XXXXXXXX)
- Bisa dicek di halaman verifikasi (jika tersedia)
- Admin bisa verify authenticity via nomor seri

### Q: Sertifikat berlaku selamanya?
**A:** Ya, sertifikat yang sudah diterbitkan berlaku **permanen** kecuali:
- Di-revoke oleh admin karena alasan tertentu
- Event dibatalkan atau invalid

## 🎨 CONTOH TAMPILAN SERTIFIKAT

### Card Sertifikat di Website:
```
┌─────────────────────────────────────────┐
│  [Badge: Tersedia]     [Badge: Peserta] │
│                                         │
│  📜 Seminar Kewirausahaan Digital       │
│                                         │
│  Sertifikat untuk: Meitanti Fadilah     │
│  No. CERT-2025-A4BEE4E6                 │
│                                         │
│  📅 Diterbitkan: 10 September 2025      │
│  🏆 Kategori: Teknologi                 │
│                                         │
│  [Button: Lihat]  [Button: Download]   │
└─────────────────────────────────────────┘
```

### PDF Sertifikat:
- Design profesional dengan logo sekolah
- Nama peserta di tengah (font besar, bold)
- Deskripsi event di bawah nama
- Tanda tangan kepala sekolah & koordinator
- Nomor seri & tanggal terbit di footer

## 📊 STATISTIK SERTIFIKAT

Di halaman Dashboard, Anda bisa lihat:
- **Total Sertifikat** yang Anda miliki
- **Event yang Diikuti** (dengan sertifikat)
- **Kategori Event** yang paling banyak
- **Timeline** perolehan sertifikat

## 🔐 KEAMANAN & PRIVASI

### Keamanan:
- ✅ Hanya Anda yang bisa download sertifikat Anda
- ✅ Sertifikat dilindungi dengan authentication
- ✅ Nomor seri unik untuk prevent fraud
- ✅ File PDF tidak bisa diedit

### Privasi:
- ✅ Data pribadi Anda aman
- ✅ Sertifikat hanya visible untuk Anda
- ✅ Admin tidak bisa download sertifikat user tanpa permission

## 💡 TIPS & TRICKS

### Tip 1: Organize Sertifikat
- Buat folder khusus di komputer untuk simpan semua sertifikat
- Beri nama file yang deskriptif: `Sertifikat_Seminar_2025.pdf`
- Backup ke cloud storage (Google Drive, Dropbox)

### Tip 2: Portfolio Digital
- Kumpulkan semua sertifikat untuk portfolio
- Upload ke LinkedIn atau platform profesional
- Gunakan untuk apply job atau beasiswa

### Tip 3: Print & Frame
- Print sertifikat penting dan frame
- Display di kamar atau workspace
- Motivasi untuk ikut lebih banyak event

### Tip 4: Share Achievement
- Share screenshot sertifikat di social media
- Tag @EduFest dan @SMKN4Bogor
- Inspire teman-teman untuk join event

## 📞 BUTUH BANTUAN?

### Contact Support:
- **Email:** admin@edufest.com
- **WhatsApp:** +62 xxx-xxxx-xxxx
- **Instagram:** @edufest.official
- **Website:** https://edufest.smkn4bogor.sch.id

### Jam Operasional:
- Senin - Jumat: 08:00 - 16:00 WIB
- Sabtu: 08:00 - 12:00 WIB
- Minggu & Libur: Closed

---

**Selamat mengikuti event dan semoga sukses! 🎉**

**Last Updated:** November 5, 2025
**Version:** 1.0
