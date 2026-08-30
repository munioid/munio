# Contributing to Munio

Terima kasih telah berkontribusi pada Munio! Panduan ini menjelaskan standar dan alur kerja untuk memastikan kontribusi Anda terintegrasi dengan lancar ke dalam proyek.

## Daftar Isi

- [Standar Branch Naming](#standar-branch-naming)
- [Tipe-Tipe Branch](#tipe-tipe-branch)
- [Alur Kerja Git](#alur-kerja-git)
- [Komitmen Pesan](#komitmen-pesan)
- [Pull Request Process](#pull-request-process)
- [Petunjuk Pengembangan](#petunjuk-pengembangan)

## Standar Branch Naming

Semua branch harus mengikuti konvensi penamaan yang terstruktur untuk memudahkan identifikasi dan pengelolaan. Format umum:

```
<type>/<issue-number>-<deskripsi-singkat>
```

Atau tanpa issue number:

```
<type>/<deskripsi-singkat>
```

**Aturan:**
- Gunakan huruf kecil (lowercase)
- Gunakan hyphen `-` untuk memisahkan kata (bukan underscore atau spasi)
- Gunakan deskripsi singkat, maksimal 50 karakter
- Hindari karakter spesial (kecuali hyphen dan slash)
- Jangan gunakan spasi di akhir branch name

**Contoh yang benar:**
```
feature/123-add-user-authentication
bugfix/456-fix-payment-validation
hotfix/789-critical-security-patch
refactor/101-improve-database-queries
docs/202-update-api-documentation
test/303-add-auth-tests
chore/404-update-dependencies
```

## Tipe-Tipe Branch

### 1. **feature/** - Fitur Baru
Digunakan untuk mengembangkan fitur baru atau menambahkan fungsionalitas.

```bash
git checkout -b feature/123-user-profile-page
```

**Deskripsi:** Menambahkan halaman profil pengguna
- Main branch: `1.x` atau `main`
- Dikembangkan dari: `1.x`
- PR ke: `1.x`

### 2. **bugfix/** - Perbaikan Bug
Digunakan untuk memperbaiki bug yang ditemukan di development branch.

```bash
git checkout -b bugfix/456-fix-login-redirect
```

**Deskripsi:** Memperbaiki redirect setelah login
- Main branch: `1.x`
- Dikembangkan dari: `1.x`
- PR ke: `1.x`

### 3. **hotfix/** - Perbaikan Darurat (Produksi)
Digunakan untuk patch emergency yang perlu langsung ke production. Ini adalah tipe branch paling kritikal.

```bash
git checkout -b hotfix/789-fix-critical-database-error
```

**Deskripsi:** Memperbaiki error database kritis di production
- Main branch: `main` (production)
- Dikembangkan dari: `main`
- PR ke: `main` (dan backport ke `1.x`)
- Prioritas: **TERTINGGI**

### 4. **refactor/** - Refactoring Kode
Digunakan untuk refactoring kode, peningkatan struktur, atau optimasi tanpa mengubah fungsionalitas.

```bash
git checkout -b refactor/101-improve-auth-service
```

**Deskripsi:** Meningkatkan struktur AuthService
- Main branch: `1.x`
- Dikembangkan dari: `1.x`
- PR ke: `1.x`

### 5. **docs/** - Dokumentasi
Digunakan untuk menambah atau memperbarui dokumentasi, README, API docs, atau panduan.

```bash
git checkout -b docs/202-add-deployment-guide
```

**Deskripsi:** Menambahkan panduan deployment
- Main branch: `1.x`
- Dikembangkan dari: `1.x`
- PR ke: `1.x`

### 6. **test/** - Pengujian
Digunakan untuk menambah test case, meningkatkan coverage, atau setup testing infrastructure.

```bash
git checkout -b test/303-add-unit-tests-payment
```

**Deskripsi:** Menambah unit tests untuk payment module
- Main branch: `1.x`
- Dikembangkan dari: `1.x`
- PR ke: `1.x`

### 7. **chore/** - Maintenance Rutin
Digunakan untuk update dependencies, build configuration, linting setup, atau maintenance umum.

```bash
git checkout -b chore/404-update-laravel-dependencies
```

**Deskripsi:** Update Laravel dan dependencies ke versi terbaru
- Main branch: `1.x`
- Dikembangkan dari: `1.x`
- PR ke: `1.x`

## Alur Kerja Git

### Workflow Umum untuk Feature/Bugfix

```bash
# 1. Update main branch
git checkout 1.x
git pull origin 1.x

# 2. Buat branch baru dari 1.x
git checkout -b feature/123-your-feature-name

# 3. Lakukan pengembangan
# ... edit files, commit changes ...

# 4. Push ke remote
git push origin feature/123-your-feature-name

# 5. Buat Pull Request di GitHub
# Gunakan PR template dan jelaskan perubahan Anda
```

### Workflow untuk Hotfix (Critical Production Patch)

```bash
# 1. Update production branch
git checkout main
git pull origin main

# 2. Buat branch hotfix dari main
git checkout -b hotfix/789-critical-fix

# 3. Lakukan perbaikan
# ... edit files, commit changes ...

# 4. Push ke remote
git push origin hotfix/789-critical-fix

# 5. Buat Pull Request ke main
# - Jelaskan urgency dan impact

# 6. Setelah merge ke main, backport ke 1.x
git checkout 1.x
git pull origin 1.x
git cherry-pick <commit-hash-dari-hotfix>
git push origin 1.x
```

### Menjaga Branch Tetap Update

```bash
# Jika branch sudah outdated
git fetch origin
git rebase origin/1.x

# Atau menggunakan merge (alternatif)
git merge origin/1.x
```

## Komitmen Pesan

Gunakan format commit message yang konsisten dan deskriptif:

```
<type>: <description>

[optional body]

[optional footer]
```

**Tipe Commit:**
- `feat:` - Fitur baru
- `fix:` - Perbaikan bug
- `docs:` - Dokumentasi
- `style:` - Formatting, whitespace
- `refactor:` - Refactoring kode
- `perf:` - Peningkatan performa
- `test:` - Menambah tests
- `chore:` - Update dependencies, build config

**Contoh:**

```
feat: add user authentication system

- Implement JWT-based authentication
- Add login and register endpoints
- Create auth middleware

Fixes #123
```

```
fix: resolve payment validation issue

The payment validator was not checking for valid card numbers
according to the Luhn algorithm. This commit fixes the validation
logic to properly validate card numbers.

Fixes #456
```

## Pull Request Process

### Sebelum Membuat PR

1. **Update branch Anda:**
   ```bash
   git fetch origin
   git rebase origin/1.x
   ```

2. **Run tests lokal:**
   ```bash
   composer install
   npm install
   php artisan test
   npm run build
   ```

3. **Lakukan self-review:**
   - Apakah kode sudah clean dan readable?
   - Apakah ada commented code yang perlu dihapus?
   - Apakah perubahan sesuai scope?

### Membuat PR

1. **Push branch ke remote:**
   ```bash
   git push origin feature/your-branch-name
   ```

2. **Buat Pull Request di GitHub**
   - Gunakan template PR yang telah disediakan
   - Tulis deskripsi yang jelas dan ringkas
   - Link issue yang relevan dengan `Fixes #123`
   - Cantumkan langkah testing jika diperlukan

3. **PR Title Format:**
   ```
   <type>: <description> (#issue-number)
   ```
   
   Contoh:
   ```
   feat: add user profile page (#123)
   fix: resolve payment validation bug (#456)
   ```

### Review dan Merge

1. **Tunggu review dari team members**
2. **Respons feedback dengan cepat**
3. **Update branch jika ada konflik:**
   ```bash
   git fetch origin
   git rebase origin/1.x
   git push origin feature/your-branch-name --force-with-lease
   ```
4. **Merge approval = ready untuk merge**
5. **Gunakan "Squash and merge" untuk feature branches**
   - Kecuali untuk hotfix (gunakan regular merge untuk history tracking)

## Petunjuk Pengembangan

### Environment Setup

1. **Clone repository:**
   ```bash
   git clone https://github.com/munioid/munio.git
   cd munio
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Setup environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run migrations:**
   ```bash
   php artisan migrate
   ```

5. **Start development server:**
   ```bash
   php artisan serve
   npm run dev
   ```

### Testing

```bash
# Run semua tests
php artisan test

# Run specific test file
php artisan test tests/Feature/UserTest.php

# Run dengan coverage
php artisan test --coverage

# Run JavaScript tests (jika ada)
npm run test
```

### Code Standards

- **PHP:** Ikuti PSR-12 standard (dikonfigurasi melalui `.editorconfig`)
- **JavaScript:** Ikuti ES6+ standard
- **Formatting:** Use provided EditorConfig
- **Linting:** Jalankan linter sebelum commit

## Branch Protection Rules

- **1.x branch:** Memerlukan minimal 1 approval sebelum merge
- **main branch:** Memerlukan minimal 2 approvals sebelum merge
- **Status checks:** Semua CI tests harus pass
- **Up to date:** Branch harus updated sebelum merge

## Need Help?

- Buat issue di GitHub untuk pertanyaan atau saran
- Hubungi tech lead untuk guidance teknis
- Review dokumentasi di README.md

---

**Terima kasih telah berkontribusi pada Munio!** 🚀
