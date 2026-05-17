<?php
session_start();
$CART_KEY = 'smartphoneCart';
$cart = isset($_SESSION[$CART_KEY]) ? $_SESSION[$CART_KEY] : (isset($_COOKIE[$CART_KEY]) ? json_decode($_COOKIE[$CART_KEY], true) : []);
?><!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Checkout - SmartphoneHub</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="styles.css" />
</head>
<body class="bg-slate-50 text-slate-900">
  <header class="shadow-sm bg-white sticky top-0 z-50">
    <nav class="container d-flex justify-content-between align-items-center py-3">
      <a href="index.php" class="fs-4 fw-bold" style="color:var(--primary); text-decoration:none;">SmartphoneHub</a>
      <a href="index.php" class="btn btn-outline-primary btn-sm">Kembali</a>
    </nav>
  </header>

  <main class="container py-8">
    <div class="mb-5 text-center">
      <h1 class="fw-bold">Checkout Pesanan</h1>
      <p class="text-slate-600">Lengkapi data berikut untuk menyelesaikan pembelian.</p>
    </div>

    <div class="row g-4">
      <div class="col-lg-8">
        <div class="card shadow-sm p-4 rounded-4 mb-4">
          <h2 class="h5 fw-bold mb-4">Data Pembeli</h2>
          <form id="checkoutForm">
            <div class="mb-3">
              <label class="form-label">Nama Lengkap</label>
              <input type="text" class="form-control form-control-lg" name="fullName" placeholder="Contoh: Ahmad Susanto" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Nomor Telepon</label>
              <input type="tel" class="form-control form-control-lg" name="phone" placeholder="Contoh: 081234567890" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control form-control-lg" name="email" placeholder="Contoh: ahmad@email.com" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Alamat Lengkap</label>
              <textarea class="form-control form-control-lg" name="address" rows="3" placeholder="Jalan, No., Kelurahan, Kecamatan, Kota, Provinsi" required></textarea>
            </div>
            <div class="mb-3 row gx-2">
              <div class="col-6">
                <label class="form-label">Kota</label>
                <input type="text" class="form-control form-control-lg" name="city" placeholder="Jakarta" required />
              </div>
              <div class="col-6">
                <label class="form-label">Kode Pos</label>
                <input type="text" class="form-control form-control-lg" name="zipCode" placeholder="12345" required />
              </div>
            </div>
            <button type="submit" class="btn btn-success btn-lg w-100">Proses Pembayaran</button>
          </form>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card shadow-sm rounded-4 p-4">
          <h2 class="h5 fw-bold mb-4">Ringkasan Pesanan</h2>
          <div class="mb-4" id="orderSummary"></div>
          <div class="border-top pt-3">
            <div class="d-flex justify-content-between mb-2">
              <span>Subtotal</span>
              <span id="subtotal">Rp0</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Biaya Pengiriman</span>
              <span>Gratis</span>
            </div>
            <div class="d-flex justify-content-between fw-bold text-lg mt-3 pt-3 border-top">
              <span>Total Bayar</span>
              <span id="grandTotal">Rp0</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="py-4 bg-white mt-8 border-top">
    <div class="container text-center text-slate-600">© 2026 SmartphoneHub. Tampilan modern untuk penjualan smartphone.</div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script>
    const CART_KEY = 'smartphoneCart';
    const API_ENDPOINT = 'api.php';

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

    function renderOrderSummary() {
      const cart = getCart();
      const summary = document.getElementById('orderSummary');
      const subtotal = document.getElementById('subtotal');
      const grandTotal = document.getElementById('grandTotal');

      if (!cart.length) {
        summary.innerHTML = '<p class="text-slate-600">Keranjang kosong. <a href="index.php">Kembali belanja</a></p>';
        subtotal.textContent = 'Rp0';
        grandTotal.textContent = 'Rp0';
        return;
      }

      let total = 0;
      summary.innerHTML = cart.map(item => {
        const itemTotal = item.price * item.qty;
        total += itemTotal;
        return `
          <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
            <div>
              <strong>${item.name}</strong>
              <div class="small text-slate-600">${item.qty} x Rp${formatPrice(item.price)}</div>
            </div>
            <span class="fw-semibold">Rp${formatPrice(itemTotal)}</span>
          </div>
        `;
      }).join('');

      subtotal.textContent = `Rp${formatPrice(total)}`;
      grandTotal.textContent = `Rp${formatPrice(total)}`;
    }

    document.getElementById('checkoutForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const cart = getCart();
      if (!cart.length) {
        alert('Keranjang kosong.');
        return;
      }

      const formData = new FormData(e.target);
      const customerData = {
        fullName: formData.get('fullName'),
        phone: formData.get('phone'),
        email: formData.get('email'),
        address: formData.get('address'),
        city: formData.get('city'),
        zipCode: formData.get('zipCode'),
      };

      const token = localStorage.getItem('jwtToken');
      const headers = { 'Content-Type': 'application/json' };
      if (token) {
        headers['Authorization'] = 'Bearer ' + token;
      }

      const response = await fetch(`${API_ENDPOINT}?action=checkout`, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({ cart, customer: customerData }),
      });

      const result = await response.json();
      if (!response.ok || result.error) {
        alert(result.error || 'Checkout gagal. Silakan coba lagi.');
        return;
      }

      saveCart([]);
      alert('Pesanan berhasil dibuat! Nomor order: #' + result.id);
      window.location.href = 'index.php';
    });

    renderOrderSummary();
  </script>
</body>
</html>
