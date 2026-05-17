<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Logout - TechStore</title>
</head>
<body>
  <script>
    localStorage.removeItem('jwtToken');
    window.location.href = 'index.php';
  </script>
  <noscript>
    <p>Silakan kembali ke <a href="index.php">halaman utama</a> dan hapus token manual jika diperlukan.</p>
  </noscript>
</body>
</html>
