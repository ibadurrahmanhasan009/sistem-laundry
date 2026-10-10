<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Hapus rincian di detail_transaksi dulu (Foreign Key)
    $hapus_detail = mysqli_query($conn, "DELETE FROM detail_transaksi WHERE id_transaksi = '$id'");

    if ($hapus_detail) {
        // 2. Baru hapus induknya di tabel transaksi
        $hapus_transaksi = mysqli_query($conn, "DELETE FROM transaksi WHERE id_transaksi = '$id'");
        
        if ($hapus_transaksi) {
            echo "<script>alert('Transaksi Berhasil Dihapus!'); window.location='transaksi.php';</script>";
        } else {
            echo "Gagal hapus transaksi utama: " . mysqli_error($conn);
        }
    } else {
        echo "Gagal hapus detail transaksi: " . mysqli_error($conn);
    }
} else {
    header("Location: transaksi.php");
}
?>