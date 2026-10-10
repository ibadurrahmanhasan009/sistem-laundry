<?php
include 'config.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($id != '') {
    // 1. Hapus dulu data transaksi pelanggan ini (biar gak bentrok relasi)
    mysqli_query($conn, "DELETE FROM transaksi WHERE id_pelanggan = '$id'");

    // 2. Baru hapus data pelanggannya
    $hapus = mysqli_query($conn, "DELETE FROM pelanggan WHERE id_pelanggan = '$id'");

    if ($hapus) {
        header("Location: pelanggan.php?pesan=hapus_berhasil");
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
} else {
    header("Location: pelanggan.php");
}
?>