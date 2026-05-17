const API_ENDPOINT = 'api.php';
const CART_KEY = 'smartphoneCart';
const TOKEN_KEY = 'jwtToken';
let products = [];

// Fallback dummy data jika API tidak bisa diakses
const FALLBACK_PRODUCTS = [
  {
    id: '1',
    name: 'Samsung Galaxy S24 Ultra',
    category: 'flagship',
    price: 15999000,
    stock: 12,
    image: 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=500&q=60',
    description: 'Smartphone flagship terbaru dengan prosesor Snapdragon 8 Gen 3 dan kamera 200MP'
  },
  {
    id: '2',
    name: 'iPhone 15 Pro',
    category: 'flagship',
    price: 19999000,
    stock: 8,
    image: 'https://images.unsplash.com/photo-1592286927505-1def25115558?auto=format&fit=crop&w=500&q=60',
    description: 'iPhone terbaru dengan chip A17 Pro dan titanium design'
  },
  {
    id: '3',
    name: 'Xiaomi 14 Pro',
    category: 'midrange',
    price: 8999000,
    stock: 15,
    image: 'https://images.unsplash.com/photo-1516321318423-f06f70674e90?auto=format&fit=crop&w=500&q=60',
    description: 'Smartphone premium dengan baterai 5000mAh dan charging cepat 120W'
  },
  {
    id: '4',
    name: 'Redmi Note 13 Pro',
    category: 'midrange',
    price: 4999000,
    stock: 20,
    image: 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=500&q=60',
    description: 'Performa solid dengan display AMOLED dan fast charging 67W'
  },
  {
    id: '5',
    name: 'Poco M6 Pro',
    category: 'budget',
    price: 2999000,
    stock: 25,
    image: 'https://images.unsplash.com/photo-1505228395891-9a51e7e86e81?auto=format&fit=crop&w=500&q=60',
    description: 'Budget smartphone dengan performa baik dan baterai tahan lama'
  },
  {
    id: '6',
    name: 'Oppo A78',
    category: 'budget',
    price: 3499000,
    stock: 18,
    image: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=500&q=60',
    description: 'Smartphone ekonomis dengan baterai 5000mAh dan layar 90Hz'
  }
];

// ========== JWT TOKEN MANAGEMENT ==========
function getToken() {
  return localStorage.getItem(TOKEN_KEY);
}

function setToken(token) {
  localStorage.setItem(TOKEN_KEY, token);
}

function removeToken() {
  localStorage.removeItem(TOKEN_KEY);
  // Hapus cookie JWT juga agar server-side tidak lagi melihat token
  document.cookie = `${TOKEN_KEY}=;path=/;max-age=0;SameSite=Lax`;
}

function isLoggedIn() {
  return !!getToken();
}

/**
 * Make API request with JWT token in header
 */
async function apiCall(endpoint, options = {}) {
  try {
    const token = getToken();
    const headers = options.headers || {};
    
    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }
    if (options.body && typeof options.body === 'object') {
      headers['Content-Type'] = 'application/json';
      options.body = JSON.stringify(options.body);
    }
    
    const response = await fetch(endpoint, { credentials: 'include', ...options, headers });
    
    // Jika unauthorized, redirect ke login
    if (response.status === 401) {
      removeToken();
      window.location.href = 'login.php';
      return null;
    }
    
    return response;
  } catch (error) {
    console.error('API call error:', error);
    return null;
  }
}

// ========== STORAGE MANAGEMENT ==========
function initializeStorage() {
  if (!localStorage.getItem(CART_KEY)) {
    localStorage.setItem(CART_KEY, JSON.stringify([]));
  }
}

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
  try {
    const params = new URLSearchParams({ action: 'list' });
    if (filter !== 'all') {
      params.set('category', filter);
    }
    const response = await apiCall(`${API_ENDPOINT}?${params.toString()}`);
    if (!response || !response.ok) {
      // Jika API gagal, gunakan fallback data
      console.log('API tidak tersedia, menggunakan fallback data');
      return filter === 'all' ? FALLBACK_PRODUCTS : FALLBACK_PRODUCTS.filter(p => p.category === filter);
    }
    return await response.json();
  } catch (error) {
    console.error('Error fetching products:', error);
    // Gunakan fallback data jika terjadi error
    return filter === 'all' ? FALLBACK_PRODUCTS : FALLBACK_PRODUCTS.filter(p => p.category === filter);
  }
}

