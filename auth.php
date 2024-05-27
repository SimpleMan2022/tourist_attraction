<?php

include "koneksi.php";
function prosesLogin()
{
  global $koneksi;
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE username = ?";
  $row = $koneksi->prepare($sql);
  $row->execute([$username]);
  $user = $row->fetch(PDO::FETCH_ASSOC);
  if ($user) {
    session_start();

    if ($user['password'] == md5($password)) {

      $_SESSION['id_user'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      header('Location: ../index.php');
    } else {
      $_SESSION['error'] = "Password salah!";
      header('Location: login.php');
    }
  } else {
    $_SESSION['error'] = "Username tidak ditemukan!";
    header('Location: login.php');
  }
  exit();
}

function register()
{
  global $koneksi;
  $username = $_POST['username'];
  $fullname = $_POST['fullname'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE username = ?";
  $row = $koneksi->prepare($sql);
  $row->execute([$username]);
  $user = $row->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    session_start();

    $_SESSION['error'] = "Email sudah terdaftar!";
    header('Location: register.php');
  } else {
    $sql = "INSERT INTO users (username, fullname, password) VALUES (?, ?, ?)";
    $row = $koneksi->prepare($sql);
    $row->execute([$username, $fullname, md5($password)]);
    $_SESSION['success'] = "Akun berhasil dibuat, silahkan login!";
    header('Location: login.php');
  }
}
