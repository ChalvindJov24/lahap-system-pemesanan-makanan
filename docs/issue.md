# Issue: Implement Navbar & Footer (Frontend Only)

## Konteks & Tujuan

Membangun **navigation bar** (navbar) dan **footer** sebagai layout shell yang konsisten di seluruh halaman aplikasi "Lahap". Komponen ini frontend only — tidak ada backend logic, database, atau session handling.

Navbar & footer mengikuti design token dari `DESIGN.md` dan harus konsisten dengan halaman login/register yang sudah ada.

---

## Scope

### Dikerjakan (Frontend)
- ✅ Navbar responsive (mobile + desktop)
- ✅ Footer minimal dengan link & copyright
- ✅ CSS token konsisten (warna, font, radius)
- ✅ Link navigasi ke login/register/menu
- ✅ Collapse hamburger menu di mobile

### TIDAK dikerjakan
- ❌ Backend session logic (lihat issue terpisah)
- ❌ Dynamic menu berdasarkan role
- ❌ API endpoints

---

## Step by Step

### Step 1: Definisikan CSS token navbar/footer
- **File:** `public/assets/css/navbar.css`
- Token: `--accent: #dc2626`, `--bg: #ffffff`, `--fg: #111827`, `--border: #e5e7eb`
- Class: `.navbar-custom`, `.footer-custom`

### Step 2: Buat navbar component
- **File:** `public/components/navbar.html` (prototype)
- Mobile: hamburger button (SVG), hidden menu off-canvas
- Desktop: full nav links (Home/Login/Register)
- Logo: "Lahap" text di kiri

### Step 3: Buat footer component
- **File:** `public/components/footer.html` (prototype)
- Content: copyright, info kontak, link helpful
- Full-width sticky di bottom

### Step 4: Integrasikan ke login.html & register.html
- Replace inline nav/footer dengan component include (via JS atau manual insert)

---

## Structure Folder

```
lahap/
├── docs/
│   └── issue.md              ← FILE INI (planning dokumen)
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   ├── login-style.css  (existing)
│   │   │   └── navbar.css       (NEW)
│   │   └── vendor/
│   │       └── bootstrap/       (existing)
│   └── components/
│       ├── navbar.html          (NEW — prototype)
│       └── footer.html          (NEW — prototype)
```

> **Catatan:** Ini structure sementara untuk development. Structure final project ada di `docs/architecture.md`.

---

## Design Principles (dari `DESIGN.md`)

| Prinsip | Implementation |
|---------|----------------|
| Clean & Utility First | White bg (#ffffff), zero gradien, zero blur |
| Single Accent (#dc2626) | Link hover, hamburger icon (maks 2 aksen/viewport) |
| Typography | Poppins font, `font-mono` untuk angka (di footer Copyright) |
| Radius | 10px (tombol/mobile menu), 14px (desktop) |
| Anti-Slopp | Inline SVG icons, no emoji, no hover scale |

### Warna navbar/footer

| Elemen | Token | Hex |
|--------|-------|-----|
| Background | `--bg` / `--surface` | #ffffff |
| Text | `--fg` | #111827 |
| Border | `--border` | #e5e7eb |
| Aksen hover | `--accent` | #dc2626 |
| Text sekunder | `--muted` | #6b7280 |

---

## Responsif (dari DESIGN.md Breakpoints)

| Viewport | Layout |
|----------|--------|
| Desktop (>960px) | Navbar horizontal full, inline link |
| Tablet (641–960px) | Navbar kompak, item terpotong |
| Mobile (≤640px) | Hamburger menu, off-canvas slide-in |

---

## Batasan Agent (dari `AGENTS.md`)

- ✅ Hanya frontend (HTML/CSS/JS)
- ✅ Ikuti token DESIGN.md (warna, font, radius)
- ✅ Bootstrap sudah terinstall lokal (tidak perlu lagi)
- ❌ No backend logic, DB ops, session handling
- ❌ No external CDN
- ❌ No font eksternal (Poppins via lokal fallback ke system-ui)
- ❌ No emoji sebagai icons — pakai inline SVG

---

## Definition of Done (dari `AGENTS.md`)

- [ ] `navbar.html` ada di `public/components/`
- [ ] `footer.html` ada di `public/components/`
- [ ] `navbar.css` ada di `public/assets/css/`
- [ ] Navbar render di login.html & register.html (bisa sebagai include)
- [ ] Mobile hamburger → responsive toggle (JS vanilla)
- [ ] Footer sticky bottom, full-width
- [ ] Semua link nav bekerja (ke page yang ada)
- [ ] CSS token konsisten dengan DESIGN.md
- [ ] No CDN, no gradien, no emoji
- [ ] HTML5 semantic (`<nav>`, `<header>`, `<footer>`)
- [ ] Renders di Chrome & Firefox (desktop + mobile)
- [ ] `php -l` lint bersih (jika ada PHP wrapper)
- [ ] Commit & push ke GitHub
- [ ] Issue ini di-close setelah PR merge

---

## Referensi
- Design system: `docs/DESIGN.md`
- Agent constraints & DoD: `docs/AGENTS.md`
- Folder structure: `docs/architecture.md`
