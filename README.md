# TechStore - E-Commerce Smartphone Modern

Aplikasi e-commerce penjualan smartphone dengan design modern, backend PHP/MySQL, JWT authentication, dan role-based access control.

## ✨ Fitur Utama

### Untuk Pelanggan
- **Toko** — Tampilkan produk dengan filter kategori, halaman detail produk, dan keranjang belanja
- **Checkout** — Form pembelian dengan data pelanggan (nama, email, telepon, alamat)
- **Registrasi & Login** — Sistem user berbasis JWT dengan keamanan bcrypt
- **Riwayat Pesanan** — Lihat pesanan yang pernah dibuat

### Untuk Admin
- **Dashboard Admin** — Kelola produk, lihat pesanan, statistik penjualan
- **Manajemen Produk** — Create, Read, Update, Delete (CRUD) smartphone
- **Manajemen Pesanan** — Lihat detail pesanan pelanggan
- **Role-Based Access** — Hanya admin yang dapat mengakses panel admin

## 🔐 Sistem Autentikasi

Aplikasi menggunakan **JWT (JSON Web Tokens)** untuk autentikasi yang aman dan scalable:

- **Login/Register** — Pengguna dapat login atau membuat akun baru
- **Password Encryption** — Password di-hash menggunakan bcrypt
- **JWT Token** — Token disimpan di localStorage dan dikirim di header Authorization
- **Role-Based Access** — Sistem admin middleware (`requireAdmin()`) melindungi rute sensitif
- **Automatic Redirect** — User yang tidak login otomatis di-redirect ke halaman login

### Demo Account
- Username: `admin`
- Password: `admin123`

## 📁 Struktur File

```
├── index.php              # Halaman toko utama
├── product.php            # Halaman detail produk
├── checkout.php           # Halaman checkout
├── admin.php              # Dashboard admin (JWT protected)
├── login.php              # Halaman login (JWT)
├── register.php           # Halaman registrasi (JWT)
├── api.php                # REST API backend
├── jwt.php                # JWT class (encode/decode)
├── middleware.php         # JWT / authorization middleware
├── db.php                 # Database connection & schema
├── app.js                 # Frontend logic (JWT handling)
├── styles.css             # Bootstrap + Tailwind styling
└── README.md              # File ini
```

## 🚀 Setup XAMPP

1. **Pastikan folder ada di path yang benar:**
   ```
   xampp\htdocs\uji coba
   ```

2. **Jalankan XAMPP:**
   - Buka XAMPP Control Panel
   - Start Apache dan MySQL

3. **Buka di browser:**
   - Toko: `http://localhost/uji%20coba/index.php`
   - Login: `http://localhost/uji%20coba/login.php`
   - Admin: `http://localhost/uji%20coba/admin.php` (hanya untuk admin)

## 🗄️ Database

Database akan **dibuat otomatis** saat akses pertama.

### Skema Tabel

**users** — Menyimpan akun user
```sql
- id (PRIMARY KEY)
- username (UNIQUE)
- email (UNIQUE)
- password_hash (bcrypt)
- role (admin | user)
- created_at
```

**products** — Menyimpan data smartphone
```sql
- id (PRIMARY KEY)
- name
- category (flagship | midrange | budget)
- price
- stock
- image
- description
- created_at
```

**orders** — Menyimpan pesanan
```sql
- id (PRIMARY KEY)
- user_id (FOREIGN KEY)
- customer_name, email, phone, address, city, zip
- total
- status (paid | pending | shipped | delivered)
- created_at
```

**order_items** — Detail item dalam pesanan
```sql
- id (PRIMARY KEY)
- order_id (FOREIGN KEY)
- product_id
- product_name, qty, price, subtotal
```

## ⚙️ Konfigurasi

Edit `db.php` jika MySQL memiliki kredensial khusus:

```php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'smartphone_store');
define('DB_USER', 'root');       // Ubah jika perlu
define('DB_PASS', '');           // Ubah jika ada password
```

## 📡 API Endpoints

Semua endpoint mengembalikan JSON. Endpoint yang protected memerlukan header:
```
Authorization: Bearer {jwt_token}
```

### Auth
- `POST /api.php?action=register` — Daftar user baru
- `POST /api.php?action=login` — Login, dapatkan JWT token
- `GET /api.php?action=me` — Ambil data user (protected)

### Products
- `GET /api.php?action=list` — Daftar semua produk
- `POST /api.php?action=save` — Tambah/edit produk (admin only)
- `POST /api.php?action=delete` — Hapus produk (admin only)

### Orders
- `POST /api.php?action=checkout` — Buat pesanan baru
- `GET /api.php?action=orders` — Daftar pesanan (admin only)

## 🔒 Keamanan

- **Password Hashing** — Menggunakan bcrypt untuk enkripsi password
- **JWT Signing** — Token ditandatangani dengan HMAC-SHA256
- **Admin Middleware** — Fungsi `requireAdmin()` memeriksa JWT dan role user
- **CSRF Protection** — JWT menggantikan session untuk proteksi lebih baik
- **SQL Injection Prevention** — Menggunakan prepared statements PDO

## 🎨 Frontend

- **Bootstrap 5.3.2** — Grid system, components, responsive
- **Tailwind CSS** — Utility-first styling untuk customization cepat
- **Vanilla JavaScript** — Tanpa jQuery, modern ES6+

## 📝 Catatan

- Database akan dibuat otomatis pada akses pertama (pastikan MySQL running)
- JWT token valid selama 24 jam (dapat dikonfigurasi di `jwt.php`)
- Default admin user dibuat otomatis di database
- Logout hanya menghapus token dari localStorage (JWT stateless)

## 🐛 Troubleshooting

**"Connection refused" error**
- Pastikan MySQL sudah running di XAMPP
- Check kredensial di `db.php`

**"Cannot find Authorization header"**
- Beberapa server tidak support `getallheaders()` - sudah di-handle dengan fallback ke `$_SERVER['HTTP_AUTHORIZATION']`

**Token invalid**
- Token mungkin sudah expired (24 jam)
- Silakan login ulang

## 📞 Support

Untuk pertanyaan atau bug report, silakan contact developer.

---

**Happy Shopping! 📱**