async function renderProducts(filter = 'all') {
  const productGrid = document.getElementById('productGrid');
  if (!productGrid) return;

  products = await fetchProducts(filter);
  if (!products.length) {
    productGrid.innerHTML = `<div class="col-12 text-center py-5"><p class="mb-0 text-slate-600">Tidak ada produk untuk kategori ini.</p></div>`;
    return;
  }

  productGrid.innerHTML = products.map(product => {
    const discount = Math.floor(Math.random() * 40) + 10; // Random discount 10-50%
    const originalPrice = Math.floor(product.price * (100 / (100 - discount)));
    return `
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="product-card">
          <div class="product-image-wrapper">
            <img src="${product.image}" alt="${product.name}" />
            <div class="discount-badge">${discount}%</div>
          </div>
          <div class="product-info">
            <div class="product-name">${product.name}</div>
            <div>
              <span class="product-price">Rp${formatPrice(product.price)}</span>
              <span class="product-original-price">Rp${formatPrice(originalPrice)}</span>
            </div>
            <div class="product-rating">
              <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
              <span>(${Math.floor(Math.random() * 500) + 50}+ terjual)</span>
            </div>
            <div class="product-store">
              ✓ Toko Resmi | Stok: ${product.stock}
            </div>
          </div>
          <div class="product-actions">
            <button class="btn btn-outline-primary btn-sm add-to-cart" data-id="${product.id}" ${product.stock === 0 ? 'disabled' : ''}>+ Keranjang</button>
            <button class="btn btn-primary btn-sm" ${product.stock === 0 ? 'disabled' : ''}>Beli</button>
          </div>
        </div>
      </div>
    `;
  }).join('');

  document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', () => addToCart(button.dataset.id));
  });
}

function renderCart() {
  const cartList = document.getElementById('cartList');
  const cartTotal = document.getElementById('cartTotal');
  const emptyCart = document.getElementById('emptyCart');
  if (!cartList || !cartTotal) return;

  const cart = getCart();
  if (!cart.length) {
    if (emptyCart) emptyCart.style.display = 'block';
    cartList.innerHTML = '';
    cartTotal.textContent = 'Rp0';
    return;
  }

  if (emptyCart) emptyCart.style.display = 'none';
  let total = 0;
  cartList.innerHTML = cart.map(item => {
    const product = products.find(prod => Number(prod.id) === Number(item.id));
    if (!product) return '';
    const itemTotal = product.price * item.qty;
    total += itemTotal;
    return `
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          <strong>${product.name}</strong>
          <div class="small text-slate-600">${item.qty} x Rp${formatPrice(product.price)}</div>
        </div>
        <span class="fw-semibold">Rp${formatPrice(itemTotal)}</span>
      </li>
    `;
  }).join('');

  cartTotal.textContent = `Rp${formatPrice(total)}`;
}

function addToCart(productId) {
  const product = products.find(prod => Number(prod.id) === Number(productId));
  if (!product || product.stock === 0) return;

  const cart = getCart();
  const existing = cart.find(item => Number(item.id) === Number(productId));
  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({ id: productId, qty: 1 });
  }

  saveCart(cart);
  renderCart();
  const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('cartCanvas'));
  offcanvas.show();
}

