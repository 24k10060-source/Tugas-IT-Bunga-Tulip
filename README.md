# 🌷 Tulip Haven - Website Ensiklopedia & Katalog Bunga Tulip

Website sederhana bertema **Bunga Tulip** yang interaktif, elegan, dan dilengkapi integrasi database MySQL serta panel manajemen Admin (CRUD). Dibuat untuk memenuhi tugas Pemrograman Web / IT.

---

## 📌 1. Tautan Proyek (Deployment & Repositori)

- **🔗 Link Website Live (HTTPS):**  
  `https://tulip-haven.onrender.com` *(atau ganti dengan URL Render/Railway Anda setelah deploy)*
- **📦 Link Repositori GitHub:**  
  `https://github.com/USERNAME/tulip-haven` *(ganti dengan username GitHub Anda)*

> *Catatan: Seluruh platform hosting modern (Render / Railway / Vercel) secara otomatis menyediakan sertifikat SSL gratis sehingga website sudah berstatus **HTTPS (Secure/Aman)**.*

---

## 🔐 2. Informasi Akun Login (Authentication Info)

Berikut adalah informasi akun yang dapat digunakan untuk login ke dalam sistem:

### 👑 Akun Administrator (Akses Penuh CRUD):
- **URL Login:** `/auth/login.php`
- **Username:** `admin`
- **Password:** `admin123`
- **Hak Akses:**
  - Melihat Dashboard statistik (total varietas tulip, total stok, total pesanan).
  - Manajemen Bunga Tulip (**CRUD**: Tambah varietas baru, Edit data, Hapus data tulip).
  - Manajemen Pesanan Pengunjung (Ubah status pesanan ke *Pending*, *Diproses*, *Selesai*, atau *Dibatalkan*).

### 👤 Akun Pengguna / Demo User:
- **Username:** `mina`
- **Password:** `user123`
- **Hak Akses:** Menjelajahi katalog, melihat detail botani, dan mengisi formulir pemesanan.

*(Tersedia juga fitur **Pendaftaran / Register** akun baru di halaman `/auth/register.php`)*

---

## ✨ 3. Fitur Utama Website

1. **Beranda & Edukasi Tulip:**
   - Hero banner visual estetik dengan filosofi bunga tulip.
   - Makna di setiap warna bunga tulip (Merah, Kuning, Putih, Ungu, Pink, dan Hitam/Gelap).
   - Panduan botani cara merawat bunga tulip di rumah (Pencahayaan, Penyiraman, Suhu, Perawatan Vas).
2. **Katalog & Filter Interaktif (Database Driven):**
   - Menampilkan seluruh varietas tulip yang tersimpan di database MySQL.
   - Fitur pencarian instan berdasarkan nama atau makna.
   - Filter berdasarkan warna dan kategori kultivar (*Darwin Hybrid, Triumph, Single Late, Parrot, dll*).
3. **Detail Varietas Tulip:**
   - Menampilkan nama ilmiah, deskripsi makna, harga, stok, foto, dan panduan perawatan khusus.
4. **Formulir Pemesanan / Buku Tamu (Database Driven):**
   - Pengunjung dapat memesan buket bunga tulip atau mengajukan pertanyaan.
   - Data langsung tersimpan di tabel `orders` database dan dapat dipantau oleh Admin.
5. **Panel Admin (CRUD Lengkap):**
   - Tambah varietas tulip baru (dengan validasi data).
   - Edit varietas tulip yang ada.
   - Hapus data varietas tulip.
   - Manajemen status pesanan pengunjung secara real-time.
6. **Smart Auto-Database Setup:**
   - Skrip `setup/connection.php` dilengkapi fitur **Auto-Migration**. Jika tabel database belum ada di server (baik di lokal maupun cloud seperti Aiven), sistem secara otomatis membuat tabel dan mengisi data awal (seed data) saat halaman web pertama kali dibuka!

---

## 💻 4. Panduan Menjalankan Secara Lokal (Laragon / XAMPP)

1. Pastikan folder proyek ini berada di direktori web server Anda:
   - **Laragon:** `C:\laragon\www\Tugas IT`
   - **XAMPP:** `C:\xampp\htdocs\Tugas IT`
2. Buka aplikasi **Laragon** dan klik **"Start All"** (pastikan Apache dan MySQL sudah berjalan / warna hijau).
3. Buka browser (Chrome / Edge / Firefox) dan kunjungi:
   - `http://localhost/Tugas IT`  
   *(atau `http://tugas-it.test` jika fitur Pretty URL Laragon aktif)*
4. Database dan data awal bunga tulip akan langsung otomatis terhubung dan terinisialisasi.
   - Jika ingin mengimpor database secara manual, buka **phpMyAdmin** (`http://localhost/phpmyadmin`), buat database baru bernama `bunga_tulip`, lalu import file `database.sql`.

---

## 🚀 5. Panduan Deploy Gratis ke Cloud (HTTPS & Database Aktif)

Sesuai instruksi tugas, Anda dapat menggunakan hosting gratis seperti **Render + Aiven (MySQL)** atau **Railway**. Berikut panduan termudahnya:

