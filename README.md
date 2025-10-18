# 🔥 Zynera - Platform Minyak Jelantah

<div align="center">
  <h3>Platform Digital untuk Transaksi Minyak Jelantah Berkelanjutan</h3>
  <p>Memfasilitasi jual beli minyak jelantah dengan sistem yang aman, transparan, dan ramah lingkungan</p>
</div>

---

## 🚀 Quick Start untuk Juri/Penguji

**⚡ PENTING: Setup cepat hanya butuh 2 menit!**

### 📏 **Langkah Cepat:**
1. 💾 **Import database** dari `zynera/database/zynera.sql` 
2. 🚀 **Akses aplikasi** di browser
3. 🔑 **Login** dengan akun demo di bawah

Untuk kemudahan pengujian, gunakan akun yang sudah tersedia:

### 👥 **AKUN DEMO TERSEDIA**

| **Role** | **Email** | **Password** | **Akses** |
|----------|-----------|--------------|----------|
| 👑 **Admin** | `admin@zynera.com` | `password` | Dashboard Admin + Full Control |
| 🏪 **Penjual** | `penjual@zynera.com` | `password` | Kelola Produk + Dashboard |
| 🚚 **Kurir** | `kurir@zynera.com` | `password` | Kelola Pengiriman |
| 🛒 **Pembeli** | `pembeli@zynera.com` | `password` | Berbelanja + Order |

### 🔗 **URL Akses:**
- **Frontend:** `http://localhost/zynera-v2` atau `http://127.0.0.1:8000`
- **Login:** `http://localhost/zynera-v2/login`
- **Admin Panel:** `http://localhost/zynera-v2/admin/dashboard`

---

## 🎯 Panduan Pengujian

### **1. 👑 Uji Coba sebagai ADMIN**
```
✅ Login: admin@zynera.com / password
📍 URL: /admin/dashboard
🔧 Fitur yang bisa diuji:
   • Dashboard lengkap dengan statistik
   • Manajemen user (CRUD semua role)
   • Manajemen produk (approve/reject)
   • Manajemen pesanan & pembayaran
   • Laporan keuangan & analytics
   • Pengaturan sistem
```

### **2. 🏪 Uji Coba sebagai PENJUAL**
```
✅ Login: penjual@zynera.com / password
📍 URL: /admin/dashboard
🔧 Fitur yang bisa diuji:
   • Tambah produk minyak jelantah
   • Kelola inventory & stok
   • Monitor pesanan masuk
   • Lihat laporan penjualan
   • Update status pesanan
```

### **3. 🚚 Uji Coba sebagai KURIR**
```
✅ Login: kurir@zynera.com / password
📍 URL: /admin/dashboard
🔧 Fitur yang bisa diuji:
   • Lihat daftar pengiriman
   • Update status delivery
   • Rute pengiriman
   • Riwayat pengantaran
```

### **4. 🛒 Uji Coba sebagai PEMBELI**
```
✅ Login: pembeli@zynera.com / password
📍 URL: / (homepage)
🔧 Fitur yang bisa diuji:
   • Browse & cari produk
   • Tambah ke keranjang
   • Proses checkout
   • Pilih metode pembayaran
   • Track pesanan
   • Beri review produk
```

---

## 💡 Fitur Utama Aplikasi

### 🌟 **Untuk Pembeli:**
- 🔍 Pencarian produk minyak jelantah
- 🛒 Keranjang belanja yang responsif
- 💳 Multiple payment gateway (Midtrans, Transfer Bank)
- 📦 Tracking pesanan real-time
- ⭐ Sistem review & rating produk
- 📱 Interface mobile-friendly

### 🌟 **Untuk Penjual:**
- 📝 Manajemen produk (CRUD)
- 📊 Dashboard analytics penjualan
- 📦 Kelola stok inventory
- 💰 Laporan keuangan
- 🏠 Input alamat asal produk untuk pickup

### 🌟 **Untuk Kurir:**
- 🚚 Daftar pengiriman
- 📍 Update status delivery
- 🗺️ Manajemen rute

