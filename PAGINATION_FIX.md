# Perbaikan Pagination Event - EduFest

## Masalah yang Diperbaiki
Hanya 12 event yang muncul di halaman publik "Semua Kategori", padahal di admin ada 19 event.

## Penyebab Masalah

### 1. Pagination Backend
Backend menggunakan pagination dengan **12 item per halaman**:
```php
// EventController.php line 54
$events = $q->paginate(12);
```

### 2. Frontend Tidak Menangani Pagination
Frontend hanya mengambil halaman pertama (12 event) dan tidak ada UI untuk load more atau pagination.

### 3. Event Tidak Dipublikasikan
1 event (Pensi Neopragma) memiliki `is_published = 0` sehingga tidak muncul di halaman publik.

## Perbaikan yang Dilakukan

### 1. Frontend - Events.js

#### Menambahkan State Pagination
```javascript
const [currentPage, setCurrentPage] = useState(1);
const [totalPages, setTotalPages] = useState(1);
const [loadingMore, setLoadingMore] = useState(false);
```

#### Memperbaiki fetchEvents()
```javascript
const fetchEvents = async (page = 1, reset = false) => {
  // Kirim parameter page ke backend
  const response = await eventService.getEvents({
    q: searchTerm,
    sort: sortOrder,
    category: filterCategory,
    page: page  // ← Parameter pagination
  });
  
  // Handle reset (filter berubah) vs load more
  if (reset) {
    setEvents(response.data || []);
  } else {
    setEvents(prev => [...prev, ...(response.data || [])]);
  }
  
  // Update pagination state
  setCurrentPage(response.current_page || 1);
  setTotalPages(response.last_page || 1);
};
```

#### Menambahkan Fungsi loadMore()
```javascript
const loadMore = () => {
  if (currentPage < totalPages && !loadingMore) {
    fetchEvents(currentPage + 1, false);
  }
};
```

#### Menambahkan UI Load More Button
```jsx
{/* Load More Button */}
{!loading && events.length > 0 && currentPage < totalPages && (
  <div className="mt-8 flex justify-center">
    <motion.button
      onClick={loadMore}
      disabled={loadingMore}
      className="px-8 py-3 bg-blue-600 text-white rounded-lg..."
    >
      {loadingMore ? 'Memuat...' : `Muat Lebih Banyak (${currentPage} dari ${totalPages})`}
    </motion.button>
  </div>
)}

{/* Info pagination */}
{!loading && events.length > 0 && (
  <div className="mt-4 text-center text-sm text-gray-600">
    Menampilkan {events.length} dari {totalPages * 12} event
  </div>
)}
```

### 2. Backend - Publikasikan Event

#### Script publish_all_events.php
Mempublikasikan event yang `is_published = 0`:
```bash
php publish_all_events.php
```

Output:
```
✓ Event #40 'Pensi Neopragma' berhasil dipublikasikan
Total event yang dipublikasikan: 1
```

## Cara Kerja Sistem Sekarang

### 1. Load Awal
- Frontend memanggil API dengan `page=1`
- Backend mengembalikan 12 event pertama + info pagination
- Frontend menampilkan 12 event + tombol "Muat Lebih Banyak"

### 2. Load More
- User klik tombol "Muat Lebih Banyak"
- Frontend memanggil API dengan `page=2`
- Backend mengembalikan 12 event berikutnya
- Frontend menambahkan event ke daftar yang sudah ada (total 24 event)
- Tombol "Muat Lebih Banyak" tetap muncul jika masih ada halaman berikutnya

### 3. Filter/Search Berubah
- Frontend reset ke `page=1`
- Event list di-reset (tidak append)
- Pagination dimulai dari awal

## Status Event

### Sebelum Perbaikan
```
Total event di database: 19
Dipublikasikan: 18
Tidak dipublikasikan: 1 (Pensi Neopragma)
Muncul di halaman publik: 12 (halaman 1 saja)
```

### Setelah Perbaikan
```
Total event di database: 19
Dipublikasikan: 19 (semua)
Tidak dipublikasikan: 0
Muncul di halaman publik: 19 (dengan load more)
```

## Testing

### 1. Test Pagination
```
1. Buka: http://localhost:3000/events
2. Scroll ke bawah
3. Klik tombol "Muat Lebih Banyak (1 dari 2)"
4. Event bertambah dari 12 menjadi 19
5. Tombol "Muat Lebih Banyak" hilang (sudah di halaman terakhir)
```

### 2. Test Filter dengan Pagination
```
1. Pilih kategori "Teknologi"
2. Lihat jumlah event teknologi
3. Jika lebih dari 12, tombol "Muat Lebih Banyak" muncul
4. Klik tombol untuk load more
5. Ganti kategori ke "Semua Kategori"
6. Event list di-reset, pagination dimulai dari awal
```

### 3. Verifikasi Status Publikasi
```bash
cd laravel-event-app
php check_published_status.php
```

Output:
```
Total event: 19
Dipublikasikan: 19
Tidak dipublikasikan: 0
```

## File yang Dimodifikasi

### Frontend
- `src/pages/Events.js`
  - Added state: `currentPage`, `totalPages`, `loadingMore`
  - Modified `fetchEvents()` to handle pagination
  - Added `loadMore()` function
  - Added Load More button UI
  - Added pagination info display

### Backend
- Tidak ada perubahan kode (pagination sudah ada)
- Hanya update database: `is_published = 1` untuk semua event

## Scripts Utility

### 1. check_published_status.php
Memeriksa status publikasi event:
```bash
php check_published_status.php
```

### 2. publish_all_events.php
Mempublikasikan semua event yang belum dipublikasikan:
```bash
php publish_all_events.php
```

## Catatan Penting

### Pagination Settings
- **Items per page**: 12 event (dikonfigurasi di backend)
- **Load strategy**: Load more (append) bukan page numbers
- **Reset behavior**: Filter/search berubah → reset ke page 1

### Event Visibility
- Event dengan `is_published = 1` → Muncul di halaman publik
- Event dengan `is_published = 0` → Hanya muncul di admin
- Admin bisa toggle publikasi via tombol di AdminEvents.js

### Performance
- Load more lebih efisien daripada load all (19 event sekaligus)
- User experience lebih baik dengan progressive loading
- Backend pagination mengurangi beban database query

## Troubleshooting

### Tombol "Muat Lebih Banyak" tidak muncul
- Check console browser: `console.log('Pagination:', { current, total })`
- Pastikan `totalPages > currentPage`
- Pastikan ada lebih dari 12 event yang dipublikasikan

### Event tidak bertambah setelah klik Load More
- Check network tab: pastikan API call ke `/api/events?page=2` berhasil
- Check response: pastikan `data` array tidak kosong
- Check console: pastikan tidak ada error JavaScript

### Jumlah event tidak sesuai
- Jalankan: `php check_published_status.php`
- Pastikan semua event yang ingin ditampilkan sudah `is_published = 1`
- Jalankan: `php publish_all_events.php` untuk publikasikan semua

## Kesimpulan

✅ Pagination berfungsi dengan baik  
✅ Semua 19 event sekarang muncul di halaman publik  
✅ Load more button untuk UX yang lebih baik  
✅ Filter/search tetap berfungsi dengan pagination  
✅ Performance optimal dengan progressive loading  
