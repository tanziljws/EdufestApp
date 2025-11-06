# 🚀 QUICK START - TESTING SISTEM SERTIFIKAT

**Panduan cepat untuk test sistem sertifikat yang sudah diperbaiki**

---

## ⚡ QUICK TEST (5 Menit)

### 1. Start Servers

```bash
# Terminal 1 - Laravel Backend
cd c:\xampp\htdocs\EduFest\laravel-event-app
php artisan serve
# Server running on http://127.0.0.1:8000

# Terminal 2 - React Frontend
cd c:\xampp\htdocs\EduFest\frontend-react.js
npm start
# Server running on http://localhost:3000
```

### 2. Test Backend

```bash
# Di folder laravel-event-app
php check_certificates.php
```

**Expected Output:**
```
=== CHECKING CERTIFICATES DATA ===
Total Certificates: 3
✅ All certificates have valid files
```

### 3. Test Frontend

1. **Buka browser:** `http://localhost:3000`
2. **Login dengan:**
   - Email: `meytantifadila@gmail.com`
   - Password: (password user Meitanti Fadilah)
3. **Navigate:** Profile → Sertifikat
4. **Verify:**
   - ✅ Sertifikat muncul
   - ✅ Informasi lengkap (nama, serial, tanggal)
   - ✅ Button Download ada
5. **Click Download**
   - ✅ PDF terdownload
   - ✅ File bisa dibuka
   - ✅ Isi sertifikat benar

---

## 🧪 DETAILED TESTING

### Backend Testing

```bash
# 1. Check database status
php check_certificates.php

# 2. Test API endpoint
php test_certificate_api.php

# 3. Test PDF download
php test_certificate_download.php

# 4. Generate certificate manual (optional)
php generate_certificate_manual.php

# 5. Generate all eligible certificates (optional)
php generate_all_eligible_certificates.php
```

### Frontend Testing

**Test Checklist:**

- [ ] **Page Load**
  - Buka: `http://localhost:3000/profile?section=certificates`
  - No errors di console
  - Loading state muncul sebentar

- [ ] **Display Certificates**
  - Sertifikat muncul dalam grid
  - Card menampilkan: nama event, nama peserta, serial number, tanggal
  - Badge status berwarna (hijau = Tersedia)

- [ ] **Search Function**
  - Ketik nama event → hasil filter
  - Ketik serial number → hasil filter
  - Clear search → semua muncul lagi

- [ ] **Filter Function**
  - Select "Tersedia" → hanya yang tersedia
  - Select "Semua Status" → semua muncul

- [ ] **Download Function**
  - Click button "Download"
  - PDF terdownload ke folder Downloads
  - Nama file: `Sertifikat_CERT-2025-XXXXXXXX.pdf`
  - File bisa dibuka dengan PDF reader

- [ ] **Refresh Function**
  - Click button "Refresh"
  - Data reload
  - No errors

---

## 🎯 TEST SCENARIOS

### Scenario 1: User Baru (Belum Punya Sertifikat)

1. Login dengan user yang belum punya sertifikat
2. Buka halaman Sertifikat
3. **Expected:** Empty state dengan message "Belum ada sertifikat"
4. **Expected:** Button "Jelajahi Event" muncul
5. Click button → redirect ke halaman Events

### Scenario 2: User dengan Sertifikat

1. Login dengan user yang punya sertifikat (Meitanti Fadilah)
2. Buka halaman Sertifikat
3. **Expected:** Sertifikat muncul (1 sertifikat)
4. **Expected:** Info lengkap ditampilkan
5. Click Download → PDF terdownload
6. Buka PDF → isi benar

### Scenario 3: Search & Filter

1. Login dengan user yang punya banyak sertifikat
2. **Test Search:**
   - Ketik "Latihan" → filter ke event "Latihan Frontend"
   - Ketik "CERT-2025" → filter by serial number
   - Clear → semua muncul
3. **Test Filter:**
   - Select "Tersedia" → hanya yang available
   - Select "Diproses" → hanya yang processing
   - Select "Semua Status" → semua muncul

### Scenario 4: Download Multiple

1. Login dengan user yang punya banyak sertifikat
2. Download sertifikat pertama → success
3. Download sertifikat kedua → success
4. Download sertifikat yang sama lagi → success (bisa download berkali-kali)

### Scenario 5: Error Handling

1. **Test Offline:**
   - Matikan internet
   - Refresh halaman
   - **Expected:** Error message muncul
   
2. **Test Invalid Token:**
   - Logout
   - Try access `/profile?section=certificates`
   - **Expected:** Redirect ke login

---

## 🐛 COMMON ISSUES & FIXES

### Issue 1: "Gagal mengambil sertifikat"

**Cause:** Backend not running atau API error

**Fix:**
```bash
# Check Laravel server
netstat -ano | findstr :8000

# If not running, start it
cd laravel-event-app
php artisan serve

# Check logs
tail -f storage/logs/laravel.log
```

### Issue 2: Sertifikat tidak muncul

**Cause:** User belum punya sertifikat atau data tidak sync

**Fix:**
```bash
# Check database
php check_certificates.php

# Generate certificates for eligible users
php generate_all_eligible_certificates.php

# Refresh frontend
Click button "Refresh" di halaman sertifikat
```

### Issue 3: Download tidak berfungsi

