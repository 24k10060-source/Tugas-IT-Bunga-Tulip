<?php
require_once __DIR__ . '/setup/connection.php';

// Filter parameter
$search = trim($_GET['q'] ?? '');
$colorFilter = trim($_GET['color'] ?? '');
$categoryFilter = trim($_GET['category'] ?? '');

$sql = "SELECT * FROM `tulips` WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (`name` LIKE ? OR `meaning` LIKE ? OR `scientific_name` LIKE ?)";
    $term = "%$search%";
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
}

if (!empty($colorFilter)) {
    $sql .= " AND `color` = ?";
    $params[] = $colorFilter;
}

if (!empty($categoryFilter)) {
    $sql .= " AND `category` = ?";
    $params[] = $categoryFilter;
}

$sql .= " ORDER BY `id` DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tulips = $stmt->fetchAll();

// Ambil list warna & kategori unik untuk filter dropdown
$colors = $pdo->query("SELECT DISTINCT `color` FROM `tulips` ORDER BY `color` ASC")->fetchAll(PDO::FETCH_COLUMN);
$categories = $pdo->query("SELECT DISTINCT `category` FROM `tulips` ORDER BY `category` ASC")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Katalog Lengkap Bunga Tulip - Tulip Haven</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="setup/style.css" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<div class="bg-light py-5 border-bottom">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h1 class="display-5 fw-bold font-serif mb-2">Katalog Bunga Tulip</h1>
        <p class="text-muted mb-0">Temukan pesona setiap varietas bunga tulip dari berbagai kultivar dunia.</p>
      </div>
      <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
          <i class="fas fa-check-circle text-success me-1"></i> Total Tersedia: <?= count($tulips) ?> Varietas
        </span>
      </div>
    </div>
  </div>
</div>

<div class="container my-5">
  <!-- Filter & Search Bar -->
  <div class="card border-0 shadow-sm rounded-4 p-4 mb-5">
    <form method="GET" action="catalog.php" class="row g-3">
      <div class="col-md-4">
        <label class="form-label small fw-semibold">Pencarian Nama / Makna</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
          <input type="text" name="q" class="form-control" placeholder="Cari tulip..." value="<?= htmlspecialchars($search) ?>">
        </div>
      </div>

      <div class="col-md-3">
        <label class="form-label small fw-semibold">Warna</label>
        <select name="color" class="form-select">
          <option value="">Semua Warna</option>
          <?php foreach ($colors as $c): ?>
            <option value="<?= htmlspecialchars($c) ?>" <?= $colorFilter === $c ? 'selected' : '' ?>>
              <?= htmlspecialchars($c) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label small fw-semibold">Kategori Kultivar</label>
        <select name="category" class="form-select">
          <option value="">Semua Kategori</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>" <?= $categoryFilter === $cat ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-2 d-flex align-items-end gap-2">
        <button type="submit" class="btn btn-tulip w-100">
          <i class="fas fa-filter me-1"></i> Filter
        </button>
        <?php if (!empty($search) || !empty($colorFilter) || !empty($categoryFilter)): ?>
          <a href="catalog.php" class="btn btn-outline-secondary" title="Reset Filter">
            <i class="fas fa-undo"></i>
          </a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <!-- Tulip Cards Grid -->
  <div class="row g-4">
    <?php if (!empty($tulips)): ?>
      <?php foreach ($tulips as $t): ?>
        <div class="col-lg-4 col-md-6">
          <div class="tulip-card h-100 d-flex flex-column">
            <div class="tulip-card-img-wrap">
              <img src="<?= htmlspecialchars($t['image_url']) ?>" alt="<?= htmlspecialchars($t['name']) ?>" class="tulip-card-img" loading="lazy">
              <span class="badge-color-pill">
                <i class="fas fa-tint me-1 text-danger"></i> <?= htmlspecialchars($t['color']) ?>
              </span>
            </div>
            <div class="card-body p-4 d-flex flex-column flex-grow-1">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                  <h5 class="card-title fw-bold mb-0 font-serif"><?= htmlspecialchars($t['name']) ?></h5>
                  <small class="text-muted fst-italic"><?= htmlspecialchars($t['scientific_name']) ?></small>
                </div>
                <span class="badge bg-light text-secondary border"><?= htmlspecialchars($t['category']) ?></span>
              </div>
              
              <p class="card-text small text-secondary my-3 flex-grow-1">
                <?= htmlspecialchars($t['meaning']) ?>
              </p>

              <div class="d-flex justify-content-between align-items-center border-top pt-3">
                <div>
                  <span class="text-muted small d-block">Stok: <strong><?= $t['stock'] ?></strong> tangkai</span>
                  <span class="fw-bold text-danger fs-5">Rp <?= number_format($t['price'], 0, ',', '.') ?></span>
                </div>
                <div class="d-flex gap-2">
                  <a href="detail.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-secondary">
                    Detail Info
                  </a>
                  <a href="index.php?order_id=<?= $t['id'] ?>#pesan" class="btn btn-sm btn-tulip">
                    Pesan
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12 text-center py-5">
        <div class="p-5 bg-white rounded-4 shadow-sm">
          <i class="fas fa-seedling text-muted fs-1 mb-3"></i>
          <h4>Tidak ada bunga tulip yang sesuai</h4>
          <p class="text-muted">Coba ubah kata kunci pencarian atau reset filter kategori.</p>
          <a href="catalog.php" class="btn btn-tulip-outline mt-2">Tampilkan Semua Tulip</a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
