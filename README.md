# 📦 Sistem Informasi Inventaris Barang

Sistem manajemen inventaris modern yang dirancang untuk memudahkan pencatatan, pemantauan, dan pelaporan aset barang. Dibangun dengan fokus pada kecepatan, keamanan, dan kemudahan penggunaan (User Friendly).

---

## 🚀 Fitur Utama

### 🔐 Autentikasi & Keamanan
- **Multi-Role System**: Pemisahan hak akses antara **Admin** dan **Petugas (User)**.
- **Secure Login**: Validasi kredensial aman dengan enkripsi password (Bcrypt).
- **Role Middleware**: Proteksi rute akses berdasarkan hak akses pengguna.
- **Akun Nonaktif**: Admin dapat menonaktifkan akses user tanpa menghapus data historis.

### 👤 Petugas Inventaris (User)
- **Dashboard Ringkas**: Ringkasan statistik barang (Baik, Rusak, Hilang).
- **Manajemen Barang (CRUD)**:
  - **Input Barang**: Validasi kode unik, pencatatan lokasi, dan kondisi awal.
  - **Edit Barang**: Update kondisi/jumlah dengan **wajib** menyertakan catatan perubahan.
  - **Soft Delete**: Data yang dihapus masuk ke "Trash" dan tidak hilang permanen, menjaga integritas histori.
- **Pencarian & Filter**: Cari barang berdasarkan Kode/Nama, atau filter berdasarkan Kategori, Lokasi, dan Kondisi.
- **Audit Logging**: Setiap perubahan kondisi atau jumlah barang otomatis tercatat di histori (Log).
- **Export Data**: Unduh data inventaris ke format CSV/Excel.

### 🛡️ Administrator (Admin)
- **Monitoring Dashboard**: Grafik ringkas kondisi aset perusahaan dan statistik user aktif.
- **Alert System**: Peringatan otomatis dini jika jumlah barang rusak/hilang meningkat.
- **Manajemen User**: Tambah petugas baru, reset password, atau nonaktifkan petugas.
- **Monitoring Barang Per User**:
  - Melihat detail inventaris yang dikelola oleh setiap user.
  - **Export Data per User**: Admin dapat mengunduh laporan barang spesifik untuk user tertentu berdasarkan filter bulan dan tahun.
  - **Hapus Clean Data**: Fitur untuk menghapus data lama berdasarkan filter waktu tertentu.

---

## 🛠️ Tech Stack

- **Framework**: [Laravel 12](https://laravel.com) (PHP)
- **Database**: MySQL
- **Frontend**: Blade Templates
- **Styling**: [Tailwind CSS](https://tailwindcss.com) (Modern & Responsive)
- **Interactivity**: Alpine.js (Ringan dan reaktif)

---

## ⚙️ Instalasi & Setup

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di lokal (Localhost):

### Prasyarat
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL

### Langkah Instalasi

1. **Clone Repository (Jika ada git)**
   ```bash
   git clone https://github.com/vrdeyy/inventaris-app.git
   cd inventaris-app
   ```
   *Atau jika deploy manual, pastikan berada di root folder project.*

2. **Setup Langsung (Otomatis)**
    Jika support script composer:
   ```bash
   composer run setup
   ```

3. **Atau Setup Manual**
   
   - **Install Dependensi**:
     ```bash
     composer install
     npm install
     ```
   
   - **Setup Environment**:
     ```bash
     cp .env.example .env
     php artisan key:generate
     ```
     Atur koneksi database di file `.env` (DB_DATABASE, DB_USERNAME, dll).

   - **Migrasi Database & Seeding**:
     ```bash
     php artisan migrate:fresh --seed
     ```

   - **Build Assets**:
     ```bash
     npm run build
     ```

4. **Jalankan Aplikasi**
   ```bash
   composer run dev
   ```
   *Perintah ini akan menjalankan server Laravel, Queue, dan Vite secara bersamaan (membutuhkan concurrently).*
   
   Atau jalankan manual terpisah:
   ```bash
   php artisan serve
   ```
   ```bash
   npm run dev
   ```

5. **Akses Aplikasi**
   Buka browser dan kunjungi: `http://127.0.0.1:8000` atau `http://inventaris.test` (jika pakai Laragon).

---

## 🔑 Akun Default (Seeder)

Gunakan akun berikut untuk masuk:

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@admin.com` | `password` |
| **User** | `user@user.com` | `password` |

---

## 🔄 Alur Kerja Sistem (User Flow)

### 1. Login
- User memasukkan email & password.
- Sistem mengecek role. Jika Admin → Dashboard Admin. Jika User → Dashboard Petugas.

### 2. Tambah Barang (User)
- Dashboard > Klik "Tambah Barang".
- Isi form (Kode, Nama, Kategori, Lokasi, Jumlah, Kondisi).
- Submit → Data tersimpan, Log histori awal dibuat otomatis.

### 3. Update Barang (User)
- Menu Data Barang > Pilih Barang > Edit.
- Ubah jumlah atau kondisi (Misal: Baik → Rusak).
- **Wajib** isi "Catatan Perubahan" (Misal: "Jatuh saat pemindahan").
- Submit → Kondisi berubah, Log histori lama & baru tersimpan.

### 4. Monitoring (Admin)
- Admin login > Dashboard.
- Melihat grafik kondisi barang & Statistik User.
- Menu **Monitoring Barang**: Melihat detail aset per petugas.
- Melakukan Export data inventaris user tertentu atau menghapus data lama jika diperlukan.

---

> **Dev by Vrdeyy**

---
© 2026 Sistem Inventaris. All rights reserved.