async function renderAdminTable() {
  const tableBody = document.getElementById('adminTable');
  const productCount = document.getElementById('productCount');
  if (!tableBody || !productCount) return;

  try {
    products = await fetchProducts();
    productCount.textContent = `${products.length} Produk`;

    if (!products.length) {
      tableBody.innerHTML = `<tr><td colspan="5" class="text-center text-slate-600">Belum ada produk. Tambahkan sekarang.</td></tr>`;
      return;
    }

    tableBody.innerHTML = products.map(product => `
      <tr>
        <td><img src="${product.image}" alt="${product.name}" width="80" class="rounded-3" /></td>
        <td>${product.name}</td>
        <td>Rp${formatPrice(product.price)}</td>
        <td>${product.stock}</td>
        <td>
          <button class="btn btn-sm btn-outline-primary me-2 edit-product" data-id="${product.id}">Edit</button>
          <button class="btn btn-sm btn-outline-danger delete-product" data-id="${product.id}">Hapus</button>
        </td>
      </tr>
    `).join('');

    document.querySelectorAll('.edit-product').forEach(button => {
      button.addEventListener('click', () => loadProductToForm(button.dataset.id));
    });
    document.querySelectorAll('.delete-product').forEach(button => {
      button.addEventListener('click', () => deleteProduct(button.dataset.id));
    });
  } catch (error) {
    console.error('Error rendering admin table:', error);
    tableBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error memuat tabel produk</td></tr>`;
  }
}

function loadProductToForm(productId) {
  const product = products.find(prod => Number(prod.id) === Number(productId));
  if (!product) return;

  document.getElementById('productId').value = product.id;
  document.getElementById('productName').value = product.name;
  document.getElementById('productCategory').value = product.category;
  document.getElementById('productPrice').value = product.price;
  document.getElementById('productStock').value = product.stock;
  document.getElementById('productImage').value = product.image;
  document.getElementById('productDescription').value = product.description;
}

function resetForm() {
  document.getElementById('productId').value = '';
  document.getElementById('productForm').reset();
}

async function deleteProduct(productId) {
  if (!confirm('Hapus produk ini?')) return;
  const response = await apiCall(`${API_ENDPOINT}?action=delete&id=${encodeURIComponent(productId)}`, { method: 'POST' });
  if (!response || !response.ok) {
    alert('Gagal menghapus produk. Silakan coba lagi.');
    return;
  }

  await renderAdminTable();
  await renderProducts();
  await renderDashboardStats();
}

async function handleProductSubmit(event) {
  event.preventDefault();
  const productId = document.getElementById('productId').value;
  const name = document.getElementById('productName').value.trim();
  const category = document.getElementById('productCategory').value;
  const price = Number(document.getElementById('productPrice').value);
  const stock = Number(document.getElementById('productStock').value);
  const image = document.getElementById('productImage').value.trim();
  const description = document.getElementById('productDescription').value.trim();

  if (!name || !image || !description || !price || !stock) {
    alert('Lengkapi semua data produk terlebih dahulu.');
    return;
  }

  const payload = { id: productId || undefined, name, category, price, stock, image, description };
  const response = await apiCall(`${API_ENDPOINT}?action=save`, {
    method: 'POST',
    body: payload
  });

  if (!response) return;
  const result = await response.json();
  if (!response.ok || result.error) {
    alert(result.error || 'Gagal menyimpan produk.');
    return;
  }

  resetForm();
  await renderAdminTable();
  await renderProducts();
  await renderDashboardStats();
}

function applyFilterButtons() {
  document.querySelectorAll('.filter-btn').forEach(button => {
    button.addEventListener('click', async () => {
      document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');
      await renderProducts(button.dataset.filter);
    });
  });
}

async function handleCheckout() {
  const cart = getCart();
  if (!cart.length) {
    alert('Keranjang masih kosong.');
    return;
  }

  const response = await apiCall(`${API_ENDPOINT}?action=checkout`, {
    method: 'POST',
    body: { cart },
  });

  if (!response) return;
  const result = await response.json();

  if (!response.ok || result.error) {
    alert(result.error || 'Checkout gagal. Silakan coba lagi.');
    return;
  }

  saveCart([]);
  renderCart();
  await renderProducts();
  alert('Checkout berhasil! Pesanan Anda telah dicatat.');
}

async function fetchOrders() {
  try {
    const response = await apiCall(`${API_ENDPOINT}?action=orders`);
    if (!response || !response.ok) {
      return [];
    }
    return await response.json();
  } catch (error) {
    console.error('Error fetching orders:', error);
    return [];
  }
}

async function renderAdminOrders() {
  const orderTable = document.getElementById('orderTable');
  const orderCount = document.getElementById('orderCount');
  if (!orderTable || !orderCount) return;

  try {
    const orders = await fetchOrders();
    orderCount.textContent = `${orders.length} Pesanan`;
    if (!orders.length) {
      orderTable.innerHTML = `<tr><td colspan="7" class="text-center text-slate-600">Belum ada pesanan.</td></tr>`;
      return;
    }

    orderTable.innerHTML = orders.map(order => {
      const itemSummary = order.items.map(item => `${item.product_name} x${item.qty}`).join(', ');
      const contact = `${order.customer_phone}<br>${order.customer_email}`;
      return `
        <tr>
          <td><strong>#${order.id}</strong></td>
          <td>${order.customer_name}</td>
          <td>${contact}</td>
          <td class="fw-bold">Rp${formatPrice(order.total)}</td>
          <td><small>${itemSummary}</small></td>
          <td><span class="badge bg-success">${order.status}</span></td>
          <td>${new Date(order.created_at).toLocaleString('id-ID')}</td>
        </tr>
      `;
    }).join('');
  } catch (error) {
    console.error('Error rendering admin orders:', error);
    orderTable.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error memuat pesanan</td></tr>`;
  }
}

