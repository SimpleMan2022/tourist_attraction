<?php
session_start();
require "../auth.php";

if (isset($_POST['submit'])) {
    prosesLogin();
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>

<body class="">
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card rounded my-5 mx-4 mx-md-0" style="max-width: 800px; width: 90%; height: auto;">
            <div class="row g-0">
                <div class="col-md-6 d-flex justify-content-center align-items-center">
                    <img src="../assets/img/samosir.jpg" class="img-fluid h-100 w-100 object-fit-cover" alt="img" loading="lazy">
                </div>

                <div class="col-md-6 p-4">
                    <div class="mb-4">
                        <h3 class="fw-bold">Halo, Selamat Datang di <span class="text-success">Explore Sumut</span>!</h3>
                        <small class="text-muted fw-semibold">Silahkan masuk ke akunmu</small>
                    </div>
                    <?php if (isset($_SESSION['success'])) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= $_SESSION['success'];
                            unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error'])) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $_SESSION['error'];
                            unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input autofocus required id="username" type="username" class="form-control rounded" placeholder="Johndoe" name="username">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input required id="password" type="password" class="form-control rounded" placeholder="****" name="password">
                        </div>
                        <div class="">

                            <a class="text-decoration-none" href="#"><i class="ri-facebook-circle-fill text-dark" style="font-size: 30px;"></i></a>
                            <a class="text-decoration-none mx-3" href="#"><i class="ri-google-fill text-dark" style="font-size: 30px;"></i></a>
                            <a class="text-decoration-none" href="#"><i class="ri-github-fill text-dark" style="font-size: 30px;"></i></a>
                        </div>
                        <p class="fw-semibold">Belum Punya Akun? <a href="register.php">Daftar</a></p>
                        <div class="d-flex justify-content-end">
                            <button type="submit" name="submit" class="btn btn-sm rounded text-white" style="background-color: #3085C3;">
                                <span>Masuk <i class="ri-login-box-line"></i></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>