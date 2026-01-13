# 📦 Sistem Informasi Inventaris Barang (SIVENT)

Platform manajemen aset dan inventaris modern berbasis web yang dirancang untuk instansi pendidikan atau perkantoran. Sistem ini mengedepankan **akurasi data per unit**, **audit riwayat transparan**, dan **laporan Excel profesional** dengan fitur cerdas *automatic partitioning*.

---

## 🌟 Fitur Unggulan (Premium Features)

### 📊 Akurasi Kondisi Bertingkat (Condition Breakdown)
Sistem memecah setiap item menjadi 3 kondisi sekaligus secara real-time:
- **Baik**: Barang siap digunakan.
- **Rusak**: Barang ada namun tidak berfungsi.
- **Hilang**: Barang tercatat namun fisik tidak ditemukan.
Sistem secara otomatis menghitung status (**Baik, Rusak, Hilang, atau Sebagian Rusak**) berdasarkan distribusi jumlah tersebut.

### 📑 Laporan Excel "Standard Audit" (Smart Exporter)
Fitur export paling cerdas yang pernah ada dalam sistem inventaris:
- **Nested Header**: Format laporan profesional dengan header bertingkat (Warna Peach & Border Hitam).
- **Auto-Partitioning Sheets**: Jika export mencakup banyak petugas atau rentang bulan, sistem otomatis membagi data ke dalam sheet terpisah berdasarkan **Nama Petugas**, **Bulan**, dan **Jenis Penempatan** (Ruang/Lemari).
- **Single File, Multi-Data**: Export "Semua Petugas" hanya menghasilkan satu file Excel rapi yang berisi seluruh database, mencegah blokir browser akibat multiple downloads.

### 👮 Audit Trail & Monitoring (Anti-Manipulasi)
- **Lock-Total Validation**: Total barang terkunci saat pengeditan. User hanya bisa mengatur distribusi kondisi (Baik/Rusak/Hilang) tanpa bisa mengubah total stok sembarangan.
- **Mandatory Audit Logs**: Setiap perubahan data **wajib** disertai alasan yang akan tercatat secara permanen dalam riwayat sistem.
- **Detailed History viewer**: Admin dapat melihat kronologi perubahan barang lengkap dengan detail "Siapa, Kapan, Dari Mana ke Mana, dan Mengapa".

### 🛠️ Maintenance & Data Safety
- **Log Cleaning**: Bersihkan database dari log lama (Periodik atau Semua Sebelum Bulan tertentu).
- **Atomic Archives**: Fitur "Download sebelum hapus" memastikan data ditarik 100% ke Excel dalam format rapi sebelum database dikosongkan.
- **User Safeguard**: Admin tidak dapat menghapus akunnya sendiri, dan akun yang dihapus dapat dipulihkan kembali (Soft Delete & Restore).

---

## 🛠️ Tech Stack

Sistem ini dibangun dengan arsitektur yang ringan namun powerful:
- **Laravel 11**: Backend framework utama dengan sistem routing dan middleware yang aman.
- **Tailwind CSS**: Desain UI premium, bersih, dan sepenuhnya responsif (Mobile Friendly).
- **Alpine.js**: Interaktivitas frontend yang ringan untuk pengalaman pengguna yang mulus.
- **PhpSpreadsheet**: Engine kustom untuk pembuatan laporan Excel kompleks dengan nested headers.
- **MySQL / MariaDB**: Database relasional untuk integritas data yang tinggi.

---

## 🔄 Alur Kerja Sistem (System Flow)

### 1. Skenario Petugas (User)
1. **Input Barang**: Mendaftarkan barang baru dengan memilih kategori dan jenis penempatan (Ruang/Lemari).
2. **Setup Kondisi**: Memasukkan jumlah awal barang dan membaginya ke kondisi Baik/Rusak/Hilang.
3. **Update Data**: Melaporkan perubahan kondisi (misal: "1 unit rusak karena jatuh"). Sistem memvalidasi agar total barang tidak berubah secara ilegal.

### 2. Skenario Admin
1. **Monitoring Global**: Memantau seluruh aset secara transparan per petugas.
2. **Pusat Laporan**: Menghasilkan laporan bulanan atau semesteran pet petugas atau seluruh petugas dalam satu file Excel terorganisir.
3. **Kelola Petugas**: Menambah, menonaktifkan, atau memulihkan akun petugas.
4. **Pemeliharaan**: Melakukan reset data aset untuk tahun ajaran baru atau membersihkan log riwayat agar aplikasi tetap ringan.

---

## ⚙️ Panduan Instalasi (Setup Guide)

Ikuti langkah berikut untuk menjalankan sistem di lingkungan lokal (Laragon/XAMPP):

### 1. Persiapan Environment
- **PHP**: Minimal versi 8.2 (Direkomendasikan 8.3)
- **Composer**: Versi terbaru
- **Node.js & NPM**: Untuk kompilasi aset CSS/JS

### 2. Instalasi Project
```bash
# Clone project
git clone https://github.com/Vrdeyy/Inventaris_App.git
cd Inventaris_App

# Install dependensi PHP & JavaScript
composer install
npm install
```

### 3. Konfigurasi
1. Buat database baru di MySQL (misal: `inventaris_db`).
2. Copy `.env.example` menjadi `.env`.
3. Update bagian database di `.env`:
   ```env
   DB_DATABASE=inventaris_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. Jalankan perintah: `php artisan key:generate`

### 4. Setup Database (Sangat Penting)
Gunakan perintah seeder untuk mendapatkan data simulasi lengkap (Mei 2025 - Desember 2025):
```bash
php artisan migrate:fresh --seed
```

### 5. Jalankan Aplikasi
Buka dua terminal terpisah:
- **Terminal 1**: `php artisan serve`
- **Terminal 2**: `npm run dev`

Akses aplikasi di: `http://localhost:8000`

---

## 🔑 Akun Akses Default

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@admin.com` | `password` |
| **Petugas 1** | `user1@user.com` | `password` |
| **Petugas 2** | `user2@user.com` | `password` |

---

## 📂 Lokasi Template Excel
Anda dapat menyesuaikan tampilan laporan Excel melalui file template yang berlokasi di:
`storage/app/templates/template_items.xlsx` (Daftar Barang)
`storage/app/templates/template_history.xlsx` (Riwayat)

*Sistem akan membaca header dari baris 5 & 6 secara otomatis.*

---
© 2026 **Vrdeyy** | Built with ❤️ for Better Asset Management.
