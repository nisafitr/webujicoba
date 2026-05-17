<?php
require_once __DIR__ . '/middleware.php';
requireAdminPage();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Kelola Produk - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root{
      --bg: #f6f8fb;
      --card: #ffffff;
      --primary: #0b3d91; /* navy */
      --muted: #6b7280;
      --soft-border: #e9eef6;
    }
    /* Clean background with white cards */
    body { background-color: var(--bg); color: #0f172a; font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
    .card { background: var(--card); border: 1px solid var(--soft-border); border-radius: 12px; }
    /* Primary button: clean navy */
    .btn-primary { background-color: var(--primary); border-color: var(--primary); color: #fff; box-shadow: none; }
    .btn-primary:hover, .btn-primary:focus { background-color: #08306f; border-color: #08306f; }
    /* secondary outlines subtle */
    .btn-outline-secondary { color: var(--muted); border-color: rgba(16,24,40,0.06); }

    /* Table header: light with navy accents */
    table thead th { background: transparent; color: var(--primary); border-bottom: 2px solid var(--soft-border); font-weight: 600; }
    .table td, .table th { vertical-align: middle; }

    /* Typography: clear hierarchy */
    .page-title { font-size: 1.5rem; line-height: 1.1; letter-spacing: -0.02em; }
    .page-sub { color: var(--muted); font-size: 0.95rem; }

    /* Form input sizing and labels */
    .form-label { font-weight: 600; color: #0f172a; font-size: 0.95rem; }
    .form-control { font-size: 1rem; padding: .65rem .75rem; }
    .form-control:focus { box-shadow: 0 0 0 .125rem rgba(11,61,145,0.12); border-color: var(--primary); }

    /* Soft striped rows */
    .table-soft-striped > tbody > tr:nth-of-type(odd) { background-color: #fbfcfe; }

    /* Buttons in action column */
    .btn-sm { padding: .28rem .5rem; font-size: .85rem; }
    .object-cover { object-fit: cover; }
  </style>
</head>
<body>
  <nav class="bg-white shadow-sm">
    <div class="container d-flex justify-content-between align-items-center py-3">
      <a href="index.php" class="fs-5 fw-bold" style="color:var(--primary); text-decoration:none;">TechStore — Admin</a>
      <div>
        <a href="admin.php" class="btn btn-outline-secondary btn-sm me-2">Dashboard</a>
        <a href="index.php" class="btn btn-outline-primary btn-sm">Lihat Toko</a>
      </div>
    </div>
  </nav>

  <main class="container py-6">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="page-title fw-bold mb-0">Kelola Produk</h1>
        <p class="page-sub mb-0">Tambah, edit, atau hapus produk smartphone Anda.</p>
      </div>
      <div>
        <button id="addProductBtn" class="btn btn-primary d-inline-flex align-items-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg me-2" viewBox="0 0 16 16"><path d="M8 1a.5.5 0 0 1 .5.5V7.5H14a.5.5 0 0 1 0 1H8.5V14a.5.5 0 0 1-1 0V8.5H2a.5.5 0 0 1 0-1h5.5V1.5A.5.5 0 0 1 8 1z"/></svg>
          Tambah Produk
        </button>
      </div>
    </div>

    <div class="card shadow-sm p-3">
      <style>
        /* very soft grey for striped rows */
        .table-soft-striped > tbody > tr:nth-of-type(odd) {
          background-color: #fafafa;
        }
        /* ensure actions don't wrap on small screens */
        .text-nowrap { white-space: nowrap; }
      </style>
      <div class="table-responsive">
        <table class="table table-soft-striped align-middle" id="productsTable">
          <thead class="table-dark">
            <tr>
              <th>Nama</th>
              <th>Harga</th>
              <th>Stok</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody id="productsTbody">
            <!-- rows injected here -->
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Modal: Add / Edit Product -->
  <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <form id="productForm">
          <div class="modal-header">
            <h5 class="modal-title" id="productModalTitle">Tambah Produk</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="productId" />
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nama Produk</label>
                <input id="productName" class="form-control form-control-lg" required />
              </div>
              <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <select id="productCategory" class="form-select form-select-lg" required>
                  <option value="flagship">Flagship</option>
                  <option value="midrange">Mid-range</option>
                  <option value="budget">Budget</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Harga (Rp)</label>
                <input id="productPrice" type="number" class="form-control form-control-lg" required />
              </div>
              <div class="col-md-6">
                <label class="form-label">Stok</label>
                <input id="productStock" type="number" class="form-control form-control-lg" required />
              </div>
              <div class="col-12">
                <label class="form-label">URL Gambar</label>
                <input id="productImage" type="url" class="form-control form-control-lg" placeholder="https://..." required />
              </div>
              <div class="col-12">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea id="productDescription" class="form-control form-control-lg" rows="3" required></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal: Confirm Delete -->
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Konfirmasi Hapus</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-0">Yakin ingin menghapus produk ini? Tindakan ini tidak bisa dibatalkan.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Hapus</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const API = 'api.php';
    const token = localStorage.getItem('jwtToken');
    let productsCache = null; // cached product list
    let currentPage = 1;
    const PAGE_SIZE = 10;

    function formatRp(v) {
      return 'Rp' + new Intl.NumberFormat('id-ID').format(v);
    }

    async function fetchProducts() {
      // Use cache if present
      if (productsCache) return productsCache;
      const res = await fetch(`${API}?action=list`, { headers: token ? { 'Authorization': 'Bearer ' + token } : {} });
      if (!res.ok) return [];
      productsCache = await res.json();
      return productsCache;
    }

    function createRow(product) {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>
          <div class="d-flex align-items-center gap-3">
            <img src="${product.image}" alt="${product.name}" width="72" height="48" loading="lazy" class="rounded-2 object-cover" style="object-fit:cover" />
            <div>
              <div class="fw-bold">${product.name}</div>
              <div class="text-muted small">${product.category || ''}</div>
            </div>
          </div>
        </td>
        <td class="fw-semibold">${formatRp(product.price)}</td>
        <td>${product.stock}</td>
        <td class="text-end text-nowrap">
          <button class="btn btn-sm btn-primary btn-edit me-2" data-id="${product.id}" title="Edit">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16"><path d="M12.146.146a.5.5 0 0 1 .708 0l2.0 2.0a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2L3 10.207V13h2.793L14 4.793 11.207 2z"/></svg>
            <span class="d-none d-md-inline ms-1">Edit</span>
          </button>
          <button class="btn btn-sm btn-danger btn-delete" data-id="${product.id}" title="Hapus">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5v-7zM14.5 3a1 1 0 0 1-1 1H12v9a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4H2.5a1 1 0 1 1 0-2H5l.5-1h5l.5 1h2.5a1 1 0 0 1 1 1z"/></svg>
            <span class="d-none d-md-inline ms-1">Hapus</span>
          </button>
        </td>
      `;
      return tr;
    }

    function renderPagination(total) {
      const containerId = 'paginationControls';
      let container = document.getElementById(containerId);
      if (!container) {
        container = document.createElement('div');
        container.id = containerId;
        container.className = 'd-flex justify-content-between align-items-center mt-3';
        document.querySelector('.card').appendChild(container);
      }
      const totalPages = Math.max(1, Math.ceil(total / PAGE_SIZE));
      const pages = [];
      for (let i = 1; i <= totalPages; i++) pages.push(i);

      container.innerHTML = `
        <div class="text-muted">Total: ${total} produk</div>
        <nav aria-label="Page navigation">
          <ul class="pagination pagination-sm mb-0">
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${currentPage-1}">Prev</a></li>
            ${pages.map(p => `<li class="page-item ${p===currentPage?'active':''}"><a class="page-link" href="#" data-page="${p}">${p}</a></li>`).join('')}
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${currentPage+1}">Next</a></li>
          </ul>
        </nav>
      `;

      // pagination click handler
      container.querySelectorAll('.page-link').forEach(a => {
        a.addEventListener('click', (ev) => {
          ev.preventDefault();
          const p = Number(a.dataset.page);
          if (isNaN(p) || p < 1) return;
          currentPage = Math.max(1, p);
          renderTable();
        });
      });
    }

    async function renderTable() {
      const tbody = document.getElementById('productsTbody');
      tbody.innerHTML = '';
      const items = await fetchProducts();
      if (!items || items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">Belum ada produk.</td></tr>';
        renderPagination(0);
        return;
      }

      const total = items.length;
      const start = (currentPage - 1) * PAGE_SIZE;
      const pageItems = items.slice(start, start + PAGE_SIZE);
      pageItems.forEach(p => tbody.appendChild(createRow(p)));
      renderPagination(total);
    }

    // Event delegation for edit/delete buttons
    document.addEventListener('click', (ev) => {
      const editBtn = ev.target.closest && ev.target.closest('.btn-edit');
      if (editBtn) {
        ev.preventDefault();
        openEdit(editBtn.dataset.id);
        return;
      }
      const delBtn = ev.target.closest && ev.target.closest('.btn-delete');
      if (delBtn) {
        ev.preventDefault();
        confirmDelete(delBtn.dataset.id);
        return;
      }
      const pageLink = ev.target.closest && ev.target.closest('.page-link');
      if (pageLink && pageLink.dataset.page) {
        // handled in renderPagination to keep binding local
      }
    });

    function confirmDelete(id) {
      const modalEl = document.getElementById('confirmDeleteModal');
      const modal = new bootstrap.Modal(modalEl);
      const btn = document.getElementById('confirmDeleteBtn');
      btn.dataset.id = id;
      modal.show();
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', async (e) => {
      const id = e.target.dataset.id;
      const modalEl = document.getElementById('confirmDeleteModal');
      const modal = bootstrap.Modal.getInstance(modalEl);
      const res = await fetch(`${API}?action=delete&id=${encodeURIComponent(id)}`, { method: 'POST', headers: token ? { 'Authorization': 'Bearer ' + token } : {} });
      if (!res.ok) { alert('Gagal menghapus produk.'); return; }
      // invalidate cache and re-render
      productsCache = null;
      if (modal) modal.hide();
      await renderTable();
    });

    function openModal() {
      const modal = new bootstrap.Modal(document.getElementById('productModal'));
      modal.show();
    }

    function closeModal() {
      const modalEl = document.getElementById('productModal');
      const modal = bootstrap.Modal.getInstance(modalEl);
      if (modal) modal.hide();
    }

    document.getElementById('addProductBtn').addEventListener('click', () => {
      document.getElementById('productForm').reset();
      document.getElementById('productId').value = '';
      document.getElementById('productModalTitle').textContent = 'Tambah Produk';
      openModal();
    });

    async function openEdit(id) {
      // try to find in cache first
      let items = productsCache;
      if (!items) items = await fetchProducts();
      const p = items.find(x => Number(x.id) === Number(id));
      if (!p) return alert('Produk tidak ditemukan');

      document.getElementById('productId').value = p.id;
      document.getElementById('productName').value = p.name;
      document.getElementById('productCategory').value = p.category || 'flagship';
      document.getElementById('productPrice').value = p.price;
      document.getElementById('productStock').value = p.stock;
      document.getElementById('productImage').value = p.image;
      document.getElementById('productDescription').value = p.description;
      document.getElementById('productModalTitle').textContent = 'Edit Produk';
      openModal();
    }

    async function removeProduct(id) {
      // deprecated; kept for backward compatibility
      const res = await fetch(`${API}?action=delete&id=${encodeURIComponent(id)}`, { method: 'POST', headers: token ? { 'Authorization': 'Bearer ' + token } : {} });
      if (!res.ok) return alert('Gagal menghapus produk.');
      productsCache = null;
      await renderTable();
    }

    document.getElementById('productForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const payload = {
        id: document.getElementById('productId').value || undefined,
        name: document.getElementById('productName').value.trim(),
        price: Number(document.getElementById('productPrice').value),
        stock: Number(document.getElementById('productStock').value),
        image: document.getElementById('productImage').value.trim(),
        description: document.getElementById('productDescription').value.trim(),
        category: document.getElementById('productCategory').value
      };

      const res = await fetch(`${API}?action=save`, {
        method: 'POST',
        headers: Object.assign({ 'Content-Type': 'application/json' }, token ? { 'Authorization': 'Bearer ' + token } : {}),
        body: JSON.stringify(payload)
      });
      const data = await res.json();
      if (!res.ok || data.error) return alert(data.error || 'Gagal menyimpan produk.');
      closeModal();
      await renderTable();
    });

    // initial load
    renderTable();
  </script>
</body>
</html>
