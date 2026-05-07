# 📦 Panduan Deploy Laravel API ke Vercel + Supabase

Panduan lengkap untuk deploy **Aplikasi Keuangan API** (Laravel) ke **Vercel** dengan database **Supabase PostgreSQL**.

---

## 📋 Daftar Isi

1. [Prasyarat](#1-prasyarat)
2. [Setup Supabase (Database)](#2-setup-supabase-database)
3. [Konfigurasi Project Laravel](#3-konfigurasi-project-laravel)
4. [Setup Vercel](#4-setup-vercel)
5. [Deploy ke Vercel](#5-deploy-ke-vercel)
6. [Menjalankan Migration](#6-menjalankan-migration)
7. [Konfigurasi Environment di Vercel](#7-konfigurasi-environment-di-vercel)
8. [Testing API](#8-testing-api)
9. [Update Flutter App](#9-update-flutter-app)
10. [Troubleshooting](#10-troubleshooting)

---

## 1. Prasyarat

Pastikan sudah memiliki:

| Tool | Link |
|------|------|
| ✅ Akun GitHub | https://github.com |
| ✅ Akun Vercel | https://vercel.com |
| ✅ Akun Supabase | https://supabase.com |
| ✅ Node.js & npm | https://nodejs.org |
| ✅ Vercel CLI | `npm i -g vercel` |
| ✅ PHP 8.3+ | https://php.net |
| ✅ Composer | https://getcomposer.org |

---

## 2. Setup Supabase (Database)

### 2.1 Buat Project Baru

1. Login ke [Supabase Dashboard](https://supabase.com/dashboard)
2. Klik **"New Project"**
3. Isi informasi project:
   - **Name**: `aplikasi-keuangan` (atau nama bebas)
   - **Database Password**: Buat password yang kuat (**CATAT PASSWORD INI!**)
   - **Region**: Pilih yang terdekat (contoh: `Southeast Asia (Singapore)`)
4. Klik **"Create new project"**
5. Tunggu beberapa menit sampai project selesai dibuat

### 2.2 Ambil Database Credentials

1. Setelah project aktif, buka **Project Settings** (ikon gear ⚙️ di sidebar kiri)
2. Klik **"Database"** di menu sebelah kiri
3. Scroll ke bagian **"Connection parameters"**
4. Catat informasi berikut:

```
Host     : db.xxxxxxxxxxxx.supabase.co
Database : postgres
Port     : 5432
User     : postgres
Password : (password yang kamu buat tadi)
```

> ⚠️ **PENTING**: Jangan share password database ke siapapun dan jangan commit ke Git!

---

## 3. Konfigurasi Project Laravel

### 3.1 File yang Sudah Dikonfigurasi

Berikut file-file yang sudah dikonfigurasi untuk deployment:

| File | Keterangan |
|------|-----------|
| `vercel.json` | Konfigurasi Vercel (routing, runtime PHP) |
| `api/index.php` | Entry point serverless function |
| `config/database.php` | SSL mode diubah ke `require` untuk Supabase |
| `config/cors.php` | CORS diizinkan untuk semua origin (Flutter) |
| `bootstrap/app.php` | Middleware CORS ditambahkan |
| `routes/web.php` | Health-check endpoint (JSON response) |
| `.env.example` | Template environment untuk Supabase + Vercel |
| `.gitignore` | Ditambahkan `.vercel` |

### 3.2 Update File `.env` Lokal (Opsional - untuk test lokal dengan Supabase)

Jika ingin test koneksi Supabase dari lokal, update `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=db.xxxxxxxxxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=password-supabase-kamu
```

---

## 4. Setup Vercel

### 4.1 Install Vercel CLI

```bash
npm i -g vercel
```

### 4.2 Login ke Vercel

```bash
vercel login
```

Pilih metode login (GitHub recommended), lalu ikuti instruksi di browser.

---

## 5. Deploy ke Vercel

### 5.1 Push Kode ke GitHub

Pastikan semua perubahan sudah di-commit dan push:

```bash
cd api_app
git add .
git commit -m "Configure for Vercel + Supabase deployment"
git push origin main
```

### 5.2 Deploy via Vercel CLI

```bash
cd api_app
vercel
```

Jawab pertanyaan Vercel CLI:

```
? Set up and deploy? → Y
? Which scope? → (pilih akun kamu)
? Link to existing project? → N
? What's your project's name? → aplikasi-keuangan-api
? In which directory is your code located? → ./
? Want to override the settings? → N
```

### 5.3 Atau Deploy via Vercel Dashboard (Alternatif)

1. Buka [Vercel Dashboard](https://vercel.com/dashboard)
2. Klik **"Add New..." → "Project"**
3. Import repository **`Aldikurniawan19/aplikasikeungan`**
4. Klik **"Deploy"**

---

## 6. Menjalankan Migration

Karena Vercel adalah serverless, migration harus dijalankan dari **lokal** ke Supabase.

### 6.1 Update `.env` Lokal

Edit file `.env` di project lokal:

```env
DB_CONNECTION=pgsql
DB_HOST=db.xxxxxxxxxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=password-supabase-kamu
```

### 6.2 Jalankan Migration

```bash
php artisan migrate
```

Output yang diharapkan:

```
Migrating: 0001_01_01_000000_create_users_table
Migrated:  0001_01_01_000000_create_users_table
Migrating: 0001_01_01_000001_create_cache_table
Migrated:  0001_01_01_000001_create_cache_table
Migrating: 0001_01_01_000002_create_jobs_table
Migrated:  0001_01_01_000002_create_jobs_table
Migrating: 2026_04_30_003457_create_personal_access_tokens_table
Migrated:  2026_04_30_003457_create_personal_access_tokens_table
Migrating: 2026_04_30_023349_add_npm_to_users_table
Migrated:  2026_04_30_023349_add_npm_to_users_table
Migrating: 2026_05_07_000000_create_transactions_table
Migrated:  2026_05_07_000000_create_transactions_table
```

> 💡 **Tips**: Kamu juga bisa melihat tabel yang dibuat di **Supabase Dashboard > Table Editor**

---

## 7. Konfigurasi Environment di Vercel

### 7.1 Set Environment Variables

Buka **Vercel Dashboard → Project → Settings → Environment Variables**, lalu tambahkan:

| Key | Value | Keterangan |
|-----|-------|-----------|
| `APP_NAME` | `Aplikasi Keuangan API` | Nama aplikasi |
| `APP_ENV` | `production` | Environment |
| `APP_KEY` | `base64:xxxxx...` | Salin dari `.env` lokal |
| `APP_DEBUG` | `false` | Matikan debug di production |
| `APP_URL` | `https://nama-project.vercel.app` | URL Vercel kamu |
| `DB_CONNECTION` | `pgsql` | Tipe database |
| `DB_HOST` | `db.xxxx.supabase.co` | Host Supabase |
| `DB_PORT` | `5432` | Port PostgreSQL |
| `DB_DATABASE` | `postgres` | Nama database |
| `DB_USERNAME` | `postgres` | Username |
| `DB_PASSWORD` | `password-kamu` | Password Supabase |
| `SESSION_DRIVER` | `cookie` | Session via cookie |
| `CACHE_STORE` | `array` | Cache in-memory |
| `LOG_CHANNEL` | `stderr` | Log ke stderr |

> 🔑 **APP_KEY**: Salin value `APP_KEY` dari file `.env` lokal kamu:
> ```
> APP_KEY=base64:r3EUM21qeHGBy8NsLy7vGeXHELtw8PHr4qEkBzAREUA=
> ```

### 7.2 Redeploy

Setelah menambahkan environment variables, redeploy:

```bash
vercel --prod
```

Atau klik **"Redeploy"** di Vercel Dashboard.

---

## 8. Testing API

### 8.1 Test Health Check

```bash
curl https://nama-project.vercel.app/
```

Expected response:
```json
{
    "message": "Aplikasi Keuangan API is running",
    "status": "ok",
    "version": "1.0.0"
}
```

### 8.2 Test Register

```bash
curl -X POST https://nama-project.vercel.app/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "npm": "12345678",
    "email": "test@example.com",
    "password": "password123"
  }'
```

### 8.3 Test Login

```bash
curl -X POST https://nama-project.vercel.app/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

### 8.4 Test Authenticated Endpoint

```bash
curl https://nama-project.vercel.app/api/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

### 8.5 Test di Postman

1. Buka **Postman**
2. Set Base URL ke: `https://nama-project.vercel.app`
3. Test semua endpoint:
   - `POST /api/register`
   - `POST /api/login`
   - `GET /api/me` (dengan Bearer Token)
   - `DELETE /api/logout` (dengan Bearer Token)
   - `GET /api/transactions` (dengan Bearer Token)
   - `POST /api/transactions` (dengan Bearer Token)
   - `GET /api/transactions/summary` (dengan Bearer Token)

---

## 9. Update Flutter App

Setelah deploy berhasil, update base URL di Flutter app:

```dart
// Ganti dari localhost ke URL Vercel
// SEBELUM:
static const String baseUrl = 'http://10.0.2.2:8000/api';

// SESUDAH:
static const String baseUrl = 'https://nama-project.vercel.app/api';
```

---

## 10. Troubleshooting

### ❌ Error 500 Internal Server Error

**Kemungkinan penyebab:**
- `APP_KEY` belum diset di Vercel Environment Variables
- Database credentials salah

**Solusi:**
1. Pastikan `APP_KEY` sudah diset di Vercel
2. Cek database credentials di Supabase Dashboard
3. Cek Vercel Function Logs: **Vercel Dashboard → Project → Deployments → Functions**

### ❌ Error "SQLSTATE[08006] could not connect"

**Kemungkinan penyebab:**
- Database credentials salah
- SSL mode tidak di-set

**Solusi:**
1. Pastikan `DB_HOST` benar (format: `db.xxxx.supabase.co`)
2. Pastikan `DB_PASSWORD` benar
3. Cek Supabase project masih aktif

### ❌ Error CORS

**Kemungkinan penyebab:**
- CORS middleware belum aktif

**Solusi:**
1. Pastikan file `config/cors.php` ada
2. Pastikan `HandleCors` middleware ada di `bootstrap/app.php`

### ❌ Migration Error "relation already exists"

**Solusi:**
```bash
php artisan migrate:fresh
```

> ⚠️ **WARNING**: `migrate:fresh` akan menghapus semua data! Gunakan hanya saat setup awal.

### ❌ Vercel Build Timeout

**Solusi:**
Pastikan `composer.json` tidak memiliki script yang butuh waktu lama saat install.

---

## 📁 Struktur File Deployment

```
api_app/
├── api/
│   └── index.php          ← Serverless entry point (BARU)
├── app/
│   ├── Http/Controllers/
│   └── Models/
├── bootstrap/
│   └── app.php            ← CORS middleware ditambahkan
├── config/
│   ├── cors.php           ← CORS config (BARU)
│   └── database.php       ← SSL mode = require
├── routes/
│   ├── api.php
│   └── web.php            ← Health-check JSON
├── .env.example           ← Template Supabase + Vercel
├── .gitignore             ← Ditambahkan .vercel
└── vercel.json            ← Vercel config (BARU)
```

---

## ✅ Checklist Deployment

- [ ] Buat project Supabase & catat credentials
- [ ] Update `.env` lokal dengan credentials Supabase
- [ ] Jalankan `php artisan migrate` dari lokal
- [ ] Verifikasi tabel di Supabase Dashboard
- [ ] Install Vercel CLI: `npm i -g vercel`
- [ ] Login Vercel: `vercel login`
- [ ] Commit & push ke GitHub
- [ ] Deploy: `vercel` kemudian `vercel --prod`
- [ ] Set environment variables di Vercel Dashboard
- [ ] Redeploy setelah set env vars
- [ ] Test health-check endpoint
- [ ] Test register & login
- [ ] Update Flutter app base URL
