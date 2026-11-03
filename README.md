# 🏢 Portal Magang BPJS Ketenagakerjaan

Aplikasi web manajemen program magang BPJS Ketenagakerjaan berbasis **Laravel 12**. Sistem ini mengelola absensi harian, pencatatan kegiatan (to-do), pengumpulan tugas, dan peringkat BPU (Bukan Penerima Upah) peserta magang secara terpusat dan real-time.

---

## 📑 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Tech Stack](#-tech-stack)
- [Prasyarat Sistem](#-prasyarat-sistem)
- [Instalasi & Setup](#-instalasi--setup)
- [Konfigurasi Environment](#-konfigurasi-environment)
- [Integrasi Google Sheets](#-integrasi-google-sheets-leaderboard-bpu)
- [Skema Database](#-skema-database)
- [Role & Sistem Akses](#-role--sistem-akses)
- [Alur Kerja Fitur](#-alur-kerja-fitur)
- [Routing Aplikasi](#-routing-aplikasi)
- [Struktur Proyek](#-struktur-proyek)
- [Keamanan](#-keamanan)
- [Perintah Artisan Berguna](#-perintah-artisan-berguna)
- [Testing](#-testing)
- [Development Mode](#-development-mode)

---

## ✨ Fitur Utama

### 👤 Intern (Peserta Magang)

| Fitur | Keterangan |
|---|---|
| **Dashboard** | Ringkasan 5 absensi terakhir, 5 tugas terakhir, total BPU, leaderboard top 3, dan jumlah tugas yang belum dikumpulkan |
| **Absensi Digital** | Check-in & Check-out harian menggunakan foto selfie via kamera browser (base64), mendukung tipe `harian` dan `luar_harian` |
| **To-Do List Harian** | Pencatatan rangkuman kegiatan satu kali per hari; otomatis dikirim ke Google Sheets setelah disimpan |
| **Pengumpulan Tugas** | Submit tugas dengan link (URL) untuk tugas yang aktif dan belum melewati deadline; tidak bisa double submit |
| **Leaderboard BPU** | Peringkat BPU real-time dari Google Spreadsheet, di-cache 30 detik untuk efisiensi |
| **Profil** | Edit nama, email, dan password; simpan link Google Drive pribadi |

> **Catatan:** Intern **wajib** mengisi to-do list terlebih dahulu sebelum bisa melakukan check-out.

---

### 👨‍🏫 Mentor

| Fitur | Keterangan |
|---|---|
| **Dashboard Mentor** | Statistik anak didik: total intern, absensi hari ini, tugas terkumpul, total BPU, leaderboard top 3 |
| **Manajemen Tugas** | Buat tugas baru (status awal `pending`, menunggu persetujuan Super Admin), lihat tugas yang dibuat sendiri, pantau submisi anak didik |
| **Daftar Anak Didik** | Lihat semua intern yang dibimbing beserta jumlah absensi dan tugas masing-masing |

> **Catatan:** Tugas yang dibuat mentor tidak langsung aktif — harus disetujui Super Admin terlebih dahulu.

---

### 🛡️ Super Admin

| Fitur | Keterangan |
|---|---|
| **Dashboard Admin** | Statistik sistem: total user, intern, mentor, absensi hari ini, submisi tugas, BPU akumulasi, top 3 leaderboard, tugas aktif & selesai |
| **Manajemen Pengguna** | CRUD user (intern, mentor, super_admin); pencarian by nama/email/NIM; filter by role; paginasi 10 per halaman |
| **Manajemen Absensi** | Lihat, edit, dan hapus data absensi semua intern |
| **Manajemen To-Do** | Lihat, edit BPU harian, dan hapus to-do semua intern |
| **Manajemen Tugas** | Buat, edit, hapus tugas; **approve/reject** tugas dari mentor; hapus submisi individu; lihat semua submisi |
| **Manajemen Anak Didik** | Lihat daftar intern beserta mentornya, edit data, update poin BPU harian per item to-do |

---

## 🛠️ Tech Stack

| Layer | Teknologi | Versi |
|---|---|---|
| Backend Framework | Laravel | ^12.0 |
| Bahasa | PHP | ^8.2 |
| Auth Scaffold | Laravel Breeze | ^2.4 |
| Frontend CSS | Tailwind CSS | ^3.4.0 |
| Frontend JS | Alpine.js | ^3.4.2 |
| Build Tool | Vite + laravel-vite-plugin | ^7.0.7 |
| Database | SQLite (default) | — |
| Google API | google/apiclient | ^2.20 |
| Testing | PHPUnit | ^11.5 |
| HTTP Client | Axios | ^1.11.0 |

---

## 📋 Prasyarat Sistem

Pastikan sistem Anda memiliki:

- **PHP** >= 8.2 dengan ekstensi aktif:
  - `pdo_sqlite` (atau `pdo_mysql` untuk MySQL)
  - `gd` (untuk pemrosesan gambar)
  - `curl`
  - `openssl`
  - `mbstring`
  - `zip`
- **Composer** >= 2.x
- **Node.js** >= 18.x
- **npm** >= 9.x
- **Git**

Cek versi PHP dan ekstensi:
```bash
php -v
php -m | findstr /i "pdo gd curl openssl mbstring"
```

---

## 🚀 Instalasi & Setup

### Opsi A — Setup Otomatis (Rekomendasi)

```bash
git clone <url-repository> portal-magang
cd portal-magang

composer run setup
```

Perintah `composer run setup` akan menjalankan secara berurutan:
1. `composer install`
2. Menyalin `.env.example` → `.env` (jika `.env` belum ada)
3. `php artisan key:generate`
4. `php artisan migrate --force`
5. `npm install`
6. `npm run build`

---

### Opsi B — Setup Manual (Step by Step)

#### 1. Clone Repository
```bash
git clone <url-repository> portal-magang
cd portal-magang
```

#### 2. Install Dependency PHP
```bash
composer install
```

#### 3. Install Dependency Node.js
```bash
npm install
```

#### 4. Buat File Environment
```bash
cp .env.example .env
```

#### 5. Generate Application Key
```bash
php artisan key:generate
```

#### 6. Jalankan Migrasi Database
```bash
php artisan migrate
```

#### 7. Buat Symlink Storage (wajib untuk foto absensi)
```bash
php artisan storage:link
```

#### 8. Build Assets Frontend
```bash
# Untuk production
npm run build

# Untuk development (mode watch)
npm run dev
```

#### 9. (Opsional) Seed Akun Mentor Awal
```bash
php artisan db:seed --class=MentorSeeder
```

Ini akan membuat 8 akun mentor default (lihat daftar di bagian [Seeder](#database-seeder)).

#### 10. Jalankan Server
```bash
php artisan serve
```

Akses aplikasi di **http://localhost:8000**

---

## ⚙️ Konfigurasi Environment

Edit file `.env` sesuai kebutuhan deployment:

```env
# ===== APLIKASI =====
APP_NAME="Portal Magang BPJSTK"
APP_ENV=local          # Ganti ke 'production' saat deploy
APP_KEY=               # Diisi otomatis oleh key:generate
APP_DEBUG=true         # Set ke false saat production
APP_URL=http://localhost

# ===== DATABASE =====
# Default: SQLite (cocok untuk development)
DB_CONNECTION=sqlite

# Untuk MySQL/PostgreSQL (uncomment dan isi):
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=portal_magang
# DB_USERNAME=root
# DB_PASSWORD=

# ===== SESSION & CACHE =====
SESSION_DRIVER=database
SESSION_LIFETIME=120   # Durasi sesi dalam menit
CACHE_STORE=database

# ===== GOOGLE SHEETS =====
GOOGLE_SPREADSHEET_ID=1Jo2qkM90WfM6DoIdLeDAcVEANO89TW3Nkr9lvbK8uBw
# Ganti dengan ID spreadsheet Anda sendiri

# ===== QUEUE =====
QUEUE_CONNECTION=database
```

### Perbedaan Konfigurasi Database

**SQLite (default — untuk development):**
```env
DB_CONNECTION=sqlite
# File database otomatis di: database/database.sqlite
```

**MySQL (untuk production):**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_magang_bpjs
DB_USERNAME=root
DB_PASSWORD=rahasia_anda
```

Jika menggunakan MySQL, buat database terlebih dahulu:
```sql
CREATE DATABASE portal_magang_bpjs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

## 🔑 Integrasi Google Sheets (Leaderboard BPU)

Aplikasi menggunakan Google Sheets untuk dua hal:
1. **Membaca leaderboard BPU** — ditampilkan real-time ke semua user
2. **Menulis data to-do** — setiap intern mengisi kegiatan harian, datanya dikirim ke spreadsheet

### Setup Google Cloud

#### Langkah 1 — Buat Project Google Cloud
1. Buka [https://console.cloud.google.com/](https://console.cloud.google.com/)
2. Klik **"New Project"** → beri nama (contoh: `Portal Magang BPJS`)
3. Klik **"Create"**

#### Langkah 2 — Aktifkan Google Sheets API
1. Di sidebar kiri, pilih **"APIs & Services"** → **"Library"**
2. Cari **"Google Sheets API"**
3. Klik **"Enable"**

#### Langkah 3 — Buat Service Account
1. Pilih **"APIs & Services"** → **"Credentials"**
2. Klik **"+ Create Credentials"** → **"Service Account"**
3. Isi nama service account (contoh: `portal-magang-sheets`)
4. Klik **"Create and Continue"** → **"Done"**

#### Langkah 4 — Download Credentials
1. Klik service account yang baru dibuat
2. Tab **"Keys"** → **"Add Key"** → **"Create new key"**
3. Pilih format **JSON** → Klik **"Create"**
4. File JSON akan otomatis terdownload

#### Langkah 5 — Simpan Credentials ke Project
```bash
# Pindahkan file JSON yang didownload ke:
storage/app/google-credentials.json
```

> **PENTING:** File `google-credentials.json` berisi private key sensitif. Pastikan sudah ada di `.gitignore` dan **jangan pernah di-commit ke repository**.

#### Langkah 6 — Share Spreadsheet ke Service Account
1. Buka Google Spreadsheet Anda
2. Klik tombol **"Share"**
3. Masukkan email service account (format: `nama@project-id.iam.gserviceaccount.com`)
4. Beri akses **Editor** (untuk write) atau **Viewer** (untuk read-only)

#### Langkah 7 — Konfigurasi `.env`
```env
GOOGLE_SPREADSHEET_ID=id_spreadsheet_anda
```

ID spreadsheet ada di URL Google Sheets:
```
https://docs.google.com/spreadsheets/d/[INI_ADALAH_ID_NYA]/edit
```

---

### Format Sheet yang Diperlukan

#### Sheet `leaderboard`
Sheet ini dibaca untuk menampilkan peringkat BPU real-time:

| Kolom A | Kolom B | Kolom C | Kolom D | Kolom E |
|---|---|---|---|---|
| NIM | Nama | JKK+JKM | JKK+JKM+JHT | Grand Total BPU |
| 123456789 | Budi Santoso | 15 | 20 | 35 |
| 987654321 | Ani Rahayu | 12 | 18 | 30 |

- Baris pertama adalah header (akan dilewati otomatis)
- Kolom Grand Total harus berisi angka
- Baris yang mengandung kata "total" (case-insensitive) akan diabaikan

#### Sheet `Sheet1` (Default untuk To-Do)
Sheet ini diisi otomatis saat intern menyimpan to-do list:

| Kolom A | Kolom B | Kolom C | Kolom D |
|---|---|---|---|
| Nama | Link Google Drive | Rangkuman Kegiatan | Tanggal |

---

### Fallback Otomatis

Jika Google Sheets tidak dapat diakses (koneksi gagal, credentials tidak ada, timeout > 6 detik), aplikasi **otomatis fallback** ke data dari database lokal tanpa error ke user.

---

## 🗄️ Skema Database

### Tabel `users`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint, PK | Auto-increment |
| `name` | string | Nama lengkap (uppercase) |
| `email` | string, unique, nullable | Email (wajib untuk mentor & admin) |
| `nim` | string(9), unique, nullable | NIM 9 digit (wajib untuk intern) |
| `password` | string | Bcrypt hash |
| `role` | string | `intern` / `mentor` / `super_admin` |
| `mentor_id` | FK → users.id, nullable | Relasi ke mentor (untuk intern) |
| `gdrive_link` | string, nullable | Link Google Drive pribadi intern |
| `email_verified_at` | timestamp, nullable | Verifikasi email |
| `remember_token` | string, nullable | Token remember-me |
| `created_at` / `updated_at` | timestamp | Auto-managed Laravel |

---

### Tabel `attendances`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint, PK | Auto-increment |
| `user_id` | FK → users.id | Cascade delete |
| `date` | date | Tanggal absensi |
| `type` | enum | `harian` atau `luar_harian` |
| `check_in_time` | timestamp, nullable | Waktu check-in |
| `check_out_time` | timestamp, nullable | Waktu check-out |
| `check_in_photo` | string, nullable | Path foto check-in (di storage/public) |
| `check_out_photo` | string, nullable | Path foto check-out (di storage/public) |
| `check_in_location` | string, nullable | Lokasi check-in (reserved) |
| `check_out_location` | string, nullable | Lokasi check-out (reserved) |
| `created_at` / `updated_at` | timestamp | Auto-managed Laravel |

---

### Tabel `todos`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint, PK | Auto-increment |
| `user_id` | FK → users.id | Cascade delete |
| `date` | date | Tanggal kegiatan |
| `summary` | text | Rangkuman kegiatan harian |
| `bpu_achievement` | integer, default 0 | Jumlah BPU yang dicapai (hanya diisi Super Admin) |
| `created_at` / `updated_at` | timestamp | Auto-managed Laravel |

---

### Tabel `assigned_tasks`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint, PK | Auto-increment |
| `title` | string | Judul tugas |
| `description` | text, nullable | Deskripsi tugas |
| `deadline` | datetime, nullable | Tenggat waktu (null = tidak ada batas) |
| `is_active` | boolean, default true | Apakah tugas aktif/bisa disubmit |
| `status` | string | `pending` / `approved` / `rejected` |
| `created_by` | FK → users.id | Yang membuat tugas (mentor/superadmin) |
| `created_at` / `updated_at` | timestamp | Auto-managed Laravel |

---

### Tabel `task_submissions`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint, PK | Auto-increment |
| `user_id` | FK → users.id | Cascade delete |
| `assigned_task_id` | FK → assigned_tasks.id | Cascade delete |
| `link` | string | URL hasil pengerjaan tugas |
| `submitted_at` | timestamp | Waktu pengumpulan (default: now) |
| `created_at` / `updated_at` | timestamp | Auto-managed Laravel |

---

### Relasi Antar Tabel

```
users (intern)
  ├── belongsTo users (mentor) via mentor_id
  ├── hasMany attendances
  ├── hasMany todos
  └── hasMany task_submissions

users (mentor)
  └── hasMany users (intern) via mentor_id

assigned_tasks
  ├── belongsTo users (created_by)
  └── hasMany task_submissions

task_submissions
  ├── belongsTo users
  └── belongsTo assigned_tasks
```

---

### Database Seeder

#### MentorSeeder
Membuat 8 akun mentor awal. Jalankan dengan:
```bash
php artisan db:seed --class=MentorSeeder
```

Akun yang dibuat:

| Nama | Email |
|---|---|
| ARI ISMOYO | ari.ismoyo@mentor.com |
| EKA MEILIND | eka.meilind@mentor.com |
| FARIZA SEPTIAN NASUTION | fariza.septian.nasution@mentor.com |
| FITRIANI HASIBUAN | fitriani.hasibuan@mentor.com |
| GHAFFAR HADI | ghaffar.hadi@mentor.com |
| RISNIAR ARITONANG | risniar.aritonang@mentor.com |
| SAKINAH RAMZAH | sakinah.ramzah@mentor.com |
| SENDI YULIANA | sendi.yuliana@mentor.com |

> Password masing-masing akun tersedia di file `database/seeders/MentorSeeder.php`.

---

## 👥 Role & Sistem Akses

### Daftar Role

| Role | Nilai di DB | Keterangan |
|---|---|---|
| Peserta Magang | `intern` | Akun dibuat Super Admin, login dengan NIM |
| Mentor | `mentor` | Login dengan email |
| Super Admin | `super_admin` | Akses penuh, login dengan email |

### Aturan Akses

- Semua halaman kecuali login memerlukan autentikasi (`auth` middleware)
- Halaman mentor: `EnsureIsMentor` middleware — Super Admin **juga bisa** akses halaman mentor
- Halaman super admin: `EnsureIsSuperAdmin` middleware — hanya role `super_admin`
- Setelah login, user di-redirect otomatis ke dashboard sesuai rolenya

### Perbedaan Akun Intern vs Mentor/Admin

| Aspek | Intern | Mentor / Super Admin |
|---|---|---|
| Identifier login | NIM (9 digit) | Email |
| Email di DB | Tidak diperlukan (null) | Wajib ada |
| NIM di DB | Wajib (9 digit, unique) | Tidak ada (null) |

---

### Membuat Akun Super Admin

Karena Super Admin tidak bisa daftar sendiri, buat via Tinker:

```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name'     => 'NAMA SUPER ADMIN',          // Otomatis diubah ke uppercase
    'email'    => 'admin@bpjstk.com',
    'password' => bcrypt('password_kuat_anda'),
    'role'     => 'super_admin',
]);
```

---

## 🔄 Alur Kerja Fitur

### Alur Absensi Intern

```
1. Intern buka halaman /attendances
2. Klik "Check-In"
   → Pilih tipe: harian / luar_harian
   → Ambil foto selfie via kamera browser
   → Foto dikonversi ke base64 → simpan ke storage/public/attendances/
   → Record attendance dibuat dengan check_in_time = now()

3. Kerjakan kegiatan hari ini...

4. Sebelum Check-Out, WAJIB isi To-Do List dulu di /todos
   → Jika belum isi, check-out ditolak dengan pesan error

5. Klik "Check-Out"
   → Ambil foto selfie
   → Attendance diupdate dengan check_out_time = now()
```

---

### Alur To-Do List

```
1. Intern buka /todos
2. (Jika belum) Simpan link Google Drive pribadi dulu
3. Isi form rangkuman kegiatan hari ini
4. Klik "Simpan"
   → Validasi: hanya 1 to-do per hari per user
   → Data disimpan ke tabel todos (bpu_achievement = 0 untuk intern)
   → Otomatis dikirim ke Google Sheets (Sheet1): nama, gdrive_link, summary, tanggal
   → Jika Sheets gagal: data DB tetap aman, user dapat warning toast
5. Super Admin bisa mengisi/update poin BPU untuk tiap entri to-do
```

---

### Alur Pengumpulan Tugas

```
1. Mentor buat tugas baru di /mentor/tasks
   → Status: pending, is_active: false
   → Menunggu persetujuan Super Admin

2. Super Admin review di /superadmin/tasks
   → Approve → status: approved, is_active: true → tugas muncul untuk intern
   → Reject → status: rejected, is_active: false → tugas tidak aktif

3. Intern buka /tasks
   → Lihat daftar tugas aktif yang belum melewati deadline
   → Submit dengan mengisi link URL hasil kerja
   → Validasi: tidak bisa double submit untuk tugas yang sama
   → Tidak bisa submit tugas yang deadline-nya sudah lewat

4. Super Admin / Mentor bisa lihat semua submisi
   → Super Admin bisa hapus submisi individual
```

---

## 🗺️ Routing Aplikasi

### Route Umum (semua role terautentikasi)

| Method | URL | Nama Route | Keterangan |
|---|---|---|---|
| GET | `/` | — | Redirect ke `/login` |
| GET | `/dashboard` | `dashboard` | Dashboard (redirect sesuai role) |
| GET | `/leaderboard/bpu` | `leaderboard.bpu` | JSON leaderboard BPU |
| GET | `/profile` | `profile.edit` | Edit profil |
| PATCH | `/profile` | `profile.update` | Update profil |
| DELETE | `/profile` | `profile.destroy` | Hapus akun |

### Route Intern

| Method | URL | Nama Route | Keterangan |
|---|---|---|---|
| GET | `/attendances` | `attendances.index` | Daftar absensi |
| POST | `/attendances/checkin` | `attendances.checkin` | Check-in |
| POST | `/attendances/checkout` | `attendances.checkout` | Check-out |
| GET | `/todos` | `todos.index` | Daftar to-do |
| POST | `/todos` | `todos.store` | Simpan to-do |
| PUT | `/todos/{todo}/bpu` | `todos.updateBpu` | Update BPU (Super Admin only) |
| POST | `/todos/gdrive` | `todos.gdrive` | Simpan link Google Drive |
| GET | `/tasks` | `tasks.index` | Daftar tugas & submisi intern |
| POST | `/tasks` | `tasks.store` | Submit tugas |

### Route Mentor (prefix: `/mentor`)

| Method | URL | Nama Route | Keterangan |
|---|---|---|---|
| GET | `/mentor/dashboard` | `mentor.dashboard` | Dashboard mentor |
| GET | `/mentor/tasks` | `mentor.tasks.index` | Kelola tugas |
| POST | `/mentor/tasks` | `mentor.tasks.store` | Buat tugas baru (pending) |
| GET | `/mentor/students` | `mentor.students.index` | Daftar anak didik |

### Route Super Admin (prefix: `/superadmin`)

| Method | URL | Nama Route | Keterangan |
|---|---|---|---|
| GET | `/superadmin/dashboard` | `superadmin.dashboard` | Dashboard admin |
| GET | `/superadmin/users` | `superadmin.users.index` | Daftar user |
| GET | `/superadmin/users/create` | `superadmin.users.create` | Form buat user |
| POST | `/superadmin/users` | `superadmin.users.store` | Simpan user baru |
| GET | `/superadmin/users/{user}/edit` | `superadmin.users.edit` | Form edit user |
| PUT | `/superadmin/users/{user}` | `superadmin.users.update` | Update user |
| DELETE | `/superadmin/users/{user}` | `superadmin.users.destroy` | Hapus user |
| GET | `/superadmin/attendances` | `superadmin.attendances.index` | Daftar absensi semua |
| GET | `/superadmin/attendances/{attendance}/edit` | `superadmin.attendances.edit` | Edit absensi |
| PUT | `/superadmin/attendances/{attendance}` | `superadmin.attendances.update` | Update absensi |
| DELETE | `/superadmin/attendances/{attendance}` | `superadmin.attendances.destroy` | Hapus absensi |
| GET | `/superadmin/todos` | `superadmin.todos.index` | Daftar to-do semua |
| GET | `/superadmin/todos/{todo}/edit` | `superadmin.todos.edit` | Edit to-do |
| PUT | `/superadmin/todos/{todo}` | `superadmin.todos.update` | Update to-do |
| DELETE | `/superadmin/todos/{todo}` | `superadmin.todos.destroy` | Hapus to-do |
| GET | `/superadmin/tasks` | `superadmin.tasks.index` | Daftar tugas |
| GET | `/superadmin/tasks/create` | `superadmin.tasks.create` | Form buat tugas |
| POST | `/superadmin/tasks` | `superadmin.tasks.store` | Simpan tugas (langsung approved) |
| GET | `/superadmin/tasks/{task}/edit` | `superadmin.tasks.edit` | Edit tugas |
| PUT | `/superadmin/tasks/{task}` | `superadmin.tasks.update` | Update tugas |
| DELETE | `/superadmin/tasks/{task}` | `superadmin.tasks.destroy` | Hapus tugas |
| PATCH | `/superadmin/tasks/{task}/approve` | `superadmin.tasks.approve` | Setujui tugas mentor |
| PATCH | `/superadmin/tasks/{task}/reject` | `superadmin.tasks.reject` | Tolak tugas mentor |
| DELETE | `/superadmin/submissions/{submission}` | `superadmin.submissions.destroy` | Hapus submisi |
| GET | `/superadmin/students` | `superadmin.students.index` | Daftar anak didik |
| GET | `/superadmin/students/{student}/edit` | `superadmin.students.edit` | Edit anak didik |
| PATCH | `/superadmin/students/todos/{todo}/bpu` | `superadmin.students.updateBpu` | Update BPU intern |

---

## 📁 Struktur Proyek

```
BPJS 2/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                                # Autentikasi Breeze
│   │   │   │   ├── AuthenticatedSessionController.php   # Login & Logout
│   │   │   │   ├── RegisteredUserController.php          # Register
│   │   │   │   ├── PasswordController.php                # Update password
│   │   │   │   └── ... (forgot password, verify email)
│   │   │   │
│   │   │   ├── SuperAdmin/                          # Khusus Super Admin
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── UserController.php
│   │   │   │   ├── AttendanceController.php
│   │   │   │   ├── TodoController.php
│   │   │   │   ├── TaskController.php
│   │   │   │   └── StudentController.php
│   │   │   │
│   │   │   ├── AttendanceController.php             # Check-in/out intern
│   │   │   ├── MentorDashboardController.php        # Dashboard mentor
│   │   │   ├── MentorStudentController.php          # Daftar anak didik
│   │   │   ├── MentorTaskController.php             # Manajemen tugas mentor
│   │   │   ├── ProfileController.php                # Edit profil
│   │   │   ├── TaskSubmissionController.php         # Submit tugas intern
│   │   │   └── TodoController.php                   # To-do list intern
│   │   │
│   │   ├── Middleware/
│   │   │   ├── EnsureIsMentor.php       # Guard route mentor
│   │   │   ├── EnsureIsSuperAdmin.php   # Guard route super admin
│   │   │   └── SecurityHeaders.php      # HTTP security headers
│   │   │
│   │   └── Requests/
│   │       ├── Auth/LoginRequest.php
│   │       └── ProfileUpdateRequest.php
│   │
│   ├── Models/
│   │   ├── User.php             # Role helper, relasi mentor/intern
│   │   ├── Attendance.php       # Absensi harian
│   │   ├── Todo.php             # Kegiatan harian
│   │   ├── AssignedTask.php     # Tugas (pending/approved/rejected)
│   │   └── TaskSubmission.php   # Pengumpulan tugas
│   │
│   ├── Services/
│   │   └── GoogleSheetsService.php   # Baca & tulis Google Sheets
│   │
│   └── Providers/
│       └── AppServiceProvider.php
│
├── database/
│   ├── migrations/              # Skema tabel (urutan sesuai prefix tanggal)
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── MentorSeeder.php     # 8 akun mentor awal
│
├── resources/
│   └── views/
│       ├── dashboard.blade.php          # Dashboard intern
│       ├── attendances/                 # Absensi intern
│       ├── todos/                       # To-do list intern
│       ├── tasks/                       # Submisi tugas intern
│       ├── mentor/                      # Semua view mentor
│       ├── superadmin/                  # Semua view super admin
│       ├── auth/                        # Login, register, reset password
│       ├── profile/                     # Edit profil
│       ├── layouts/                     # Layout app & guest
│       ├── components/                  # Komponen Blade reusable
│       └── errors/                      # Halaman error (403, 404, 419, 500)
│
├── routes/
│   ├── web.php      # Seluruh routing utama aplikasi
│   └── auth.php     # Routing autentikasi (generate Breeze)
│
├── storage/
│   └── app/
│       ├── google-credentials.json   # ⚠️ HARUS DIISI MANUAL — jangan commit
│       └── public/
│           └── attendances/          # Foto absensi intern
│
├── public/
│   └── storage/                      # Symlink ke storage/app/public
│
├── .env                  # Konfigurasi environment (jangan commit)
├── .env.example          # Template konfigurasi
├── composer.json
├── package.json
├── tailwind.config.js
├── vite.config.js
└── artisan
```

---

## 🔒 Keamanan

### Middleware Keamanan

| Middleware | Fungsi |
|---|---|
| `auth` | Memastikan user sudah login |
| `verified` | Memastikan email sudah diverifikasi |
| `EnsureIsMentor` | Akses hanya untuk role `mentor` dan `super_admin` |
| `EnsureIsSuperAdmin` | Akses hanya untuk role `super_admin` |
| `SecurityHeaders` | Menambahkan HTTP security headers ke setiap response |

### HTTP Security Headers (SecurityHeaders Middleware)

| Header | Nilai | Fungsi |
|---|---|---|
| `X-Frame-Options` | `SAMEORIGIN` | Cegah clickjacking (iframe dari domain lain) |
| `X-Content-Type-Options` | `nosniff` | Cegah MIME-sniffing |
| `X-XSS-Protection` | `1; mode=block` | Aktifkan filter XSS bawaan browser |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | Batasi kebocoran URL via Referer |
| `Permissions-Policy` | `camera=(self), microphone=(), geolocation=()` | Izinkan kamera (untuk absensi), blokir lainnya |

### Praktik Keamanan Lainnya

- Password di-hash dengan **bcrypt** (tidak disimpan plain-text)
- Foto absensi disimpan di **private storage** (`storage/app/public/`) dan hanya bisa diakses via symlink public
- CSRF protection aktif di semua form (bawaan Laravel)
- Validasi input di semua request (server-side)
- Super Admin tidak bisa menghapus akunnya sendiri
- Intern tidak bisa mengisi poin BPU sendiri (selalu 0, hanya Super Admin yang bisa update)

### File yang Harus Ada di `.gitignore`

```
.env
storage/app/google-credentials.json
database/database.sqlite
```

---

## 🛠️ Perintah Artisan Berguna

```bash
# Setup awal
php artisan key:generate          # Generate app key
php artisan migrate               # Jalankan semua migrasi
php artisan migrate:fresh         # Reset & jalankan ulang semua migrasi
php artisan migrate:fresh --seed  # Reset + seed
php artisan storage:link          # Buat symlink public storage

# Seed data
php artisan db:seed --class=MentorSeeder   # Seed mentor

# Cache management
php artisan cache:clear           # Hapus cache aplikasi
php artisan config:clear          # Hapus cache konfigurasi
php artisan view:clear            # Hapus cache view
php artisan optimize:clear        # Hapus semua cache

# Untuk production
php artisan config:cache          # Cache konfigurasi
php artisan route:cache           # Cache routing
php artisan view:cache            # Cache view (Blade)
php artisan optimize              # Cache semua

# Tinker (REPL interaktif)
php artisan tinker

# List semua route
php artisan route:list
php artisan route:list --path=superadmin  # Filter by prefix
```

---

## 🧪 Testing

```bash
# Jalankan semua test
composer run test

# Atau langsung via artisan
php artisan test

# Jalankan test spesifik
php artisan test --filter=NamaTest

# Dengan tampilan verbose
php artisan test --verbose
```

Konfigurasi test ada di `phpunit.xml`. Secara default menggunakan database SQLite in-memory untuk isolasi.

---

## 💻 Development Mode

Untuk menjalankan semua layanan development **secara bersamaan** dalam satu terminal:

```bash
composer run dev
```

Perintah ini menjalankan 4 proses secara paralel (via `concurrently`):

| Proses | Warna | Fungsi |
|---|---|---|
| `php artisan serve` | Biru | Web server PHP bawaan |
| `php artisan queue:listen` | Ungu | Queue worker (untuk jobs/notifikasi) |
| `php artisan pail` | Merah muda | Log viewer real-time di terminal |
| `npm run dev` | Oranye | Vite HMR (Hot Module Replacement) |

Semua proses akan berhenti otomatis jika salah satunya diterminasi (`--kill-others`).

---

## 📝 Catatan Tambahan

- **Foto absensi** diambil via API `getUserMedia()` browser dan dikirim sebagai base64. Server mendekode dan menyimpan sebagai file gambar di `storage/app/public/attendances/`
- **Leaderboard BPU** di-cache selama **30 detik** menggunakan Laravel Cache untuk mengurangi request ke Google Sheets
- **To-do list** hanya bisa diisi **sekali per hari** per user
- **Tugas mentor** memerlukan **persetujuan Super Admin** sebelum bisa diakses intern
- Nama user selalu disimpan dalam format **HURUF KAPITAL** (`mb_strtoupper`)
- **NIM intern** harus tepat **9 digit angka** dan unik

---

## 📄 Lisensi

Proyek ini menggunakan lisensi **MIT**.
