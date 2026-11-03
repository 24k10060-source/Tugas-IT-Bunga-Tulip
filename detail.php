<?php
require_once __DIR__ . '/setup/connection.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM `tulips` WHERE `id` = ?");
$stmt->execute([$id]);
$tulip = $stmt->fetch();

if (!$tulip) {
    header("Location: catalog.php");
    exit;
}

// Rekomendasi tulip lainnya
$stmtOthers = $pdo->prepare("SELECT * FROM `tulips` WHERE `id` != ? ORDER BY RAND() LIMIT 3");
$stmtOthers->execute([$id]);
$otherTulips = $stmtOthers->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($tulip['name']) ?> - Detail Bunga Tulip</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="setup/style.css" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<div class="container my-5">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Beranda</a></li>
      <li class="breadcrumb-item"><a href="catalog.php" class="text-decoration-none">Katalog</a></li>
      <li class="breadcrumb-item active"><?= htmlspecialchars($tulip['name']) ?></li>
    </ol>
  </nav>

  <div class="row g-5">
    <div class="col-lg-6">
      <div class="card border-0 rounded-4 overflow-hidden shadow-lg sticky-top" style="top: 100px;">
        <img src="<?= htmlspecialchars($tulip['image_url']) ?>" alt="<?= htmlspecialchars($tulip['name']) ?>" class="img-fluid w-100" style="max-height: 500px; object-fit: cover;">
      </div>
    </div>

    <div class="col-lg-6">
      <div class="d-flex align-items-center gap-2 mb-2">
        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
          <i class="fas fa-palette me-1"></i> <?= htmlspecialchars($tulip['color']) ?>
        </span>
        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">
          Kultivar: <?= htmlspecialchars($tulip['category']) ?>
        </span>
      </div>

      <h1 class="display-5 fw-bold font-serif mb-1"><?= htmlspecialchars($tulip['name']) ?></h1>
      <p class="fs-5 text-muted fst-italic mb-3"><?= htmlspecialchars($tulip['scientific_name']) ?></p>

      <div class="p-3 bg-light rounded-4 my-4 d-flex justify-content-between align-items-center">
        <div>
          <small class="text-muted d-block">Harga per Tangkai / Buket</small>
          <span class="fs-3 fw-bold text-danger">Rp <?= number_format($tulip['price'], 0, ',', '.') ?></span>
        </div>
        <div>
          <span class="badge <?= $tulip['stock'] > 0 ? 'bg-success' : 'bg-danger' ?> px-3 py-2">
            <?= $tulip['stock'] > 0 ? 'Tersedia: ' . $tulip['stock'] . ' tangkai' : 'Stok Habis' ?>
          </span>
        </div>
      </div>

      <div class="mb-4">
        <h5 class="fw-bold font-serif">Filosofi & Makna:</h5>
        <div class="p-3 bg-danger-subtle text-dark rounded-3 border-start border-4 border-danger">
          <i class="fas fa-quote-left text-danger me-2"></i>
          <?= htmlspecialchars($tulip['meaning']) ?>
        </div>
      </div>

      <div class="mb-4">
        <h5 class="fw-bold font-serif"><i class="fas fa-seedling text-success me-2"></i>Panduan Perawatan Khusus:</h5>
        <p class="text-secondary small leading-relaxed">
          <?= nl2br(htmlspecialchars($tulip['care_tips'])) ?>
        </p>
      </div>

      <div class="d-flex gap-3 pt-3 border-top">
        <a href="index.php?order_id=<?= $tulip['id'] ?>#pesan" class="btn btn-tulip btn-lg px-4 flex-grow-1 shadow-sm">
          <i class="fas fa-shopping-bag me-2"></i> Pesan Varietas Ini
        </a>
        <a href="catalog.php" class="btn btn-outline-secondary btn-lg px-4">
          <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
      </div>
    </div>
  </div>

  <!-- Varietas Lainnya -->
  <?php if (!empty($otherTulips)): ?>
    <div class="mt-5 pt-5 border-top">
      <h3 class="fw-bold font-serif mb-4">Varietas Menarik Lainnya</h3>
      <div class="row g-4">
        <?php foreach ($otherTulips as $ot): ?>
          <div class="col-md-4">
            <div class="tulip-card h-100">
              <div class="tulip-card-img-wrap" style="height: 180px;">
                <img src="<?= htmlspecialchars($ot['image_url']) ?>" alt="<?= htmlspecialchars($ot['name']) ?>" class="tulip-card-img">
              </div>
              <div class="card-body p-3">
                <h6 class="fw-bold mb-1 font-serif"><?= htmlspecialchars($ot['name']) ?></h6>
                <p class="small text-muted mb-2"><?= htmlspecialchars($ot['color']) ?> • Rp <?= number_format($ot['price'], 0, ',', '.') ?></p>
                <a href="detail.php?id=<?= $ot['id'] ?>" class="btn btn-sm btn-tulip-outline w-100">Lihat Detail</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
