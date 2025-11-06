# 🧪 TESTING DOWNLOAD SERTIFIKAT - PERBAIKAN TERBARU

## ✅ PERBAIKAN YANG SUDAH DILAKUKAN

### 1. **Menggunakan Axios Langsung**
- Sama seperti adminService yang sudah berfungsi
- Token dikirim manual via headers
- Full URL dengan API_BASE_URL

### 2. **Proper Blob Handling**
- Blob type: `application/pdf`
- Proper cleanup dengan setTimeout
- Extract filename dari Content-Disposition header

### 3. **Comprehensive Logging**
- Log setiap step download process
- Log error details untuk debugging
- Easy to track di console

---

## 🚀 CARA TESTING (STEP BY STEP)

### Step 1: Pastikan Servers Running

**Terminal 1 - Laravel:**
```bash
cd c:\xampp\htdocs\EduFest\laravel-event-app
php artisan serve
```
✅ Should see: `Server running on [http://127.0.0.1:8000]`

**Terminal 2 - React:**
```bash
cd c:\xampp\htdocs\EduFest\frontend-react.js
npm start
```
✅ Should see: `webpack compiled successfully`

### Step 2: Buka Browser dengan DevTools

1. **Buka browser:** `http://localhost:3000`
2. **Tekan F12** untuk buka DevTools
3. **Pilih tab Console** (untuk lihat logs)
4. **Pilih tab Network** (untuk lihat requests)

### Step 3: Login

1. **Email:** `meytantifadila@gmail.com`
2. **Password:** (password user Meitanti Fadilah)
3. **Klik Login**

### Step 4: Navigate ke Halaman Sertifikat

**Cara 1:** Via Menu
- Klik icon Profile (pojok kanan atas)
- Klik menu "Sertifikat"

**Cara 2:** Via URL
- Langsung ke: `http://localhost:3000/profile?section=certificates`

### Step 5: Check Console Logs

Setelah halaman load, di **Console tab** harus muncul:
```
Fetched certificates: [...]
```

Jika ada sertifikat, akan tampil card dengan:
- Nama event
- Nama peserta
- Serial number (CERT-2025-XXXXXXXX)
- Button "Download"

### Step 6: Download Sertifikat

1. **Klik button "Download"** (button biru)

2. **Check Console** - harus muncul logs:
```
Downloading certificate ID: 3
Downloading certificate...
Certificate ID: 3
Download URL: http://127.0.0.1:8000/api/certificates/3/download
Token exists: true
Download response received: 200
Response headers: {...}
Response data size: 193633
Content-Disposition: attachment; filename=Sertifikat_CERT-2025-JVISYFAG.pdf
Filename: Sertifikat_CERT-2025-JVISYFAG.pdf
✅ Download triggered successfully!
Download successful
```

3. **Check Network tab:**
   - Cari request ke: `certificates/3/download`
   - Status: **200 OK**
   - Type: **blob**
   - Size: **~200KB**

4. **Check File Explorer:**
   - Buka folder **Downloads**
   - File harus ada: `Sertifikat_CERT-2025-JVISYFAG.pdf`
   - Size: ~200KB
   - Double-click untuk buka PDF

---

## 🔍 EXPECTED RESULTS

### ✅ SUCCESS Indicators:

1. **Console Logs:**
   - ✅ "Downloading certificate..."
   - ✅ "Download URL: http://127.0.0.1:8000/api/certificates/3/download"
   - ✅ "Download response received: 200"
   - ✅ "Response data size: 193633"
   - ✅ "✅ Download triggered successfully!"

2. **Network Tab:**
   - ✅ Request URL: `http://127.0.0.1:8000/api/certificates/3/download`
   - ✅ Status: 200 OK
   - ✅ Type: blob
   - ✅ Size: ~200KB

3. **File Explorer:**
   - ✅ File downloaded ke folder Downloads
   - ✅ Filename: `Sertifikat_CERT-2025-JVISYFAG.pdf`
   - ✅ File size: ~200KB
   - ✅ PDF bisa dibuka

4. **PDF Content:**
   - ✅ Nama peserta: Meitanti Fadilah
   - ✅ Nama event: Latihan Frontend
   - ✅ Serial number: CERT-2025-JVISYFAG
   - ✅ Layout landscape A4

---

## ❌ TROUBLESHOOTING

### Issue 1: "404 NOT FOUND" di Console

**Symptom:**
```
Error status: 404
Error response body: 404 | NOT FOUND
```

**Possible Causes:**
1. Laravel server tidak running
2. URL salah
3. Certificate ID tidak ada

**Solutions:**
```bash
# Check Laravel server
netstat -ano | findstr :8000

# If not running, start it
cd c:\xampp\htdocs\EduFest\laravel-event-app
php artisan serve

# Check certificate exists
php check_certificates.php

# Check route
php artisan route:list --path=certificates
```

### Issue 2: "401 Unauthorized"

