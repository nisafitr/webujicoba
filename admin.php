<?php
require_once __DIR__ . '/middleware.php';
requireAdminPage();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin - TechStore</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="styles.css" />
</head>
<body class="bg-slate-50 text-slate-900">
  <header class="topbar bg-white shadow-sm sticky-top">
    <div class="container d-flex justify-content-between align-items-center py-2">
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-light btn-sm d-md-none" id="sidebarToggle" aria-label="Toggle menu">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M2.5 12.5a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-11zm0-4a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-11zm0-4a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-11z"/></svg>
        </button>
        <a href="index.php" class="fs-4 fw-bold" style="color:var(--primary); text-decoration:none;">Admin TechStore</a>
        <button class="btn btn-light btn-sm ms-2 d-none d-md-inline" id="sidebarCollapseToggle" title="Toggle sidebar">▸</button>
      </div>
      <div class="d-flex gap-2 align-items-center">
        <a href="index.php" class="btn btn-outline-primary btn-sm d-none d-md-inline">Kembali ke Store</a>
        <button class="btn btn-outline-danger btn-sm d-none d-md-inline logout-btn">Logout</button>
        <div class="d-md-none dropdown">
          <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">Menu</button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="index.php">Kembali ke Store</a></li>
            <li><a class="dropdown-item logout-btn-mobile" href="#">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>

  <div class="admin-layout d-md-flex">
    <aside class="sidebar bg-white d-none d-md-block" id="sidebar">
      <div class="sidebar-brand">
        <div class="brand-title">Admin Menu</div>
      </div>
      <nav class="nav flex-column px-3">
        <a href="admin.php" class="nav-link active">
          <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M8 3.293l6 6V15a1 1 0 0 1-1 1h-3v-4H6v4H3a1 1 0 0 1-1-1V9.293l6-6z"/></svg>
          <span class="nav-text">Dashboard</span>
        </a>
        <a href="manage_products.php" class="nav-link">
          <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M0 1h2l1 9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-6H4"/></svg>
          <span class="nav-text">Kelola Produk</span>
        </a>
        <a href="#" class="nav-link logout-btn">
          <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M6 2a1 1 0 0 0-1 1v2h1V3h6v10H6v-2H5v2a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H6z"/><path d="M2 8h7" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
          <span class="nav-text">Logout</span>
        </a>
      </nav>
    </aside>
    <div class="admin-content flex-fill">
      <main class="container py-8">
        <div class="dashboard-header">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
            <div>
              <h1 class="fw-bold">Dashboard Admin</h1>
              <p class="text-slate-600">Kelola produk dan lihat ringkasan pesanan yang masuk.</p>
            </div>
            <div class="d-flex gap-2">
              <a href="manage_products.php" class="btn btn-outline-primary btn-sm">Kelola Produk</a>
              <a href="index.php" class="btn btn-outline-secondary btn-sm">Lihat Store</a>
            </div>
          </div>
        </div>

        <div class="row g-3 stats-row mb-4">
          <div class="col-md-4">
            <div class="card stat-card p-4">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="stat-label">Total Produk</div>
                  <div id="statsProducts" class="stat-value">0</div>
                </div>
                <div class="stat-chip">
                  <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="24" height="24"><path d="M6 1a1 1 0 0 0-1 1v1H3.5a.5.5 0 0 0-.5.5v2H1.5a.5.5 0 0 0-.5.5v5a.5.5 0 0 0 .5.5H2v1a1 1 0 0 0 1 1h1v1h6v-1h1a1 1 0 0 0 1-1v-1h.5a.5.5 0 0 0 .5-.5v-5a.5.5 0 0 0-.5-.5H13V3.5a.5.5 0 0 0-.5-.5H11V2a1 1 0 0 0-1-1H6Zm1 3V2h2v2H7Zm-3 2H2v4h2V6Zm8 4h2V6h-2v4Zm-8 2H3v-1h4v1H6Zm6 0h-4v-1h4v1Z"/></svg>
                </div>
              </div>
              <div class="stat-note">Ringkasan stok produk.</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card stat-card p-4">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="stat-label">Pesanan</div>
                  <div id="statsOrders" class="stat-value">0</div>
                </div>
                <div class="stat-chip">
                  <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="24" height="24"><path d="M8 1.5a.5.5 0 0 0-1 0V2H3.5A1.5 1.5 0 0 0 2 3.5v9A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V3.5A1.5 1.5 0 0 0 12.5 2H9v-.5Zm-1 2V2h1v1.5H7Zm-3.5 1h9a.5.5 0 0 1 .5.5V7H3V5.5a.5.5 0 0 1 .5-.5Zm0 3h9v3.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5V8.5Zm.5 4.5h8v.5a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-.5Z"/></svg>
                </div>
              </div>
              <div class="stat-note">Pesanan masuk terakhir.</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card stat-card p-4">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="stat-label">Pendapatan</div>
                  <div id="statsRevenue" class="stat-value">Rp0</div>
                </div>
                <div class="stat-chip">
                  <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="24" height="24"><path d="M8 1C4.686 1 2 3.686 2 7s2.686 6 6 6 6-2.686 6-6-2.686-6-6-6Zm0 1a5 5 0 1 1 0 10A5 5 0 0 1 8 2Zm.75 2.764a.75.75 0 0 0-1.5 0v.741c-.419.07-.81.227-1.146.46-.508.348-.792.845-.792 1.544h1.5c0-.211.1-.356.227-.445.161-.11.42-.176.711-.176.32 0 .656.09.872.28.242.216.377.556.377 1.01 0 .489-.163.853-.442 1.085-.339.281-.863.443-1.488.556l-.16.028c-.509.087-.937.172-1.232.355-.292.182-.516.455-.516.873 0 .514.393.86.83.96v.756a.75.75 0 0 0 1.5 0v-.695c.46-.065.96-.196 1.35-.444.57-.366.877-.95.877-1.622 0-.569-.206-1.05-.562-1.352-.326-.269-.821-.462-1.35-.555l-.169-.036c-.578-.129-1.106-.23-1.445-.467-.216-.146-.354-.356-.354-.636 0-.333.18-.548.41-.668.301-.154.682-.232 1.11-.232.407 0 .888.06 1.285.204v.745Z"/></svg>
                </div>
              </div>
              <div class="stat-note">Total pendapatan pesanan.</div>
            </div>
          </div>
        </div>

        <div class="row g-4 mb-4">
          <div class="col-12">
            <div class="card shadow-sm rounded-4 p-4 chart-card">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <h2 class="h5 mb-1">Tren Penjualan</h2>
                  <p class="text-slate-600 mb-0">Ringkasan pendapatan dan pesanan 7 hari terakhir.</p>
                </div>
              </div>
              <canvas id="salesTrendChart" height="160"></canvas>
            </div>
          </div>
        </div>

        <div class="row g-4 mb-4">
      <div class="col-lg-5">
        <div class="card shadow-sm p-4 rounded-4">
          <h2 class="h5 mb-3">Form Produk</h2>
          <form id="productForm">
            <input type="hidden" id="productId" />
            <div class="mb-3">
              <label class="form-label">Nama Produk</label>
              <input type="text" class="form-control" id="productName" placeholder="Contoh: Galaxy S24" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Kategori</label>
              <select class="form-select" id="productCategory" required>
                <option value="flagship">Flagship</option>
                <option value="midrange">Mid-range</option>
                <option value="budget">Budget</option>
              </select>
            </div>
            <div class="mb-3 row gx-2">
              <div class="col-6">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" class="form-control" id="productPrice" placeholder="5000000" required />
              </div>
              <div class="col-6">
                <label class="form-label">Stok</label>
                <input type="number" class="form-control" id="productStock" placeholder="10" required />
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">URL Gambar</label>
              <input type="url" class="form-control" id="productImage" placeholder="https://..." required />
            </div>
            <div class="mb-3">
              <label class="form-label">Deskripsi Singkat</label>
              <textarea class="form-control" id="productDescription" rows="3" placeholder="Deskripsi produk..." required></textarea>
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">Simpan Produk</button>
              <button type="button" class="btn btn-outline-secondary" id="resetForm">Reset</button>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="card shadow-sm rounded-4 p-3">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 mb-0">Daftar Produk</h2>
            <span id="productCount" class="badge bg-primary">0 Produk</span>
          </div>
          <div class="table-responsive responsive-table-wrap">
            <table class="table align-middle">
              <thead class="table-light">
                <tr>
                  <th>Preview</th>
                  <th>Nama</th>
                  <th>Harga</th>
                  <th>Stok</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="adminTable"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm rounded-4 p-3">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 mb-0">Riwayat Pesanan</h2>
        <span class="badge bg-success" id="orderCount">0 Pesanan</span>
      </div>
          <div class="table-responsive responsive-table-wrap">
            <table class="table align-middle">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Pelanggan</th>
                  <th>Kontak</th>
                  <th>Total</th>
                  <th>Detail Item</th>
                  <th>Status</th>
                  <th>Waktu</th>
                </tr>
              </thead>
              <tbody id="orderTable"></tbody>
            </table>
          </div>
    </div>
      </main>
    </div>
  </div>
  <div class="sidebar-overlay"></div>

  <footer class="py-4 bg-white border-top text-center">
    <div class="container text-slate-600">Admin panel sederhana untuk manajemen smartphone dan pemesanan.</div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" integrity="sha384-GAwx6LMbPaB1P7PZ1CAHgN+QYxWz8Fio9NyveQZTJdhyOQvriRV+1or8zDh3Kf7z" crossorigin="anonymous"></script>
  <script src="app.js"></script>
  <script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarCollapseToggle = document.getElementById('sidebarCollapseToggle');

    const storedSidebarCollapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';
    const updateCollapseButton = (collapsed) => {
      if (!sidebarCollapseToggle) return;
      sidebarCollapseToggle.textContent = collapsed ? '»' : '◂';
    };

    if (sidebar && storedSidebarCollapsed) {
      sidebar.classList.add('collapsed');
      updateCollapseButton(true);
    }

    sidebarToggle?.addEventListener('click', () => {
      if (!sidebar) return;
      sidebar.classList.remove('d-none', 'hidden');
      sidebar.classList.add('open');
      overlay?.classList.add('active');
    });

    overlay?.addEventListener('click', () => {
      if (!sidebar) return;
      sidebar.classList.remove('open');
      sidebar.classList.add('d-none');
      overlay.classList.remove('active');
    });

    sidebarCollapseToggle?.addEventListener('click', () => {
      if (!sidebar) return;
      const collapsed = sidebar.classList.toggle('collapsed');
      updateCollapseButton(collapsed);
      localStorage.setItem('adminSidebarCollapsed', collapsed);
    });


  </script>
</body>
</html>
