<?php
$host = "localhost";
$user = "root";
$pass = ""; // Kosong kalo pake laragon
$db   = "laundry_db";

$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Set timezone agar waktu transaksi akurat sesuai daerah
date_default_timezone_set('Asia/Jakarta');
?>