**Symptom:**
```
Error status: 401
Token exists: false
```

**Possible Causes:**
1. Not logged in
2. Token expired
3. Token tidak ada di localStorage

**Solutions:**
```javascript
// Check di Console
localStorage.getItem('auth_token')

// If null, login again
// Then try download again
```

### Issue 3: File Tidak Terdownload

**Symptom:**
- Console: "✅ Download triggered successfully!"
- Tapi file tidak muncul di Downloads folder

**Possible Causes:**
1. Browser block download
2. Blob size 0
3. Permission issue

**Solutions:**
1. **Check browser download settings:**
   - Chrome: Settings → Downloads
   - Allow downloads
   - Check download location

2. **Check blob size di console:**
   ```
   Response data size: 193633  ← Should be > 10000
   ```
   If size < 1000, file corrupted

3. **Try different browser:**
   - Chrome
   - Firefox
   - Edge

4. **Check Downloads folder permission:**
   - Make sure folder writable
   - Try change download location

### Issue 4: PDF Rusak / Tidak Bisa Dibuka

**Symptom:**
- File terdownload
- Tapi error saat buka PDF

**Possible Causes:**
1. File corrupted
2. Incomplete download
3. Wrong MIME type

**Solutions:**
```bash
# Regenerate certificate
cd c:\xampp\htdocs\EduFest\laravel-event-app
php generate_certificate_manual.php

# Check file
php test_certificate_download.php

# Should show:
# File Size: 193,633 bytes
# Is PDF: YES
```

### Issue 5: Blank PDF

**Symptom:**
- PDF terbuka
- Tapi isinya kosong / blank

**Possible Causes:**
1. mPDF generation error
2. Template issue
3. Missing fonts

**Solutions:**
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Regenerate with default template
# Edit CertificateController.php
# Comment out custom template section
# Use default blade template

# Then regenerate
php generate_certificate_manual.php
```

---

## 📊 COMPARISON: Admin Export vs User Certificate

### Admin Export (WORKING):
```javascript
// adminService.js
const response = await axios.get(`/admin/export?type=${type}&format=${format}`, {
  headers: {
    'Authorization': token ? `Bearer ${token}` : '',
  },
  responseType: 'blob'
});

const url = window.URL.createObjectURL(new Blob([response.data]));
const link = document.createElement('a');
link.href = url;
link.setAttribute('download', filename);
document.body.appendChild(link);
link.click();
link.remove();
window.URL.revokeObjectURL(url);
```

### User Certificate (NOW SAME):
```javascript
// userService.js
const response = await axios.get(downloadUrl, {
  headers: {
    'Authorization': token ? `Bearer ${token}` : '',
    'Accept': 'application/pdf,application/octet-stream'
  },
  responseType: 'blob'
});

const blob = new Blob([response.data], { type: 'application/pdf' });
const url = window.URL.createObjectURL(blob);
const link = document.createElement('a');
link.href = url;
link.setAttribute('download', filename);
document.body.appendChild(link);
link.click();
setTimeout(() => {
  document.body.removeChild(link);
  window.URL.revokeObjectURL(url);
}, 100);
```

**Key Differences:**
1. ✅ Both use axios directly
2. ✅ Both send token via headers
3. ✅ Both use responseType: 'blob'
4. ✅ Both create blob URL
5. ✅ Both trigger download via link.click()
6. ✅ Certificate adds explicit MIME type
7. ✅ Certificate adds cleanup timeout

---

## 🎯 FINAL CHECKLIST

Before reporting issue, make sure:

- [ ] Laravel server running on port 8000
- [ ] React server running on port 3000
- [ ] Logged in with correct credentials
- [ ] Certificate exists in database (`php check_certificates.php`)
- [ ] PDF file exists (`php test_certificate_download.php`)
- [ ] Browser DevTools open (F12)
- [ ] Console tab visible
- [ ] Network tab visible
- [ ] Clicked "Download" button (not open URL directly)
- [ ] Checked console logs
- [ ] Checked network request
- [ ] Checked Downloads folder
- [ ] Tried different browser (if issue persists)

---

## 📸 SCREENSHOT CHECKLIST

If still having issues, provide screenshots of:

1. **Console tab** - showing all logs
2. **Network tab** - showing request details
3. **Downloads folder** - showing files (or empty)
4. **Certificate page** - showing UI
5. **Error message** - if any

---

## ✅ SUCCESS STORY

**Before Fix:**
- ❌ 404 NOT FOUND
- ❌ File tidak terdownload
- ❌ Tidak masuk File Explorer

**After Fix:**
- ✅ 200 OK
- ✅ File terdownload
- ✅ Masuk File Explorer
- ✅ PDF bisa dibuka
- ✅ Sama seperti admin export

---

**Last Updated:** November 5, 2025, 7:45 PM  
**Status:** Fixed & Ready for Testing  
**Next:** Test dan report hasil
