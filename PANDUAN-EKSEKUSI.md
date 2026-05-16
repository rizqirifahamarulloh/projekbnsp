# Panduan Eksekusi — BookStore BNSP

## Prasyarat
- PHP >= 8.2
- Composer >= 2.x
- Node.js >= 18.x / npm >= 9.x
- MySQL 8.x
- Git (opsional)

---

## 1. Clone / Setup Proyek

```bash
# Jika dari Git
git clone <repo-url> BookStore-BNSP
cd BookStore-BNSP

# Atau jika sudah ada folder proyek
cd BookStore-BNSP
```

---

## 2. Instalasi Dependensi Backend (Laravel)

```bash
cd backend
composer install
```

---

## 3. Konfigurasi .env Backend

```bash
# Salin .env.example lalu sesuaikan
copy .env.example .env    # Windows
cp .env.example .env      # Linux/Mac

# Edit .env dan sesuaikan:
# DB_CONNECTION=mysql
# DB_DATABASE=bookstore_bnsp
# DB_USERNAME=root
# DB_PASSWORD=
#
# SANCTUM_STATEFUL_DOMAINS=localhost:5173
# SESSION_DOMAIN=localhost
#
# MIDTRANS_SERVER_KEY=SB-Mid-server-XXXXXX
# MIDTRANS_CLIENT_KEY=SB-Mid-client-XXXXXX

# Generate application key
php artisan key:generate
```

---

## 4. Buat Database & Jalankan Migrasi + Seeder

```bash
# Buat database MySQL
mysql -u root -e "CREATE DATABASE IF NOT EXISTS bookstore_bnsp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Jalankan migrasi dan seeder
php artisan migrate --seed

# Buat symlink storage untuk akses file upload
php artisan storage:link
```

**Akun Demo yang Dibuat:**
| Role  | Email                  | Password |
|-------|------------------------|----------|
| Admin | admin@bookstore.test   | password |
| User  | user@bookstore.test    | password |

---

## 5. Instalasi Dependensi Frontend (ReactJS)

```bash
cd ../frontend
npm install
```

---

## 6. Menjalankan Server

### Terminal 1 — Backend Laravel (port 8000)
```bash
cd backend
php artisan serve --port=8000
```

### Terminal 2 — Frontend React (port 5173)
```bash
cd frontend
npm run dev
```

---

## 7. Akses Aplikasi

| Komponen         | URL                          |
|------------------|------------------------------|
| Frontend (React) | http://localhost:5173         |
| Backend API      | http://localhost:8000/api     |
| Admin Panel      | http://localhost:8000/admin/login |

---

## 8. Queue Worker (Opsional)

Jika menggunakan notifikasi email atau job queue:
```bash
cd backend
php artisan queue:work
```

---

## 9. Testing dengan Postman

1. Buka Postman
2. Import file `BookStore.postman_collection.json`
3. Jalankan "Login Admin" terlebih dahulu → token akan otomatis tersimpan
4. Jalankan "Login User" → token user tersimpan
5. Test semua endpoint sesuai urutan folder

---

## Struktur Direktori Proyek

```
BookStore-BNSP/
├── backend/                    # Laravel Backend
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── Api/        # API Controllers (Auth, Book, Cart, dll)
│   │   │   │   └── Admin/      # Admin Web Controllers
│   │   │   ├── Middleware/      # CheckRole middleware
│   │   │   ├── Requests/       # Form Request validations
│   │   │   └── Resources/      # API Resource transformations
│   │   ├── Models/             # Eloquent Models (7 model)
│   │   └── Traits/             # ApiResponse trait
│   ├── database/
│   │   ├── migrations/         # 7 tabel + default Laravel
│   │   └── seeders/            # DatabaseSeeder
│   ├── resources/views/admin/  # Blade views (AdminLTE)
│   ├── routes/
│   │   ├── api.php             # 34 API routes
│   │   └── web.php             # Admin web routes
│   └── config/cors.php         # CORS configuration
├── frontend/                   # React Frontend
│   ├── src/
│   │   ├── api/axios.js        # Axios config
│   │   ├── components/         # Navbar, Footer, BookCard, dll
│   │   ├── contexts/           # AuthContext, CartContext
│   │   └── pages/              # 10 halaman
│   ├── index.html              # Entry + Midtrans Snap.js
│   └── vite.config.js
├── deskripsi-projek.md         # Dokumentasi proyek
└── BookStore.postman_collection.json
```
