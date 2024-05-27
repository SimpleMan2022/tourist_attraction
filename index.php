<?php
session_start();
require "functions.php";
if (!isset($_SESSION['username'])) {
  header('Location: auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selectedKategori'])) {
  $kategori = $_POST['selectedKategori'];
  $wisata = personalized($kategori);
  $_SESSION['wisata'] = $wisata;
  $data_json = json_encode($wisata);
} else {
  $wisata = $_SESSION['wisata'] ?? [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoryWithBudget'])) {
  $kategoriSearch = $_POST['kategori'];
  $budgetMin = $_POST['budget_min'] ?? '0';
  $budgetMax = $_POST['budget_max'] ?? '999999';

  $budgetMin = filter_input(INPUT_POST, 'budget_min', FILTER_VALIDATE_INT, array('options' => array('default' => 0, 'min_range' => 0)));
  $budgetMax = filter_input(INPUT_POST, 'budget_max', FILTER_VALIDATE_INT, array('options' => array('default' => 999999, 'min_range' => 0)));

  $wisata = categoryWithBudget($kategoriSearch, $budgetMin, $budgetMax);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plan'])) {
  $iduser = $_SESSION['id_user'];
  $travelDate = $_POST['travelDate'];
  $travelDestination = $_POST['travelDestination'];
  plan($iduser, $travelDate, $travelDestination);
}

if (isset($_GET['category'])) {
  header('Location: wisata.php');
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Explore Sumut</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
</head>

<body>
  <?php include_once '_partials/navbar.php' ?>
  <p class="d-none list-wisata"><?= $data_json; ?></p>
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
            <input type="text" name="kategori" class="form-control" placeholder="Cari kategori objek wisata">
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
              <div class="card-body card-wisata">
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

    <div class="row mb-4" id="kategori">
      <div class="col" data-aos="fade-right">
        <h2 class="fw-bold text-green">Kategori</h2>
      </div>
    </div>
    <div class="splide" id="kategori-slider">
      <div class="splide__track">
        <ul class="splide__list">
          <li class="splide__slide">
            <div class="col" data-aos="fade-up">
              <div class="card h-100 custom-card">
                <img src="assets/img/kategori-alam.jpg" class="card-img-top" alt="Alam">
                <div class="card-body">
                  <h5 class="card-title"><a class="text-decoration-none text-success" href="wisata.php?category=alam">Alam</a></h5>
                </div>
              </div>
            </div>
          </li>
          <li class="splide__slide">
            <div class="col" data-aos="fade-up" data-aos-delay="100">
              <div class="card h-100 custom-card">
                <img src="assets/img/kategori-sejarah.JPG" class="card-img-top" alt="Sejarah">
                <div class="card-body">
                  <h5 class="card-title"><a class="text-decoration-none text-success" href="wisata.php?category=sejarah">Sejarah</a></h5>
                </div>
              </div>
            </div>
          </li>
          <li class="splide__slide">
            <div class="col" data-aos="fade-up" data-aos-delay="200">
              <div class="card h-100 custom-card">
                <img src="assets/img/kategori-budaya.jpg" class="card-img-top" alt="Seni Budaya">
                <div class="card-body">
                  <h5 class="card-title"><a class="text-decoration-none text-success" href="wisata.php?category=seni_budaya">Seni Budaya</a></h5>
                </div>
              </div>
            </div>
          </li>
          <li class="splide__slide">
            <div class="col" data-aos="fade-up" data-aos-delay="300">
              <div class="card h-100 custom-card">
                <img src="assets/img/kategori-family.jpg" class="card-img-top" alt="Keluarga">
                <div class="card-body">
                  <h5 class="card-title"><a class="text-decoration-none text-success" href="wisata.php?category=keluarga">Keluarga</a></h5>
                </div>
              </div>
            </div>
          </li>
          <li class="splide__slide">
            <div class="col" data-aos="fade-up" data-aos-delay="400">
              <div class="card h-100 custom-card">
                <img src="assets/img/kategori-wahana.jpg" class="card-img-top" alt="wahana">
                <div class="card-body">
                  <h5 class="card-title"><a class="text-decoration-none text-success" href="wisata.php?category=wahana">Wahana</a></h5>
                </div>
              </div>
            </div>
          </li>
          <li class="splide__slide">
            <div class="col" data-aos="fade-up" data-aos-delay="500">
              <div class="card h-100 custom-card">
                <img src="assets/img/kategori-relax.jpg" class=" card-img-top" alt="relaksasi">
                <div class="card-body">
                  <h5 class="card-title"><a class="text-decoration-none text-success" href="wisata.php?category=relaksasi">Relaksasi</a></h5>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>

  </div>

  <svg id="wave" style="transform:rotate(0deg); transition: 0.3s" viewBox="0 0 1440 230" version="1.1" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <linearGradient id="sw-gradient-0" x1="0" x2="0" y1="1" y2="0">
        <stop stop-color="rgba(25, 135, 84, 1)" offset="0%"></stop>
        <stop stop-color="rgba(25, 135, 84, 1)" offset="100%"></stop>
      </linearGradient>
    </defs>
    <path style="transform:translate(0, 0px); opacity:1" fill="url(#sw-gradient-0)" d="M0,92L18.5,103.5C36.9,115,74,138,111,122.7C147.7,107,185,54,222,30.7C258.5,8,295,15,332,46C369.2,77,406,130,443,130.3C480,130,517,77,554,61.3C590.8,46,628,69,665,76.7C701.5,84,738,77,775,88.2C812.3,100,849,130,886,126.5C923.1,123,960,84,997,57.5C1033.8,31,1071,15,1108,23C1144.6,31,1182,61,1218,92C1255.4,123,1292,153,1329,141.8C1366.2,130,1403,77,1440,65.2C1476.9,54,1514,84,1551,84.3C1587.7,84,1625,54,1662,42.2C1698.5,31,1735,38,1772,46C1809.2,54,1846,61,1883,65.2C1920,69,1957,69,1994,72.8C2030.8,77,2068,84,2105,92C2141.5,100,2178,107,2215,126.5C2252.3,146,2289,176,2326,164.8C2363.1,153,2400,100,2437,92C2473.8,84,2511,123,2548,118.8C2584.6,115,2622,69,2640,46L2658.5,23L2658.5,230L2640,230C2621.5,230,2585,230,2548,230C2510.8,230,2474,230,2437,230C2400,230,2363,230,2326,230C2289.2,230,2252,230,2215,230C2178.5,230,2142,230,2105,230C2067.7,230,2031,230,1994,230C1956.9,230,1920,230,1883,230C1846.2,230,1809,230,1772,230C1735.4,230,1698,230,1662,230C1624.6,230,1588,230,1551,230C1513.8,230,1477,230,1440,230C1403.1,230,1366,230,1329,230C1292.3,230,1255,230,1218,230C1181.5,230,1145,230,1108,230C1070.8,230,1034,230,997,230C960,230,923,230,886,230C849.2,230,812,230,775,230C738.5,230,702,230,665,230C627.7,230,591,230,554,230C516.9,230,480,230,443,230C406.2,230,369,230,332,230C295.4,230,258,230,222,230C184.6,230,148,230,111,230C73.8,230,37,230,18,230L0,230Z"></path>
  </svg>
  <div class="container-fluid content-center content-full bg-success" style="margin-top: -2px; margin-bottom: -2px;">
    <div class=" row d-flex justify-content-center align-items-center">
      <div class="col-md-6" data-aos="fade-right" data-aos-delay="200">
        <h1 class="fw-bold">Selamat Datang di Sumatera Utara</h1>
        <p class="fw-semibold">Sumatera Utara, salah satu provinsi terbesar di Indonesia, terkenal dengan keanekaragaman budaya, alam yang memukau, dan sejarah yang kaya. Ibukotanya, Medan, merupakan pusat ekonomi dan perdagangan yang ramai. Dari Danau Toba yang megah hingga kuliner khas Batak yang menggugah selera, Sumatera Utara menawarkan pengalaman yang tak terlupakan bagi setiap pengunjungnya.</p>
      </div>
      <div class="col-md-6" data-aos="fade-left" data-aos-delay="400">
        <img src="assets/img/sumut-map.png" alt="Peta Sumatera Utara" class="img-fluid img-fluid-custom">
      </div>
    </div>
  </div>

  <svg id="wave" style="transform:rotate(180deg); transition: 0.3s" viewBox="0 0 1440 280" version="1.1" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <linearGradient id="sw-gradient-0" x1="0" x2="0" y1="1" y2="0">
        <stop stop-color="rgba(25, 135, 84, 1)" offset="0%"></stop>
        <stop stop-color="rgba(25, 135, 84, 1)" offset="100%"></stop>
      </linearGradient>
    </defs>
    <path style="transform:translate(0, 0px); opacity:1" fill="url(#sw-gradient-0)" d="M0,84L21.8,102.7C43.6,121,87,159,131,168C174.5,177,218,159,262,140C305.5,121,349,103,393,112C436.4,121,480,159,524,154C567.3,149,611,103,655,79.3C698.2,56,742,56,785,84C829.1,112,873,168,916,163.3C960,159,1004,93,1047,74.7C1090.9,56,1135,84,1178,107.3C1221.8,131,1265,149,1309,144.7C1352.7,140,1396,112,1440,88.7C1483.6,65,1527,47,1571,74.7C1614.5,103,1658,177,1702,177.3C1745.5,177,1789,103,1833,93.3C1876.4,84,1920,140,1964,135.3C2007.3,131,2051,65,2095,42C2138.2,19,2182,37,2225,56C2269.1,75,2313,93,2356,98C2400,103,2444,93,2487,74.7C2530.9,56,2575,28,2618,51.3C2661.8,75,2705,149,2749,191.3C2792.7,233,2836,243,2880,205.3C2923.6,168,2967,84,3011,42C3054.5,0,3098,0,3120,0L3141.8,0L3141.8,280L3120,280C3098.2,280,3055,280,3011,280C2967.3,280,2924,280,2880,280C2836.4,280,2793,280,2749,280C2705.5,280,2662,280,2618,280C2574.5,280,2531,280,2487,280C2443.6,280,2400,280,2356,280C2312.7,280,2269,280,2225,280C2181.8,280,2138,280,2095,280C2050.9,280,2007,280,1964,280C1920,280,1876,280,1833,280C1789.1,280,1745,280,1702,280C1658.2,280,1615,280,1571,280C1527.3,280,1484,280,1440,280C1396.4,280,1353,280,1309,280C1265.5,280,1222,280,1178,280C1134.5,280,1091,280,1047,280C1003.6,280,960,280,916,280C872.7,280,829,280,785,280C741.8,280,698,280,655,280C610.9,280,567,280,524,280C480,280,436,280,393,280C349.1,280,305,280,262,280C218.2,280,175,280,131,280C87.3,280,44,280,22,280L0,280Z"></path>
  </svg>

  <div class="container mt-5" data-aos="fade-up">
    <div class="row mb-4" id="kategori" data-aos="fade-right">
      <div class="col">
        <h2 class="fw-bold text-green">Rencana Perjalanan</h2>
      </div>
    </div>
    <div class="d-flex justify-content-center" data-aos="zoom-in">
      <img src="assets/img/itenary.png" class="img-fluid mb-4" alt="...">
    </div>
    <div class="card" style="background-color: #198754;" data-aos="fade-up" data-aos-delay="200">
      <div class="card-body">
        <p class="card-text fw-semibold" style="color: #feb941;">Rencanakan perjalanan Anda dengan mudah menggunakan aplikasi kami. Pilih destinasi, atur itinerary, dan nikmati perjalanan Anda!</p>
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#travelPlanModal">
          Mulai Rencana Perjalanan
        </button>
      </div>
    </div>
  </div>
  <!-- Tombol untuk membuka modal -->

  </div>

  <button id="openChatBtn" class="btn btn-success rounded-circle position-fixed bottom-0 end-0 m-3">
    <i class="fas fa-comments"></i>
  </button>

  <!-- Modal Chat -->
  <div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="chatModalLabel"><i class="fas fa-robot me-2"></i> ExSum Bot</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Isi chat bot -->
          <div class="chat-container">
            <div class="chat-message">Selamat datang di ExSum Bot! Saya adalah bot yang siap membantu Anda dalam mencari objek wisata yang sesuai dengan keinginan Anda. <i class="fas fa-rocket"></i></div>
          </div>

        </div>
        <div class="modal-footer">
          <textarea type="text" class="form-control" placeholder="Ketik pesan..." id="chatInput"></textarea>
          <button type="button" class="btn btn-primary" id="sendMessageBtn">Kirim</button>
        </div>
      </div>
    </div>
  </div>

  <?php include_once '_partials/footer.php' ?>


  <div class="modal fade modal-xl" id="kategoriModal" tabindex="-1" aria-labelledby="kategoriModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="kategoriModalLabel">Pilih Kategori</h5>
        </div>
        <form action="" method="post">
          <input type="hidden" name="selectedKategori" id="selectedKategori">
          <div class="modal-body overflow-auto">
            <div class="row row-cols-3">
              <div class="col mb-5">
                <div class="card custom-card card-modal" data-kategori="alam">
                  <img src="assets/img/kategori-alam.jpg" class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">Alam</h5>
                  </div>
                </div>
              </div>
              <div class="col mb-5">
                <div class="card custom-card card-modal" data-kategori="relaksasi">
                  <img src="assets/img/kategori-relax.jpg" class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">Relaksasi</h5>
                  </div>
                </div>
              </div>
              <div class="col mb-5">
                <div class="card custom-card card-modal" data-kategori="keluarga">
                  <img src="assets/img/kategori-family.jpg" class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">Keluarga</h5>
                  </div>
                </div>
              </div>
              <div class="col mb-5">
                <div class="card custom-card card-modal" data-kategori="sejarah">
                  <img src="assets/img/kategori-sejarah.JPG" class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">Sejarah</h5>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="card custom-card card-modal" data-kategori="seni_budaya">
                  <img src="assets/img/kategori-budaya.jpg" class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">Seni Budaya</h5>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="card custom-card card-modal" data-kategori="wahana">
                  <img src="assets/img/kategori-wahana.jpg" class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">Wahana</h5>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="wisata" class="btn btn-primary" id="simpanKategori" disabled>Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- modal itenary -->

  <div class="modal fade" id="travelPlanModal" tabindex="-1" aria-labelledby="travelPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="travelPlanModalLabel">Rencana Perjalanan Anda</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="post">
            <!-- Date selection -->
            <div class="mb-3">
              <label for="travelDate" class="form-label">Pilih Tanggal</label>
              <input type="date" class="form-control" name="travelDate" id="travelDate" required>
            </div>
            <!-- Category selection -->
            <div class="mb-3">
              <label for="travelDestination" class="form-label">Pilih Wisata</label>
              <select class="form-select" id="travelDestination" name="travelDestination" required>
                <option selected disabled value="">Pilih Wisata</option>
                <?php foreach ($wisata['data'] as $w) : ?>
                  <option value="<?= $w['nama']; ?>"><?= $w['nama']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" name="plan" class="btn btn-primary">Simpan Rencana</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Add Bootstrap 5 JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>


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

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
  <script>
    var dataWisata = `<?php echo $data_json ?? ''; ?>`;
  </script>
  <script src="assets/js/chatbot.js"></script>
  </script>
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
  <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      new Splide('#kategori-slider', {
        type: 'loop',
        perPage: 3,
        gap: '1rem',
        autoplay: true,
        breakpoints: {
          768: {
            perPage: 2,
          },
          480: {
            perPage: 1,
          },
        },
      }).mount();
    });
  </script>

</body>

</html>