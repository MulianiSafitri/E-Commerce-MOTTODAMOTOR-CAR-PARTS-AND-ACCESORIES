<?php
// Config.php - Database Configuration

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'mottoda_motor';

// Base URL DINAMIS (jalan di localhost & hosting)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/";

// Koneksi database
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Timezone
date_default_timezone_set('Asia/Jakarta');