### 🌟 **Untuk Admin:**
- 👥 User management (semua role)
- 📈 Dashboard analytics menyeluruh
- 🛡️ Sistem keamanan & permissions
- 📊 Laporan komprehensif
- ⚙️ Konfigurasi sistem

---

## 🔧 Tech Stack

- **Backend:** Laravel 10+ (PHP 8.4)
- **Frontend:** Blade Templates + TailwindCSS + Alpine.js
- **Database:** MySQL/MariaDB
- **Payment:** Midtrans Integration
- **Email:** SMTP (Mailtrap untuk development)
- **Authentication:** Laravel Breeze + Google OAuth
- **File Storage:** Laravel Storage (public disk)

---

## 💾 Database Siap Pakai

**🎉 GOOD NEWS: Database sudah tersedia dan siap import!**

Untuk mempermudah pengujian, database lengkap dengan data sample sudah tersedia di:
```
📁 zynera/database/zynera.sql
```

### 🚀 **Quick Import Database:**

**Untuk Laragon/XAMPP:**
1. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`)
2. Buat database baru dengan nama `zynera`
3. Import file `zynera/database/zynera.sql`
4. Selesai! Akun demo sudah tersedia

**Untuk Command Line:**
```bash
# Buat database
mysql -u root -p -e "CREATE DATABASE zynera;"

# Import database
mysql -u root -p zynera < zynera/database/zynera.sql
```

**Untuk MySQL Workbench:**
1. Connect to MySQL server
2. Create schema `zynera`
3. Server → Data Import → Import from Self-Contained File
4. Select `zynera/database/zynera.sql`

---

## 📋 Instalasi Manual (Opsional)

*Jika ingin setup dari awal atau database import gagal*

### Prerequisites:
- PHP 8.4+
- Composer
- Node.js & NPM
- MySQL/MariaDB

### Langkah Instalasi:
```bash
# 1. Clone repository
git clone [repository-url]
cd zynera-v2

# 2. Install dependencies
composer install
npm install && npm run build

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Database setup (jika tidak import SQL)
php artisan migrate
php artisan db:seed

# 5. Storage link
php artisan storage:link

# 6. Start server
php artisan serve
```

### ⚙️ **Konfigurasi .env (Sesuaikan):**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zynera
DB_USERNAME=root
DB_PASSWORD=

# Midtrans (untuk payment testing)
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
```

---

## 📚 Flow Pengujian yang Disarankan

### **Skenario 1: Complete E-commerce Flow**
1. **Admin** → Buat kategori produk baru
2. **Penjual** → Tambah produk dengan alamat asal
3. **Pembeli** → Browse, add to cart, checkout
4. **Admin** → Approve pesanan
5. **Kurir** → Update status pengiriman
6. **Pembeli** → Berikan review

### **Skenario 2: Payment Integration**
1. **Pembeli** → Lakukan pemesanan
2. Pilih metode pembayaran (Midtrans/Transfer)
3. Simulasi pembayaran berhasil
4. Cek update status otomatis

### **Skenario 3: Admin Management**
1. **Admin** → Kelola user (tambah/edit/hapus)
2. Monitor dashboard analytics
3. Generate laporan keuangan
4. Atur permissions user

---

## 🔐 Keamanan

- ✅ CSRF Protection
- ✅ XSS Protection  
- ✅ SQL Injection Prevention
- ✅ Authentication & Authorization
- ✅ Rate Limiting
- ✅ Input Validation
- ✅ File Upload Security

---

## 📞 Support

Untuk pertanyaan teknis atau bantuan pengujian:
- 📧 Email: [developer-email]
- 📱 WhatsApp: [phone-number]

---

## 📄 License

Project ini menggunakan [MIT License](https://opensource.org/licenses/MIT).

---

<div align="center">
  <p><strong>🌱 Zynera - Mendukung Ekonomi Sirkular Berkelanjutan</strong></p>
  <p><em>Mengubah minyak jelantah menjadi peluang bisnis yang menguntungkan</em></p>
</div>
