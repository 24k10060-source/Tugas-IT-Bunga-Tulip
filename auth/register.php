<?php
require_once __DIR__ . '/../setup/connection.php';

$error = '';
$success = '';

if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($name) || empty($email) || empty($username) || empty($password)) {
        $error = 'Semua kolom wajib diisi!';
    } elseif (strlen($password) < 4) {
        $error = 'Password minimal 4 karakter.';
    } else {
        try {
            // Cek apakah username atau email sudah ada
            $stmtCheck = $pdo->prepare("SELECT `id` FROM `users` WHERE `username` = ? OR `email` = ?");
            $stmtCheck->execute([$username, $email]);
            if ($stmtCheck->rowCount() > 0) {
                $error = 'Username atau Email sudah terdaftar. Silakan gunakan yang lain.';
            } else {
                $hashedPass = password_hash($password, PASSWORD_DEFAULT);
                $stmtInsert = $pdo->prepare("INSERT INTO `users` (`name`, `email`, `username`, `password`, `role`) VALUES (?, ?, ?, ?, 'user')");
                $stmtInsert->execute([$name, $email, $username, $hashedPass]);

                $success = 'Pendaftaran berhasil! Silakan masuk dengan akun baru Anda.';
            }
        } catch (PDOException $e) {
            $error = 'Gagal mendaftar: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun Baru - Tulip Haven</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../setup/style.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="container my-auto py-5">
  <div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
      <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="p-4 p-sm-5 bg-white">
          <div class="text-center mb-4">
            <span class="fs-1">🌷</span>
            <h3 class="fw-bold font-serif mt-2 mb-1">Daftar Akun Baru</h3>
            <p class="text-muted small">Bergabung dengan komunitas pecinta botani Tulip Haven</p>
          </div>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show small" role="alert">
              <i class="fas fa-exclamation-circle me-1"></i> <?= htmlspecialchars($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show small" role="alert">
              <i class="fas fa-check-circle me-1"></i> <?= htmlspecialchars($success) ?>
              <div class="mt-2">
                <a href="login.php" class="btn btn-sm btn-success">Ke Halaman Login</a>
              </div>
            </div>
          <?php else: ?>
            <form action="register.php" method="POST">
              <div class="mb-3">
                <label class="form-label small fw-semibold">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" placeholder="Nama Anda" required>
              </div>

              <div class="mb-3">
                <label class="form-label small fw-semibold">Alamat Email</label>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
              </div>

              <div class="mb-3">
                <label class="form-label small fw-semibold">Username</label>
                <input type="text" name="username" class="form-control" placeholder="username unik" required>
              </div>

              <div class="mb-4">
                <label class="form-label small fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 4 karakter" required>
              </div>

              <button type="submit" class="btn btn-tulip w-100 py-2 fw-semibold shadow-sm mb-3">
                <i class="fas fa-user-plus me-1"></i> Daftar Sekarang
              </button>
            </form>
          <?php endif; ?>

          <div class="text-center mt-3 small text-muted">
            Sudah memiliki akun? <a href="login.php" class="text-danger fw-semibold text-decoration-none">Masuk di sini</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
