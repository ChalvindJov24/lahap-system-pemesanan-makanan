# Lahap — Design System

Acuan visual untuk aplikasi pemesanan makanan Lahap, bukan POS inventori. Spesifikasi ini merupakan target desain, bukan pernyataan bahwa halaman sudah selesai. Scope bisnis mengikuti `PRD.md`.

## 1. Prinsip

- Bersih dan fungsional: latar putih, hierarki jelas, tanpa gradien atau dekorasi yang mengganggu.
- Aksen merah untuk aksi utama dan navigasi aktif; target maksimal dua titik aksen dekoratif per viewport. Error, fokus keyboard, dan status penting tidak dibatasi aturan dekoratif ini.
- Customer memprioritaskan makanan, harga, keranjang, dan progres pesanan. Admin memprioritaskan antrean dan tindakan berikutnya; bukan stok/SKU yang belum masuk scope.
- Satu aksi utama dominan per kelompok tugas. Tombol tambah pada daftar menu tetap boleh berulang, dengan gaya sekunder agar tidak bersaing.
- Status selalu memakai teks; warna tidak boleh menjadi satu-satunya petunjuk.
- Layout customer dan admin boleh berbeda, tetapi token, formulir, tombol, dan pola feedback konsisten.
- Tanpa emoji sebagai ikon fungsional, hover scale berlebihan, ilustrasi dekoratif tidak relevan, atau blur berat. Ikon SVG konsisten; ikon dekoratif disembunyikan dari pembaca layar.

## 2. Token Warna dan Bentuk

| Token | Nilai | Pemakaian |
|---|---|---|
| `--bg` | `#ffffff` | Latar halaman |
| `--surface` | `#ffffff` | Kartu/panel |
| `--fg` | `#111827` | Teks utama |
| `--muted` | `#6b7280` | Teks sekunder pada putih |
| `--border` | `#e5e7eb` | Pemisah dekoratif |
| `--control-border` | `#6b7280` | Batas input/kontrol yang diperlukan untuk mengenalinya |
| `--accent` | `#dc2626` | CTA utama dan navigasi aktif |
| `--accent-hover` | `#b91c1c` | Hover CTA, teks tetap putih |
| `--accent-soft` | `#fee2e2` | Tint aksen/fokus |
| `--danger` | `#b91c1c` | Error/batal, teks |
| `--danger-soft` | `#fee2e2` | Latar error |
| `--warn` | `#9a3412` | Pending/perhatian, teks |
| `--warn-soft` | `#ffedd5` | Latar perhatian |
| `--success` | `#166534` | Selesai/siap, teks |
| `--success-soft` | `#dcfce7` | Latar sukses |
| `--neutral-soft` | `#f3f4f6` | Status netral |
| `--radius` | `10px` | Input/tombol |
| `--radius-lg` | `14px` | Kartu/panel |

Definisikan token dalam satu sumber CSS bersama saat integrasi layout; jangan menyalin blok :root berbeda untuk setiap komponen. Prototipe yang sudah memakai token lama perlu diselaraskan pada issue implementasi, bukan dianggap telah berubah oleh dokumen ini.

Kontras target WCAG AA: teks biasa minimal 4.5:1, teks besar 3:1, kontrol/fokus yang diperlukan 3:1 terhadap warna bersebelahan. Uji kombinasi aktual termasuk hover/error. Jangan memakai latar merah muda dengan teks putih untuk tombol utama; jangan mengasumsikan semua warna Bootstrap sudah sesuai.

## 3. Tipografi

- Font utama Poppins lokal di `public/assets/fonts/`, fallback system-ui, sans-serif. Bila font belum tersedia, laporkan fallback; deklarasi font-family saja tidak memasang Poppins.
- Angka harga, kuantitas, kode order, dan laporan memakai font-mono/tabular. Copyright biasa tidak wajib monospace.
- Teks utama 16px, teks bantuan 14px, judul halaman 24–32px, judul seksi 18–24px; line-height teks sekitar 1.5.
- Tidak memakai Google Fonts/CDN. Font lokal harus disertai lisensi yang sesuai saat ditambahkan.

## 4. Layout dan Responsif

### Customer
Navbar berisi brand Lahap, Menu, serta tautan auth atau Pesanan Saya sesuai integrasi backend nanti. Keranjang terlihat pada alur pemesanan. Konten utama katalog/checkout/riwayat berada dalam container. Footer minimal berisi identitas dan informasi yang benar-benar tersedia, bukan kontak fiktif.

### Admin
Navigasi dashboard, kategori/menu, pesanan, dan laporan. Sidebar opsional di desktop, collapse/offcanvas di layar sempit. Tidak memasukkan SKU, pembelian, retur, atau stok opname.

