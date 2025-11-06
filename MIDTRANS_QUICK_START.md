# 🚀 MIDTRANS QUICK START

## ⚡ 5 MENIT SETUP

### 1️⃣ Update Laravel `.env`

```bash
# Buka: laravel-event-app/.env
# Tambahkan/update:

MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
```

**Ganti `xxxxxxxxxxxxxxxx` dengan credentials Anda!**

---

### 2️⃣ Update React `.env`

```bash
# Buka: frontend-react.js/.env
# Tambahkan:

REACT_APP_MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxx
```

**Gunakan Client Key yang SAMA dengan Laravel!**

---

### 3️⃣ Clear Cache & Restart

```bash
# Di folder laravel-event-app:
php artisan config:clear
php artisan cache:clear

# Restart Laravel server:
Ctrl + C
php artisan serve

# Restart React server (di folder frontend-react.js):
Ctrl + C
npm start
```

---

### 4️⃣ Verify Setup

```bash
# Di folder laravel-event-app:
php setup_midtrans.php
```

Jika muncul "🎉 SETUP COMPLETED SUCCESSFULLY!" → **BERHASIL!**

---

### 5️⃣ Test Payment

**Test Card (Sandbox):**
```
Card Number: 4811 1111 1111 1114
CVV: 123
Exp Date: 01/25
OTP: 112233
```

**Steps:**
1. Buka aplikasi
2. Pilih event berbayar
3. Klik "Beli Sekarang"
4. Masukkan test card
5. Selesaikan pembayaran

---

## 🎯 WHERE TO GET CREDENTIALS?

### Midtrans Dashboard (Sandbox)
🔗 https://dashboard.sandbox.midtrans.com

**Steps:**
1. Login ke dashboard
2. Settings → **Access Keys**
3. Copy **Server Key** (SB-Mid-server-...)
4. Copy **Client Key** (SB-Mid-client-...)
5. Paste ke `.env` files

---

## 📋 CHECKLIST

- [ ] Server Key di `laravel-event-app/.env`
- [ ] Client Key di `laravel-event-app/.env`
- [ ] Client Key di `frontend-react.js/.env`
- [ ] Run `php artisan config:clear`
- [ ] Restart Laravel server
- [ ] Restart React server
- [ ] Run `php setup_midtrans.php`
- [ ] Test payment dengan test card

---

## 🐛 TROUBLESHOOTING

### "Server Key is not set"
```bash
php artisan config:clear
php artisan cache:clear
# Restart server
```

### "Snap Token null"
- Check Server Key di `.env`
- Pastikan tidak ada spasi/typo
- Verify di: `php setup_midtrans.php`

### Payment tidak muncul
- Check Client Key di React `.env`
- Restart React server
- Clear browser cache (Ctrl + Shift + R)

---

## 📚 FULL DOCUMENTATION

📖 **MIDTRANS_SETUP_GUIDE.md** - Panduan lengkap  
🔧 **setup_midtrans.php** - Verification script  

---

## 🎉 DONE!

Setelah semua checklist ✅, payment gateway sudah siap digunakan!

**Happy Coding! 🚀**
