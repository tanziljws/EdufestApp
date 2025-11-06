# ✅ TESTING CHECKLIST - SISTEM SERTIFIKAT EDUFEST

## 🎯 TESTING OVERVIEW

Checklist lengkap untuk memastikan sistem sertifikat berfungsi dengan baik sebelum production.

---

## 📋 BACKEND TESTING

### 1. Database & Models

- [ ] **Check Certificates Table**
  ```bash
  php check_certificates.php
  ```
  - [ ] Table exists
  - [ ] Columns correct (id, registration_id, serial_number, file_path, issued_at)
  - [ ] Foreign keys working
  - [ ] Indexes created

- [ ] **Check Relationships**
  - [ ] Certificate → Registration (belongsTo)
  - [ ] Registration → Certificate (hasOne)
  - [ ] Registration → User (belongsTo)
  - [ ] Registration → Event (belongsTo)

### 2. API Endpoints

- [ ] **GET /api/me/certificates**
  ```bash
  php test_certificate_api.php
  ```
  - [ ] Returns array of certificates
  - [ ] Includes registration.user data
  - [ ] Includes registration.event data
  - [ ] Includes download_url
  - [ ] Requires authentication
  - [ ] Returns 401 if not authenticated

- [ ] **GET /api/certificates/{id}/download**
  ```bash
  php test_certificate_download.php
  ```
  - [ ] Returns PDF file
  - [ ] Generates PDF if not exists
  - [ ] Proper Content-Type header
  - [ ] Proper Content-Disposition header
  - [ ] File size reasonable (~200KB)
  - [ ] PDF is valid and openable

- [ ] **POST /api/registrations/{id}/generate-certificate**
  - [ ] Creates certificate record
  - [ ] Generates unique serial number
  - [ ] Dispatches PDF generation job
  - [ ] Returns 400 if already exists
  - [ ] Returns 400 if not attended
  - [ ] Requires authentication

- [ ] **GET /api/registrations/{id}/certificate-status**
  - [ ] Returns status: not_generated / generated
  - [ ] Returns certificate data if exists
  - [ ] Returns download_url if generated

### 3. PDF Generation

- [ ] **mPDF Library**
  ```bash
  composer show mpdf/mpdf
  ```
  - [ ] Version 8.2.0 or higher installed
  - [ ] No dependency conflicts

- [ ] **Custom Template**
  - [ ] Template upload works
  - [ ] Template stored in cert_templates/
  - [ ] PDF uses custom template if exists
  - [ ] Name overlay positioned correctly
  - [ ] Description overlay positioned correctly

- [ ] **Default Template**
  - [ ] Blade template exists (certificate_modern.blade.php)
  - [ ] PDF generates without custom template
  - [ ] Layout correct (landscape A4)
  - [ ] All data displayed correctly

- [ ] **File Storage**
  - [ ] PDF saved to certificates/ folder
  - [ ] Filename format: CERT-2025-XXXXXXXX.pdf
  - [ ] File permissions correct (readable)
  - [ ] Storage symlink exists

### 4. Certificate Generation Job

- [ ] **GenerateCertificatePdfJob**
  ```bash
  php generate_certificate_manual.php
  ```
  - [ ] Job handles successfully
  - [ ] Creates certificate record
  - [ ] Generates unique serial number
  - [ ] Creates PDF file
  - [ ] Updates file_path in database
  - [ ] Sets issued_at timestamp

- [ ] **Error Handling**
  - [ ] Handles missing user data
  - [ ] Handles missing event data
  - [ ] Handles file write errors
  - [ ] Logs errors properly

### 5. Routes & Middleware

- [ ] **Route Registration**
  ```bash
  php artisan route:list --path=certificates
  ```
  - [ ] All certificate routes listed
  - [ ] Correct HTTP methods
  - [ ] Correct middleware applied

- [ ] **Authentication**
  - [ ] Protected routes require auth:sanctum
  - [ ] Public download route works
  - [ ] User can only access own certificates

---

## 🎨 FRONTEND TESTING

### 1. Certificates Page

- [ ] **Page Access**
  - [ ] Route /profile?section=certificates works
  - [ ] Redirects to login if not authenticated
  - [ ] Loads without errors
  - [ ] No console errors

- [ ] **UI Components**
  - [ ] Header displays "Sertifikat Saya"
  - [ ] Refresh button visible and works
  - [ ] Search box functional
  - [ ] Filter dropdown functional
  - [ ] Certificate cards render correctly

- [ ] **Data Loading**
  - [ ] API call to /me/certificates successful
  - [ ] Loading state shows while fetching
  - [ ] Data maps correctly to UI
  - [ ] Serial numbers display correctly
  - [ ] Dates format correctly (Indonesian)
  - [ ] Categories map correctly

