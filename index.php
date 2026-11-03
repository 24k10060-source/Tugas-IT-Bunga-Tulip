<?php
require_once __DIR__ . '/setup/connection.php';

$successMsg = '';
$errorMsg = '';

// Proses form pemesanan / inquiry bunga tulip
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'order') {
    $custName  = trim($_POST['customer_name'] ?? '');
    $custEmail = trim($_POST['customer_email'] ?? '');
    $custPhone = trim($_POST['customer_phone'] ?? '');
    $tulipId   = !empty($_POST['tulip_id']) ? (int)$_POST['tulip_id'] : null;
    $quantity  = max(1, (int)($_POST['quantity'] ?? 1));
    $message   = trim($_POST['message'] ?? '');

    if (empty($custName) || empty($custEmail) || empty($custPhone)) {
        $errorMsg = 'Mohon lengkapi Nama, Email, dan Nomor WhatsApp Anda.';
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO `orders` (`customer_name`, `customer_email`, `customer_phone`, `tulip_id`, `quantity`, `message`, `status`) 
                VALUES (?, ?, ?, ?, ?, ?, 'pending')
            ");
            $stmt->execute([$custName, $custEmail, $custPhone, $tulipId, $quantity, $message]);
            $successMsg = 'Terima kasih, ' . htmlspecialchars($custName) . '! Pesanan/pertanyaan Anda telah berhasil tersimpan di database kami. Tim kami akan segera menghubungi Anda.';
        } catch (PDOException $e) {
            $errorMsg = 'Terjadi kesalahan sistem saat menyimpan pesanan: ' . $e->getMessage();
        }
    }
}

