<nav class="navbar navbar-expand-lg fixed-top bg-transparent">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#" style="color: #FEB941;">Explore Sumut</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto fw-semibold">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#wisata">Wisata</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#kategori">Kategori</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="perjalanan.php">Perjalanan</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="foto.php">Foto</a>
        </li>
      </ul>
      <ul class="navbar-nav fw-semibold">
        <!-- User Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link dropbtn" href="#" id="userDropdown" role="button">
            <!-- Display the logged-in user's name here -->
            <span id="logged-in-user"><i class="fas fa-user"></i> <?= $_SESSION['username']; ?></span>
          </a>
          <div class="dropdown-content" aria-labelledby="userDropdown">
            <a href="auth/logout.php" id="logout-link">Logout</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>