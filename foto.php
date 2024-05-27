<?php
session_start();
require "functions.php";

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
        <h2 class="fw-bold text-green">Lihat Foto Wisata</h2>
      </div>
    </div>
    <div class="row mb-4">
      <div class="col">
        <form id="searchForm">
          <div class="input-group">
            <input type="text" id="kategori" name="kategori" class="form-control" placeholder="Lihat foto wisata">
            <button id="searchButton" class="btn btn-success" type="submit">Cari</button>
          </div>
        </form>
      </div>
    </div>
    <!-- card -->
    <div class="row row-cols-1 row-cols-md-3 g-4" id="results">
      <!-- Results will be appended here -->
    </div>
  </div>


  <?php include_once '_partials/footer.php' ?>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
  <script>
    $(document).ready(function() {
      $('#searchForm').submit(function(event) {
        event.preventDefault(); // Mencegah form melakukan submit secara default

        var kategori = $('#kategori').val(); // Ambil nilai input
        var url = 'https://api.ngodingaja.my.id/api/pinterest';
        var fullUrl = url + '?search=' + encodeURIComponent(kategori);

        console.log('Request URL:', fullUrl); // Log URL lengkap

        $.ajax({
          url: fullUrl,
          method: 'GET',
          success: function(response) {
            $('#results').empty(); // Kosongkan hasil sebelumnya

            // Loop melalui array hasil
            $.each(response.hasil, function(index, item) {
              var card = `
                            <div class="col">
                                <div class="card custom-card card-modal">
                                    <img src="${item}" class="card-img-top w-100 h-100" alt="Image">
                                </div>
                            </div>`;
              $('#results').append(card);
            });
          },
          error: function() {
            alert('Terjadi kesalahan. Silakan coba lagi.');
          }
        });
      });
    });
  </script>

</body>

</html>