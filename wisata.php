<?php
session_start();
require "functions.php";

if (isset($_GET['category'])) {
  $wisata = wisataByCategory($_GET['category']);
}
if (!isset($_SESSION['username'])) {
  header('Location: auth/login.php');
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Explore Sumut | Wisata</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>

<body>
  <?php include_once '_partials/navbar.php' ?>

  <div class="hero-image">
    <div class="hero-text text-center fw-semibold">
      <h1 class="fw-bold"><span id="typed-element"></span></h1>
      <p>Jelajahi Keindahan Sumatera Utara</p>
    </div>
  </div>

  <div class="container mt-5" id="wisata">
    <div class="row mb-4">
      <div class="col">
        <h2 class="fw-bold text-green">Cari Objek Wisata</h2>
      </div>
    </div>
    <div class="row mb-4">
      <div class="col">
        <form action="" method="post">
          <div class="input-group">
            <input type="text" name="kategori" class="form-control" placeholder="Cari kategori objek wisata" value="<?= $_GET['category'] ?? '' ?>" readonly>
            <input type="number" name="budget_min" class="form-control" placeholder="Anggaran minimum">
            <input type="number" name="budget_max" class="form-control" placeholder="Anggaran maksimum">
            <button name="categoryWithBudget" class="btn btn-success" type="submit">Cari</button>
          </div>
        </form>
      </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5 justify-content-center" data-aos="fade-up">
      <?php if (empty($wisata['data']) || is_null($wisata['data'])) : ?>
        <p class="btn btn-sm btn-danger">Tidak ada objek wisata</p>
      <?php else : ?>
        <?php
        $delay = 0; // Initialize delay
        foreach ($wisata['data'] as $index => $w) : ?>
          <?php
          $gambarUrl = 'https://via.placeholder.com/150';
          foreach ($wisata['items'] as $g) {
            if (strtolower($w['nama']) == strtolower($g['nama'])) {
              $gambarUrl = 'assets/img/' . $g['url'];
              $lokasi = $g['location'];
              $rating = $g['rating'];
              break;
            }
          }
          ?>
          <div class="col" data-aos="fade-up" data-aos-delay="<?= $delay; ?>">
            <div class="card h-100">
              <img src="<?= $gambarUrl ?>" class="card-img-top" style="height: 300px; object-fit: cover;" alt="<?= $w['nama'] ?>">
              <div class="card-body">
                <h5 class="card-title"><?= $w['nama']; ?></h5>
                <p class="card-text"><?= $w['deskripsi']; ?></p>
              </div>
              <div class="card-footer">
                <small class="text-muted">Biaya: Rp<?= $w['biaya']; ?></small>
                <button class="btn btn-success btn-sm lihat-detail" data-nama="<?= $w['nama']; ?>" data-deskripsi="<?= $w['deskripsi']; ?>" data-rating="<?= $rating; ?>" data-lokasi="<?= $lokasi; ?>" data-biaya="<?= $w['biaya']; ?>" data-gambar="<?= $gambarUrl; ?>">Lihat detail</button>
              </div>
            </div>
          </div>
          <?php $delay += 80; ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>

  <?php include_once '_partials/footer.php' ?>


  <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailModalLabel">Detail Objek Wisata</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
          <div class="container">
            <img id="modal-img" src="" class="modal-img-custom img-fluid mb-3" alt="Gambar Wisata">
            <div class="modal-text-container p-4">
              <h5 id="modal-nama" class="modal-title-custom fw-bold text-primary-custom"></h5>
              <p id="modal-deskripsi" class="modal-desc-custom text-secondary-custom"></p>
              <h5 class="modal-title-custom fw-bold text-primary-custom">Perkiraan Biaya</h5>
              <p class="text-muted fw-semibold">Rp<span id="modal-biaya" class="text-danger-custom"></span></p>
            </div>
            <div class="modal-text-container p-4">
              <h5 class="modal-title-custom fw-bold text-primary-custom">Rating</h5>
              <div id="rating-stars"></div>
            </div>

            <div class="modal-text-container p-4">
              <h5 class="modal-title-custom fw-bold text-primary-custom">Lokasi</h5>
            </div>
            <div class="d-flex justify-content-center">
              <iframe src="" id="modal-map" width="600" height="450" style="border:0;" allowfullscreen="true" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
          </div>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
  <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
  <script>
    function getQueryParam(name) {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get(name);
    }

    const category = getQueryParam('category');
    var typed = new Typed('#typed-element', {
      strings: [
        'Selamat Datang di Explore <span class="text-success">Sumatera Utara</span>',
        'Selamat Datang di Explore <span class="text-success">Sumut</span>' + '<span class="text-white"> Kategori ' + category + '</span>'
      ],
      typeSpeed: 50,
      backSpeed: 50,
      loop: false,
      showCursor: true
    });
  </script>



</body>

</html>