# Panduan Export Data dengan Revenue Per Event

## Fitur Baru yang Ditambahkan

### 1. Export Data Peserta dengan Informasi Pembayaran
File export sekarang sudah include:
- **Harga Tiket** - Harga tiket event (atau "Gratis" jika free)
- **Status Pembayaran** - Status pembayaran (Lunas, Menunggu, Belum Bayar, dll)
- **Jumlah Dibayar** - Nominal yang sudah dibayarkan
- **Ringkasan di akhir file**:
  - Total Peserta
  - Total Peserta Berbayar
  - **Total Pendapatan** (Revenue per event)

### 2. Data Dummy untuk Testing
Seeder akan membuat:
- 50 user dummy
- 10-30 peserta per event (random)
- 80% pembayaran lunas, 20% pending
- 70% peserta hadir
- 60% dapat sertifikat

## Cara Menggunakan

### Step 1: Jalankan Seeder untuk Buat Data Dummy

```bash
cd c:\xampp\htdocs\EduFest\laravel-event-app
php artisan db:seed --class=DummyParticipantsSeeder
```

**Output yang diharapkan:**
```
Creating dummy participants...
Created 50 dummy users
Event 'Workshop Web Development': 25 participants
Event 'Seminar Digital Marketing': 18 participants
Event 'Training UI/UX Design': 22 participants
...

=== Summary ===
Total registrations created: 150
Total paid payments: 120

✓ Dummy participants seeded successfully!
```

### Step 2: Export Data via API

#### Export Peserta Per Event (dengan revenue event tersebut)
```bash
# Ganti {event_id} dengan ID event yang mau di-export
GET http://localhost:8000/api/admin/events/{event_id}/export-participants?format=csv
```

**Contoh:**
```bash
GET http://localhost:8000/api/admin/events/1/export-participants?format=csv
```

**File yang dihasilkan:** `participants-event-1.csv`

**Isi file CSV:**
```csv
ID,Nama Peserta,Email,Nama Event,Harga Tiket,Status Pembayaran,Jumlah Dibayar,Status Registrasi,Tanggal Registrasi,Status Kehadiran,Status Sertifikat,Token Sent At
1,Peserta Dummy 1,dummy1@example.com,Workshop Web Development,Rp 150.000,Lunas,Rp 150.000,Dikonfirmasi,10/11/2025 08:30,Hadir,Sudah Diterbitkan,10/11/2025 08:30
2,Peserta Dummy 2,dummy2@example.com,Workshop Web Development,Rp 150.000,Menunggu Pembayaran,Rp 0,Dikonfirmasi,09/11/2025 14:20,Tidak Hadir,Belum Diterbitkan,09/11/2025 14:20
...

RINGKASAN
Total Peserta,25
Total Peserta Berbayar,20
Total Pendapatan,Rp 3.000.000
```

#### Export Semua Peserta (semua event)
```bash
GET http://localhost:8000/api/admin/export-all-participants?format=csv
```

**File yang dihasilkan:** `all-participants.csv`

### Step 3: Test di Frontend React

1. Login sebagai admin
2. Buka halaman Admin Dashboard
3. Pilih event yang mau di-export
4. Klik tombol **Download** (icon download)
5. File CSV akan terdownload otomatis

## Format Data Export

### Kolom-kolom dalam CSV:

| Kolom | Deskripsi |
|-------|-----------|
| ID | ID registrasi |
| Nama Peserta | Nama lengkap peserta |
| Email | Email peserta |
| Nama Event | Judul event |
| **Harga Tiket** | Harga tiket (Rp xxx atau "Gratis") |
| **Status Pembayaran** | Lunas / Menunggu Pembayaran / Belum Bayar |
| **Jumlah Dibayar** | Nominal yang sudah dibayar (Rp xxx) |
| Status Registrasi | Dikonfirmasi / Menunggu / Dibatalkan |
| Tanggal Registrasi | Tanggal daftar |
| Status Kehadiran | Hadir / Tidak Hadir |
| Status Sertifikat | Sudah Diterbitkan / Belum Diterbitkan |
| Token Sent At | Waktu token dikirim |

### Ringkasan di Akhir File:

```csv
RINGKASAN
Total Peserta,25
Total Peserta Berbayar,20
Total Pendapatan,Rp 3.000.000
```

## Contoh Perhitungan Revenue

**Contoh Event: Workshop Web Development**
- Harga tiket: Rp 150.000
- Total peserta: 25 orang
- Peserta yang sudah bayar: 20 orang
- **Total Pendapatan: Rp 3.000.000** (20 × Rp 150.000)

**Contoh Event Gratis: Seminar Motivasi**
- Harga tiket: Gratis
- Total peserta: 30 orang
- **Total Pendapatan: Rp 0**

## Tips

1. **Untuk reset data dummy:**
   ```bash
   # Hapus data dummy (optional)
   DELETE FROM registrations WHERE user_id IN (SELECT id FROM users WHERE email LIKE 'dummy%@example.com');
   DELETE FROM users WHERE email LIKE 'dummy%@example.com';
   
   # Jalankan seeder lagi
   php artisan db:seed --class=DummyParticipantsSeeder
   ```

2. **Untuk melihat total revenue semua event:**
   - Export all participants
   - Buka file CSV
   - Lihat ringkasan di bagian bawah

3. **Format file:**
   - Default: CSV (bisa dibuka di Excel/Google Sheets)
   - Encoding: UTF-8
   - Delimiter: Comma (,)

## Troubleshooting

### Error: "No events found"
**Solusi:** Buat event dulu sebelum jalankan seeder
```bash
# Buat event via admin panel atau via API
```

### File CSV tidak bisa dibuka di Excel
**Solusi:** 
1. Buka Excel
2. File > Import > Text/CSV
3. Pilih file CSV
4. Set encoding ke UTF-8
5. Set delimiter ke Comma

### Data dummy terlalu sedikit
**Solusi:** Edit file seeder, ubah angka di line:
```php
// Dari 50 jadi 100
for ($i = 1; $i <= 100; $i++) {

// Dari 10-30 jadi 20-50
$participantCount = rand(20, 50);
```

## File yang Dimodifikasi

1. **app/Exports/EventParticipantsExport.php**
   - Tambah kolom: Harga Tiket, Status Pembayaran, Jumlah Dibayar
   - Tambah ringkasan: Total Peserta, Total Peserta Berbayar, Total Pendapatan

2. **database/seeders/DummyParticipantsSeeder.php** (NEW)
   - Seeder untuk buat 50 user dummy
   - Generate 10-30 peserta per event
   - Generate payment data (80% paid, 20% pending)

## Selesai! 🎉

Sekarang admin bisa:
- ✅ Export data peserta per event
- ✅ Lihat harga tiket dan status pembayaran
- ✅ **Lihat total pendapatan (revenue) per event**
- ✅ Test dengan data dummy yang banyak
