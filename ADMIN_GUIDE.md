# 🔐 EduFest Admin Guide

## Cara Login Admin

### Akun Admin yang Tersedia
```
Admin 1:
Email: admin@smkn4bogor.sch.id
Password: admin123

Admin 2:
Email: admin@edufest.com
Password: password
```

### Langkah Login
1. Buka aplikasi EduFest di browser
2. Klik "Login" di navbar
3. Masukkan email dan password admin
4. Setelah login berhasil, akses `/admin/dashboard`

## 📊 Halaman Admin

### 1. Dashboard (`/admin/dashboard`)
**Fitur:**
- Statistics cards: Total Events, Registrations, Attendees, Attendance Rate
- Bar chart: Kegiatan per bulan (Januari-Desember)
- Bar chart: Peserta hadir per bulan
- Top 10 events dengan peserta terbanyak
- Weekly plan progress (60%)
- Quick export buttons
- Year selector untuk filter data

**Cara Menggunakan:**
- Pilih tahun di dropdown untuk melihat data historis
- Klik "Refresh" untuk update data terbaru
- Gunakan tombol export untuk download data

### 2. Event Management (`/admin/events`)
**Fitur:**
- Tabel semua events dengan search dan filter
- Publish/unpublish events
- Export participants per event
- CRUD operations (view, edit, delete)
- Filter berdasarkan kategori
- Pagination

**Cara Menggunakan:**
- Search events menggunakan search box
- Filter berdasarkan kategori (Teknologi, Seni & Budaya, dll)
- Toggle status published dengan klik status badge
- Export peserta event dengan klik icon download
- Edit/delete event dengan action buttons

### 3. Participants (`/admin/participants`)
**Fitur:**
- Overview peserta dengan statistics
- Filter berdasarkan status registrasi
- Export data peserta

**Cara Menggunakan:**
- Lihat statistik peserta di cards atas
- Filter peserta berdasarkan status
- Export semua data peserta dengan tombol "Export Data"

### 4. Reports (`/admin/reports`)
**Fitur:**
- Summary statistics untuk tahun tertentu
- Charts untuk monthly events dan attendees
- Top events ranking
- Quick stats dan export options

**Cara Menggunakan:**
- Pilih tahun untuk melihat laporan
- Lihat grafik perbandingan bulanan
- Export laporan dalam format CSV

### 5. Export Data (`/admin/export`)
**Fitur:**
- Export Events, Registrations, Attendances
- Export history tracking
- Format CSV dengan UTF-8 encoding
- Export guidelines

**Cara Menggunakan:**
- Pilih jenis data yang ingin di-export
- Klik "Export CSV" untuk download
- Lihat riwayat export di bagian bawah

## 🔒 Keamanan Admin

### Role-based Access
- Hanya user dengan `role = 'admin'` bisa akses halaman admin
- User biasa akan diarahkan ke dashboard user jika coba akses admin
- Authentication menggunakan Bearer token

### Validasi Admin Routes
```javascript
// AdminRoute component melakukan pengecekan:
if (user.role !== 'admin' && !user.is_admin) {
  return <Navigate to="/dashboard" />;
}
```

## 📋 Aturan Bisnis Admin

### Event Management
1. **H-3 Rule**: Admin hanya bisa buat event minimal H-3 dari tanggal event
2. **Auto-close Registration**: Pendaftaran otomatis tutup saat event dimulai
3. **Category Validation**: Event harus memiliki kategori yang valid
4. **Publish Control**: Admin bisa publish/unpublish event

### Data Export
1. **Real-time Data**: Export mengambil data terbaru dari database
2. **UTF-8 Encoding**: File CSV menggunakan encoding UTF-8
3. **Security**: Data sensitif sudah di-filter
4. **Format**: CSV kompatibel dengan Excel dan Google Sheets

## 🚀 Tips Penggunaan

### Dashboard
- Gunakan year selector untuk analisis historis
- Refresh data secara berkala untuk update terbaru
- Perhatikan attendance rate untuk evaluasi event

### Event Management
- Publish event setelah semua data lengkap
- Monitor jumlah peserta secara real-time
- Export data peserta sebelum event untuk persiapan

### Reports
- Bandingkan data antar bulan untuk trend analysis
- Identifikasi bulan dengan aktivitas tertinggi
- Gunakan top events data untuk planning event future

### Export
- Export data secara berkala untuk backup
- Gunakan export history untuk tracking
- Pastikan data ter-download dengan benar

## 🔧 Troubleshooting

### Login Issues
- Pastikan menggunakan email dan password yang benar
- Cek apakah Laravel backend berjalan di port 8000
- Verify database connection

### Access Issues
- Pastikan user memiliki role 'admin' di database
- Clear browser cache jika ada masalah routing
- Check network connection ke backend API

### Export Issues
- Pastikan browser mengizinkan download
- Check file permissions di server
- Verify export API endpoints

## 📞 Support

Jika mengalami masalah:
1. Check browser console untuk error messages
2. Verify Laravel logs di `storage/logs/laravel.log`
3. Pastikan semua services berjalan (Laravel + React)
4. Check database connection dan migrations

---

**EduFest Admin System v1.0**  
*SMKN 4 Bogor - Event Management System*
