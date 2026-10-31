# Issue: Backend Auth — Register, Login, Logout, dan Akses Role

> **Draft untuk review.** Issue ini menyiapkan rencana backend auth saja; belum mengimplementasikan. Baca `docs/PRD.md`, `docs/ERD.md`, `docs/database.sql`, `docs/agents.md`, `docs/architecture.md`, dan `docs/DESIGN.md` sebelum kode. Keputusan bisnis berlabel usulan pada PRD harus disetujui sebelum implementasi backend terkait.

## 1. Konteks dan Tujuan

Lahap memiliki dua role: admin dan customer. Katalog/beranda dapat dilihat pengunjung, tetapi pemesanan/checkout/riwayat wajib login. Registrasi publik **selalu** menghasilkan `customer`, bukan admin. Admin dibuat melalui proses setup terbatas (issue tersendiri), **bukan** pemilihan role pada form registrasi. Dokumen ini juga menindaklanjuti `docs/issue.md` (navbar/footer) yang secara sengaja tidak menggunakan backend.

Pemeriksaan saat ini menemukan:
- `public/login.php` sudah pakai prepared statement dan `password_verify()`, tapi validasi hanya input kosong; belum ada regenerasi session ID, CSRF, pembatasan percobaan login, validasi tipe/format, atau path action yang benar pada subfolder lokal.
- `includes/session.php` punya `is_logged_in()`, `get_role()`, `require_login()`, `require_role()`, `current_user()`. Strict session mode, cookie aman, CSRF, dan redirect internal yang aman belum eksplisit.
- `public/register.php` dan `public/logout.php` **belum ada**.
- Form login/redirect memakai path root seperti `/login.php`; tidak sesuai `/lahap/public/`.

**Tujuan:** auth server-side aman, konsisten dengan desain dan backend yang ada, dapat diuji, tanpa mengerjakan checkout/katalog/menu/dashboard/navbar-footer baru.

## 2. Referensi dan Perbedaan Lingkungan

| Sumber | Relevansi |
|---|---|
| `docs/PRD.md` | Auth, role, password ≥ 8 karakter, HP opsional saat daftar, redirect internal |
| `docs/ERD.md`, `docs/database.sql` | `users(id, name, email UNIQUE, phone, password, role, created_at)`; `password VARCHAR(255)` hash; email unik. DDL belum otomatis diterapkan ke DB aktif. |
| `docs/agents.md` | Batasan perubahan, keamanan, DoD per issue, testing |
| `docs/architecture.md` | Backend PHP native, document root `public/`, base URL dan konfigurasi |
| `docs/DESIGN.md` | Bootstrap lokal, label/fokus aksesibel, kontras |

**Kondisi lokal terakhir vs target dokumen** (perlu verifikasi ulang, bukan asumsi):
- Pilihan pengguna: database `lahap` dipakai pada MariaDB 10.4.11 port **8111**, sesuai `config/database.php`. Jangan pindahkan kembali ke 3306/3307. (Dokumen arsitektur menargetkan MySQL ≥ 8.0.16; MariaDB 10.4 memadai untuk checklist ini.)
- Runtime sebelumnya terdeteksi PHP 7.4.3 CLI/Apache. Verifikasi ulang CLI dan Apache sebelum tes; jangan anggap sama. Bila 7.4 belum dipakai backend lain, ini hambatan penerimaan.
- Akun `root` lokal **bukan** baseline produksi. Upgrade ke akun DB hak minimum memerlukan persetujuan terpisah; laporkan sebagai blocker penerimaan, jangan ganti server diam-diam. `root` hanya untuk dev lokal.

## 3. Scope

**Termasuk:** register customer+validasi, login dua role+session+CSRF+limiter, logout POST, guard login/role, redirect internal aman, integrasi form dengan tampilan auth, dan pengujian terhadap DB test.

**Tidak termasuk:** checkout/keranjang/katalog/CRUD menu/dashboard/navbar-footer baru/tracking/laporan/forgot-password/verifikasi-email/remember-me/OAuth, **seed admin**, **migrasi/import ulang DB**, **perubahan kredensial/server**, atau framework baru.

## 4. File yang Direvisi

