<?php
require_once __DIR__ . '/auth_check.php';

$search = trim($_GET['q'] ?? '');
if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT * FROM `tulips` WHERE `name` LIKE ? OR `color` LIKE ? OR `category` LIKE ? ORDER BY `id` DESC");
    $term = "%$search%";
    $stmt->execute([$term, $term, $term]);
} else {
    $stmt = $pdo->query("SELECT * FROM `tulips` ORDER BY `id` DESC");
}
$tulips = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Data Bunga Tulip - Admin</title>
  
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
  <!-- Alerts -->
  <?php if (isset($_GET['msg']) && $_GET['msg'] === 'added'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-1"></i> Data bunga tulip berhasil ditambahkan!
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-1"></i> Data bunga tulip berhasil diperbarui!
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <i class="fas fa-trash me-1"></i> Data bunga tulip berhasil dihapus!
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pb-3 mb-4 border-bottom">
    <div>
      <h2 class="fw-bold font-serif mb-1">Manajemen Varietas Tulip</h2>
      <p class="text-muted mb-0">Kelola katalog, harga, stok, dan deskripsi tanaman bunga tulip.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex gap-2">
      <a href="tulip_add.php" class="btn btn-tulip shadow-sm">
        <i class="fas fa-plus me-1"></i> Tambah Tulip Baru
      </a>
      <a href="index.php" class="btn btn-outline-secondary">
        <i class="fas fa-chart-line me-1"></i> Dashboard
      </a>
    </div>
  </div>

  <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
    <div class="card-header bg-white py-3 px-4">
      <form method="GET" action="tulips.php" class="row g-2 align-items-center">
        <div class="col-md-6 col-lg-4">
          <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
            <input type="text" name="q" class="form-control border-start-0" placeholder="Cari nama, warna, kategori..." value="<?= htmlspecialchars($search) ?>">
          </div>
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-secondary btn-sm">Cari</button>
          <?php if (!empty($search)): ?>
            <a href="tulips.php" class="btn btn-link btn-sm text-secondary">Reset</a>
          <?php endif; ?>
        </div>
      </form>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light small">
          <tr>
            <th style="width: 70px;">Foto</th>
            <th>Nama & Nama Ilmiah</th>
            <th>Warna</th>
            <th>Kategori</th>
            <th>Harga (Rp)</th>
            <th>Stok</th>
            <th class="text-end">Aksi (CRUD)</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($tulips)): ?>
            <?php foreach ($tulips as $t): ?>
              <tr>
                <td>
                  <img src="<?= htmlspecialchars($t['image_url']) ?>" alt="Foto" class="rounded-3 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                </td>
                <td>
                  <strong class="d-block text-dark"><?= htmlspecialchars($t['name']) ?></strong>
                  <small class="text-muted fst-italic"><?= htmlspecialchars($t['scientific_name']) ?></small>
                </td>
                <td>
                  <span class="badge bg-light text-dark border"><?= htmlspecialchars($t['color']) ?></span>
                </td>
                <td><?= htmlspecialchars($t['category']) ?></td>
                <td class="fw-semibold text-danger">Rp <?= number_format($t['price'], 0, ',', '.') ?></td>
                <td>
                  <span class="badge <?= $t['stock'] > 5 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?> px-2 py-1">
                    <?= $t['stock'] ?> pcs
                  </span>
                </td>
                <td class="text-end">
                  <a href="../detail.php?id=<?= $t['id'] ?>" target="_blank" class="btn btn-sm btn-outline-info" title="Lihat di Web">
                    <i class="fas fa-eye"></i>
                  </a>
                  <a href="tulip_edit.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-primary" title="Ubah Data">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="tulip_delete.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus varietas <?= addslashes($t['name']) ?>?')">
                    <i class="fas fa-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center py-5 text-muted">
                Tidak ada data bunga tulip yang ditemukan.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