### 2. Certificate Cards

- [ ] **Card Display**
  - [ ] Event name shows correctly
  - [ ] Participant name shows correctly
  - [ ] Serial number shows with monospace font
  - [ ] Issue date formatted correctly
  - [ ] Category displays correctly
  - [ ] Status badge shows correct color

- [ ] **Buttons**
  - [ ] "Lihat" button present
  - [ ] "Download" button present
  - [ ] Buttons disabled if status not "available"
  - [ ] Download button triggers download
  - [ ] No errors on button click

### 3. Search & Filter

- [ ] **Search Functionality**
  - [ ] Search by event name works
  - [ ] Search by participant name works
  - [ ] Search by serial number works
  - [ ] Search is case-insensitive
  - [ ] Results update in real-time

- [ ] **Filter Functionality**
  - [ ] "Semua Status" shows all
  - [ ] "Tersedia" filters correctly
  - [ ] "Diproses" filters correctly
  - [ ] "Kedaluwarsa" filters correctly
  - [ ] Filter works with search

### 4. Download Functionality

- [ ] **Download Process**
  - [ ] Click download triggers API call
  - [ ] Blob created correctly
  - [ ] Browser download triggered
  - [ ] File saves to Downloads folder
  - [ ] Filename correct format
  - [ ] Blob URL cleaned up

- [ ] **Error Handling**
  - [ ] Shows error if download fails
  - [ ] Shows error if certificate not found
  - [ ] Shows error if network issue
  - [ ] Error messages user-friendly

### 5. Empty States

- [ ] **No Certificates**
  - [ ] Shows empty state message
  - [ ] Message: "Belum ada sertifikat"
  - [ ] Shows CTA "Jelajahi Event"
  - [ ] CTA links to /events

- [ ] **No Search Results**
  - [ ] Shows "Tidak ada sertifikat ditemukan"
  - [ ] Shows hint to change search/filter
  - [ ] No CTA button shown

### 6. Responsive Design

- [ ] **Mobile (< 768px)**
  - [ ] Layout stacks vertically
  - [ ] Cards full width
  - [ ] Search box full width
  - [ ] Buttons readable
  - [ ] No horizontal scroll

- [ ] **Tablet (768px - 1024px)**
  - [ ] 2 columns grid
  - [ ] Proper spacing
  - [ ] Readable text

- [ ] **Desktop (> 1024px)**
  - [ ] 3 columns grid
  - [ ] Proper spacing
  - [ ] All elements visible

---

## 🔄 INTEGRATION TESTING

### 1. End-to-End Flow

- [ ] **User Registration to Certificate**
  1. [ ] User registers for event
  2. [ ] User attends event (mark attendance)
  3. [ ] Certificate auto-generates
  4. [ ] Certificate appears in user's list
  5. [ ] User can download certificate
  6. [ ] PDF opens correctly

### 2. Admin Flow

- [ ] **Template Upload**
  1. [ ] Admin creates event
  2. [ ] Admin uploads certificate template
  3. [ ] Template saves to storage
  4. [ ] Template path saved in database
  5. [ ] Template used for PDF generation

### 3. Multiple Users

- [ ] **Concurrent Access**
  - [ ] Multiple users can download simultaneously
  - [ ] No file conflicts
  - [ ] Each user sees only their certificates
  - [ ] Performance acceptable under load

---

## 🐛 ERROR SCENARIOS

### 1. Backend Errors

- [ ] **Database Errors**
  - [ ] Handle missing registration
  - [ ] Handle missing user
  - [ ] Handle missing event
  - [ ] Return proper error codes

- [ ] **File System Errors**
  - [ ] Handle storage full
  - [ ] Handle permission denied
  - [ ] Handle file not found
  - [ ] Log errors properly

- [ ] **PDF Generation Errors**
  - [ ] Handle mPDF errors
  - [ ] Handle memory limit
  - [ ] Handle timeout
  - [ ] Retry mechanism works

### 2. Frontend Errors

- [ ] **API Errors**
  - [ ] Handle 401 (unauthorized)
  - [ ] Handle 404 (not found)
  - [ ] Handle 500 (server error)
  - [ ] Show user-friendly messages

- [ ] **Network Errors**
  - [ ] Handle offline mode
  - [ ] Handle timeout
  - [ ] Show retry option
  - [ ] Don't crash app

---

## 🔒 SECURITY TESTING

### 1. Authentication

- [ ] **Access Control**
  - [ ] Unauthenticated users redirected
  - [ ] Users can't access others' certificates
  - [ ] Admin can access all certificates
  - [ ] Token validation works

### 2. File Security

- [ ] **Upload Validation**
  - [ ] File type validated
  - [ ] File size limited (2MB)
  - [ ] Malicious files rejected
  - [ ] Proper sanitization

