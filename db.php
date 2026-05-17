<?php
// Koneksi database MySQL untuk XAMPP.
// Ubah DB_USER/DB_PASS jika Anda menggunakan kredensial berbeda.
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'smartphone_store');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

function createDatabase(): PDO {
    $dsn = sprintf('mysql:host=%s;charset=%s', DB_HOST, DB_CHARSET);
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET ' . DB_CHARSET . ' COLLATE ' . DB_CHARSET . '_unicode_ci');
    $pdo->exec('USE `' . DB_NAME . '`');
    $pdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  category VARCHAR(50) NOT NULL,
  price INT NOT NULL,
  stock INT NOT NULL,
  image TEXT NOT NULL,
  description TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED,
  customer_name VARCHAR(255) NOT NULL,
  customer_email VARCHAR(255) NOT NULL,
  customer_phone VARCHAR(20) NOT NULL,
  customer_address TEXT NOT NULL,
  customer_city VARCHAR(100) NOT NULL,
  customer_zip VARCHAR(10) NOT NULL,
  total INT NOT NULL,
  status VARCHAR(50) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS order_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  product_name VARCHAR(255) NOT NULL,
  qty INT NOT NULL,
  price INT NOT NULL,
  subtotal INT NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL
    );

    // Create default admin user if not exists
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
    $stmt->execute(['username' => 'admin']);
    if ($stmt->fetchColumn() == 0) {
        $adminPassword = password_hash('admin123', PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :hash, :role)');
        $stmt->execute([
            'username' => 'admin',
            'email' => 'admin@smartphonehub.com',
            'hash' => $adminPassword,
            'role' => 'admin',
        ]);
    }

    return $pdo;
}

function getConnection(): PDO {
    static $pdo;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $pdo = createDatabase();
    return $pdo;
}

function seedProducts(PDO $pdo): void {
    $count = $pdo->query('SELECT COUNT(*) AS total FROM products')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $products = [
        [
            'name' => 'Galaxy S24 Ultra',
            'category' => 'flagship',
            'price' => 19999000,
            'stock' => 8,
            'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=900&q=80',
            'description' => 'Flagship terbaru dengan kamera profesional, layar dinamis, dan performa tinggi.'
        ],
        [
            'name' => 'iPhone 15 Pro',
            'category' => 'flagship',
            'price' => 24999000,
            'stock' => 5,
            'image' => 'https://images.unsplash.com/photo-1510552776732-01accb4c6d20?auto=format&fit=crop&w=900&q=80',
            'description' => 'Desain premium dengan ekosistem Apple dan pengalaman pengguna halus.'
        ],
        [
            'name' => 'Redmi Note 13',
            'category' => 'midrange',
            'price' => 3999000,
            'stock' => 18,
            'image' => 'https://images.unsplash.com/photo-1522199710521-72d69614c702?auto=format&fit=crop&w=900&q=80',
            'description' => 'Pilihan mid-range terbaik dengan baterai besar dan performa lancar.'
        ],
        [
            'name' => 'Realme C55',
            'category' => 'budget',
            'price' => 1799000,
            'stock' => 25,
            'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=900&q=80',
            'description' => 'Smartphone budget yang stylish dengan kamera ganda dan fast charging.'
        ]
    ];

    $insert = $pdo->prepare('INSERT INTO products (name, category, price, stock, image, description) VALUES (:name, :category, :price, :stock, :image, :description)');
    foreach ($products as $product) {
        $insert->execute($product);
    }
}
