# 📦 Sistem Informasi Inventaris Barang (SIVENT)

Sistem manajemen inventaris berbasis web modern yang dirancang untuk efisiensi pencatatan, pemantauan aset secara *real-time*, dan pelaporan otomatis. Dibangun dengan fokus pada akurasi data dan kemudahan pengelolaan kapasitas server.

---

## ✨ Fitur Unggulan

### �️ Dashboard Admin (Pusat Kendali)
- **Monitoring Multi-User**: Memantau beban aset yang dikelola oleh setiap petugas lapangan.
- **Laporan Akumulatif (Snapshot)**: Export data inventaris yang menunjukkan posisi aset terakhir (Real-Time), bukan sekadar data input baru.
- **Visualisasi Statistik**: Grafik kondisi barang (Baik, Rusak, Hilang) dan aktivitas user.
- **Template Management**: Admin dapat mengunggah template Excel custom (`.xlsx`) untuk menyesuaikan tampilan laporan cetak.

### 👤 Dashboard Petugas (User)
- **Input Cepat**: Form penambahan barang yang simpel namun mendetail.
- **Log Perubahan Wajib**: Setiap update jumlah atau kondisi wajib disertai catatan alasan perubahan untuk transparansi data.
- **Export Mandiri**: Petugas dapat mengunduh daftar aset yang menjadi tanggung jawabnya kapan saja.

### ⚙️ Pemeliharaan Sistem (Advanced)
- **Log Cleanup**: Menghapus riwayat aktivitas lama berdasarkan filter waktu (Hanya bulan tertentu atau semua sebelum tanggal tertentu).
- **Arsip & Reset**: Mengarsipkan seluruh data ke Excel secara otomatis sebelum melakukan penghapusan database (Keamanan Data 100%).
- **Reset Barang per User**: Kemampuan untuk mereset data aset petugas tertentu tanpa mengganggu petugas lain.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade + Tailwind CSS (Purple & Indigo Theme)
- **Interactivity**: Alpine.js (Lightweight Reactivity)
- **Excel Engine**: PhpSpreadsheet (Support `.xlsx` Templates)
- **Database**: MySQL (With Soft Deletes & Cascading)

---

## ⚙️ Instalasi & Setup

### Prasyarat
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL

### Langkah Instalasi

1. **Persiapan Folder**
   ```bash
   git clone https://github.com/Vrdeyy/Inventaris_App.git
   cd inventaris
   ```

2. **Instalasi Dependensi**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Lingkungan**
   - Buat file `.env` (copy dari `.env.example`)
   - Atur `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` sesuai MySQL Anda.
   ```bash
   php artisan key:generate
   ```

4. **Persiapan Database & Data Dummy (Penting)**
   Sistem ini dilengkapi dengan generator data dummy untuk periode **Mei - Desember 2025**.
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Menjalankan Server**
   ```bash
   php artisan serve
   # Buka Terminal baru
   npm run dev
   ```

---

## 🔄 Logic Pelaporan & Monitoring

Sistem ini menggunakan algoritma **"Inventory Snapshot"**:
- **Bukan Berdasarkan Tanggal Input**: Laporan bulan "Desember" akan menampilkan barang yang diinput bulan Mei, Juni, dst. SELAMA barang tersebut belum dihapus.
- **Real-Time Update**: Jika barang yang diisikan bulan Mei di-update kondisinya di bulan Juli, maka laporan bulan Desember akan menampilkan status **kondisi terbaru** (Juli), bukan kondisi awalnya.
- **Multi-Sheet Export**: Saat Admin melakukan export rentang bulan (misal: Mei-Desember), sistem akan membuat file Excel dengan banyak sheet, di mana setiap sheet mencerminkan "Snapshot Posisi Aset" di akhir bulan tersebut.

---

## 🔑 Akun Akses Default

| Peran | Email | Password |
|-------|-------|----------|
| **Admin** | `admin@admin.com` | `password` |
| **Petugas 1** | `user1@user.com` | `password` |
| **Petugas 2** | `user2@user.com` | `password` |

---

## � Menu Pemeliharaan (Admin Only)

Menu ini dirancang untuk mencegah database membengkak:
1. **Bersihkan Riwayat**: Masukkan Bulan & Tahun -> Pilih "Hapus Permanen".
2. **Export & Reset Log**: Download semua riwayat -> Bersihkan tabel logs.
3. **Reset Item**: Pilih User -> Ketik verifikasi **"RESET"** -> Data item user tersebut terhapus bersih (dengan opsi download arsip otomatis).

---

## 📁 Struktur Template Excel

Sistem mencari template di folder `storage/app/templates/`:
- `template_items.xlsx`: Digunakan untuk daftar barang/monitoring.
- `template_history.xlsx`: Digunakan untuk laporan riwayat aktivitas.
*Admin bisa mengganti file ini melalui menu **Template Laporan** di Dashboard.*

---

## 📌 Catatan Keamanan
- **Soft Deletes**: Data barang yang dihapus user tidak langsung hilang dari database (hanya disembunyikan), kecuali jika Admin melakukan "Force Delete" melalui menu Pemeliharaan.
- **Audit Trail**: Setiap aktivitas `Create`, `Update`, dan `Delete` terekam siapa pelakunya, kapan waktunya, dan apa yang diubah.

---

© 2026 **SIVENT App** | Dev by **Vrdeyy**