### Auth
Form login/register ringkas, label selalu terlihat, password toggle dapat dioperasikan dengan keyboard, error tidak menghilangkan input yang aman untuk dipertahankan.

### Breakpoint
Gunakan breakpoint Bootstrap lokal, bukan breakpoint 640/960 yang berlawanan dengan kelas bawaannya:

| Lebar | Perilaku |
|---|---|
| <576px | Satu kolom, navigasi ringkas, padding 16px |
| 576–991px | Katalog dapat dua kolom; navigasi tetap collapse; checkout menyesuaikan ruang |
| >=992px | Navigasi horizontal; katalog dapat tiga/empat kolom; ringkasan checkout dapat berdampingan |

Konten tidak boleh terpotong pada tablet. Uji minimal 320px, 768px, 1024px, dan 1440px serta zoom 200%. Tabel lebar boleh scroll dalam region berlabel, bukan membuat seluruh halaman overflow.

Footer memakai layout flex kolom dengan tinggi minimum viewport: di bawah pada halaman pendek, mengikuti konten pada halaman panjang. Bukan position fixed yang menutupi tombol/form.

## 5. Halaman dan State

| Halaman | Isi utama | State wajib |
|---|---|---|
| Katalog | Foto, nama, harga, kategori, tambah item | Kosong, hasil filter kosong, menu tidak tersedia |
| Login/register | Label, bantuan, tombol submit, tautan silang | Error input, kredensial salah secara umum, pembatasan percobaan |
| Checkout | Item, jumlah, harga, kontak, metode simulasi, total | Keranjang kosong, harga berubah, menu nonaktif, sedang kirim, gagal, berhasil |
| Riwayat/detail | Kode, snapshot item, total, status | Belum ada order, tidak ditemukan/tidak berhak tanpa bocoran data |
| Admin pesanan | Antrean, filter, detail, aksi status sah | Kosong, koneksi gagal, pembaruan status konflik |
| Admin menu/kategori | Daftar, form, aktif/nonaktif | Validasi gagal, upload ditolak, penghapusan ditolak karena referensi |
| Laporan/struk | Periode, total selesai, item terlaris, export/print | Belum ada penjualan, label simulasi, tampilan cetak tanpa navigasi |

Status badge:
- `pending`: teks Menunggu, warn.
- `processing`: teks Disiapkan, neutral.
- `ready`: teks Siap diambil, success.
- `completed`: teks Selesai, success; dibedakan dari ready melalui label.
- `cancelled`: teks Dibatalkan, danger.

Status pembayaran tidak disamakan dengan status pesanan. Jangan menampilkan Lunas hanya karena completed.

## 6. Interaksi dan Aksesibilitas

- Gunakan header, nav berlabel, main tunggal, footer, urutan heading, dan skip link ke konten.
- Tombol memiliki nama yang jelas; SVG dekoratif aria-hidden. Tautan aktif memakai aria-current.
- Semua kontrol memiliki fokus terlihat; target sentuh minimal 24×24 CSS px, rekomendasi 44×44 untuk kontrol utama.
- Label terhubung ke input; error terhubung lewat aria-describedby dan aria-invalid, bukan border merah saja. Pesan server tidak membocorkan detail internal.
- Form mendukung autocomplete sesuai fungsi; toggle password memperbarui label/status aksesibel.
- Navigasi collapse memakai komponen Bootstrap lokal dengan aria-controls/aria-expanded. Jika offcanvas dipilih untuk admin, uji Escape, pengelolaan fokus, dan pengembalian fokus.
- Jangan menonaktifkan outline tanpa pengganti. Hormati prefers-reduced-motion.
- Notifikasi penting dapat dibaca pembaca layar; polling tidak memindahkan fokus atau mengumumkan seluruh tabel setiap interval.
- Tombol submit boleh dinonaktifkan selama request, tetapi pencegahan order ganda tetap tanggung jawab backend.
- Gambar menu memiliki alt yang berguna; jangan mengandalkan gambar untuk nama/harga.

## 7. Batas Prototipe dan Verifikasi

Frontend-only boleh memakai data contoh yang jelas, tetapi tidak boleh mengklaim login, pembayaran, atau penyimpanan berhasil secara nyata. Backend dibuat pada issue terpisah.

Periksa resource lokal tidak 404, interaksi keyboard, error form, layout sempit, zoom, dan kontras. Catat browser serta viewport yang diuji. HTTP 200, lint, dan inspeksi source bukan pengganti pengujian render/interaksi di browser. Tidak ada halaman berstatus selesai hanya karena tercantum dalam dokumen ini.
