<?php
require_once __DIR__ . '/auth_check.php';

// Ambil statistik
$totalTulips = $pdo->query("SELECT COUNT(*) FROM `tulips`")->fetchColumn();
$totalStock  = $pdo->query("SELECT SUM(`stock`) FROM `tulips`")->fetchColumn() ?: 0;
$totalOrders = $pdo->query("SELECT COUNT(*) FROM `orders`")->fetchColumn();
$pendingOrders = $pdo->query("SELECT COUNT(*) FROM `orders` WHERE `status` = 'pending'")->fetchColumn();

// Ambil 5 pesanan terbaru
$stmtRecent = $pdo->query("
    SELECT o.*, t.name as tulip_name 
    FROM `orders` o 
    LEFT JOIN `tulips` t ON o.tulip_id = t.id 
    ORDER BY o.id DESC LIMIT 5
");
$recentOrders = $stmtRecent->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - Tulip Haven</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../setup/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="container my-5">
  <!-- Header -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pb-4 mb-4 border-bottom">
    <div>
      <h2 class="fw-bold font-serif mb-1">Dashboard Administrator</h2>
      <p class="text-muted mb-0">Selamat bertugas, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>! Kelola katalog bunga tulip dan pesanan pengunjung.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
      <a href="tulip_add.php" class="btn btn-tulip shadow-sm">
        <i class="fas fa-plus me-1"></i> Tambah Bunga Tulip
      </a>
      <a href="tulips.php" class="btn btn-outline-secondary">
        <i class="fas fa-list me-1"></i> Data Tulip
      </a>
    </div>
  </div>

  <!-- Statistik Cards -->
  <div class="row g-4 mb-5">
    <div class="col-md-3 col-sm-6">
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <span class="text-muted small fw-semibold">Varietas Tulip</span>
            <h3 class="fw-bold my-1 text-dark"><?= $totalTulips ?></h3>
            <span class="small text-success"><i class="fas fa-seedling me-1"></i> Di Database</span>
          </div>
          <div class="p-3 bg-danger-subtle text-danger rounded-4 fs-3">
            🌷
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6">
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <span class="text-muted small fw-semibold">Total Stok Tangkai</span>
            <h3 class="fw-bold my-1 text-dark"><?= $totalStock ?></h3>
            <span class="small text-muted">Tersedia di Gudang</span>
          </div>
          <div class="p-3 bg-success-subtle text-success rounded-4 fs-4">
            <i class="fas fa-boxes"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6">
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <span class="text-muted small fw-semibold">Total Pesanan</span>
            <h3 class="fw-bold my-1 text-dark"><?= $totalOrders ?></h3>
            <span class="small text-primary"><i class="fas fa-shopping-bag me-1"></i> Form Pengunjung</span>
          </div>
          <div class="p-3 bg-primary-subtle text-primary rounded-4 fs-4">
            <i class="fas fa-inbox"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6">
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <span class="text-muted small fw-semibold">Pesanan Pending</span>
            <h3 class="fw-bold my-1 text-warning"><?= $pendingOrders ?></h3>
            <span class="small text-warning"><i class="fas fa-clock me-1"></i> Perlu Ditindak</span>
          </div>
          <div class="p-3 bg-warning-subtle text-warning rounded-4 fs-4">
            <i class="fas fa-bell"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Orders Table -->
  <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
      <h5 class="fw-bold mb-0 font-serif">Pesanan & Pesan Masuk Terbaru</h5>
      <a href="orders.php" class="btn btn-sm btn-outline-secondary">Kelola Semua Pesanan</a>
    </div>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light small">
          <tr>
            <th>ID</th>
            <th>Nama Pemesan</th>
            <th>Kontak</th>
            <th>Bunga Tulip</th>
            <th>Jumlah</th>
            <th>Status</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($recentOrders)): ?>
            <?php foreach ($recentOrders as $order): ?>
              <tr>
                <td>#<?= $order['id'] ?></td>
                <td class="fw-semibold"><?= htmlspecialchars($order['customer_name']) ?></td>
                <td>
                  <small class="d-block"><?= htmlspecialchars($order['customer_email']) ?></small>
                  <small class="text-muted"><?= htmlspecialchars($order['customer_phone']) ?></small>
                </td>
                <td><?= htmlspecialchars($order['tulip_name'] ?? 'Pertanyaan Umum') ?></td>
                <td><?= $order['quantity'] ?></td>
                <td>
                  <?php if ($order['status'] === 'pending'): ?>
                    <span class="badge bg-warning text-dark">Pending</span>
                  <?php elseif ($order['status'] === 'diproses'): ?>
                    <span class="badge bg-primary">Diproses</span>
                  <?php elseif ($order['status'] === 'selesai'): ?>
                    <span class="badge bg-success">Selesai</span>
                  <?php else: ?>
                    <span class="badge bg-secondary">Dibatalkan</span>
                  <?php endif; ?>
                </td>
                <td class="small text-muted"><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center py-4 text-muted">Belum ada pesanan masuk.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
