<?php
require_once __DIR__ . '/auth_check.php';

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
        $error = 'Nama, Warna, Kategori, dan Harga wajib diisi dengan benar.';
    } else {
        if (empty($image_url)) {
            $image_url = 'https://images.unsplash.com/photo-1520763185298-1b434c919102?auto=format&fit=crop&w=800&q=80';
        }
        if (empty($scientific_name)) {
            $scientific_name = 'Tulipa sp.';
        }

        try {
            $stmt = $pdo->prepare("
                INSERT INTO `tulips` (`name`, `scientific_name`, `color`, `category`, `meaning`, `price`, `stock`, `image_url`, `care_tips`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$name, $scientific_name, $color, $category, $meaning, $price, $stock, $image_url, $care_tips]);
            header("Location: tulips.php?msg=added");
            exit;
        } catch (PDOException $e) {
            $error = 'Gagal menyimpan data: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Bunga Tulip - Admin</title>
  
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
          <h4 class="fw-bold font-serif mb-0"><i class="fas fa-plus-circle text-danger me-2"></i>Tambah Varietas Tulip Baru</h4>
          <a href="tulips.php" class="btn btn-outline-secondary btn-sm">Batal / Kembali</a>
        </div>
        
        <div class="card-body p-4 p-md-5">
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-circle me-1"></i> <?= htmlspecialchars($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <form action="tulip_add.php" method="POST">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama Varietas Tulip *</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Angelique Tulip" required>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama Ilmiah / Botani</label>
                <input type="text" name="scientific_name" class="form-control" placeholder="Contoh: Tulipa 'Angelique'">
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Warna Bunga *</label>
                <input type="text" name="color" class="form-control" placeholder="Contoh: Merah Muda / Pink" required>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Kategori Kultivar *</label>
                <select name="category" class="form-select" required>
                  <option value="Darwin Hybrid">Darwin Hybrid</option>
                  <option value="Triumph">Triumph</option>
                  <option value="Single Late">Single Late</option>
                  <option value="Single Early">Single Early</option>
                  <option value="Double Late (Peony)">Double Late (Peony)</option>
                  <option value="Parrot Tulip">Parrot Tulip</option>
                  <option value="Fosteriana">Fosteriana</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Harga Jual (Rp) *</label>
                <input type="number" name="price" class="form-control" placeholder="35000" min="0" required>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Stok (Tangkai/Paket)</label>
                <input type="number" name="stock" class="form-control" value="15" min="0" required>
              </div>

              <div class="col-12">
                <label class="form-label small fw-semibold">URL Foto Gambar Bunga</label>
                <input type="url" name="image_url" class="form-control" placeholder="https://images.unsplash.com/...">
                <div class="form-text small">Bisa menggunakan tautan gambar online (Unsplash, Pexels, dsb).</div>
              </div>

              <div class="col-12">
                <label class="form-label small fw-semibold">Makna & Filosofi Warna</label>
                <textarea name="meaning" class="form-control" rows="2" placeholder="Jelaskan arti atau pesan yang terkandung dalam varietas tulip ini..."></textarea>
              </div>

              <div class="col-12">
                <label class="form-label small fw-semibold">Tips Perawatan & Budidaya</label>
                <textarea name="care_tips" class="form-control" rows="3" placeholder="Informasi mengenai penyiraman, kebutuhan suhu, dan pencahayaan..."></textarea>
              </div>

              <div class="col-12 mt-4">
                <button type="submit" class="btn btn-tulip px-4 py-2">
                  <i class="fas fa-save me-1"></i> Simpan Data Tulip
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
