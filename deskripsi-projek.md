# Dokumentasi Proyek: Aplikasi Toko Buku Online (BookStore App)

## 1. Ringkasan Proyek dan Tujuan Aplikasi
Aplikasi Toko Buku Online (BookStore App) adalah sebuah platform e-commerce berbasis web yang dirancang untuk memudahkan pelanggan dalam mencari, melihat detail, dan membeli buku secara online. Proyek ini dibangun dengan memisahkan sisi backend (API) dan frontend (SPA), memberikan pengalaman pengguna yang cepat, responsif, dan dinamis.

**Tujuan aplikasi:**
- Memberikan kemudahan bagi pengguna untuk berbelanja buku.
- Menyediakan sistem manajemen inventaris dan pesanan yang efisien bagi administrator.
- Mengimplementasikan sistem pembayaran online (payment gateway) yang aman.
- Memenuhi kriteria unjuk kerja untuk sertifikasi BNSP Skema Junior Web Developer.

*(Terkait dengan kompetensi [BNSP: Mengimplementasikan Pemrograman Terstruktur, Membuat Dokumen Kode Program])*

## 2. Tech Stack dan Versi
**Backend:**
- Framework: Laravel (Versi Terbaru - 11.x)
- Bahasa: PHP 8.2+
- Database: MySQL 8.x
- Authentication: Laravel Sanctum (Cookie-based SPA / Token-based)
- Payment Gateway: Midtrans Snap.js (Sandbox Mode)

**Frontend:**
- Library Utama: ReactJS (v18.x)
- Build Tool: Vite (Terbaru)
- Routing: React Router v6
- HTTP Client: Axios
- Styling: TailwindCSS (v3.x)
- Notifikasi: SweetAlert2
- Icons: @heroicons/react

**Testing & Dokumentasi:**
- API Testing: Postman Collection v2.1

## 3. Skema Basis Data (Textual ERD)

### `users`
- `id` (PK, BigInt, Auto Increment)
- `name` (String)
- `email` (String, Unique)
- `password` (String)
- `role` (Enum: 'admin', 'user', Default: 'user')
- `avatar` (String, Nullable)
- `timestamps` (created_at, updated_at)

### `categories`
- `id` (PK, BigInt, Auto Increment)
- `name` (String)
- `slug` (String, Unique)
- `description` (Text, Nullable)
- `timestamps`

### `books`
- `id` (PK, BigInt, Auto Increment)
- `category_id` (FK -> categories.id)
- `title` (String)
- `slug` (String, Unique)
- `author` (String)
- `publisher` (String)
- `year` (Integer)
- `price` (Decimal/Integer)
- `stock` (Integer)
- `cover_image` (String, Nullable)
- `description` (Text)
- `timestamps`

### `carts`
- `id` (PK, BigInt, Auto Increment)
- `user_id` (FK -> users.id)
- `book_id` (FK -> books.id)
- `quantity` (Integer)
- `timestamps`

### `orders`
- `id` (PK, BigInt, Auto Increment)
- `user_id` (FK -> users.id)
- `order_code` (String, Unique)
- `total_price` (Decimal/Integer)
- `status` (Enum: 'pending', 'paid', 'cancelled', Default: 'pending')
- `payment_method` (String, Nullable)
- `midtrans_token` (String, Nullable)
- `timestamps`

### `order_items`
- `id` (PK, BigInt, Auto Increment)
- `order_id` (FK -> orders.id)
- `book_id` (FK -> books.id)
- `quantity` (Integer)
- `price` (Decimal/Integer)
- `timestamps`

### `contacts`
- `id` (PK, BigInt, Auto Increment)
- `name` (String)
- `email` (String)
- `subject` (String)
- `message` (Text)
- `is_read` (Boolean, Default: false)
- `timestamps`

## 4. Daftar Endpoint API (Route List)

### [AUTH]
- `POST   /api/register` : Mendaftarkan pengguna baru
- `POST   /api/login`    : Autentikasi pengguna
- `POST   /api/logout`   : Keluar dari sesi (auth required)
- `GET    /api/user`     : Mendapatkan data user yang sedang login (auth required)

### [BUKU & KATEGORI - Publik]
- `GET    /api/books`                 : Menampilkan daftar buku (Mendukung filter: `?category=`, `search=`, `sort=`)
- `GET    /api/books/{slug}`          : Detail informasi sebuah buku
- `GET    /api/categories`            : Daftar semua kategori
- `GET    /api/categories/{slug}/books`: Menampilkan daftar buku berdasarkan kategori tertentu

### [KERANJANG - Auth User]
- `GET    /api/cart`       : Menampilkan keranjang belanja user aktif
- `POST   /api/cart`       : Menambahkan buku ke dalam keranjang
- `PATCH  /api/cart/{id}`  : Mengubah kuantitas item di keranjang
- `DELETE /api/cart/{id}`  : Menghapus item dari keranjang

### [PESANAN - Auth User]
- `GET    /api/orders`               : Melihat riwayat pesanan user aktif
- `POST   /api/orders`               : Membuat pesanan baru dan menghasilkan Midtrans token
- `GET    /api/orders/{order_code}`  : Melihat detail sebuah pesanan
- `POST   /api/orders/notification`  : Endpoint Webhook untuk menerima notifikasi dari Midtrans (Publik)

### [KONTAK - Publik]
- `POST   /api/contact`    : Mengirim pesan kontak (pertanyaan/feedback)

### [ADMIN - Middleware: auth + role:admin]
- `GET    /api/admin/dashboard`         : Statistik dashboard (total users, orders, revenue, buku terlaris)
- `GET|POST|PUT|DELETE /api/admin/books`      : CRUD Data Buku
- `GET|POST|PUT|DELETE /api/admin/categories` : CRUD Data Kategori
- `GET    /api/admin/users`             : Daftar Pengguna aplikasi
- `GET    /api/admin/orders`            : Daftar seluruh pesanan
- `PATCH  /api/admin/orders/{id}/status`: Mengubah status sebuah pesanan (pending/paid/cancelled)
- `GET    /api/admin/contacts`          : Daftar pesan masuk (kontak)
- `PATCH  /api/admin/contacts/{id}/read`: Menandai pesan masuk telah dibaca

## 5. Petunjuk Instalasi & Konfigurasi `.env`

*(Akan dilengkapi dengan panduan eksekusi perintah terminal pada tahap selanjutnya)*

**Konfigurasi `.env` Penting (Backend):**
```env
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:5173
SANCTUM_STATEFUL_DOMAINS=localhost:5173

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bookstore_bnsp
DB_USERNAME=root
DB_PASSWORD=

# MIDTRANS CONFIGURATION (Sandbox Mode)
MIDTRANS_SERVER_KEY=your_server_key_here
MIDTRANS_CLIENT_KEY=your_client_key_here
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```
