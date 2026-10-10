# Agents Guidelines — Sistem Pemesanan Makanan "Lahap"

## 1. Agent Constraints & Boundaries

### 1.1 Allowed Actions
- ✅ Edit/create PHP files within the defined project structure
- ✅ Run shell commands (mysql, php built-in server, composer if needed)
- ✅ Create documentation in `docs/`
- ✅ Modify `config/database.php` (DB credentials only)
- ✅ Commit and push to Git (if remote configured)
- ✅ Create new folders/files within `lahap/`
- ✅ Create/modify test files in `tests/`

### 1.2 Prohibited Actions
- ❌ Hard-code database credentials (use config file)
- ❌ Store plain-text passwords (always use `password_hash()`)
- ❌ Upload files outside `assets/uploads/` — no arbitrary file write
- ❌ Modify `docs/` files unless explicitly requested
- ❌ Add external dependencies without approval (keep native PHP only)
- ❌ Skip input validation or prepared statements (SQL injection risk)
- ❌ Expose debug errors in production (use try-catch, log internally)

### 1.3 Code Standards
| Category | Rule |
|----------|------|
| **Naming** | snake_case for vars/funcs, PascalCase for classes |
| **PHP** | PHP 8.x, strict types recommended |
| **SQL** | Prepared statements ONLY, snake_case tables/columns |
| **HTML/CSS** | Bootstrap 5 classes, semantic HTML |
| **JS** | Vanilla JS only, no frameworks |
| **Security** | XSS protection: always `htmlspecialchars()` via `e()` helper |

### 1.4 File Structure Rules
- Place auth logic in `includes/session.php`
- All admin files MUST start with `require_role('admin')`
- All customer pages that need login MUST use `require_login()`
- Use `flash('message')` for user notifications
- Images uploaded to `assets/uploads/` only

---

## 2. Definition of Done (DoD)

A feature/task is complete when **ALL** criteria below are met:

### 2.1 Code Quality
- [ ] **No PHP errors** — `php -l` lint clean, `error_reporting(E_ALL)` produces zero warnings/notices/errors
- [ ] All code follows style guide (snake_case, prepared SQL, e())
- [ ] No hardcoded values (paths, secrets, statuses)
- [ ] Password hashed with `password_hash()` + verified with `password_verify()`
- [ ] SQL uses prepared statements (no string concatenation)

### 2.2 Implementation
- [ ] Feature implemented per PRD & ERD
- [ ] Input validation on all user-facing form submissions

### 2.3 Testing — Unit Tests
- [ ] **Unit test passed** seluruh test suite (lihat `/tests/`)
- [ ] Unit test minimal untuk helper functions (`e()`, `generate_order_code()`, `flash()`)
- [ ] Unit test untuk auth logic (`is_logged_in()`, `require_login()`, `require_role()`)
- [ ] Unit test untuk order code generation & status transitions
- [ ] Integration test: full checkout flow (add to cart → submit → order created)
- [ ] `php tests/run.php` output: semua test **PASS**

### 2.4 Testing — Manual
- [ ] Feature works on latest Chrome & Firefox (desktop + mobile)
- [ ] All redirects work correctly (after login, after submit, etc.)
- [ ] Order status transitions work as defined in PRD
- [ ] Admin-only pages blocked for non-admin users
- [ ] Guest tracking works (valid code shows order, invalid code shows error)

### 2.3 Documentation
- [ ] PRD.md reflects any scope changes
- [ ] Code comments where logic is non-obvious
- [ ] README.md updated (if created)

### 2.4 Deployment Readiness
- [ ] Database migrations run successfully (via `docs/database.sql`)
- [ ] No debug `echo`/`var_dump` left
- [ ] All form submissions protected against empty/missing required fields
- [ ] Files upload safely (size limit, extension check)
- [ ] Print/struk page renders correctly

---

## 3. Testing Infrastructure

### 3.1 Test Runner
- All tests run via: `php tests/run.php`
- Output format: semua test individual, laporan PASS/FAIL, exit code non-zero jika ada failure
- Tests otomatis load config/database.php via PDO connection (MySQL test database)

### 3.2 Test Categories
- **Unit tests** — helper functions, pure logic
- **Integration tests** — DB interaction, full checkout flow
- **Auth tests** — login, session, role-based access control

### 3.3 Test DoD Enforcement
- `php tests/run.php` harus melewati **100%** semua test
- Tidak boleh ada test yang dilewati (*skipped*) tanpa approval eksplisit
- `php -l` lint harus bersih untuk semua file yang dimodifikasi