// Ambil data varietas tulip dari database (Katalog Utama)
try {
    $stmtTulips = $pdo->query("SELECT * FROM `tulips` ORDER BY `id` ASC LIMIT 6");
    $featuredTulips = $stmtTulips->fetchAll();

    // Ambil list semua tulip untuk dropdown form pemesanan
    $stmtList = $pdo->query("SELECT `id`, `name`, `price`, `color` FROM `tulips` ORDER BY `name` ASC");
    $allTulipsList = $stmtList->fetchAll();
} catch (PDOException $e) {
    $featuredTulips = [];
    $allTulipsList = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tulip Haven - Keindahan & Ensiklopedia Bunga Tulip Dunia</title>
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Custom CSS -->
  <link href="setup/style.css" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Alerts Notification -->
<?php if (!empty($successMsg)): ?>
  <div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center gap-2" role="alert">
      <i class="fas fa-check-circle fs-5"></i>
      <div><?= $successMsg ?></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
<?php endif; ?>

<?php if (!empty($errorMsg)): ?>
  <div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center gap-2" role="alert">
      <i class="fas fa-exclamation-triangle fs-5"></i>
      <div><?= $errorMsg ?></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
<?php endif; ?>

<!-- Hero Section -->
<section class="hero-section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-7">
        <span class="hero-badge mb-3">
          <i class="fas fa-certificate text-danger"></i> Ensiklopedia Flora & Budidaya Tulip
        </span>
        <h1 class="display-4 fw-bold mb-3" style="color: #2c3e50;">
          Keanggunan Abadi & Makna Mendalam <span style="color: var(--tulip-primary);">Bunga Tulip</span>
        </h1>
        <p class="lead text-muted mb-4">
          Jelajahi ragam varietas bunga tulip tercantik di dunia, selami sejarah fenomena <em>Tulipmania</em>, pelajari makna di setiap warnanya, dan dapatkan panduan praktis perawatannya langsung dari para pecinta botani.
        </p>
        <div class="d-flex flex-wrap gap-3">
          <a href="#katalog" class="btn btn-tulip btn-lg px-4 shadow-sm">
            <i class="fas fa-seedling me-2"></i> Jelajahi Koleksi Tulip
          </a>
          <a href="#perawatan" class="btn btn-tulip-outline btn-lg px-4">
            <i class="fas fa-book-open me-2"></i> Panduan Perawatan
          </a>
        </div>
        
        <div class="row mt-5 pt-3 border-top g-3">
          <div class="col-4">
            <h4 class="fw-bold mb-0 text-dark">150+</h4>
            <small class="text-muted">Spesies di Dunia</small>
          </div>
          <div class="col-4">
            <h4 class="fw-bold mb-0" style="color: var(--tulip-primary);">100%</h4>
            <small class="text-muted">Data MySQL Aktif</small>
          </div>
          <div class="col-4">
            <h4 class="fw-bold mb-0 text-success">HTTPS</h4>
            <small class="text-muted">Aman & Terenkripsi</small>
          </div>
        </div>
      </div>
      
      <div class="col-lg-5">
        <div class="position-relative">
          <div style="position: absolute; width: 300px; height: 300px; background: rgba(232, 67, 147, 0.15); filter: blur(50px); border-radius: 50%; top: 10%; right: 5%; z-index: 1;"></div>
          <div class="card border-0 shadow-lg rounded-4 overflow-hidden position-relative" style="z-index: 2;">
            <img src="https://images.unsplash.com/photo-1520763185298-1b434c919102?auto=format&fit=crop&w=900&q=80" alt="Bunga Tulip Merah Mekar" class="img-fluid" style="height: 420px; object-fit: cover;">
            <div class="card-body p-4 bg-white">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h5 class="fw-bold mb-1 font-serif">Red Impression</h5>
                  <p class="text-muted small mb-0">Tulipa Darwin Hybrid • Keukenhof, Belanda</p>
                </div>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                  Simbol Cinta Abadi
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Section: Makna Warna Tulip -->
<section class="py-5 bg-white">
  <div class="container py-4">
    <div class="text-center max-w-700 mx-auto mb-5">
      <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill mb-2">Filosofi Bunga</span>
      <h2 class="display-6 fw-bold font-serif">Arti & Makna di Setiap Warna Tulip</h2>
      <p class="text-muted">Sejak abad ke-16, warna bunga tulip telah digunakan sebagai bahasa visual untuk mengungkapkan perasaan terdalam manusia.</p>
    </div>

    <div class="row g-4">
      <div class="col-md-4 col-sm-6">
        <div class="card h-100 border-0 p-4 rounded-4 shadow-sm" style="background: #fff5f5; border-left: 5px solid #e74c3c !important;">
          <div class="d-flex align-items-center gap-3 mb-3">
            <span class="fs-1">❤️</span>
            <div>
              <h5 class="fw-bold mb-0 text-danger font-serif">Tulip Merah</h5>
              <small class="text-muted">Cinta Sejati & Gairah</small>
            </div>
          </div>
          <p class="small text-secondary mb-0">
            Menggambarkan deklarasi cinta yang abadi dan keyakinan hati yang teguh. Legenda Persia mengaitkannya dengan tetesan darah Farhad demi cintanya, Shirin.
          </p>
        </div>
      </div>

      <div class="col-md-4 col-sm-6">
        <div class="card h-100 border-0 p-4 rounded-4 shadow-sm" style="background: #fffdf0; border-left: 5px solid #f1c40f !important;">
          <div class="d-flex align-items-center gap-3 mb-3">
            <span class="fs-1">💛</span>
            <div>
              <h5 class="fw-bold mb-0 text-warning font-serif">Tulip Kuning</h5>
              <small class="text-muted">Keceriaan & Persahabatan</small>
            </div>
          </div>
          <p class="small text-secondary mb-0">
            Menyimbolkan sinar matahari yang hangat, kebahagiaan murni, optimisme, dan senyuman yang membawa energi positif bagi siapa pun yang menerimanya.
          </p>
        </div>
      </div>

      <div class="col-md-4 col-sm-6">
        <div class="card h-100 border-0 p-4 rounded-4 shadow-sm" style="background: #f8fafc; border-left: 5px solid #94a3b8 !important;">
          <div class="d-flex align-items-center gap-3 mb-3">
            <span class="fs-1">🤍</span>
            <div>
              <h5 class="fw-bold mb-0 text-secondary font-serif">Tulip Putih</h5>
              <small class="text-muted">Ketulusan & Maaf</small>
            </div>
          </div>
          <p class="small text-secondary mb-0">
            Pilihan sempurna untuk memohon maaf dengan tulus, menghormati seseorang, atau merayakan babak baru dalam kehidupan seperti pernikahan suci.
          </p>
        </div>
      </div>

      <div class="col-md-4 col-sm-6">
        <div class="card h-100 border-0 p-4 rounded-4 shadow-sm" style="background: #faf5ff; border-left: 5px solid #8e44ad !important;">
          <div class="d-flex align-items-center gap-3 mb-3">
            <span class="fs-1">💜</span>
            <div>
              <h5 class="fw-bold mb-0 font-serif" style="color: #8e44ad;">Tulip Ungu</h5>
              <small class="text-muted">Keagungan & Kemewahan</small>
            </div>
          </div>
          <p class="small text-secondary mb-0">
            Warna ungu lekat dengan martabat keluarga kerajaan dan keanggunan aristokrat. Cocok untuk mengapresiasi figur yang sangat Anda hormati dan kagumi.
          </p>
        </div>
      </div>

      <div class="col-md-4 col-sm-6">
        <div class="card h-100 border-0 p-4 rounded-4 shadow-sm" style="background: #fff0f6; border-left: 5px solid #e84393 !important;">
          <div class="d-flex align-items-center gap-3 mb-3">
            <span class="fs-1">🌸</span>
            <div>
              <h5 class="fw-bold mb-0 font-serif" style="color: #e84393;">Tulip Pink</h5>
              <small class="text-muted">Kasih Sayang & Perhatian</small>
            </div>
          </div>
          <p class="small text-secondary mb-0">
            Menyampaikan perhatian penuh kelembutan, harapan baik, dan rasa syukur atas kehadiran sahabat atau keluarga tercinta dalam hidup Anda.
          </p>
        </div>
      </div>

      <div class="col-md-4 col-sm-6">
        <div class="card h-100 border-0 p-4 rounded-4 shadow-sm" style="background: #f1f2f6; border-left: 5px solid #2f3542 !important;">
          <div class="d-flex align-items-center gap-3 mb-3">
            <span class="fs-1">🖤</span>
            <div>
              <h5 class="fw-bold mb-0 text-dark font-serif">Tulip Gelap (Black)</h5>
              <small class="text-muted">Misteri & Keteguhan Hati</small>
            </div>
          </div>
          <p class="small text-secondary mb-0">
            Dikenal lewat varietas legendaris 'Queen of Night', melambangkan keunikan langka, daya tarik misterius, serta keteguhan jiwa yang mandiri.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Section: Katalog Bunga Tulip (Database Driven) -->
<section id="katalog" class="py-5" style="background: #fafbfc;">
  <div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5">
      <div>
        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill mb-2">Koleksi Terpilih</span>
        <h2 class="display-6 fw-bold font-serif mb-0">Katalog Varietas Bunga Tulip</h2>
        <p class="text-muted mb-0">Data real-time diambil langsung dari database MySQL.</p>
      </div>
      <div class="mt-3 mt-md-0">
        <a href="catalog.php" class="btn btn-tulip-outline">
          Lihat Semua Varietas (<?= count($allTulipsList) ?>) <i class="fas fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <div class="row g-4">
      <?php if (!empty($featuredTulips)): ?>
        <?php foreach ($featuredTulips as $tulip): ?>
          <div class="col-lg-4 col-md-6">
            <div class="tulip-card h-100 d-flex flex-column">
              <div class="tulip-card-img-wrap">
                <img src="<?= htmlspecialchars($tulip['image_url']) ?>" alt="<?= htmlspecialchars($tulip['name']) ?>" class="tulip-card-img" loading="lazy">
                <span class="badge-color-pill">
                  <i class="fas fa-palette me-1 text-danger"></i> <?= htmlspecialchars($tulip['color']) ?>
                </span>
              </div>
              <div class="card-body p-4 d-flex flex-column flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div>
                    <h5 class="card-title fw-bold mb-0 font-serif"><?= htmlspecialchars($tulip['name']) ?></h5>
                    <small class="text-muted fst-italic"><?= htmlspecialchars($tulip['scientific_name']) ?></small>
                  </div>
                  <span class="badge bg-light text-secondary border"><?= htmlspecialchars($tulip['category']) ?></span>
                </div>
                
                <p class="card-text small text-secondary my-3 flex-grow-1">
                  <?= htmlspecialchars($tulip['meaning']) ?>
                </p>

                <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                  <div>
                    <small class="text-muted d-block">Estimasi Harga</small>
                    <span class="fw-bold text-danger fs-5">Rp <?= number_format($tulip['price'], 0, ',', '.') ?></span>
                  </div>
                  <div class="d-flex gap-2">
                    <a href="detail.php?id=<?= $tulip['id'] ?>" class="btn btn-sm btn-outline-secondary">
                      Detail
                    </a>
                    <a href="#pesan" onclick="selectTulip(<?= $tulip['id'] ?>)" class="btn btn-sm btn-tulip">
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
          <p class="text-muted">Belum ada data bunga tulip di database.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Section: Panduan Perawatan -->
<section id="perawatan" class="py-5 bg-white">
  <div class="container py-4">
    <div class="text-center max-w-700 mx-auto mb-5">
      <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill mb-2">Tips Botani</span>
      <h2 class="display-6 fw-bold font-serif">Cara Merawat Bunga Tulip Agar Segar & Tahan Lama</h2>
      <p class="text-muted">Bunga tulip memerlukan perhatian khusus terhadap suhu, kelembaban, dan drainase air.</p>
    </div>

    <div class="row g-4">
      <div class="col-lg-3 col-md-6">
        <div class="p-4 rounded-4 border bg-light h-100 text-center">
          <div class="rounded-circle bg-white shadow-sm d-inline-flex p-3 mb-3 text-warning fs-3">
            <i class="fas fa-sun"></i>
          </div>
          <h5 class="fw-bold font-serif">1. Cahaya Matahari</h5>
          <p class="small text-muted mb-0">
            Letakkan di area dengan cahaya terang tetapi <em>indirect</em> (tidak terpapar terik matahari siang secara langsung) agar kelopak tidak cepat layu.
          </p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="p-4 rounded-4 border bg-light h-100 text-center">
          <div class="rounded-circle bg-white shadow-sm d-inline-flex p-3 mb-3 text-info fs-3">
            <i class="fas fa-tint"></i>
          </div>
          <h5 class="fw-bold font-serif">2. Pengaturan Air</h5>
          <p class="small text-muted mb-0">
            Siram hanya saat media tanam mulai terasa kering. Hindari genangan air karena umbi tulip sangat rentan membusuk bila terlalu lembab.
          </p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="p-4 rounded-4 border bg-light h-100 text-center">
          <div class="rounded-circle bg-white shadow-sm d-inline-flex p-3 mb-3 text-primary fs-3">
            <i class="fas fa-temperature-low"></i>
          </div>
          <h5 class="fw-bold font-serif">3. Suhu Ruangan</h5>
          <p class="small text-muted mb-0">
            Tulip tumbuh optimal pada iklim sejuk (15°C – 22°C). Jika diletakkan di dalam ruangan ber-AC, jauhkan dari semburan angin AC langsung.
          </p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="p-4 rounded-4 border bg-light h-100 text-center">
          <div class="rounded-circle bg-white shadow-sm d-inline-flex p-3 mb-3 text-success fs-3">
            <i class="fas fa-scissors"></i>
          </div>
          <h5 class="fw-bold font-serif">4. Perawatan Vas Bunga</h5>
          <p class="small text-muted mb-0">
            Untuk buket vas air, potong ujung tangkai 1-2 cm secara diagonal tiap 2 hari sekali, dan ganti air dingin bersih secara berkala.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Section: Form Pemesanan / Inquiry (Database Driven) -->
<section id="pesan" class="py-5" style="background: linear-gradient(135deg, #fff0f5 0%, #fff 100%);">
  <div class="container py-4">
    <div class="row align-items-center g-5">
      <div class="col-lg-5">
        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill mb-2">Formulir Interaktif</span>
        <h2 class="display-6 fw-bold font-serif mb-3">Tertarik dengan Bunga Tulip Kami?</h2>
        <p class="text-muted mb-4">
          Silakan kirimkan permintaan pemesanan buket atau pertanyaan seputar varietas bunga tulip. Data Anda akan langsung tersimpan secara aman di database MySQL kami.
        </p>

        <div class="card border-0 rounded-4 p-4 shadow-sm bg-white mb-3">
          <div class="d-flex align-items-center gap-3">
            <div class="p-3 bg-danger-subtle text-danger rounded-circle">
              <i class="fas fa-shield-alt fs-4"></i>
            </div>
            <div>
              <h6 class="fw-bold mb-1">Tersimpan di Database</h6>
              <p class="small text-muted mb-0">Pesanan dapat dipantau langsung lewat Panel Admin.</p>
            </div>
          </div>
        </div>

        <div class="card border-0 rounded-4 p-4 shadow-sm bg-white">
          <div class="d-flex align-items-center gap-3">
            <div class="p-3 bg-success-subtle text-success rounded-circle">
              <i class="fas fa-lock fs-4"></i>
            </div>
            <div>
              <h6 class="fw-bold mb-1">HTTPS Encryption</h6>
              <p class="small text-muted mb-0">Komunikasi data terenkripsi dan terlindungi aman.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white">
          <h4 class="fw-bold font-serif mb-4 text-dark d-flex align-items-center gap-2">
            <span>🌷</span> Form Pemesanan / Pesan Bunga
          </h4>

          <form action="index.php#pesan" method="POST">
            <input type="hidden" name="action" value="order">

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama Lengkap *</label>
                <input type="text" name="customer_name" class="form-control" placeholder="Contoh: Mina" required>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Alamat Email *</label>
                <input type="email" name="customer_email" class="form-control" placeholder="nama@email.com" required>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Nomor WhatsApp / HP *</label>
                <input type="tel" name="customer_phone" class="form-control" placeholder="08xxxxxxxxxx" required>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-semibold">Pilih Varietas Tulip</label>
                <select name="tulip_id" id="tulipSelect" class="form-select">
                  <option value="">-- Pertanyaan Umum / Belum Memilih --</option>
                  <?php foreach ($allTulipsList as $item): ?>
                    <option value="<?= $item['id'] ?>">
                      <?= htmlspecialchars($item['name']) ?> (<?= htmlspecialchars($item['color']) ?>) - Rp <?= number_format($item['price'], 0, ',', '.') ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-4">
                <label class="form-label small fw-semibold">Jumlah Tangkai / Buket</label>
                <input type="number" name="quantity" class="form-control" value="1" min="1" max="100">
              </div>

              <div class="col-md-8">
                <label class="form-label small fw-semibold">Catatan Khusus / Pesan</label>
                <input type="text" name="message" class="form-control" placeholder="Misal: Mohon sertakan kartu ucapan selamat ulang tahun">
              </div>

              <div class="col-12 mt-4">
                <button type="submit" class="btn btn-tulip w-100 py-2 fs-6 shadow-sm">
                  <i class="fas fa-paper-plane me-2"></i> Kirim Pemesanan / Pertanyaan
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
function selectTulip(id) {
  const select = document.getElementById('tulipSelect');
  if (select) {
    select.value = id;
  }
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