async function renderDashboardStats() {
  const statsProducts = document.getElementById('statsProducts');
  const statsOrders = document.getElementById('statsOrders');
  const statsRevenue = document.getElementById('statsRevenue');
  if (!statsProducts || !statsOrders || !statsRevenue) return;

  const products = await fetchProducts();
  const orders = await fetchOrders();
  const totalRevenue = orders.reduce((sum, order) => sum + Number(order.total || 0), 0);

  statsProducts.textContent = products.length;
  statsOrders.textContent = orders.length;
  statsRevenue.textContent = `Rp${formatPrice(totalRevenue)}`;
}

async function renderSalesTrendChart() {
  const canvas = document.getElementById('salesTrendChart');
  if (!canvas || typeof Chart === 'undefined') return;

  const orders = await fetchOrders();
  const dataByDay = {};
  const now = new Date();

  for (let i = 6; i >= 0; i--) {
    const day = new Date(now);
    day.setDate(now.getDate() - i);
    const label = day.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short' });
    dataByDay[label] = { count: 0, revenue: 0 };
  }

  orders.forEach(order => {
    const orderDate = new Date(order.created_at);
    const label = orderDate.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short' });
    if (dataByDay[label]) {
      dataByDay[label].count += 1;
      dataByDay[label].revenue += Number(order.total || 0);
    }
  });

  const labels = Object.keys(dataByDay);
  const countData = labels.map(label => dataByDay[label].count);
  const revenueData = labels.map(label => Number(dataByDay[label].revenue));

  new Chart(canvas, {
    type: 'line',
    data: {
      labels,
      datasets: [
        {
          label: 'Pesanan',
          data: countData,
          borderColor: 'rgba(11,61,145,0.9)',
          backgroundColor: 'rgba(11,61,145,0.16)',
          borderWidth: 2,
          tension: 0.35,
          fill: true,
          pointRadius: 4,
          pointHoverRadius: 6,
          yAxisID: 'y',
        },
        {
          label: 'Pendapatan',
          data: revenueData,
          borderColor: 'rgba(0,128,93,0.9)',
          backgroundColor: 'rgba(0,128,93,0.16)',
          borderWidth: 2,
          tension: 0.35,
          fill: true,
          pointRadius: 4,
          pointHoverRadius: 6,
          yAxisID: 'y1',
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          grid: { display: false },
          ticks: { color: '#334155' },
        },
        y: {
          type: 'linear',
          position: 'left',
          title: { display: true, text: 'Jumlah Pesanan', color: '#334155' },
          ticks: { color: '#334155', precision: 0 },
          grid: { borderDash: [4, 4], color: 'rgba(15,23,42,0.08)' },
        },
        y1: {
          type: 'linear',
          position: 'right',
          title: { display: true, text: 'Pendapatan', color: '#334155' },
          ticks: {
            callback: value => `Rp${formatPrice(value)}`,
            color: '#334155',
          },
          grid: { drawOnChartArea: false },
        },
      },
      plugins: {
        legend: {
          display: true,
          labels: { color: '#334155' },
        },
        tooltip: {
          mode: 'index',
          intersect: false,
          callbacks: {
            label: context => {
              if (context.dataset.label === 'Pendapatan') {
                return `Pendapatan: Rp${formatPrice(context.parsed.y)}`;
              }
              return `${context.dataset.label}: ${context.parsed.y}`;
            },
          },
        },
      },
    },
  });
}

