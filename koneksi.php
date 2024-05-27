<?php

$user = "root"; 
$password = "200405";

try {
    $koneksi = new PDO("mysql:host=localhost; dbname=tourist_attraction;", $user, $password);
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "koneksi bermasalah : " . $e;
    die();
}
