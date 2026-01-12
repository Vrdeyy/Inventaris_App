# 📦 Sistem Informasi Inventaris Barang 

Platform manajemen aset dan inventaris modern berbasis web yang dirancang khusus untuk instansi pendidikan atau perkantoran. Sistem ini mengedepankan **akurasi data per unit**, **audit riwayat transparan**, dan **laporan Excel profesional dengan nested headers**.

---

## 🌟 Fitur Unggulan (Premium Features)

### � Data Akurat (Condition Breakdown)
Berbeda dengan sistem inventaris biasa, SIVENT memecah setiap item menjadi 3 kondisi sekaligus:
- **Baik**: Barang siap digunakan.
- **Rusak**: Barang ada namun tidak berfungsi.
- **Hilang**: Barang tercatat namun fisik tidak ditemukan.
Sistem secara otomatis menghitung status (**Baik, Rusak, Hilang, atau Sebagian Rusak**) berdasarkan distribusi jumlah tersebut.

### 📑 Laporan Excel "Standard Audit"
Export data menggunakan format **Nested Header** (Header Bertingkat) yang profesional:
- **Warna Peach & Border Hitam**: Desain premium mengikuti standar laporan kantoran.
- **Sub-Header Kondisi**: Kolom "Kondisi" membawahi sub-kolom "Baik", "Rusak", dan "Hilang".
- **Multi-Sheet & Multi-Month**: Export ribuan data dalam hitungan detik dengan pemisahan sheet otomatis per bulan dan per penempatan (Dalam Ruang/Dalam Lemari).

### 👮 Audit Trail & Monitoring (Anti-Manipulasi)
- **Lock-Total Validation**: Saat mengedit, user dilarang "mengada-ngada" jumlah barang. Total baru harus sama dengan stok awal yang tercatat.
- **Mandatory Description**: Setiap perubahan data **wajib** disertai alasan (misal: "Rusak karena jatuh saat dipindahkan").
- **Monitoring Petugas**: Admin dapat memantau aset secara spesifik per petugas dengan filter kategori dan penempatan yang detail.

---

## 🛠️ Tech Stack (Tools yang Digunakan)

Sistem ini dibangun menggunakan teknologi mutakhir untuk memastikan kecepatan dan kemudahan pemeliharaan:

| Tool | Kegunaan |
|------|----------|
| **Laravel 11** | Backend framework utama |
| **PHP 8.3** | Versi PHP terbaru untuk performa maksimal |
| **Tailwind CSS** | Framework UI untuk desain premium & responsif |
| **PhpSpreadsheet** | Mesin pembuat laporan Excel (.xlsx) |
| **MySQL / MariaDB** | Penyimpanan database relasional |
| **Laragon** | Rekomendasi server lokal (Development environment) |

---

## 🔄 Alur Penggunaan (System Flow)

### 1. Peran Petugas (User)
1. **Tambah Barang**: Input Nama, Kategori, Lokasi, dan pilih Penempatan (Ruang/Lemari).
2. **Breakdown Jumlah**: Masukkan jumlah barang dan bagi ke dalam kategori Baik, Rusak, atau Hilang.
3. **Kelola & Update**: Jika kondisi barang berubah, edit data dan berikan **Catatan Perubahan**. Sistem akan memvalidasi agar total barang tidak berubah tiba-tiba.

### 2. Peran Admin
1. **Monitoring**: Lihat sebaran aset di seluruh petugas. Bisa difilter berdasarkan penempatan atau kondisi spesifik.
2. **Cek Riwayat**: Lihat kronologi barang dari pertama diinput hingga perubahan terakhir (siapa yang mengubah, kapan, dan alasannya).
3. **Laporan Bulanan**: Export laporan aset dalam bentuk folder bulanan (sheet per bulan) untuk arsip instansi.
4. **Maintenance**: Lakukan pembersihan log atau reset data tahun ajaran baru dengan fitur pengamanan kata kunci "RESET".

---

## ⚙️ Panduan Instalasi (Setup Guide)

Pastikan Anda mengikuti langkah ini agar aplikasi berjalan lancar:

### 1. Persiapan Environment
Paling mudah gunakan **Laragon**. Pastikan versi PHP sudah **8.3**.

### 2. Clone & Install
Buka terminal di folder `laragon/www/` dan jalankan:
```bash
# Clone project
git clone https://github.com/Vrdeyy/Inventaris_App.git
cd Inventaris_App

# Install Dependensi
composer install
npm install
```

### 3. Konfigurasi Database
1. Buat database baru di MySQL (misal nama: `db_inventaris`).
2. Copy file `.env.example` menjadi `.env`.
3. Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di file `.env`.

### 4. Setup Database & Data Dummy
Jalankan perintah sakti ini untuk me-reset database dan mengisi data simulasi (Mei-Desember 2025):
```bash
php artisan migrate:fresh --seed
```

### 5. Jalankan Aplikasi
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```
Akses: `http://localhost:8000`

---

## 🔑 Akun Login Default

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@admin.com` | `password` |
| **Petugas 1** | `user1@user.com` | `password` |
| **Petugas 2** | `user2@user.com` | `password` |

---

## 📂 Custom Template Excel
Admin bisa mengganti tampilan Excel di menu **Setting Template**. 
Sistem akan membaca header dari template tersebut mulai dari **baris 5 dan 6** untuk daftar barang.

---
© 2026 **Vrdeyy** | Built with ❤️ for Better Asset Management.
