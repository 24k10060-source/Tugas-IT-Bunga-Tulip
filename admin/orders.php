<?php
require_once __DIR__ . '/auth_check.php';

$msg = '';

// Update status
if (isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $orderId = (int)($_POST['order_id'] ?? 0);
    $newStatus = trim($_POST['status'] ?? 'pending');
    $allowed = ['pending', 'diproses', 'selesai', 'dibatalkan'];

    if ($orderId > 0 && in_array($newStatus, $allowed)) {
        $stmt = $pdo->prepare("UPDATE `orders` SET `status` = ? WHERE `id` = ?");
        $stmt->execute([$newStatus, $orderId]);
        $msg = 'Status pesanan #' . $orderId . ' berhasil diperbarui!';
    }
}

// Hapus pesanan
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    if ($delId > 0) {
        $stmt = $pdo->prepare("DELETE FROM `orders` WHERE `id` = ?");
        $stmt->execute([$delId]);
        header("Location: orders.php?msg=deleted");
        exit;
    }
}

$statusFilter = trim($_GET['status'] ?? '');
if (!empty($statusFilter)) {
    $stmt = $pdo->prepare("
        SELECT o.*, t.name as tulip_name, t.price as tulip_price, t.image_url as tulip_image
        FROM `orders` o 
        LEFT JOIN `tulips` t ON o.tulip_id = t.id 
        WHERE o.status = ?
        ORDER BY o.id DESC
    ");
    $stmt->execute([$statusFilter]);
} else {
    $stmt = $pdo->query("
        SELECT o.*, t.name as tulip_name, t.price as tulip_price, t.image_url as tulip_image
        FROM `orders` o 
        LEFT JOIN `tulips` t ON o.tulip_id = t.id 
        ORDER BY o.id DESC
    ");
}
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Pesanan Pengunjung - Admin</title>
  
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
  <?php if (!empty($msg) || (isset($_GET['msg']) && $_GET['msg'] === 'deleted')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-1"></i> <?= !empty($msg) ? htmlspecialchars($msg) : 'Pesanan berhasil dihapus!' ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pb-3 mb-4 border-bottom">
    <div>
      <h2 class="fw-bold font-serif mb-1">Manajemen Pesanan & Pesan Masuk</h2>
      <p class="text-muted mb-0">Daftar pengunjung yang mengisi formulir pemesanan tulip.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
      <a href="index.php" class="btn btn-outline-secondary">
        <i class="fas fa-chart-pie me-1"></i> Dashboard
      </a>
      <a href="tulips.php" class="btn btn-outline-secondary">
        <i class="fas fa-seedling me-1"></i> Data Tulip
      </a>
    </div>
  </div>

  <!-- Filter Status Tab -->
  <ul class="nav nav-pills mb-4">
    <li class="nav-item">
      <a class="nav-link <?= empty($statusFilter) ? 'active bg-danger text-white' : 'text-dark' ?>" href="orders.php">Semua</a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $statusFilter === 'pending' ? 'active bg-warning text-dark' : 'text-dark' ?>" href="orders.php?status=pending">Pending</a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $statusFilter === 'diproses' ? 'active bg-primary text-white' : 'text-dark' ?>" href="orders.php?status=diproses">Diproses</a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $statusFilter === 'selesai' ? 'active bg-success text-white' : 'text-dark' ?>" href="orders.php?status=selesai">Selesai</a>
    </li>
  </ul>

  <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light small">
          <tr>
            <th>ID</th>
            <th>Data Pemesan</th>
            <th>Varietas Tulip</th>
            <th>Jumlah & Estimasi</th>
            <th>Catatan/Pesan</th>
            <th>Status Saat Ini</th>
            <th class="text-end">Ubah Status / Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $o): ?>
              <tr>
                <td><strong>#<?= $o['id'] ?></strong></td>
                <td>
                  <strong class="d-block text-dark"><?= htmlspecialchars($o['customer_name']) ?></strong>
                  <div class="small text-muted"><i class="fas fa-envelope me-1"></i> <?= htmlspecialchars($o['customer_email']) ?></div>
                  <div class="small text-success"><i class="fab fa-whatsapp me-1"></i> <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $o['customer_phone']) ?>" target="_blank" class="text-decoration-none text-success"><?= htmlspecialchars($o['customer_phone']) ?></a></div>
                </td>
                <td>
                  <?php if (!empty($o['tulip_name'])): ?>
                    <div class="d-flex align-items-center gap-2">
                      <?php if (!empty($o['tulip_image'])): ?>
                        <img src="<?= htmlspecialchars($o['tulip_image']) ?>" class="rounded-2" style="width: 35px; height: 35px; object-fit: cover;">
                      <?php endif; ?>
                      <span><?= htmlspecialchars($o['tulip_name']) ?></span>
                    </div>
                  <?php else: ?>
                    <span class="badge bg-light text-secondary border">Pertanyaan Umum</span>
                  <?php endif; ?>
                </td>
                <td>
                  <strong><?= $o['quantity'] ?></strong> tangkai
                  <?php if (!empty($o['tulip_price'])): ?>
                    <div class="small text-danger fw-semibold">Total: Rp <?= number_format($o['tulip_price'] * $o['quantity'], 0, ',', '.') ?></div>
                  <?php endif; ?>
                </td>
                <td>
                  <small class="text-secondary"><?= !empty($o['message']) ? htmlspecialchars($o['message']) : '<em>Tidak ada catatan</em>' ?></small>
                </td>
                <td>
                  <?php if ($o['status'] === 'pending'): ?>
                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Pending</span>
                  <?php elseif ($o['status'] === 'diproses'): ?>
                    <span class="badge bg-primary"><i class="fas fa-spinner fa-spin me-1"></i> Diproses</span>
                  <?php elseif ($o['status'] === 'selesai'): ?>
                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Selesai</span>
                  <?php else: ?>
                    <span class="badge bg-secondary">Dibatalkan</span>
                  <?php endif; ?>
                </td>
                <td class="text-end">
                  <form action="orders.php" method="POST" class="d-inline-flex align-items-center gap-1">
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 120px;">
                      <option value="pending" <?= $o['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                      <option value="diproses" <?= $o['status'] === 'diproses' ? 'selected' : '' ?>>Diproses</option>
                      <option value="selesai" <?= $o['status'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                      <option value="dibatalkan" <?= $o['status'] === 'dibatalkan' ? 'selected' : '' ?>>Batalkan</option>
                    </select>
                  </form>
                  <a href="orders.php?delete_id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-danger ms-1" title="Hapus" onclick="return confirm('Hapus pesanan #<?= $o['id'] ?>?')">
                    <i class="fas fa-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center py-5 text-muted">
                Tidak ada data pesanan pada filter ini.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
