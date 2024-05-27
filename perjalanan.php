<?php
session_start();
require "functions.php";

$wisata = getItenary($_SESSION['id_user']);

if (!isset($_SESSION['username'])) {
  header('Location: auth/login.php');
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Explore Sumut | Perjalanan Anda</title>
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
        <h2 class="fw-bold text-green">Lihat Jadwal Perjalanan Anda</h2>
      </div>
    </div>

    <?php if (empty($wisata) || is_null($wisata)) : ?>
      <p class="btn btn-sm btn-danger">Tidak ada objek wisata</p>
    <?php else : ?>
      <?php
      $groupedByDate = [];
      foreach ($wisata as $w) {
        $tanggal = $w['date'];
        if (!isset($groupedByDate[$tanggal])) {
          $groupedByDate[$tanggal] = [];
        }
        $groupedByDate[$tanggal][] = $w;
      }
      ?>

      <?php foreach ($groupedByDate as $tanggal => $wisataPerTanggal) : ?>
        <div class="row mb-3">
          <div class="col">
            <h3 class="fw-semibold text-success"><?= date('d F Y', strtotime($tanggal)); ?></h3>
          </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4 mb-5 justify-content-center" data-aos="fade-up">
          <?php $delay = 0; ?>
          <?php foreach ($wisataPerTanggal as $w) : ?>
            <div class="col" data-aos="fade-up" data-aos-delay="<?= $delay; ?>">
              <div class="card h-100">
                <img src="assets/img/<?= $w['url'] ?>" class="card-img-top" style="height: 300px; object-fit: cover;" alt="<?= $w['nama'] ?>">
                <div class="card-body">
                  <h5 class="card-title"><?= $w['nama']; ?></h5>
                  <?php
                  $rating = $w['rating'];
                  $convert = floatval($rating);
                  ?>
                  <div class="rating-stars-container" id="rating-stars-<?= $w['id']; ?>"></div>
                  <script>
                    var rating = <?= $convert ?>;
                    var ratingStarsContainer = document.getElementById("rating-stars-<?= $w['id']; ?>");
                    ratingStarsContainer.innerHTML = "";

                    for (var i = 1; i <= 5; i++) {
                      var star = document.createElement("span");
                      star.classList.add("star");
                      if (i <= rating) {
                        star.classList.add("active");
                      }
                      star.innerHTML = "&#9733;";
                      ratingStarsContainer.appendChild(star);
                    }
                  </script>
                </div>
              </div>
            </div>
            <?php $delay += 80; ?>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>

    <?php endif; ?>
  </div>


  <footer class="footer text-white mt-5" style="background-color: #198754;">
    <div class="container">
      <div class="row py-4">
        <div class="col-md-4" style="color: #feb941;">
          <h5>About Us</h5>
          <p class="">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
        </div>
        <div class="col-md-4" style=" color: #feb941;">
          <h5>Quick Links</h5>
          <ul class="list-unstyled ">
            <li><a href="#" style=" color: #feb941;" class="text-decoration-none">Home</a></li>
            <li><a href="#" style=" color: #feb941;" class="text-decoration-none">About</a></li>
            <li><a href="#" style=" color: #feb941;" class="text-decoration-none">Services</a></li>
            <li><a href="#" style=" color: #feb941;" class="text-decoration-none">Contact</a></li>
          </ul>
        </div>
        <div class="col-md-4" style=" color: #feb941;">
          <h5>Contact Us</h5>
          <ul class="list-unstyled">
            <li><i class="bi bi-geo-alt-fill"></i> 123 Main St, Anytown, USA</li>
            <li><i class="bi bi-telephone-fill"></i> (123) 456-7890</li>
            <li><i class="bi bi-envelope-fill"></i> email@example.com</li>
          </ul>
        </div>
      </div>
      <div class="row" style=" color: #feb941;">
        <div class="col text-center">
          <p class="">&copy; 2024 Your Company. All Rights Reserved.</p>
        </div>
      </div>
    </div>
  </footer>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      once: true
    });
  </script>
  <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
  <script>
    var typed = new Typed('#typed-element', {
      strings: [
        'Selamat Datang di Explore <span class="text-success">Sumatera Utara</span>',
        'Selamat Datang di Explore <span class="text-success">Sumut</span>'
      ],
      typeSpeed: 50,
      backSpeed: 50,
      loop: false,
      showCursor: true
    });
  </script>



</body>

</html>