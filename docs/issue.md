# Issue: Implement Login & Register Pages (Frontend Only)

## Konteks & Tujuan

Membangun halaman **login** dan **register** (frontend only — HTML/CSS/JS). Backend (auth logic, session handling, DB interaction) akan diimplementasikan di issue terpisah.

Halaman ini harus mengikuti design system dari `DESIGN.md`:

- **Warna aksen:** `--accent: #dc2626` (maks 2 titik per viewport)
- **Font:** Poppins (primary), font-mono (angka/kode)
- **Style:** clean, high-contrast, zero gradien, zero blur
- **Responsive:** desktop >960px, tablet 641–960px, mobile ≤640px

---

## Scope

### Dikerjakan (Frontend)
- ✅ HTML5 semantic, CSS via Bootstrap 5 + custom token CSS
- ✅ Register page — form: nama, email, HP, password, konfirmasi
- ✅ Login page — form: email, password
- ✅ Form validation visual (client-side)
- ✅ Responsive layout (centered card)
- ✅ Password toggle visibility (eye icon SVG)

### TIDAK dikerjakan (Backend — issue terpisah)
- ❌ Auth logic, DB interaction, session management
- ❌ Form submission handler / PHP backend
- ❌ User registration DB insertion

---

## Step by Step

### Step 1: Buat CSS design token
- **File:** `public/assets/css/login-style.css`
- Copy token dari `DESIGN.md` (`:root` variables)
- Custom class: `.auth-card`, `.auth-form-control`, `.auth-cta`, dll

### Step 2: Buat `register.html`
- Layout: centered card (max-width 400px)
- Background: `--bg` (#ffffff)
- Card: `--surface`, radius 10px, shadow
- Form fields:
  - Nama Lengkap (required)
  - Email (type=email, required)
  - No. HP (type=tel, pattern digit)
  - Password (type=password, min 6 karakter)
  - Konfirmasi Password (must match)
- CTA: tombol "Daftar" — primary (`--accent`, text putih)
- Link kecil: "Sudah punya akun? Login" → `/login.html`

### Step 3: Buat `login.html`
- Layout: centered card (max-width 400px)
- Form fields:
  - Email (type=email, required)
  - Password (type=password, required)
- CTA: tombol "Login" — primary (`--accent`, text putih)
- Link kecil: "Belum punya akun? Daftar" → `/register.html`

### Step 4: Validasi form
- HTML5 validation (`required`, `type`, `pattern`)
- CSS feedback: `.is-invalid`, `.invalid-feedback`
- Password strength meter (opsional, client-side)

### Step 5: Password toggle
- Eye icon SVG (inline) — toggle visibility password field
- Click → switch input type password/text

---

## Structure Folder (Temporary)

```
lahap/
├── docs/
│   └── issue.md          ← FILE INI
├── public/
│   ├── assets/
│   │   └── css/
│   │       ├── login-style.css   ← custom tokens & class
│   │   └── (bootstrap via CDN)
│   ├── login.html         ← prototype (nanti login.php)
│   └── register.html      ← prototype (nanti register.php)
```

> **Catatan:** Setelah backend siap, file `.html` akan diganti menjadi `.php` dengan form action menuju backend auth.

---

## Design Principles (dari `DESIGN.md`)

| Prinsip | Implementation |
|--------|-----------------|
| Clean & Utility First | Putih bersih, kontras ≥ 4.9:1, zero gradien |
| Single Accent (#dc2626) | Maks 2 aksen per viewport (CTA + active state) |
| Typography | Poppins font, `font-mono tabular` untuk angka |
| Radius | 10px (inputs/buttons), 14px (card) |
| Input Focus | `box-shadow: 0 0 0 3px var(--accent-soft)` |
| Validation Error | Border `--danger`, glow ring `--danger-soft` |

### Anti-AI Slop Rules
- ❌ No gradien (ungu, biru, pelangi)
- ❌ No emoji sebagai icon — pakai inline SVG stroke 2px
- ❌ Max 1 primary CTA per viewport
- ❌ No font Inter/Roboto — pakai Poppins konsisten
- ❌ No ilustrasi kartun / hand-drawn SVG
- ❌ No hover scale animasi berlebihan

---

## Batasan Agent (Agent Constraints)

- ✅ Hanya frontend (HTML/CSS/JS)
- ✅ Bootstrap 5 via CDN
- ✅ Ikuti `DESIGN.md` token & anti-slopp rules
- ❌ Tidak boleh menulis backend logic
- ❌ Tidak boleh akses DB / session
- ❌ No external icon libraries (fontawesome dll)
- ❌ No hardcoded hex values — pakai CSS custom properties

---

## Definition of Done

- [ ] `login.html` ada di `public/`
- [ ] `register.html` ada di `public/`
- [ ] `login-style.css` ada di `public/assets/css/`
- [ ] Kedua halaman render di Chrome & Firefox (desktop + mobile)
- [ ] Form validation visual: error border `--danger`, focus ring `--accent-soft`
- [ ] Password toggle visibility (eye icon SVG)
- [ ] Link navigasi antar halaman (Login ↔ Register)
- [ ] Menggunakan CSS token dari `DESIGN.md` (warna, font, radius)
- [ ] Tidak ada gradien, emoji, atau efek hover berlebihan
- [ ] HTML5 semantic (`<form>`, `<label>`, `<input>`, `<section>`)
- [ ] Commit & push ke GitHub
- [ ] Issue ini di-close setelah PR merge

---

## Referensi
- Design system: `docs/DESIGN.md`
- Folder structure: `docs/architecture.md`
- Agent constraints & DoD: `docs/AGENTS.md`