// ========== AUTH HANDLERS ==========
async function handleLogin(event) {
  if (event) event.preventDefault();
  
  const username = document.getElementById('username')?.value.trim();
  const password = document.getElementById('password')?.value.trim();
  
  if (!username || !password) {
    alert('Username dan password harus diisi.');
    return;
  }
  
  const response = await fetch(`${API_ENDPOINT}?action=login`, {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ username, password })
  });
  
  const result = await response.json();
  if (!response.ok || result.error) {
    alert(result.error || 'Login gagal.');
    return;
  }
  
  setToken(result.token);
  // Simpan token juga di cookie agar PHP dapat memvalidasi server-side (admin page)
  // Token berlaku 24 jam di backend; atur max-age sama (86400 detik)
  document.cookie = `${TOKEN_KEY}=${result.token};path=/;max-age=86400;SameSite=Lax`;
  alert('Login berhasil!');
  window.location.href = result.user.role === 'admin' ? 'admin.php' : 'index.php';
}

async function handleRegister(event) {
  if (event) event.preventDefault();
  
  const username = document.getElementById('username')?.value.trim();
  const email = document.getElementById('email')?.value.trim();
  const password = document.getElementById('password')?.value.trim();
  const confirmPassword = document.getElementById('confirmPassword')?.value.trim();
  
  if (!username || !email || !password || !confirmPassword) {
    alert('Semua field harus diisi.');
    return;
  }
  
  if (password !== confirmPassword) {
    alert('Password tidak cocok.');
    return;
  }
  
  const response = await fetch(`${API_ENDPOINT}?action=register`, {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ username, email, password })
  });
  
  const result = await response.json();
  if (!response.ok || result.error) {
    alert(result.error || 'Registrasi gagal.');
    return;
  }
  
  alert('Registrasi berhasil! Silakan login.');
  window.location.href = 'login.php';
}

async function handleLogout() {
  if (!confirm('Yakin ingin logout?')) {
    return;
  }

  try {
    await fetch(`${API_ENDPOINT}?action=logout`, {
      method: 'POST',
      credentials: 'include',
    });
  } catch (error) {
    console.warn('Logout server cleanup gagal:', error);
  }

  removeToken();
  window.location.href = 'index.php';
}

// ========== PAGE INITIALIZATION ==========
async function initPage() {
  initializeStorage();
  
  // Login page handler
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', handleLogin);
    return;
  }
  
  // Register page handler
  const registerForm = document.getElementById('registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', handleRegister);
    return;
  }
  
  // Logout button handlers
  document.querySelectorAll('.logout-btn, .logout-btn-mobile').forEach(btn => {
    btn.addEventListener('click', handleLogout);
  });
  
  // Sidebar toggle handlers untuk admin page
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebarCollapseToggle = document.getElementById('sidebarCollapseToggle');
  const sidebar = document.getElementById('sidebar');
  
  // Mobile sidebar toggle (untuk responsive mobile view)
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', () => {
      sidebar.classList.toggle('open');
      // Close sidebar when clicking on nav links
      sidebar.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
          sidebar.classList.remove('open');
        });
      });
    });
  }
  
  // Desktop sidebar collapse toggle
  if (sidebarCollapseToggle && sidebar) {
    sidebarCollapseToggle.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
      // Update toggle button text
      sidebarCollapseToggle.textContent = sidebar.classList.contains('collapsed') ? '◂' : '▸';
    });
  }
  
  await renderProducts();
  renderCart();
  applyFilterButtons();

  const form = document.getElementById('productForm');
  if (form) {
    form.addEventListener('submit', handleProductSubmit);
    document.getElementById('resetForm').addEventListener('click', resetForm);
    await renderAdminTable();
    await renderAdminOrders();
    await renderDashboardStats();
    await renderSalesTrendChart();
  }

  const checkoutButton = document.getElementById('checkoutButton');
  if (checkoutButton) {
    checkoutButton.addEventListener('click', handleCheckout);
  }
}

window.addEventListener('DOMContentLoaded', initPage);
