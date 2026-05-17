<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar - TechStore</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="styles.css" />
</head>
<body class="min-vh-100 d-flex align-items-center">
  <div class="container">
    <div class="mx-auto" style="max-width:520px;">
      <div class="card shadow-sm">
        <div class="card-body p-4">
        <div class="logo">
          <h1 style="color:var(--primary);">📱 TechStore</h1>
          <p style="color:var(--muted);">Bergabunglah dengan Komunitas Kami</p>
        </div>

        <h5 class="card-title mb-4 text-center fw-bold page-title">Buat Akun Baru</h5>

        <form id="registerForm">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Pilih username unik" required />
            <small class="text-muted">Minimal 3 karakter, hanya huruf, angka, dan underscore</small>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Masukkan email Anda" required />
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Minimal 6 karakter" required />
          </div>

          <div class="mb-4">
            <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control form-control-lg" id="confirmPassword" name="confirmPassword" placeholder="Ulangi password Anda" required />
          </div>

          <button type="submit" class="btn btn-primary w-100">Daftar</button>
        </form>

        <div class="divider">atau</div>

        <div class="login-link">
          Sudah punya akun? <a href="login.php">Masuk di sini</a>
        </div>

        <div class="text-center mt-4" style="font-size: 13px; color: #999;">
          <p class="mb-1">Dengan mendaftar, Anda menyetujui Syarat &amp; Ketentuan kami</p>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="app.js"></script>
</body>
</html>
