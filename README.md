<div align="center">
  <img src="public/dompetKuTP.png" alt="DompetKu Logo" width="100" />
  <h1>DompetKu</h1>
  <p><strong>Aplikasi Pencatatan dan Manajemen Keuangan Pribadi</strong></p>
</div>

---

## 📖 Deskripsi Proyek

**DompetKu** adalah aplikasi web modern yang dirancang untuk membantu Anda melacak, mengelola, dan merencanakan keuangan pribadi dengan lebih bijak. Dari pencatatan transaksi masuk dan keluar, manajemen limit anggaran (budget), pengelolaan berbagai jenis dompet, hingga pengingat tagihan bulanan.

Proyek ini menggunakan teknologi **TALL Stack** untuk memberikan pengalaman *full-stack* yang cepat, responsif, dan *developer-friendly*.

## ✨ Fitur Utama

- **Pencatatan Transaksi**: Catat setiap pemasukan dan pengeluaran dengan detail.
- **Manajemen Dompet**: Kelola saldo dari berbagai sumber (Bank, E-Wallet, Tunai).
- **Pengingat Tagihan**: Jangan pernah lewatkan tagihan bulanan Anda.
- **Limit Anggaran**: Atur batas pengeluaran agar keuangan lebih terkontrol.
- **Laporan Berkala**: Pantau statistik dan ringkasan keuangan secara harian atau bulanan.

## 🚀 Tech Stack

Proyek ini dibangun menggunakan **TALL Stack**:
- **T**ailwind CSS (v4) - Untuk *styling* dan desain *utility-first* yang cepat dan responsif.
- **A**lpine.js - Untuk interaktivitas *frontend* ringan (seperti *dropdown*, *modals*, dan animasi) tanpa perlu menulis banyak *JavaScript*.
- **L**aravel (v11) - Framework *backend* tangguh menggunakan PHP.
- **L**ivewire (v3) - Membangun antarmuka dinamis dan *reactive* di Laravel tanpa perlu beranjak dari komponen Blade.

## 📋 Requirements

Sebelum memulai instalasi, pastikan sistem Anda sudah memiliki perangkat lunak berikut:
- **PHP** >= 8.2
- **Composer** (untuk manajemen dependensi PHP)
- **Node.js** >= 18 dan **NPM** (untuk *build tools* dan Tailwind CSS)
- **MySQL** (Database utama yang dikonfigurasi)

## 🛠️ Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek ini di mesin lokal Anda:

1. **Clone repositori** atau masuk ke direktori proyek:
   ```bash
   cd dompetku
   ```

2. **Install dependensi PHP (Laravel):**
   ```bash
   composer install
   ```

3. **Install dependensi JavaScript & CSS (Vite & Tailwind):**
   ```bash
   npm install
   ```

4. **Siapkan environment variables:**
   Salin file `.env.example` menjadi `.env`.
   ```bash
   cp .env.example .env
   ```
   Lalu, sesuaikan konfigurasi *database* (seperti `DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, dll) di dalam file `.env` tersebut.

5. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database:**
   *(Jika Anda menggunakan database sesungguhnya)*
   ```bash
   php artisan migrate
   ```

7. **Kompilasi Aset Frontend (Vite):**
   Jalankan perintah ini agar Tailwind CSS dan skrip lainnya dikompilasi:
   ```bash
   npm run build
   ```
   *Atau jika sedang dalam mode development:*
   ```bash
   npm run dev
   ```

8. **Jalankan Server Lokal Laravel:**
   ```bash
   php artisan serve
   ```
   Aplikasi Anda kini dapat diakses di `http://localhost:8000` (Atau bisa langsung diakses melalui `http://dompetku.test` jika menggunakan **Laragon**).

## 🗂️ Struktur Direktori Penting
- `app/Livewire/`: Lokasi *Class* komponen Livewire (Controller).
- `resources/views/livewire/`: Lokasi *Blade view* untuk komponen Livewire.
- `resources/views/components/`: Lokasi komponen Blade *stateless* (seperti `<x-sidebar>` dan `<x-topbar>`).

## 📄 Lisensi
The Laravel framework and this codebase are open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