**Cause:** File tidak ada atau permission issue

**Fix:**
```bash
# Check file exists
php test_certificate_download.php

# Check storage permission
chmod -R 775 storage/

# Recreate symlink
php artisan storage:link

# Clear cache
php artisan cache:clear
```

### Issue 4: PDF rusak atau tidak bisa dibuka

**Cause:** PDF generation error atau file corrupted

**Fix:**
```bash
# Regenerate certificate
php generate_certificate_manual.php

# Check file size
ls -lh storage/app/public/certificates/

# If size < 10KB, file is corrupted
# Delete and regenerate
rm storage/app/public/certificates/CERT-2025-XXXXX.pdf
php generate_certificate_manual.php
```

---

## 📊 VERIFICATION CHECKLIST

### Backend ✅

- [ ] Laravel server running on port 8000
- [ ] Database has certificates table
- [ ] Certificates records exist
- [ ] PDF files exist in storage/app/public/certificates/
- [ ] Files are valid PDFs (check with test script)
- [ ] API endpoints return correct data
- [ ] No errors in Laravel logs

### Frontend ✅

- [ ] React server running on port 3000
- [ ] No console errors
- [ ] Page loads correctly
- [ ] Certificates display correctly
- [ ] Search works
- [ ] Filter works
- [ ] Download works
- [ ] Refresh works
- [ ] Empty state works

### Integration ✅

- [ ] Frontend can call backend API
- [ ] Authentication works
- [ ] Data flows correctly
- [ ] Download triggers correctly
- [ ] PDF opens in browser/reader
- [ ] No CORS errors
- [ ] No network errors

---

## 🎓 TEST USERS

### User dengan Sertifikat:

**User 1:**
- Email: `meytantifadila@gmail.com`
- Name: Meitanti Fadilah
- Certificates: 1 (Latihan Frontend)

**User 2:**
- Email: `fadilahmeita0@gmail.com`
- Name: Meitanti
- Certificates: 2 (Seminar Kewirausahaan, Workshop Desain Grafis)

### User tanpa Sertifikat:

**User 3:**
- Email: `nurjamilalfath@gmail.com`
- Name: Alfath
- Certificates: 0

---

## 📈 PERFORMANCE BENCHMARKS

### Expected Performance:

- **Page Load:** < 2 seconds
- **API Call (/me/certificates):** < 500ms
- **Download PDF:** < 3 seconds
- **Search/Filter:** Instant (< 100ms)
- **Refresh:** < 1 second

### If Slower:

1. Check network connection
2. Check server load
3. Check database queries
4. Check file size
5. Optimize if needed

---

## 🔍 DEBUG MODE

### Enable Debug Logging:

**Backend (Laravel):**
```php
// In .env
APP_DEBUG=true
LOG_LEVEL=debug

// Check logs
tail -f storage/logs/laravel.log
```

**Frontend (React):**
```javascript
// Already has console.log in Certificates.js
// Check browser console (F12)
```

### Debug Steps:

1. **Open Browser DevTools** (F12)
2. **Go to Network tab**
3. **Refresh page**
4. **Check API calls:**
   - `/api/me/certificates` - Should return 200
   - Response should be array of certificates
5. **Check Console tab:**
   - Should see: "Fetched certificates: [...]"
   - No red errors

---

## ✅ SUCCESS INDICATORS

### You know it's working when:

1. ✅ No errors in browser console
2. ✅ No errors in Laravel logs
3. ✅ Certificates display on page
4. ✅ All information correct (name, event, date, serial)
5. ✅ Download button works
6. ✅ PDF downloads successfully
7. ✅ PDF opens and displays correctly
8. ✅ Search filters results
9. ✅ Filter changes display
10. ✅ Refresh reloads data

---

## 🎉 READY FOR PRODUCTION

### Final Checks:

- [ ] All tests passed
- [ ] No errors anywhere
- [ ] Performance acceptable
- [ ] UI looks good
- [ ] UX is smooth
- [ ] Documentation complete
- [ ] Team trained
- [ ] Backup created

### Deploy:

```bash
# 1. Backup
mysqldump -u root edufest > backup_$(date +%Y%m%d).sql
tar -czf storage_backup_$(date +%Y%m%d).tar.gz storage/

# 2. Pull latest code
git pull origin main

# 3. Install dependencies
composer install --no-dev
npm install
npm run build

# 4. Migrate (if needed)
php artisan migrate

# 5. Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# 6. Set permissions
chmod -R 775 storage bootstrap/cache

# 7. Restart services
# (depends on your server setup)

# 8. Verify
# Test all scenarios again on production
```

---

## 📞 NEED HELP?

### Resources:

1. **Main Documentation:** `CERTIFICATE_SYSTEM_FIXED.md`
2. **Admin Guide:** `ADMIN_CERTIFICATE_GUIDE.md`
3. **User Guide:** `USER_CERTIFICATE_GUIDE.md`
4. **Testing Checklist:** `TESTING_CHECKLIST.md`
5. **Summary:** `SUMMARY_PERBAIKAN_SERTIFIKAT.md`

### Contact:

- Check documentation first
- Run testing scripts
- Review Laravel logs
- Check browser console
- Ask team if stuck

---

**Happy Testing! 🚀**

**Last Updated:** November 5, 2025
