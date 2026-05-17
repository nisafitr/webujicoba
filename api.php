<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/middleware.php';

try {
    $pdo = getConnection();
    seedProducts($pdo);
    $action = $_GET['action'] ?? 'list';

    switch ($action) {
        // ========== AUTH ENDPOINTS ==========
        case 'register':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['error' => 'Method tidak diizinkan.']);
                break;
            }

            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
                http_response_code(422);
                echo json_encode(['error' => 'Username, email, dan password harus diisi.']);
                break;
            }

            if (strlen($data['password']) < 6) {
                http_response_code(422);
                echo json_encode(['error' => 'Password minimal 6 karakter.']);
                break;
            }

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                http_response_code(422);
                echo json_encode(['error' => 'Format email tidak valid.']);
                break;
            }

            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username OR email = :email');
            $stmt->execute(['username' => $data['username'], 'email' => $data['email']]);
            if ($stmt->fetch()) {
                http_response_code(409);
                echo json_encode(['error' => 'Username atau email sudah terdaftar.']);
                break;
            }

            $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :hash, :role)');
            $stmt->execute([
                'username' => $data['username'],
                'email' => $data['email'],
                'hash' => $passwordHash,
                'role' => 'user',
            ]);
            echo json_encode(['success' => true, 'message' => 'Registrasi berhasil. Silakan login.']);
            break;

        case 'login':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['error' => 'Method tidak diizinkan.']);
                break;
            }

            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['username']) || empty($data['password'])) {
                http_response_code(422);
                echo json_encode(['error' => 'Username dan password harus diisi.']);
                break;
            }

            $stmt = $pdo->prepare('SELECT id, username, email, password_hash, role FROM users WHERE username = :username');
            $stmt->execute(['username' => $data['username']]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($data['password'], $user['password_hash'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Username atau password salah.']);
                break;
            }

            $token = JWT::encode([
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role'],
            ]);
            // Set HttpOnly cookie for server-side page protection (secure when HTTPS)
            $secureFlag = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            setcookie('jwtToken', $token, [
                'expires' => time() + 86400,
                'path' => '/',
                'secure' => $secureFlag,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            echo json_encode(['success' => true, 'token' => $token, 'user' => ['id' => $user['id'], 'username' => $user['username'], 'role' => $user['role']]]);
            break;

        case 'logout':
            // Clear HttpOnly cookie server-side as well
            setcookie('jwtToken', '', [
                'expires' => time() - 3600,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            echo json_encode(['success' => true, 'message' => 'Logout berhasil.']);
            break;

        case 'me':
            $user = getAuthUser();
            if (!$user) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                break;
            }
            echo json_encode(['success' => true, 'user' => $user]);
            break;

        // ========== PRODUCT ENDPOINTS ==========
        case 'list':
            $category = $_GET['category'] ?? null;
            if ($category) {
                $stmt = $pdo->prepare('SELECT * FROM products WHERE category = :category ORDER BY created_at DESC');
                $stmt->execute(['category' => $category]);
            } else {
                $stmt = $pdo->query('SELECT * FROM products ORDER BY created_at DESC');
            }
            echo json_encode($stmt->fetchAll());
            break;

        case 'save':
            requireAdmin();
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['name']) || empty($data['category']) || empty($data['price']) || empty($data['stock']) || empty($data['image']) || empty($data['description'])) {
                http_response_code(422);
                echo json_encode(['error' => 'Data produk tidak lengkap.']);
                break;
            }

            if (!empty($data['id'])) {
                $stmt = $pdo->prepare('UPDATE products SET name = :name, category = :category, price = :price, stock = :stock, image = :image, description = :description WHERE id = :id');
                $stmt->execute([
                    'id' => (int) $data['id'],
                    'name' => $data['name'],
                    'category' => $data['category'],
                    'price' => (int) $data['price'],
                    'stock' => (int) $data['stock'],
                    'image' => $data['image'],
                    'description' => $data['description'],
                ]);
                echo json_encode(['success' => true]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO products (name, category, price, stock, image, description) VALUES (:name, :category, :price, :stock, :image, :description)');
                $stmt->execute([
                    'name' => $data['name'],
                    'category' => $data['category'],
                    'price' => (int) $data['price'],
                    'stock' => (int) $data['stock'],
                    'image' => $data['image'],
                    'description' => $data['description'],
                ]);
                echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            }
            break;

        case 'delete':
            requireAdmin();
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            if (!$id) {
                http_response_code(422);
                echo json_encode(['error' => 'ID produk tidak valid.']);
                break;
            }
            $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
            $stmt->execute(['id' => $id]);
            echo json_encode(['success' => true]);
            break;

        // ========== CHECKOUT & ORDER ENDPOINTS ==========
        case 'checkout':
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['cart']) || !is_array($data['cart'])) {
                http_response_code(422);
                echo json_encode(['error' => 'Keranjang tidak valid.']);
                break;
            }

            $cartItems = $data['cart'];
            $customer = $data['customer'] ?? null;
            if (!$cartItems) {
                http_response_code(422);
                echo json_encode(['error' => 'Keranjang kosong.']);
                break;
            }

            $userId = null;
            $user = getAuthUser();
            if ($user) {
                $userId = $user['id'];
            }

            $pdo->beginTransaction();
            try {
                $total = 0;
                $orderItems = [];

                foreach ($cartItems as $item) {
                    $id = (int) $item['id'];
                    $qty = (int) $item['qty'];
                    if ($qty <= 0) {
                        throw new Exception('Jumlah produk tidak valid.');
                    }

                    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id FOR UPDATE');
                    $stmt->execute(['id' => $id]);
                    $product = $stmt->fetch();
                    if (!$product) {
                        throw new Exception('Produk tidak ditemukan: ' . $id);
                    }
                    if ($product['stock'] < $qty) {
                        throw new Exception('Stok tidak mencukupi untuk produk: ' . $product['name']);
                    }

                    $subtotal = $product['price'] * $qty;
                    $total += $subtotal;
                    $orderItems[] = [
                        'product_id' => $id,
                        'product_name' => $product['name'],
                        'qty' => $qty,
                        'price' => $product['price'],
                        'subtotal' => $subtotal,
                    ];
                }

                $stmt = $pdo->prepare('INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, customer_address, customer_city, customer_zip, total, status) VALUES (:user_id, :name, :email, :phone, :address, :city, :zip, :total, :status)');
                $stmt->execute([
                    'user_id' => $userId,
                    'name' => $customer['fullName'] ?? 'Guest',
                    'email' => $customer['email'] ?? '',
                    'phone' => $customer['phone'] ?? '',
                    'address' => $customer['address'] ?? '',
                    'city' => $customer['city'] ?? '',
                    'zip' => $customer['zipCode'] ?? '',
                    'total' => $total,
                    'status' => 'paid',
                ]);
                $orderId = $pdo->lastInsertId();

                $insertItem = $pdo->prepare('INSERT INTO order_items (order_id, product_id, product_name, qty, price, subtotal) VALUES (:order_id, :product_id, :product_name, :qty, :price, :subtotal)');
                $updateStock = $pdo->prepare('UPDATE products SET stock = stock - :qty WHERE id = :id');

                foreach ($orderItems as $item) {
                    $insertItem->execute([
                        'order_id' => $orderId,
                        'product_id' => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                    ]);
                    $updateStock->execute(['qty' => $item['qty'], 'id' => $item['product_id']]);
                }

                $pdo->commit();
                echo json_encode(['success' => true, 'id' => $orderId]);
            } catch (Exception $ex) {
                $pdo->rollBack();
                http_response_code(422);
                echo json_encode(['error' => $ex->getMessage()]);
            }
            break;

        case 'orders':
            requireAdmin();
            $orders = $pdo->query('SELECT * FROM orders ORDER BY created_at DESC')->fetchAll();
            $orderStmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = :order_id');
            foreach ($orders as &$order) {
                $orderStmt->execute(['order_id' => $order['id']]);
                $order['items'] = $orderStmt->fetchAll();
            }
            echo json_encode($orders);
            break;

        default:
            http_response_code(404);
            echo json_encode(['error' => 'Aksi tidak dikenali.']);
            break;
    }
} catch (Exception $ex) {
    http_response_code(500);
    echo json_encode(['error' => 'Terjadi kesalahan server.', 'detail' => $ex->getMessage()]);
}
