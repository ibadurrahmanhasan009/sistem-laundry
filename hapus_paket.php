<?php
session_start();
include 'config.php';

// 1. Cek Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// 2. Ambil ID dari URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // 3. Cek apakah paket ini sedang digunakan di tabel transaksi?
    // Ini penting bro, biar database lo nggak error (Relational Integrity)
    $cek_penggunaan = mysqli_query($conn, "SELECT id_paket FROM detail_transaksi WHERE id_paket = '$id' LIMIT 1");
    
    if (mysqli_num_rows($cek_penggunaan) > 0) {
        // Jika paket sudah pernah dipakai transaksi, jangan dihapus tapi kasih peringatan
        echo "<script>
                alert('Gagal Hapus! Paket ini sudah memiliki riwayat transaksi. Silakan edit saja namanya jika ingin diubah.');
                window.location='paket.php';
              </script>";
    } else {
        // Jika belum pernah dipakai, sikat hapus!
        $hapus = mysqli_query($conn, "DELETE FROM paket WHERE id_paket = '$id'");

        if ($hapus) {
            echo "<script>
                    alert('Paket Berhasil Dihapus!');
                    window.location='paket.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menghapus data!');
                    window.location='paket.php';
                  </script>";
        }
    }
} else {
    // Jika tidak ada ID, balikin ke halaman paket
    header("Location: paket.php");
    exit;
}
?>