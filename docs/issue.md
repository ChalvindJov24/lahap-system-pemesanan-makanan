# Issue: Setup Project Guide
# Sistem Pemesanan Makanan "Lahap"

## Tujuan
Panduan langkah-demi-langkah untuk menginstal seluruh dependensi dan mengonfigurasi environment agar project "Lahap" dapat dijalankan dan dikembangkan oleh agent berikutnya.

---

## 1. Teknologi yang Diperlukan

| Komponen | Versi | Keterangan |
|----------|-------|------------|
| **PHP** | 8.x | CLI + extension: PDO, mbstring, gd, fileinfo, json |
| **MySQL** | 8.0+ | Database server |
| **Apache** | 2.4+ | Web server (bisa via XAMPP) |
| **Bootstrap** | 5.x | Frontend framework (via CDN) |
| **Composer** | optional | Hanya jika diperlukan dependency tambahan |

---

## 2. Prasyaaat Sistem Perangkat Lunak

Sebelum memulai, pastikan komponen berikut terpasang:

```bash
php -v        # Harus >= PHP 8.0
php -m        # Cek extension: pdo_mysql, mbstring, gd, fileinfo, json
mysql --version  # Harus >= MySQL 8.0
```

Pastikan port berikut tersedia (tidak sedang digunakan):
- `:80` (Apache/web server)
- `:3306` (MySQL)

---

## 3. Instalasi Perangkat Lunak

### Step 1: Install PHP 8.x
- **Windows (XAMPP):** Download dari https://www.apachefriends.org, install XAMPP yang termasuk PHP
- **Alternatif:** Install PHP standalone dari https://www.php.net/downloads.php
- **Verifikasi:** `php -v`
- **Cek extension penting:**
  ```bash
  php -m | findstr pdo_mysql
  php -m | findstr mbstring
  php -m | findstr gd
  php -m | findstr fileinfo
  php -m | findstr json
  ```

### Step 2: Install MySQL 8.0
- **Via XAMPP:** Biasanya termasuk MySQL/MariaDB
- **Standalone:** Download dari https://dev.mysql.com/downloads/installer/
- **Verifikasi:** `mysql --version`

### Step 3: Install Apache / Web Server
- **Via XAMPP:** Otomatis terinstall
- **Verifikasi:** Buka `http://localhost/` di browser

### Step 4: Konfigurasi Virtual Host
- Atur `DocumentRoot` ke folder project agar routing berfungsi dengan sempurna
- Contoh konfigurasi Apache virtual host:
  ```apache
  <VirtualHost *:80>
      DocumentRoot "D:/xampp/htdocs/lahap/public"
      ServerName lahap.local
  </VirtualHost>
  ```
- Tambahkan `127.0.0.1 lahap.local` ke file `hosts`

### Step 5: Import Database Schema
```bash
mysql -u root -p lahap < docs/database.sql
```
- Gunakan credentials MySQL yang sesuai
- Database `lahap` akan dibuat otomatis oleh script

---

## 4. Setup Project

### Step 1: Letakkan Project
- Pastikan project berada di folder yang dapat diakses web server
- Contoh: `D:/xampp/htdocs/lahap/` atau `/var/www/html/lahap/`

### Step 2: Konfigurasi Database
- Edit file `config/database.php`
- Isi credential MySQL sesuai environment:
  ```php
  $host = 'localhost';
  $db   = 'lahap';
  $user = 'root';
  $pass = '';  // ubah sesuai password MySQL Anda
  ```

### Step 3: Buat Folder yang Diperlukan
Pastikan folder berikut ada dan bisa ditulis oleh web server:
```
assets/upimg/     -- tempat upload gambar menu
tests/            -- tempat file unit/integration test
```
Jika belum ada, buat secara manual:
```bash
mkdir -p assets/upimg
mkdir -p tests
```

### Step 4: Verifikasi Environment
- Buka browser, akses: `http://lahap.local/login`
- Halaman login harus muncul (bisa error koneksi DB, itu normal sebelum import schema)
- Jalankan: `php tests/run.php` — harus output semua PASS (setelah test terbuat)

### Step 5: Seeding Data (Opsional)
- Import data contoh (kategori menu, admin user) untuk testing awal
- File seed akan tersedia di `docs/seed.sql` (optional, hanya untuk dev)

---

## 5. Batasan Agent (Agent Constraints)

Agent yang mengerjakan setup ini **hanya diperbolehkan** melakukan:

- ✅ Instal PHP, MySQL, Apache/XAMPP
- ✅ Konfigurasi virtual host / document root
- ✅ Import database schema dari `docs/database.sql`
- ✅ Edit `config/database.php` (hanya credential DB)
- ✅ Membuat folder `assets/upimg/` dan `tests/`
- ✅ Verifikasi via `php -l` dan `php tests/run.php`

Agent **dilarang**:

- ❌ Mengubah struktur folder final (lihat `docs/architecture.md`)
- ❌ Menambahkan dependency eksternal (kecuali Bootstrap CSS via CDN)
- ❌ Mengubah tech stack (wajib pakai PHP native, bukan framework)
- ❌ Membuat file code PHP selain yang tercantum di architecture.md
- ❌ Menghardcode credential di file PHP selain `config/database.php`

---

## 6. Definition of Done (Phase: Setup Project)

Setup project dianggap **selesai / Done** apabila **semua** kondisi berikut terpenuhi:

- [ ] PHP 8.x terpasang & dapat diakses via CLI (`php -v`)
- [ ] Cek extension penting ada: pdo_mysql, mbstring, gd, fileinfo, json
- [ ] MySQL 8.0+ berjalan dan dapat di-connect via CLI (`mysql -u root -p`)
- [ ] Web server dapat serve halaman (`http://localhost/` merespon)
- [ ] Database schema berhasil di-import (`USE lahap; SHOW TABLES;` menampilkan 6 tables)
- [ ] `config/database.php` berisi credential yang valid
- [ ] Folder `assets/upimg/` dan `tests/` sudah dibuat & writable
- [ ] `php -l` lint bersih tanpa error/warning/notices untuk semua file PHP
- [ ] `php tests/run.php` — hasil semua test: **PASS** (jika sudah tersedia)
- [ ] Halaman login dapat diakses via browser (`http://lahap.local/login`)
- [ ] Error log dibersihkan (tidak ada fatal error yang menghalangi akses)

---

## Notes / Troubleshooting

1. **MySQL root password** — Jika lupa password, reset via safe mode atau minta admin sistem
2. **Permission denied** — Pastikan folder `assets/upimg/` & `tests/` writable oleh web server
3. **Port conflict** — Cek apakah port 80/3306 sudah dipakai aplikasi lain
4. **PHP extensions** — Jika `php -m` tidak dapat menemukan extension, aktifkan di `php.ini`

```
Semua langkah di atas harus selesai sebelum melangkah ke fase implementasi kode sumber.
```
