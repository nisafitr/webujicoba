<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Smartphone Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="styles.css" />
</head>
<body class="bg-slate-50 text-slate-900">
  <header class="main-header sticky-top shadow-sm">
    <div class="top-strip text-white d-none d-md-flex align-items-center justify-content-between">
      <div class="d-flex gap-3 align-items-center top-links">
        <span class="small">Seller Centre</span>
        <span class="divider">|</span>
        <span class="small">Mulai Berjualan</span>
        <span class="divider">|</span>
        <span class="small">Download</span>
        <span class="divider">|</span>
        <span class="small">Ikuti kami di</span>
        <a href="#" class="social-link"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22.23 0H1.77A1.77 1.77 0 0 0 0 1.77v20.46A1.77 1.77 0 0 0 1.77 24h10.97v-8.87H9.69v-3.46h3.05V8.41c0-3.02 1.84-4.67 4.53-4.67 1.29 0 2.4.1 2.73.14v3.17h-1.87c-1.46 0-1.75.7-1.75 1.72v2.26h3.5l-.46 3.46h-3.05V24h5.98A1.77 1.77 0 0 0 24 22.23V1.77A1.77 1.77 0 0 0 22.23 0Z"/></svg></a>
        <a href="#" class="social-link"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.372 0 0 5.372 0 12c0 5.303 3.438 9.8 8.205 11.385.6.111.82-.261.82-.58 0-.287-.011-1.045-.017-2.052-3.338.726-4.043-1.61-4.043-1.61-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.73.083-.73 1.205.085 1.839 1.237 1.839 1.237 1.07 1.835 2.806 1.305 3.49.998.108-.775.418-1.305.76-1.605-2.665-.303-5.466-1.332-5.466-5.93 0-1.31.469-2.381 1.236-3.221-.124-.303-.536-1.523.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.289-1.552 3.295-1.23 3.295-1.23.655 1.653.243 2.873.119 3.176.77.84 1.235 1.911 1.235 3.221 0 4.61-2.804 5.624-5.475 5.921.43.37.813 1.102.813 2.222 0 1.606-.015 2.898-.015 3.293 0 .321.216.697.825.579C20.565 21.796 24 17.299 24 12 24 5.372 18.627 0 12 0Z"/></svg></a>
      </div>
      <div class="d-flex gap-3 align-items-center top-links" id="topAuthLinks">
        <span class="small">Notifikasi</span>
        <span class="divider">|</span>
        <span class="small">Bantuan</span>
        <span class="divider">|</span>
        <span class="small">Bahasa Indonesia</span>
        <span class="divider">|</span>
        <a href="register.php" class="small text-white text-decoration-none">Daftar</a>
        <a href="login.php" class="small text-white text-decoration-none">Log In</a>
      </div>
    </div>
    <div class="header-main bg-white shadow-sm">
      <div class="container d-flex flex-wrap align-items-center justify-content-between py-3 gap-3">
        <div class="d-flex align-items-center gap-2 logo-block">
          <div class="header-logo rounded-3 d-flex align-items-center justify-content-center">
            <span class="fs-4 fw-bold">S</span>
          </div>
          <div>
            <a href="index.php" class="brand-logo fs-4 fw-bold mb-0">TechStore</a>
            <div class="text-muted small">Smartphone & Gadget</div>
          </div>
        </div>
        <div class="search-bar flex-grow-1 mx-3 d-none d-md-flex align-items-center shadow-sm rounded-pill overflow-hidden">
          <input type="text" class="form-control border-0" placeholder="Cari smartphone, aksesoris, merek..." />
          <button class="btn btn-primary rounded-pill ms-2 px-4">Cari</button>
        </div>
        <div class="d-flex align-items-center gap-2 header-actions" id="mainHeaderActions">
          <button class="btn btn-light rounded-circle p-2 d-flex align-items-center justify-content-center" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas" aria-label="Keranjang">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M7 18c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm10 0c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm1.368-11.721l-1.5 8.979c-.086.52-.533.862-1.05.862H9.648c-.518 0-.964-.347-1.05-.866L7.1 5.65H4V4h3.21l1.838 10.994h7.137l1.296-7.764h-12.5V5H20V3H5.5l-.21-1.25A1 1 0 0 0 4.29 0H1v2h2.29l3.6 21.5c.08.47.489.82.968.82h12.7c.549 0 1.01-.44 1.05-.988l1.5-8.98c.042-.251-.03-.508-.197-.71-.166-.203-.408-.322-.663-.322H8.725l-.503-3H19c.828 0 1.5-.672 1.5-1.5S19.828 4 19 4H9.473L8.7 2H1V0h7.7c.552 0 1.03.385 1.141.924L9.6 4h9.368c.684 0 1.265.47 1.394 1.13l1.5 8.98a1.5 1.5 0 0 1-1.395 1.87H8.15L6.897 6h10.47z"/></svg>
          </button>
          <a href="register.php" class="btn btn-outline-secondary rounded-pill px-3 py-2">Daftar</a>
          <a href="login.php" class="btn btn-primary rounded-pill px-3 py-2">Log In</a>
        </div>
      </div>
    </div>
  </header>

  <script>
    // Update user menu based on JWT token dynamically
    window.addEventListener('DOMContentLoaded', () => {
      const token = localStorage.getItem('jwtToken');
      const topAuthLinks = document.getElementById('topAuthLinks');
      const mainHeaderActions = document.getElementById('mainHeaderActions');
      
      function parseJwt(t) {
        try {
          const base64Url = t.split('.')[1];
          const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
          const jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
              return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
          }).join(''));
          return JSON.parse(jsonPayload);
        } catch (e) {
          return null;
        }
      }

      const user = token ? parseJwt(token) : null;

      if (user) {
        // User is logged in
        const isAdmin = user.role === 'admin';
        
        // Update top-links
        if (topAuthLinks) {
          topAuthLinks.innerHTML = `
            <span class="small">Notifikasi</span>
            <span class="divider">|</span>
            <span class="small">Bantuan</span>
            <span class="divider">|</span>
            <span class="small">Bahasa Indonesia</span>
            <span class="divider">|</span>
            <span class="small text-white">Halo, <strong>${user.username}</strong>${isAdmin ? ' (Admin)' : ''}</span>
            <span class="divider">|</span>
            <a href="#" id="topLogoutBtn" class="small text-white text-decoration-none">Logout</a>
          `;
        }

        // Update main header actions
        if (mainHeaderActions) {
          mainHeaderActions.innerHTML = `
            <button class="btn btn-light rounded-circle p-2 d-flex align-items-center justify-content-center me-2" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas" aria-label="Keranjang">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M7 18c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm10 0c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm1.368-11.721l-1.5 8.979c-.086.52-.533.862-1.05.862H9.648c-.518 0-.964-.347-1.05-.866L7.1 5.65H4V4h3.21l1.838 10.994h7.137l1.296-7.764h-12.5V5H20V3H5.5l-.21-1.25A1 1 0 0 0 4.29 0H1v2h2.29l3.6 21.5c.08.47.489.82.968.82h12.7c.549 0 1.01-.44 1.05-.988l1.5-8.98c.042-.251-.03-.508-.197-.71-.166-.203-.408-.322-.663-.322H8.725l-.503-3H19c.828 0 1.5-.672 1.5-1.5S19.828 4 19 4H9.473L8.7 2H1V0h7.7c.552 0 1.03.385 1.141.924L9.6 4h9.368c.684 0 1.265.47 1.394 1.13l1.5 8.98a1.5 1.5 0 0 1-1.395 1.87H8.15L6.897 6h10.47z"/></svg>
            </button>
            ${isAdmin ? '<a href="admin.php" class="btn btn-outline-primary rounded-pill px-3 py-2 me-2">Admin Panel</a>' : ''}
            <button id="mainLogoutBtn" class="btn btn-danger rounded-pill px-3 py-2">Logout</button>
          `;
        }

        // Add logout handler
        const logoutHandler = (e) => {
          e.preventDefault();
          if (confirm('Yakin ingin logout?')) {
            localStorage.removeItem('jwtToken');
            document.cookie = 'jwtToken=;path=/;max-age=0;SameSite=Lax';
            fetch('api.php?action=logout', { method: 'POST' }).finally(() => {
              window.location.reload();
            });
          }
        };

        document.getElementById('topLogoutBtn')?.addEventListener('click', logoutHandler);
        document.getElementById('mainLogoutBtn')?.addEventListener('click', logoutHandler);
      } else {
        // User is logged out
        if (topAuthLinks) {
          topAuthLinks.innerHTML = `
            <span class="small">Notifikasi</span>
            <span class="divider">|</span>
            <span class="small">Bantuan</span>
            <span class="divider">|</span>
            <span class="small">Bahasa Indonesia</span>
            <span class="divider">|</span>
            <a href="register.php" class="small text-white text-decoration-none">Daftar</a>
            <a href="login.php" class="small text-white text-decoration-none">Log In</a>
          `;
        }
        if (mainHeaderActions) {
          mainHeaderActions.innerHTML = `
            <button class="btn btn-light rounded-circle p-2 d-flex align-items-center justify-content-center me-2" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas" aria-label="Keranjang">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M7 18c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm10 0c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm1.368-11.721l-1.5 8.979c-.086.52-.533.862-1.05.862H9.648c-.518 0-.964-.347-1.05-.866L7.1 5.65H4V4h3.21l1.838 10.994h7.137l1.296-7.764h-12.5V5H20V3H5.5l-.21-1.25A1 1 0 0 0 4.29 0H1v2h2.29l3.6 21.5c.08.47.489.82.968.82h12.7c.549 0 1.01-.44 1.05-.988l1.5-8.98c.042-.251-.03-.508-.197-.71-.166-.203-.408-.322-.663-.322H8.725l-.503-3H19c.828 0 1.5-.672 1.5-1.5S19.828 4 19 4H9.473L8.7 2H1V0h7.7c.552 0 1.03.385 1.141.924L9.6 4h9.368c.684 0 1.265.47 1.394 1.13l1.5 8.98a1.5 1.5 0 0 1-1.395 1.87H8.15L6.897 6h10.47z"/></svg>
            </button>
            <a href="register.php" class="btn btn-outline-secondary rounded-pill px-3 py-2 me-2">Daftar</a>
            <a href="login.php" class="btn btn-primary rounded-pill px-3 py-2">Log In</a>
          `;
        }
      }
    });
  </script>

  <main id="home">
    <section class="hero-section py-14">
      <div class="container grid lg:grid-cols-2 gap-8 items-center">
        <div>
          <span class="badge bg-slate-900 text-white mb-3 hero-pill">Promo Terbaru</span>
          <h1 class="display-5 fw-bold mb-4 hero-title">Temukan Smartphone Impianmu<br />dengan Penawaran Spesial</h1>
          <p class="lead text-slate-200 mb-5 hero-text">Dapatkan pilihan smartphone flagship, mid-range, dan budget terbaik lengkap dengan fitur unggulan dan layanan garansi resmi.</p>
          <div class="d-flex gap-2 flex-wrap">
            <a href="#products" class="btn btn-primary btn-lg">Belanja Sekarang</a>
            <a href="admin.php" class="btn btn-outline-light btn-lg">Kelola Produk</a>
          </div>
          <div class="d-flex flex-wrap gap-3 mt-5 hero-feature-list">
            <div class="feature-chip">Gratis Ongkir</div>
            <div class="feature-chip">7 Hari Garansi Pengembalian</div>
            <div class="feature-chip">Cicilan 0%</div>
          </div>
        </div>
        <div class="position-relative hero-card overflow-hidden rounded-4 shadow-xl">
          <div class="hero-image-frame"></div>
          <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=900&q=80" alt="Smartphone display" class="w-100 hero-img" />
          <div class="hero-overlay p-4 text-white">
            <h2 class="fs-4 fw-bold">Smartphone Terbaru</h2>
            <p>Kamera profesional, baterai tahan lama, performa kencang.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="container py-6" id="features">
      <div class="row g-4 text-center mb-5">
        <div class="col-md-4">
          <div class="feature-card p-4 shadow-sm rounded-4 h-100">
            <h3 class="h5 mb-3">Pengiriman Cepat</h3>
            <p class="text-slate-600">Pesanan dikirim dalam 24 jam untuk wilayah Jabodetabek.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-card p-4 shadow-sm rounded-4 h-100">
            <h3 class="h5 mb-3">Harga Kompetitif</h3>
            <p class="text-slate-600">Bandingkan semua pilihan smartphone dalam satu halaman.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-card p-4 shadow-sm rounded-4 h-100">
            <h3 class="h5 mb-3">Support 24/7</h3>
            <p class="text-slate-600">Tim kami siap membantu setiap hari untuk pesananmu.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="container py-8" id="products">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
          <h2 class="fw-bold mb-1">Pilihan Smartphone</h2>
          <p class="text-slate-600 mb-0">Kelola dan bandingkan smartphone &ndash; semua dalam satu tempat.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
          <button class="btn btn-outline-primary btn-sm filter-btn active" data-filter="all">Semua</button>
          <button class="btn btn-outline-primary btn-sm filter-btn" data-filter="flagship">Flagship</button>
          <button class="btn btn-outline-primary btn-sm filter-btn" data-filter="midrange">Mid-range</button>
          <button class="btn btn-outline-primary btn-sm filter-btn" data-filter="budget">Budget</button>
        </div>
      </div>

      <div class="row g-4" id="productGrid"></div>
    </section>
  </main>

  <div class="offcanvas offcanvas-end" tabindex="-1" id="cartCanvas" aria-labelledby="cartLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="cartLabel">Keranjang Belanja</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
      <ul class="list-group mb-3" id="cartList"></ul>
      <div class="mt-auto">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="fw-semibold">Total</span>
          <span id="cartTotal" class="fw-bold">Rp0</span>
        </div>
        <button class="btn btn-primary w-100" id="checkoutButton">Checkout</button>
      </div>
    </div>
  </div>

  <footer class="py-4 bg-white mt-8 border-top">
    <div class="container text-center text-slate-600">© 2026 SmartphoneHub. Tampilan modern untuk penjualan smartphone.</div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const API_ENDPOINT = 'api.php';
    const CART_KEY = 'smartphoneCart';

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

    async function fetchProducts(filter = 'all') {
      const params = new URLSearchParams({ action: 'list' });
      if (filter !== 'all') {
        params.set('category', filter);
      }
      const response = await fetch(`${API_ENDPOINT}?${params.toString()}`);
      return response.ok ? await response.json() : [];
    }

    async function renderProducts(filter = 'all') {
      const grid = document.getElementById('productGrid');
      const products = await fetchProducts(filter);

      if (!products.length) {
        grid.innerHTML = `<div class="col-12 text-center py-5"><p class="mb-0 text-slate-600">Tidak ada produk.</p></div>`;
        return;
      }

      grid.innerHTML = products.map(p => `
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 shadow-sm border-0 overflow-hidden">
            <img src="${p.image}" class="card-img-top" alt="${p.name}" style="height:260px; object-fit:cover;" />
            <div class="card-body d-flex flex-column">
              <span class="badge bg-primary mb-2 text-uppercase">${p.category}</span>
              <h5 class="card-title">${p.name}</h5>
              <p class="card-text text-slate-600 flex-grow-1">${p.description}</p>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <strong class="text-slate-900">Rp${formatPrice(p.price)}</strong>
                <span class="text-slate-500">Stok ${p.stock}</span>
              </div>
              <div class="d-flex gap-2">
                <a href="product.php?id=${p.id}" class="btn btn-outline-secondary flex-grow-1">Lihat Detail</a>
                <button class="btn btn-outline-primary add-to-cart" data-id="${p.id}" ${p.stock === 0 ? 'disabled' : ''}>Keranjang</button>
              </div>
            </div>
          </div>
        </div>
      `).join('');

      document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
          const id = this.dataset.id;
          const prod = products.find(x => Number(x.id) === Number(id));
          const cart = getCart();
          const existing = cart.find(x => Number(x.id) === Number(id));
          if (existing) existing.qty++; else cart.push({id, name: prod.name, price: prod.price, qty: 1});
          saveCart(cart);
          renderCart();
          bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('cartCanvas')).show();
        });
      });
    }

    function renderCart() {
      const cart = getCart();
      const list = document.getElementById('cartList');
      const total = document.getElementById('cartTotal');

      if (!cart.length) {
        list.innerHTML = `<li class="list-group-item text-center text-slate-600">Keranjang kosong.</li>`;
        total.textContent = 'Rp0';
        return;
      }

      let sum = 0;
      list.innerHTML = cart.map(item => {
        const subtotal = item.price * item.qty;
        sum += subtotal;
        return `<li class="list-group-item d-flex justify-content-between"><div><strong>${item.name}</strong><div class="small text-slate-600">${item.qty} x Rp${formatPrice(item.price)}</div></div><span class="fw-semibold">Rp${formatPrice(subtotal)}</span></li>`;
      }).join('');
      total.textContent = `Rp${formatPrice(sum)}`;
    }

    document.querySelectorAll('.filter-btn').forEach(btn => {
      btn.addEventListener('click', async function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        await renderProducts(this.dataset.filter);
      });
    });

    document.getElementById('checkoutButton').addEventListener('click', () => {
      if (!getCart().length) alert('Keranjang kosong.');
      else window.location.href = 'checkout.php';
    });

    renderProducts();
    renderCart();
  </script>
</body>
</html>
