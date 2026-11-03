<?php
require_once __DIR__ . '/../setup/connection.php';

$error = '';
$success = '';

if (isset($_SESSION['user_id'])) {
    if (($_SESSION['user_role'] ?? '') === 'admin') {
        header("Location: ../admin/index.php");
    } else {
        header("Location: ../index.php");
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($usernameOrEmail) || empty($password)) {
        $error = 'Silakan isi username/email dan password Anda.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `username` = ? OR `email` = ? LIMIT 1");
            $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
            $user = $stmt->fetch();

            if ($user && (password_verify($password, $user['password']) || $password === $user['password'])) {
                // Login sukses
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['valid']     = true;

                if ($user['role'] === 'admin') {
                    header("Location: ../admin/index.php");
                } else {
                    header("Location: ../index.php");
                }
                exit;
            } else {
                $error = 'Username atau Password salah! Silakan coba lagi.';
            }
        } catch (PDOException $e) {
            $error = 'Terjadi kesalahan sistem: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk - Tulip Haven</title>
  
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
    <div class="col-md-6 col-lg-5">
      <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="p-4 p-sm-5 bg-white">
          <div class="text-center mb-4">
            <span class="fs-1">🌷</span>
            <h3 class="fw-bold font-serif mt-2 mb-1">Selamat Datang</h3>
            <p class="text-muted small">Masuk ke akun Tulip Haven Anda</p>
          </div>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show small" role="alert">
              <i class="fas fa-exclamation-circle me-1"></i> <?= htmlspecialchars($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <form action="login.php" method="POST">
            <div class="mb-3">
              <label class="form-label small fw-semibold">Username atau Email</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                <input type="text" name="username" id="loginUsername" class="form-control border-start-0" placeholder="admin atau mina" required>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label small fw-semibold">Password</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                <input type="password" name="password" id="loginPassword" class="form-control border-start-0" placeholder="••••••••" required>
              </div>
            </div>

            <button type="submit" class="btn btn-tulip w-100 py-2 fw-semibold shadow-sm mb-3">
              <i class="fas fa-sign-in-alt me-1"></i> Masuk Sekarang
            </button>
          </form>

          <!-- Quick Test Credentials Box (Sangat membantu dosen & penguji tugas IT) -->
          <div class="p-3 bg-light rounded-3 border small">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <strong class="text-secondary"><i class="fas fa-key text-warning me-1"></i> Akun Pengujian (Testing):</strong>
            </div>
            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
              <span>Admin: <code>admin</code> / <code>admin123</code></span>
              <button type="button" class="btn btn-xs btn-outline-danger btn-sm py-0 px-2" onclick="quickFill('admin', 'admin123')">Gunakan</button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-2">
              <span>User: <code>mina</code> / <code>user123</code></span>
              <button type="button" class="btn btn-xs btn-outline-primary btn-sm py-0 px-2" onclick="quickFill('mina', 'user123')">Gunakan</button>
            </div>
          </div>

          <div class="text-center mt-4 small text-muted">
            Belum punya akun? <a href="register.php" class="text-danger fw-semibold text-decoration-none">Daftar di sini</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function quickFill(user, pass) {
  document.getElementById('loginUsername').value = user;
  document.getElementById('loginPassword').value = pass;
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