- [ ] **Download Security**
  - [ ] No directory traversal
  - [ ] No unauthorized access
  - [ ] Proper headers set
  - [ ] CORS configured correctly

### 3. Data Validation

- [ ] **Input Validation**
  - [ ] Serial number format validated
  - [ ] Registration ID validated
  - [ ] Template path sanitized
  - [ ] SQL injection prevented

---

## 📊 PERFORMANCE TESTING

### 1. Load Testing

- [ ] **API Performance**
  - [ ] /me/certificates responds < 500ms
  - [ ] Download responds < 2s
  - [ ] Generate responds < 60s
  - [ ] No memory leaks

### 2. Database Performance

- [ ] **Query Optimization**
  - [ ] Proper indexes used
  - [ ] N+1 queries avoided
  - [ ] Eager loading works
  - [ ] Query time acceptable

### 3. File Operations

- [ ] **Storage Performance**
  - [ ] PDF generation < 30s
  - [ ] File read/write fast
  - [ ] No blocking operations
  - [ ] Cleanup old files works

---

## 🎯 USER ACCEPTANCE TESTING

### 1. User Experience

- [ ] **Ease of Use**
  - [ ] User can find certificates easily
  - [ ] Download process intuitive
  - [ ] Search works as expected
  - [ ] No confusing elements

### 2. Visual Design

- [ ] **UI Quality**
  - [ ] Design consistent with app
  - [ ] Colors accessible
  - [ ] Fonts readable
  - [ ] Icons appropriate

### 3. Feedback

- [ ] **User Feedback**
  - [ ] Loading states clear
  - [ ] Success messages shown
  - [ ] Error messages helpful
  - [ ] Progress indicators present

---

## 📱 BROWSER COMPATIBILITY

- [ ] **Chrome** (latest)
  - [ ] All features work
  - [ ] Download works
  - [ ] PDF opens

- [ ] **Firefox** (latest)
  - [ ] All features work
  - [ ] Download works
  - [ ] PDF opens

- [ ] **Edge** (latest)
  - [ ] All features work
  - [ ] Download works
  - [ ] PDF opens

- [ ] **Safari** (latest)
  - [ ] All features work
  - [ ] Download works
  - [ ] PDF opens

---

## 🚀 PRE-PRODUCTION CHECKLIST

### Environment

- [ ] **Server Configuration**
  - [ ] PHP 8.1+ installed
  - [ ] Composer dependencies installed
  - [ ] Node.js dependencies installed
  - [ ] Storage permissions correct (775)
  - [ ] Symlink created

- [ ] **Database**
  - [ ] Migrations run
  - [ ] Seeders run (if needed)
  - [ ] Backup created
  - [ ] Indexes created

- [ ] **Environment Variables**
  - [ ] .env configured correctly
  - [ ] FILESYSTEM_DISK=public
  - [ ] APP_URL correct
  - [ ] Database credentials correct

### Monitoring

- [ ] **Logging**
  - [ ] Laravel logs working
  - [ ] Error tracking enabled
  - [ ] Log rotation configured
  - [ ] Alerts set up

- [ ] **Backups**
  - [ ] Database backup scheduled
  - [ ] File backup scheduled
  - [ ] Restore tested
  - [ ] Backup location secure

---

## 📝 DOCUMENTATION

- [ ] **User Documentation**
  - [ ] USER_CERTIFICATE_GUIDE.md complete
  - [ ] Screenshots added
  - [ ] FAQ updated
  - [ ] Contact info correct

- [ ] **Admin Documentation**
  - [ ] ADMIN_CERTIFICATE_GUIDE.md complete
  - [ ] Template guidelines clear
  - [ ] Troubleshooting section complete
  - [ ] Examples provided

- [ ] **Developer Documentation**
  - [ ] CERTIFICATE_SYSTEM_FIXED.md complete
  - [ ] API documented
  - [ ] Code comments added
  - [ ] Architecture explained

---

## ✅ FINAL SIGN-OFF

### Testing Team

- [ ] **Backend Developer**
  - Name: _______________
  - Date: _______________
  - Signature: _______________

- [ ] **Frontend Developer**
  - Name: _______________
  - Date: _______________
  - Signature: _______________

- [ ] **QA Tester**
  - Name: _______________
  - Date: _______________
  - Signature: _______________

- [ ] **Project Manager**
  - Name: _______________
  - Date: _______________
  - Signature: _______________

### Deployment Approval

- [ ] All critical tests passed
- [ ] All documentation complete
- [ ] Backup created
- [ ] Rollback plan ready
- [ ] Team notified

**Approved for Production:** ☐ YES  ☐ NO

**Deployment Date:** _______________

---

**Last Updated:** November 5, 2025
**Version:** 1.0
**Status:** Ready for Testing