### Langkah A: Upload Proyek ke GitHub
1. Buat repositori baru di akun [GitHub](https://github.com/new) Anda (misalnya beri nama `tulip-haven`).
2. Buka terminal di folder proyek ini (`C:\laragon\www\Tugas IT`) dan jalankan perintah:
   ```bash
   git init
   git add .
   git commit -m "Initial commit - Website Bunga Tulip"
   git branch -M main
   git remote add origin https://github.com/USERNAME/tulip-haven.git
   git push -u origin main
   ```

---

### Langkah B: Buat Database MySQL Gratis di Aiven
1. Daftar akun gratis di [Aiven.io](https://aiven.io/).
2. Buat layanan baru: Pilih **MySQL** -> Pilih Free Tier (atau paket gratis yang tersedia).
3. Setelah database aktif, salin informasi koneksi:
   - **Host** (contoh: `mysql-xxxxx.aivencloud.com`)
   - **Port** (contoh: `12345`)
   - **User** (biasanya `avnadmin`)
   - **Password**
   - **Database Name** (biasanya `defaultdb`)

---

### Langkah C: Deploy Web ke Render (Gratis & Otomatis HTTPS)
1. Daftar atau masuk ke [Render.com](https://render.com/) menggunakan akun GitHub Anda.
2. Klik tombol **"New +"** lalu pilih **"Web Service"**.
3. Pilih repositori GitHub Anda (`tulip-haven`).
4. Pada bagian pengaturan:
   - **Environment:** Pilih `Docker` (Render otomatis mendeteksi file `Dockerfile` yang sudah disediakan).
   - **Region:** Singapore (paling cepat untuk Indonesia).
   - **Plan:** Free ($0/month).
5. Masuk ke tab **"Environment Variables"**, lalu tambahkan:
   - `DB_HOST` = *(Host dari Aiven)*
   - `DB_PORT` = *(Port dari Aiven)*
   - `DB_NAME` = `defaultdb` *(atau nama database di Aiven)*
   - `DB_USER` = `avnadmin`
   - `DB_PASSWORD` = *(Password dari Aiven)*
6. Klik **"Deploy Web Service"**.
7. Tunggu sekitar 2-3 menit hingga proses build selesai. Website Anda langsung online dengan protokol **HTTPS**, misalnya: `https://tulip-haven.onrender.com`!

---

## 🌐 6. Panduan Menghubungkan Custom Domain (Opsional)

Jika Anda ingin membeli domain sendiri (misalnya `tuliphaven.my.id` seharga ~Rp 12.000/tahun di Niagahoster, Domainesia, atau IDwebhost):

1. **Beli Domain:** Beli nama domain pilihan Anda di registrar lokal atau Cloudflare.
2. **Masuk ke Pengaturan Hosting (Render / Vercel):**
   - Buka dashboard Render -> Pilih Web Service Anda -> Buka menu **"Settings"** -> **"Custom Domains"**.
   - Masukkan nama domain Anda (contoh: `tuliphaven.my.id` atau `www.tuliphaven.my.id`).
3. **Atur DNS di Registrar Domain:**
   - Tambahkan **CNAME Record**:
     - *Name/Host:* `www` (atau `@`)
     - *Value/Target:* URL Render Anda (contoh: `tulip-haven.onrender.com`).
4. Render secara otomatis akan memverifikasi DNS dan menerbitkan sertifikat SSL Let's Encrypt gratis (HTTPS) dalam beberapa menit.

---

## 📁 7. Struktur Berkas Direktori

```text
c:\laragon\www\Tugas IT\
├── .gitignore               # Daftar berkas yang diabaikan Git
├── .htaccess                # Konfigurasi Apache & rewrite
├── Dockerfile               # Skrip kontainer Docker untuk deploy Render/Railway
├── render.yaml              # Konfigurasi Blueprint 1-klik Render
├── README.md                # Dokumentasi lengkap & info login
├── database.sql             # Skema & data awal (seed) MySQL
├── index.php                # Beranda, filosofi, katalog preview, form pesan
├── catalog.php              # Katalog lengkap dengan fitur pencarian & filter warna
├── detail.php               # Halaman detail bunga tulip & tips botani
├── about.php                # Halaman sejarah tulip & fenomena Tulipmania
├── includes/
│   ├── navbar.php           # Navigasi utama responsif
│   └── footer.php           # Bagian footer & informasi hak cipta
├── setup/
│   ├── connection.php       # Koneksi PDO (Auto-migration lokal & cloud)
│   └── style.css            # Desain tampilan & variabel warna bunga tulip
├── auth/
│   ├── login.php            # Halaman masuk (Admin & Pengguna)
│   ├── register.php         # Halaman pendaftaran akun baru
│   └── logout.php           # Halaman keluar sesi
└── admin/
    ├── auth_check.php       # Proteksi hak akses halaman admin
    ├── index.php            # Dashboard statistik admin
    ├── tulips.php           # Tabel manajemen varietas bunga (CRUD)
    ├── tulip_add.php        # Form tambah varietas tulip
    ├── tulip_edit.php       # Form edit varietas tulip
    ├── tulip_delete.php     # Logika hapus varietas tulip
    └── orders.php           # Manajemen pesanan pengunjung & ganti status
```

---
*Dibuat dengan ❤️ untuk Tugas IT / Pemrograman Web.*
