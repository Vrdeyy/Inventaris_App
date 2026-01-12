# 📦 SIVENT - Sistem Informasi Inventaris Barang v2.1

SIVENT adalah platform manajemen aset dan inventaris modern berbasis web yang dirancang khusus untuk instansi pendidikan atau perkantoran. Sistem ini mengedepankan **akurasi data real-time**, **keamanan riwayat aset**, dan **estetika antarmuka premium**.

---

## 🌟 Fitur Utama (Features)

### 👨‍💼 Panel Administrator (Pusat Kendali)
- **Dashboard Premium**: Visualisasi statistik kondisi barang, leaderboard petugas paling aktif, dan timeline aktivitas terbaru secara real-time.
- **Monitoring Multi-Petugas**: Admin dapat memantau ribuan barang yang dikelola oleh petugas berbeda dalam satu tampilan terpusat.
- **Manajemen User**: Kontrol penuh untuk menambah, mengedit, menonaktifkan, atau mereset password petugas lapangan.
- **Custom Template Laporan**: Fitur unggah template Excel (`.xlsx`) sehingga desain laporan cetak bisa disesuaikan dengan standar instansi tanpa mengubah kode program.
- **Sistem Alert Pintar**: Notifikasi otomatis di dashboard jika terdapat lonjakan barang rusak atau barang hilang yang memerlukan perhatian segera.

### � Panel Petugas (Inputer Data)
- **Inventarisasi Cepat**: Form input barang yang detail (Kode, Nama, Kategori, Lokasi, Jumlah, Kondisi).
- **Audit Trail (Log) Wajib**: Setiap perubahan data barang (update jumlah/kondisi) **wajib** disertai catatan alasan perubahan untuk transparansi.
- **Manajemen Mandiri**: Petugas hanya fokus mengelola barang yang menjadi tanggung jawabnya (data isolasi).
- **Export Excel Mandiri**: Petugas dapat mengunduh daftar aset miliknya kapan saja untuk keperluan pelaporan internal.

### 🛠️ Fitur Pemeliharaan (System Maintenance)
- **Pembersihan Log Cerdas**: Fitur untuk menghapus riwayat aktivitas lama agar database tetap ringan, dengan filter per bulan atau "sebelum periode tertentu".
- **Arsip Sebelum Hapus**: Sistem otomatis menawarkan download backup Excel sebelum data dihapus permanen.
- **Hard Reset**: Fitur untuk membersihkan seluruh data barang atau data per user jika terjadi kesalahan input masal di tahun ajaran baru.
- **Keamanan Verifikasi**: Mewajibkan pengetikan kata kunci "RESET" untuk menghindari kesalahan fatal penghapusan data.

---

## 🔄 Alur Kerja Sistem (System Flow)

### 1. Alur Pendataan (Data Entry)
1. **User/Petugas** mengisi form barang baru.
2. Sistem mencatat data barang di tabel `items` dan membuat entri pertama di tabel `item_logs` sebagai riwayat "Penambahan Awal".
3. Barang muncul di monitoring Admin secara otomatis.

### 2. Alur Pemeliharaan & Audit (Update Flow)
1. User melakukan update kondisi (misal: "Baik" menjadi "Rusak").
2. User memberikan deskripsi (misal: "Layar pecah saat dipindahkan").
3. Sistem menyimpan status terbaru di tabel `items` dan menambahkan baris baru di `item_logs` yang mencatat detail perubahan tersebut.
4. Admin dapat melihat "History" barang tersebut dari awal beli hingga kondisi saat ini.

### 3. Alur Pelaporan (Reporting Logic)
Sistem ini menggunakan algoritma **"Inventory Snapshot"**:
- **Bukan Berdasarkan Tanggal Input**: Jika Anda export laporan bulan **Desember**, barang yang diinput bulan **Mei** tetap akan muncul.
- **Akumulatif**: Laporan mencerminkan "Apa saja barang yang ada di gudang sampai detik ini".
- **Multi-Sheet**: Jika export rentang (Mei-Des), sistem membuat file Excel dengan sheet yang berbeda untuk setiap bulan, menunjukkan perkembangan aset tiap bulannya.

---

## 🛠️ Tech Stack & Requirements

- **Backend**: Laravel 12 (Modern PHP Framework)
- **Frontend**: Blade, Tailwind CSS, Alpine.js
- **Excel Library**: PhpSpreadsheet
- **Database**: MySQL 8.0+
- **Minimum PHP**: 8.2

---

## ⚙️ Panduan Instalasi (Setup Guide)

Lakukan langkah-langkah berikut untuk menjalankan SIVENT di komputer Anda:

### 1. Persiapan Awal
```bash
# Clone repository
git clone https://github.com/Vrdeyy/Inventaris_App.git
cd inventaris

# Install dependensi PHP & Javascript
composer install
npm install
```

### 2. Konfigurasi Database
1. Copy file `.env.example` menjadi `.env`.
2. Buka file `.env` dan sesuaikan pengaturan DB:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_db_anda
   DB_USERNAME=root
   DB_PASSWORD=password_mysql_anda
   ```
3. Generate Key: `php artisan key:generate`

### 3. Migrasi & Data Dummy (Testing)
Jalankan perintah ini untuk membuat tabel dan mengisi data dummy (Mei - Des 2025):
```bash
php artisan migrate:fresh --seed
```

### 4. Menjalankan Aplikasi
```bash
# Terminal 1: Jalankan Server Laravel
php artisan serve

# Terminal 2: Jalankan Vite (Untuk CSS/JS)
npm run dev
```
Akses di browser: `http://127.0.0.1:8000`

---

## 🔑 Kredensial Login (Default)

Sistem telah menyediakan akun bawaan untuk uji coba:

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@admin.com` | `password` |
| **Petugas 1** | `user1@user.com` | `password` |
| **Petugas 2** | `user2@user.com` | `password` |

---

## 📂 Struktur Template Excel

Jika Admin ingin mengganti desain laporan, unggah file baru di menu **Template Laporan**:
- **Monitoring**: Template untuk daftar aset (Kolom A-G).
- **Riwayat**: Template untuk log aktivitas (Kolom A-G).
*Pastikan format file adalah `.xlsx`.*

---

## 📌 Catatan Keamanan
- **Soft Deletes**: Barang yang dihapus user tidak benar-benar hilang dari database, hanya "disembunyikan". Admin bisa menghapusnya permanen via menu Pemeliharaan.
- **Isolasi Data**: Petugas tidak bisa melihat atau mengedit barang milik petugas lain.

---

© 2026 **Vrdeyy** 
