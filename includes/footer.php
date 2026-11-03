<?php
$currentScript = $_SERVER['SCRIPT_NAME'] ?? '';
$basePrefix = (strpos($currentScript, '/admin/') !== false || strpos($currentScript, '/auth/') !== false) ? '../' : '';
?>
<footer class="footer-tulip mt-5">
  <div class="container">
    <div class="row g-4 mb-4">
      <div class="col-lg-4 col-md-6">
        <h5 class="text-white font-serif mb-3 d-flex align-items-center gap-2">
          <span>🌷</span> Tulip Haven
        </h5>
        <p class="small text-secondary">
          Website ensiklopedia, panduan budidaya, dan galeri keindahan bunga tulip dunia. Dibuat dengan dedikasi untuk mengapresiasi pesona flora dan keanggunan abadi bunga musim semi.
        </p>
        <div class="d-flex gap-3 text-secondary">
          <span class="badge bg-light text-dark"><i class="fas fa-lock me-1 text-success"></i> HTTPS Terenkripsi</span>
          <span class="badge bg-light text-dark"><i class="fas fa-database me-1 text-primary"></i> MySQL Database</span>
        </div>
      </div>
      
      <div class="col-lg-2 col-md-6">
        <h6 class="text-white fw-bold mb-3">Tautan Cepat</h6>
        <ul class="list-unstyled small">
          <li class="mb-2"><a href="<?= $basePrefix ?>index.php">Beranda</a></li>
          <li class="mb-2"><a href="<?= $basePrefix ?>catalog.php">Katalog Varietas</a></li>
          <li class="mb-2"><a href="<?= $basePrefix ?>about.php">Sejarah Tulip</a></li>
          <li class="mb-2"><a href="<?= $basePrefix ?>index.php#perawatan">Tips Perawatan</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-6">
        <h6 class="text-white fw-bold mb-3">Kategori Populer</h6>
        <ul class="list-unstyled small text-secondary">
          <li class="mb-2">🌷 Darwin Hybrid Tulips</li>
          <li class="mb-2">🌷 Triumph Tulips</li>
          <li class="mb-2">🌷 Single Late Tulips</li>
          <li class="mb-2">🌷 Parrot & Peony Tulips</li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-6">
        <h6 class="text-white fw-bold mb-3">Info Tugas & Autentikasi</h6>
        <p class="small text-secondary mb-2">
          Website ini dilengkapi panel Admin untuk CRUD data varietas tulip dan manajemen pesanan.
        </p>
        <div class="p-2 bg-dark rounded border border-secondary small text-white">
          <div><i class="fas fa-user-shield me-1 text-warning"></i> <strong>Admin Login:</strong> <code>admin</code></div>
          <div><i class="fas fa-key me-1 text-warning"></i> <strong>Password:</strong> <code>admin123</code></div>
        </div>
      </div>
    </div>

    <hr class="border-secondary my-4">

    <div class="row align-items-center">
      <div class="col-md-6 text-center text-md-start small text-secondary">
        &copy; <?= date('Y') ?> Tulip Haven. Dibuat untuk Tugas Pemrograman Web / IT.
      </div>
      <div class="col-md-6 text-center text-md-end small text-secondary mt-2 mt-md-0">
        Status Server: <span class="badge bg-success"><i class="fas fa-check-circle"></i> Database Connected</span>
      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
