<?php
require_once __DIR__ . '/db.php';
$pdo = getConnection();
seedProducts($pdo);

$productId = $_GET['id'] ?? null;
$product = null;

if ($productId) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->execute(['id' => (int) $productId]);
    $product = $stmt->fetch();
}

if (!$product) {
    header('Location: index.php');
    exit;
}
?><!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($product['name']) ?> - SmartphoneHub</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="styles.css" />
</head>
<body class="bg-slate-50 text-slate-900">
  <header class="shadow-sm bg-white sticky top-0 z-50">
    <nav class="container d-flex justify-content-between align-items-center py-3">
      <a href="index.php" class="fs-4 fw-bold" style="color:var(--primary); text-decoration:none;">SmartphoneHub</a>
      <div class="d-flex gap-3 align-items-center">
        <a href="index.php" class="btn btn-outline-primary btn-sm">Belanja</a>
        <button class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas">Keranjang</button>
        <a id="adminBtn" href="admin.php" class="btn btn-outline-secondary btn-sm" style="display: none;">Admin</a>
      </div>
    </nav>
  </header>

  <main class="container py-8">
    <div class="row g-5">
      <div class="col-lg-6">
        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid rounded-4 shadow-sm" />
      </div>
      <div class="col-lg-6">
        <span class="badge bg-primary text-uppercase mb-3"><?= htmlspecialchars($product['category']) ?></span>
        <h1 class="h2 fw-bold mb-3"><?= htmlspecialchars($product['name']) ?></h1>
        <p class="lead text-slate-700 mb-4"><?= htmlspecialchars($product['description']) ?></p>

        <div class="border-top border-bottom py-4 mb-4">
          <div class="row g-3">
            <div class="col-6">
              <div class="text-slate-600 small">Harga</div>
              <div class="h3 fw-bold text-slate-900">Rp<span id="priceDisplay"><?= number_format($product['price'], 0, ',', '.') ?></span></div>
            </div>
            <div class="col-6">
              <div class="text-slate-600 small">Stok Tersedia</div>
              <div class="h3 fw-bold text-slate-900"><?= (int)$product['stock'] ?> unit</div>
            </div>
          </div>
        </div>

        <div class="d-flex gap-3 mb-5">
          <div class="d-flex align-items-center border rounded-3 px-3 py-2">
            <button class="btn btn-sm btn-link text-slate-600" id="decreaseQty">−</button>
            <input type="number" id="quantity" value="1" min="1" max="<?= (int)$product['stock'] ?>" class="form-control form-control-sm text-center border-0" style="width: 60px;" />
            <button class="btn btn-sm btn-link text-slate-600" id="increaseQty">+</button>
          </div>
          <button class="btn btn-primary btn-lg flex-grow-1" id="addToCartBtn">Tambah ke Keranjang</button>
        </div>

        <div class="card bg-slate-100 border-0 p-4 rounded-4">
          <h5 class="fw-bold mb-3">Spesifikasi & Keunggulan</h5>
          <ul class="list-unstyled">
            <li class="mb-2">✓ Garansi resmi</li>
            <li class="mb-2">✓ Original 100%</li>
            <li class="mb-2">✓ Pengiriman gratis ke seluruh Indonesia</li>
            <li class="mb-2">✓ Dukungan pelanggan 24/7</li>
          </ul>
        </div>
      </div>
    </div>
  </main>

  <div class="offcanvas offcanvas-end" tabindex="-1" id="cartCanvas">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title">Keranjang Belanja</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <ul class="list-group mb-3" id="cartList"></ul>
      <div class="mt-auto">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="fw-semibold">Total</span>
          <span id="cartTotal" class="fw-bold">Rp0</span>
        </div>
        <a href="checkout.php" class="btn btn-primary w-100">Checkout</a>
      </div>
    </div>
  </div>

  <footer class="py-4 bg-white mt-8 border-top">
    <div class="container text-center text-slate-600">© 2026 SmartphoneHub. Tampilan modern untuk penjualan smartphone.</div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script>
    const API_ENDPOINT = 'api.php';
    const CART_KEY = 'smartphoneCart';
    const productId = <?= (int)$product['id'] ?>;
    const productPrice = <?= (int)$product['price'] ?>;
    const maxStock = <?= (int)$product['stock'] ?>;

    window.addEventListener('DOMContentLoaded', () => {
      const token = localStorage.getItem('jwtToken');
      const adminBtn = document.getElementById('adminBtn');
      if (token && adminBtn) {
        try {
          const base64Url = token.split('.')[1];
          const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
          const user = JSON.parse(window.atob(base64));
          if (user.role === 'admin') {
            adminBtn.style.display = 'inline-block';
          }
        } catch (e) {
          console.warn('Gagal membaca token admin:', e);
        }
      }
    });

    function getCart() {
      const raw = localStorage.getItem(CART_KEY);
      return raw ? JSON.parse(raw) : [];
    }

    function saveCart(cart) {
      localStorage.setItem(CART_KEY, JSON.stringify(cart));
    }

    function formatPrice(value) {
      return new Intl.NumberFormat('id-ID').format(value);
    }

    function renderCart() {
      const cart = getCart();
      const cartList = document.getElementById('cartList');
      const cartTotal = document.getElementById('cartTotal');

      if (!cart.length) {
        cartList.innerHTML = `<li class="list-group-item text-center text-slate-600">Keranjang kosong.</li>`;
        cartTotal.textContent = 'Rp0';
        return;
      }

      let total = 0;
      cartList.innerHTML = cart.map(item => {
        const itemTotal = item.price * item.qty;
        total += itemTotal;
        return `
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <strong>${item.name}</strong>
              <div class="small text-slate-600">${item.qty} x Rp${formatPrice(item.price)}</div>
            </div>
            <span class="fw-semibold">Rp${formatPrice(itemTotal)}</span>
          </li>
        `;
      }).join('');
      cartTotal.textContent = `Rp${formatPrice(total)}`;
    }

    document.getElementById('decreaseQty').addEventListener('click', () => {
      const input = document.getElementById('quantity');
      if (input.value > 1) input.value--;
    });

    document.getElementById('increaseQty').addEventListener('click', () => {
      const input = document.getElementById('quantity');
      if (input.value < maxStock) input.value++;
    });

    document.getElementById('addToCartBtn').addEventListener('click', () => {
      const qty = parseInt(document.getElementById('quantity').value);
      const cart = getCart();
      const existing = cart.find(item => Number(item.id) === Number(productId));

      if (existing) {
        existing.qty += qty;
      } else {
        cart.push({ id: productId, name: '<?= htmlspecialchars(addslashes($product['name'])) ?>', price: productPrice, qty });
      }

      saveCart(cart);
      renderCart();
      const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('cartCanvas'));
      offcanvas.show();
    });

    renderCart();
  </script>
</body>
</html>
