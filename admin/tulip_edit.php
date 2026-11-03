<?php
require_once __DIR__ . '/auth_check.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM `tulips` WHERE `id` = ?");
$stmt->execute([$id]);
$tulip = $stmt->fetch();

if (!$tulip) {
    header("Location: tulips.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name            = trim($_POST['name'] ?? '');
    $scientific_name = trim($_POST['scientific_name'] ?? '');
    $color           = trim($_POST['color'] ?? '');
    $category        = trim($_POST['category'] ?? '');
    $price           = (float)($_POST['price'] ?? 0);
    $stock           = (int)($_POST['stock'] ?? 0);
    $image_url       = trim($_POST['image_url'] ?? '');
    $meaning         = trim($_POST['meaning'] ?? '');
    $care_tips       = trim($_POST['care_tips'] ?? '');

    if (empty($name) || empty($color) || empty($category) || $price <= 0) {
        $error = 'Nama, Warna, Kategori, dan Harga wajib diisi.';
    } else {
        try {
            $stmtUpdate = $pdo->prepare("
                UPDATE `tulips` 
                SET `name` = ?, `scientific_name` = ?, `color` = ?, `category` = ?, `meaning` = ?, `price` = ?, `stock` = ?, `image_url` = ?, `care_tips` = ? 
                WHERE `id` = ?
            ");
            $stmtUpdate->execute([$name, $scientific_name, $color, $category, $meaning, $price, $stock, $image_url, $care_tips, $id]);
            header("Location: tulips.php?msg=updated");
            exit;
        } catch (PDOException $e) {
            $error = 'Gagal memperbarui data: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Tulip - Admin</title>
  
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
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
          <h4 class="fw-bold font-serif mb-0"><i class="fas fa-edit text-primary me-2"></i>Edit Varietas Tulip</h4>
          <a href="tulips.php" class="btn btn-outline-secondary btn-sm">Batal / Kembali</a>
        </div>
        
        <div class="card-body p-4 p-md-5">
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-circle me-1"></i> <?= htmlspecialchars($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <form action="tulip_edit.php?id=<?= $tulip['id'] ?>" method="POST">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama Varietas Tulip *</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($tulip['name']) ?>" required>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama Ilmiah / Botani</label>
                <input type="text" name="scientific_name" class="form-control" value="<?= htmlspecialchars($tulip['scientific_name']) ?>">
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Warna Bunga *</label>
                <input type="text" name="color" class="form-control" value="<?= htmlspecialchars($tulip['color']) ?>" required>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Kategori Kultivar *</label>
                <select name="category" class="form-select" required>
                  <?php
                  $cats = ['Darwin Hybrid', 'Triumph', 'Single Late', 'Single Early', 'Double Late (Peony)', 'Parrot Tulip', 'Fosteriana'];
                  foreach ($cats as $c) {
                      $sel = ($tulip['category'] === $c) ? 'selected' : '';
                      echo "<option value=\"$c\" $sel>$c</option>";
                  }
                  ?>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Harga Jual (Rp) *</label>
                <input type="number" name="price" class="form-control" value="<?= (int)$tulip['price'] ?>" min="0" required>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Stok (Tangkai/Paket)</label>
                <input type="number" name="stock" class="form-control" value="<?= (int)$tulip['stock'] ?>" min="0" required>
              </div>

              <div class="col-12">
                <label class="form-label small fw-semibold">URL Foto Gambar Bunga</label>
                <input type="url" name="image_url" class="form-control" value="<?= htmlspecialchars($tulip['image_url']) ?>">
                <?php if (!empty($tulip['image_url'])): ?>
                  <div class="mt-2">
                    <img src="<?= htmlspecialchars($tulip['image_url']) ?>" alt="Preview" class="rounded-3 border" style="height: 80px; width: 80px; object-fit: cover;">
                  </div>
                <?php endif; ?>
              </div>

              <div class="col-12">
                <label class="form-label small fw-semibold">Makna & Filosofi Warna</label>
                <textarea name="meaning" class="form-control" rows="2"><?= htmlspecialchars($tulip['meaning']) ?></textarea>
              </div>

              <div class="col-12">
                <label class="form-label small fw-semibold">Tips Perawatan & Budidaya</label>
                <textarea name="care_tips" class="form-control" rows="3"><?= htmlspecialchars($tulip['care_tips']) ?></textarea>
              </div>

              <div class="col-12 mt-4">
                <button type="submit" class="btn btn-tulip px-4 py-2">
                  <i class="fas fa-save me-1"></i> Perbarui Data
                </button>
                <a href="tulips.php" class="btn btn-secondary px-4 py-2 ms-2">Batal</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
