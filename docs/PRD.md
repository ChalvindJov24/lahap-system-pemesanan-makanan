# Product Requirements Document (PRD)
# Sistem Pemesanan Makanan "Lahap"

## 1. Gambaran Umum
Aplikasi pemesanan makanan berbasis web. Customer bisa browse menu dan checkout (wajib login). Admin mengelola menu, semua pesanan, statistik penjualan, dan mencetak struk.

## 2. Roles & Akses

| Role | Kemampuan |
|------|-----------|
| **Admin** | Kelola menu (CRUD), kelola semua pesanan, statistik penjualan, cetak struk |
| **Customer** | Browse menu, login/register untuk checkout, riwayat pesanan, tracking via kode |

## 3. Flow Aplikasi

### 3.1 Authentication
```
User akses → belum login?
  ├── Ya → arahkan ke /login atau /register
  └── Tidak → lanjut ke fitur yang diinginkan
```

### 3.2 Checkout Flow
```
Customer browsing menu
  → klik "Pesan"
  → cek login?
    ├── belum login → redirect /login, kembali ke checkout setelah login
    └── sudah login → form checkout otomatis terisi (nama, email, HP)
  → submit → order masuk DB (status = pending)
  → muncul di dashboard admin secara real-time
  → customer dapat kode order (ORD-YYYYMMDD-HHMMSS)
```

### 3.3 Admin Order Management
```
Admin lihat order (pending)
  → "Process" → processing
  → "Mark Ready" → ready
  → "Complete" → completed
  → "Cancel" → cancelled (opsional)
```

### 3.4 Cetak Struk
```
Admin /order?id=X → klik "Cetak" → halaman struk print → auto window.print()
```

## 4. Fitur Detail

### Auth
- `/register` – customer daftar, role otomatis customer
- `/login` – login (admin/customer)
- `/logout` – hapus session

### Customer
- **Menu** (`/`) – browse semua menu aktif, filter kategori
- **Checkout** (`/checkout`) – pilih items, qty, payment dummy, submit order
- **Riwayat** (`/orders`) – daftar pesanan user login
- **Tracking** (`/track`) – cek status via kode order (tanpa login)

### Admin
- **Dashboard** (`/admin`) – statistik ringkasan (order count per status)
- **Menu** (`/admin/menu`) – CRUD menu + upload gambar
- **Orders** (`/admin/orders`) – lihat/update status semua order
- **Sales** (`/admin/sales`) – statistik menu terlaris + export CSV
- **Print** (`/admin/print?id=X`) – struk printer thermal friendly

## 5. Status Pesanan

| Status | Deskripsi |
|--------|-----------|
| `pending` | Baru dibuat, menunggu proses |
| `processing` | Sedang disiapkan |
| `ready` | Siap ambil |
| `completed` | Selesai / diambil |
| `cancelled` | Dibatalkan |

## 6. Prinsip & Asumsi

1. **Payment dummy** – simulasi cash/e-wallet, tidak ada gateway asli
2. **Customer wajib login** untuk checkout; tracking via kode bisa tanpa login
3. **Admin manages everything** – menu, orders, stats, struk
4. **Real-time order** – order masuk langsung tampil di admin dashboard
5. **Responsive** – web bisa diakses di HP/laptop
6. **Export statistik** – CSV untuk laporan penjualan
7. **Print via browser** – struk menggunakan `window.print()`
8. **Upload gambar menu** – JPG/PNG, simpan di folder assets
9. **Email verifikasi** – dilewati (registrasi langsung aktif)