| File | Rencana |
|---|---|
| `includes/session.php` | Cookie config sebelum `session_start`; strict mode, CSRF, guard login/role, regenerasi, validasi redirect, logout |
| `public/login.php` | Validasi input, CSRF, limiter, error umum, login session, redirect; perbaiki `action`/tautan subfolder |
| `public/register.php` (baru) | GET form + POST register customer; hash password; tangani duplicate email/race; 303 ke login |
| `public/logout.php` (baru) | Hanya POST, CSRF, hapus session+cookie, 303 ke login |
| `includes/functions.php` | Helper kecil jika dipakai (mis. normalisasi) — **jangan** mengubah `generate_order_code`/`status_badge`/`payment_label` (milik order) |
| `includes/header.php`, `includes/footer.php` | Integrasi resource lokal + tautan auth + form logout POST; jangan rebuild layout |
| `public/login.html`, `public/register.html` | Referensi visual saja; jangan hapus; tautan aktif ke PHP |
| `public/assets/css/login-style.css` | Perubahan minimal error/fokus auth bila perlu |
| `includes/auth_rate_limit.php` (baru bila perlu) | Storage limiter atomik, terpisah dari session, sesuai kebijakan persetujian |
| `tests/run.php`, `tests/auth.php` (baru bila perlu) | Pemeriksa auth/limiter tanpa framework, DB test eksplisit |

`config/database.php` hanya diubah bila diperlukan untuk injeksi konfigurasi test; port 8111 tetap. Struktur ini hanya file terkait issue, bukan ganti arsitektur.

## 5. Keputusan Teknis (wajib disetujui sebelum kode)

1. **Redirect:** base URL dipercaya dari konfigurasi (bukan Host/header request). Allowlist per role; tolak eksternal/`//host`/backslash/traversal/karakter kontrol/CRLF/path diluar base+role. Hapus penyimpanan redirect setelah pakai.
2. **Fallback tujuan login:** jika `/admin/dashboard.php` belum ada, rekomendasi 303 ke `login.php` dengan flash "Anda sudah masuk" + form logout. (usulan — butuh persetujuan)
3. **CSRF:** token acak kriptografis terikat session, string-only, `hash_equals()`; GET login/register tolak token. Logout GET → 405. Rotasi setelah autentikasi.
4. **Password:** ≥ 8 karakter; tolak NUL; bila bcrypt bcrypt maks 72 byte, bukan 72 karakter; konfirmasi harus identik. Hash hanya lewat `password_hash()`.
5. **Register:** role literal `customer` di server, `email UNIQUE` primary guard, duplicate-key ditangani eksplisit; semua error DB lain tidak disamarkan. 303 ke login, tidak auto-login.
6. **Login:** `LIMITER` sebelum verifikasi; prepared query; `password_verify()` + password hash dummy agar path email-tidak-ada tak lompat. Pesan sama untuk tidak ditemukan/salah. Regenerasi ID sesi lama dihapus. Redirect hanya ke tujuan aman.
7. **Session:** strict mode, cookie-only, HttpOnly, SameSite=Lax, Secure pada HTTPS, path mengikuti base URL; jangan percaya X-Forwarded-* tanpa proxy tepercaya.
8. **Pembatasan login (usulan konkret — butuh persetujuan):** 5 gagal/15 menit per email (ternormalisasi); 30 gagal/15 menit per IP; independen, 429+ Retry-Aouter, tidak reset akun sukses. Storage atomik di luar document root via file locking; tidak file password/session/email. Jika belum ada lokasi yang disetujui → blocker; gunakan komentar `ponytail:` tentukan ceiling dan jalur upgrade.
9. **Guard:** tujuan login terpisah dari guard role; role salah → 403 (bukan loop); POST-only endpoint tidak diterima pada GET.

**Blocker sebelum kode:** (a) fallback tujuan login jika dashboard belum ada; (b) kebijakan + lokasi storage limiter; (c) konfirmasi runtime PHP di CLI/Apache; (d) base URL final. Jangan menebak atau menghilangkan limiter agar issue tampak selesai.

## 6. Langkah Implementasi

1. Audit awal: git status, file auth/layout/test, runtime/CLI, DB schema, base URL. Jangan jalankan tes lama sebelum memastikan tidak menulis ke DB utama.
2. Konfigurasi session/cookie sebelum `session_start()` (strict mode).
3. Tambahkan CSRF (generate_token/validate) — satu implementasi, dipakai login/register/logout.
4. Validasi input: tolak non-pengguna/array sebelum `trim`; UTF-8 nama 2–100; email normalisasi (trim, lowercase, maks 100, valid ASCII); password tidak di-trim, ≥ 8, tolak NUL dan batas hash. HP opsional: kosong→NULL, normalisasi spasi/pemisah, validasi 10–15 digit `+` optional.
5. Register POST: prepared INSERT, hash, role literal `customer`, tangani duplicate Key, 303 ke login, flash. Jangan isi ulang password.
6. Login POST: limiter, query prepared, `password_verify()` + hash dummy, session login, regenerasi ID, aman pada keranjang guest, redirect allowlist, 303.
7. Guard + logout: pisahkan tujuan login; role salah→403; logout GET→405; POST validasi CSRF, hancur session+cookie, 303 ke login.
8. UI: pakai styling auth yang ada + Bootstrap lokal + `e()`; action/form action benar relatif ke subfolder; label/aria-describedby/aria-invalid; autocomplete sesuai; auth bekerja tanpa JS.
9. Uji & laporkan: lint, test DB test terpisah, alur browser; cantum hid.

