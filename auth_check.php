<?php
// Jangan panggil session_start() lagi di sini kalau di transaksi.php sudah ada
include 'config.php'; // <--- INI KUNCINYA

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>