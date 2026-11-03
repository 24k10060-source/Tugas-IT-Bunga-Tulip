<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Deteksi path dasar aplikasi (berguna jika di subfolder localhost vs root cloud)
$baseUrl = '';
$currentScript = $_SERVER['SCRIPT_NAME'] ?? '';
if (strpos($currentScript, '/admin/') !== false || strpos($currentScript, '/auth/') !== false) {
    $basePrefix = '../';
} else {
    $basePrefix = '';
}
?>
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-tulip sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand navbar-brand-tulip d-flex align-items-center gap-2" href="<?= $basePrefix ?>index.php">
      <span style="font-size: 1.6rem;">🌷</span>
      <span>Tulip Haven</span>
    </a>
    
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
        <li class="nav-item">
          <a class="nav-link" href="<?= $basePrefix ?>index.php">Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= $basePrefix ?>catalog.php">Katalog Tulip</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= $basePrefix ?>about.php">Filosofi & Sejarah</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= $basePrefix ?>index.php#perawatan">Perawatan</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= $basePrefix ?>index.php#pesan">Pesan Bunga</a>
        </li>
      </ul>

      <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
              <i class="fas fa-user-circle"></i>
              <span><?= htmlspecialchars($_SESSION['user_name'] ?? 'Akun Saya') ?></span>
              <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                <span class="badge bg-danger ms-1" style="font-size: 0.7rem;">Admin</span>
              <?php endif; ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
              <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                <li><a class="dropdown-item" href="<?= $basePrefix ?>admin/index.php"><i class="fas fa-chart-pie me-2 text-danger"></i>Dashboard Admin</a></li>
                <li><a class="dropdown-item" href="<?= $basePrefix ?>admin/tulips.php"><i class="fas fa-seedling me-2 text-success"></i>Kelola Tulip</a></li>
                <li><a class="dropdown-item" href="<?= $basePrefix ?>admin/orders.php"><i class="fas fa-shopping-bag me-2 text-primary"></i>Kelola Pesanan</a></li>
                <li><hr class="dropdown-divider"></li>
              <?php endif; ?>
              <li><a class="dropdown-item text-danger" href="<?= $basePrefix ?>auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Keluar (Logout)</a></li>
            </ul>
          </div>
        <?php else: ?>
          <a href="<?= $basePrefix ?>auth/login.php" class="btn btn-tulip-outline btn-sm px-3">
            <i class="fas fa-sign-in-alt me-1"></i> Masuk
          </a>
          <a href="<?= $basePrefix ?>auth/register.php" class="btn btn-tulip btn-sm px-3">
            <i class="fas fa-user-plus me-1"></i> Daftar
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