## 7. Pengujian Wajib

DB test terpisah, konfigurasi eksplisit, fixture hash-only, tidak membuat admin di DB nyata. Tidak perlu framework.

- Register: valid, HP kosong, nama Unicode, email duplikat+variasi kapital, race condition insert bersamaan.
- Register negatif: password pendek, melebihi batas hash, konfirmasi beda, email salah, array, kosong, HP salah, role=admin — semua ditolak.
- Login: customer/admin valid, salah (user ada/tidak ada) identik, role tidak dikenal ditolak.
- Session: ID berubah post-login, ID lama tidak dapat akses, keranjang aman; cookie Secure HttpOnly SameSite.
- CSRF: missing/invalid/array/token-cross-session semua 403; rotasi valid.
- Limiter: reset session/cookie baru memakai batas penuh; akun & IP independen; race paralel tidak melebihi batas; expired kunci; storage gagal → tolak aman. (Jika belum ada storage yang disetujui: **blocker**, tidak gunakan session storage.)
- Redirect: eksternal/`//`/`../`/encoded CRLF/path role-salah/subfolder diuji.
- Guard: guest →login; role salah→403; GET POST-only endpoint→405.
- Logout: GET→405 tidak logout; POST valid → session invalid, cookie tidak reusable.
- Error: DB gagal → pesan aman, tidak ada password/query/stack trace.
- UX: form bekerja JS-off; resource lokal tidak 404; Chrome/Firefox desktop/mobile + keyboard; jelaskan bila uji tidak tersedia.

## 8. Definition of Done (Per Issue — Auth)

- [ ] `login.php`/`register.php`/`logout.php` PHP tersedia & terhubung ke form.
- Registrasi selalu customer; email unik ditangani; password hanya hash.
- Validasi server menangani tipe/format/panjang, empty array, batas hash algoritma.
- CSRF berlaku semua POST auth; logout GET tidak mengubah state.
- Session ID diregenerasi; cookie aman; logout menonaktifkan session lama.
- Limiter persisten & memenuhi kebijakan yang disetujui; aman race/storage-gagal (atau blocker dicatat).
- Base URL/folder benar; tidak ada redirect eksternal, 404, loop; halaman tujuan belum ada pakai fallback yang disetujui.
- Guard role diuji; **bukan** klaim ownership order selesai (di luar scope).
- Error aman, input ter-`e()`.
- Minimal satu pemeriksa auth terhubung runner; semua tes relevan **PASS** pada DB test terpisah.
- `php -l` bersih untuk PHP yang berubah; runtime tidak warning/error.
- Browser/aksesibilitas/JS-off diuji; HTTP 200 ≠ kelulusan interaksi.
- Selisih runtime dokumen (MariaDB/PHP) dilaporkan; jangan klaim produksi siap.
- Diff sesuai izin (tidak ada DB utama/migrasi/seed admin/server/role).
- Laporan: file, tes, hambatan, dan apa yang belum selesai.

DoD rilis (checkout/stok/laporan/struk/ownership/upload) **N/A** untuk issue ini.

## 9. Batasan Agent

- Tunggu persetujuan keputusan §5 sebelum kode/auth/limiter/storage.
- PHP native, PDO, Bootstrap lokal, vanilla JS. Tidak framework/Composer/Redis/CDN/abstraksi.
- Jangan menghapus prototipe/dump/file temp lama tanpa izin; jangan hapus `.tmp_mysql.ini/.tmpdb` atau ganti pilihan 8111.
- Tidak menjalankan import/migrasi/reset/drop DB, seed admin, change auth MySQL/MariaDB, service server, atau konfigurasi phpMyAdmin.
- Jangan menulis kredensial nyata ke repo/dokumen/perintah; gunakan env/local-only untuk test.
- Jangan menyederhanakan validasi/CSRF/limiter/session/guard aksesibilitas.
- Tidak commit/push, create/close issue, atau merge tanpa instruksi eksplisit.
