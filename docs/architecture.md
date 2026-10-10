# Architecture Documentation
# Sistem Pemesanan Makanan "Lahap"

## Tech Stack

| Layer | Teknologi | Versi |
|-------|-----------|-------|
| **Backend** | PHP | 8.x (native, no framework) |
| **Database** | MySQL | 8.0 |
| **Web Server** | Apache | (bundel XAMPP) |
| **Frontend** | Bootstrap | 5.x (CDN) |
| **JavaScript** | Vanilla JS | native, no frameworks |

## Project Folder Structure

```
lahap/
├── config/
│   └── database.php          # Koneksi PDO ke MySQL (central config)
│
├── includes/
│   ├── session.php           # Session management + auth middleware
│   ├── functions.php         # Helper functions (e(), flash(), generate_order_code(), etc.)
│   ├── header.php            # HTML <head> + navbar (layout template)
│   └── footer.php            # Akhir layout + Bootstrap JS
│
├── public/
│   ├── index.php             # Landing page — redirect berdasarkan peran
│   ├── login.php             # Halaman login (admin & customer)
│   ├── register.php          # Registrasi customer baru
│   └── logout.php            # Logout & destroy session
│
├── customer/                  # Area customer (butuh login kecuali track.php)
│   ├── menu.php              # Browse semua menu aktif + filter kategori
│   ├── checkout.php          # Cart → form checkout → submit order
│   ├── orders.php            # Riwayat pesanan user yang login
│   └── track.php             # Cek status via kode order (tanpa login)
│
├── admin/                     # Area admin (wajib login, role=admin)
│   ├── dashboard.php         # Ringkasan order count per status (hari ini)
│   ├── menu/
│   │   ├── index.php         # List + filter menu
│   │   ├── create.php        # Tambah menu + upload gambar
│   │   ├── edit.php          # Edit menu
│   │   └── delete.php        # Toggle is_active (soft delete)
│   ├── orders/
│   │   ├── index.php         # Semua order dengan filter status
│   │   ├── view.php          # Detail order + update status dropdown
│   │   └── update-status.php # Handler POST update status + log history
│   ├── sales.php             # Statistik menu terlaris + export CSV
│   └── print.php             # Cetak struk (print.php?order_id=X)
│
├── assets/
│   ├── css/
│   │   └── style.css         # Custom CSS
│   ├── js/
│   │   └── main.js           # Interaktivitas frontend (keranjang, dll)
│   └── upimg/                # Upload gambar menu (JPG/PNG)
│
├── tests/
│   ├── run.php               # Test runner — `php tests/run.php`
│   ├── TestCase.php          # Base class untuk semua test
│   ├── unit/
│   │   ├── FunctionsTest.php # Test helper functions
│   │   └── AuthTest.php      # Test session & role middleware
│   └── integration/
│       ├── OrderFlowTest.php # Full checkout → order created
│       └── SalesReportTest.php # Statistik query & CSV export
│
└── docs/
    ├── PRD.md                # Product Requirements Document (flow, fitur, prinsip)
    ├── ERD.md                # Entity Relationship Diagram (schema lengkap)
    ├── agents.md             # Agent guidelines & Definition of Done
    ├── AGENTS.md             # (alias agents.md — dibaca oleh sistem)
    ├── architecture.md       # File ini — tech stack & folder structure
    └── database.sql          # SQL dump skema database (buat import)
```

## Path Aliases

Untuk konsistensi, definisikan path relatif dari document root (`lahap/`):

```
/app     → ./includes (core logic)
/public   → ./public (entry point)
/admin    → ./admin (area admin)
/customer → ./customer (area customer)
/assets   → ./assets (css/js/img)
/config   → ./config (database settings)
```

## Koneksi Database

- File: `config/database.php`
- Metode: PDO dengan prepared statements
- Credentials: `root`, password kosong, database `lahap`

## Routing Notes

- Tidak ada router framework — tiap file PHP langsung diakses via URL
- `public/` berisi entri poin publik
- Admin & customer memakai struktur folder terpisah untuk keamanan middleware

## Testing Strategy

- Unit tests: helper functions, auth middleware, order code generation
- Integration tests: full checkout flow, sales data query, CSV export
- Runner: `php tests/run.php` — output PASS/FAIL + exit code
