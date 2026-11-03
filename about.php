<?php
require_once __DIR__ . '/setup/connection.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sejarah & Filosofi Bunga Tulip Dunia - Tulip Haven</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="setup/style.css" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Header -->
<div class="py-5" style="background: linear-gradient(135deg, #fff0f5 0%, #fff 100%);">
  <div class="container py-4 text-center">
    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill mb-2">Ensiklopedia Botani</span>
    <h1 class="display-4 fw-bold font-serif mb-3">Sejarah & Perjalanan Epik Bunga Tulip</h1>
    <p class="lead text-muted max-w-700 mx-auto">
      Dari pegunungan liar Asia Tengah, istana megah Kekaisaran Ottoman, hingga mengguncang perekonomian dunia lewat fenomena <em>Tulipmania</em> di Belanda.
    </p>
  </div>
</div>

<div class="container my-5">
  <div class="row g-5 align-items-center mb-5">
    <div class="col-lg-6">
      <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill mb-2">Asal Usul Nama</span>
      <h2 class="fw-bold font-serif mb-3">Dari Turban Ottoman hingga Menjadi Lambang Kemegahan</h2>
      <p class="text-secondary leading-relaxed">
        Nama <em>Tulip</em> berasal dari bahasa Persia <strong>"dulband"</strong> (sorban atau turban), karena bentuk kelopak bunganya saat kuncup menyerupai lilitan kain turban para bangsawan Timur Tengah.
      </p>
      <p class="text-secondary leading-relaxed">
        Bunga ini pertama kali dibudidayakan secara ekstensif pada masa Kekaisaran Ottoman di bawah kepemimpinan Sultan Suleiman yang Agung pada abad ke-16. Motif tulip menghiasi keramik Iznik, karpet istana, dan puisi-puisi sufi sebagai lambang keesaan Ilahi.
      </p>
    </div>
    <div class="col-lg-6">
      <div class="rounded-4 overflow-hidden shadow">
        <img src="https://images.unsplash.com/photo-1520763185298-1b434c919102?auto=format&fit=crop&w=800&q=80" alt="Bunga Tulip Sejarah" class="img-fluid w-100" style="height: 350px; object-fit: cover;">
      </div>
    </div>
  </div>

  <!-- Tulipmania Section -->
  <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-light my-5">
    <div class="row g-4 align-items-center">
      <div class="col-lg-4 text-center text-lg-start">
        <span class="badge bg-warning-subtle text-dark px-3 py-2 rounded-pill mb-3">Tahun 1636 - 1637</span>
        <h3 class="fw-bold font-serif text-dark mb-3">Tulipmania: Gelembung Ekonomi Pertama di Dunia</h3>
        <p class="small text-muted mb-0">Sebuah era ketika satu umbi tulip berharga lebih mahal daripada sebuah rumah mewah di kanal Amsterdam.</p>
      </div>
      <div class="col-lg-8 border-start-lg ps-lg-4">
        <p class="text-secondary">
          Ketika botanis Carolus Clusius menanam tulip di Kebun Botani Leiden di Belanda, pesona bunga ini memicu obsesi masal di kalangan saudagar kaya. Varietas yang memiliki corak belang-belang unik (akibat mosaik virus, seperti varietas legendaris <em>Semper Augustus</em>) dihargai dengan emas murni, ternak sapi, dan tanah hektaran.
        </p>
        <p class="text-secondary mb-0">
          Pada puncaknya di Februari 1637, harga kontrak umbi tulip runtuh dalam semalam. Meskipun demikian, peristiwa ini meletakkan fondasi bagi Belanda untuk menjadi eksportir bunga potong dan florikultura nomor satu di planet bumi hingga hari ini.
        </p>
      </div>
    </div>
  </div>

  <!-- Taman Keukenhof -->
  <div class="row g-5 align-items-center mt-4">
    <div class="col-lg-6 order-lg-2">
      <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill mb-2">Taman Bunga Terbesar</span>
      <h2 class="fw-bold font-serif mb-3">Keukenhof: Surga Musim Semi di Lisse, Belanda</h2>
      <p class="text-secondary leading-relaxed">
        Dikenal sebagai <em>Garden of Europe</em>, Keukenhof adalah salah satu taman bunga terindah di dunia dengan luas lebih dari 32 hektar. Setiap musim gugur, lebih dari <strong>7 juta umbi bunga</strong> ditanam dengan tangan oleh para pekebun ahli.
      </p>
      <p class="text-secondary leading-relaxed">
        Saat musim semi tiba (Maret hingga Mei), hamparan karpet warna-warni bunga tulip bermekaran menarik jutaan wisatawan dari seluruh penjuru dunia.
      </p>
      <a href="catalog.php" class="btn btn-tulip">
        <i class="fas fa-seedling me-2"></i> Jelajahi Koleksi Varietas Tulip Kami
      </a>
    </div>
    <div class="col-lg-6 order-lg-1">
      <div class="rounded-4 overflow-hidden shadow">
        <img src="https://images.unsplash.com/photo-1588628566587-dbd176de94b4?auto=format&fit=crop&w=800&q=80" alt="Taman Keukenhof" class="img-fluid w-100" style="height: 350px; object-fit: cover;">
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
