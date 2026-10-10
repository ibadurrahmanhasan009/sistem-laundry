<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'config.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Ambil data transaksi saat ini
$query = mysqli_query($conn, "SELECT transaksi.*, pelanggan.nama 
                              FROM transaksi 
                              JOIN pelanggan ON transaksi.id_pelanggan = pelanggan.id_pelanggan 
                              WHERE id_transaksi = '$id'");
$data = mysqli_fetch_assoc($query);

// Proses Update ketika tombol simpan diklik
if (isset($_POST['update'])) {
    $status = $_POST['status'];
    $status_bayar = $_POST['status_bayar'];
    
    // Jika status bayar diubah jadi 'dibayar', set tgl_bayar ke waktu sekarang
    $tgl_bayar_sql = "";
    if ($status_bayar == 'dibayar') {
        $tgl_now = date('Y-m-d H:i:s');
        $tgl_bayar_sql = ", tgl_bayar = '$tgl_now'";
    }

    $update = mysqli_query($conn, "UPDATE transaksi SET 
                                   status = '$status', 
                                   status_bayar = '$status_bayar' 
                                   $tgl_bayar_sql 
                                   WHERE id_transaksi = '$id'");

    if ($update) {
        echo "<script>alert('Status Berhasil Diperbarui!'); window.location='transaksi.php';</script>";
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status - Bad_Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .main-content { padding: 40px 20px; display: flex; justify-content: center; }
        .card-update { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background: white; width: 100%; max-width: 500px; padding: 30px; }
    </style>
</head>
<body>

    <div class="main-content">
        <div class="card-update text-center">
            <h4 class="fw-bold mb-1">Update Status</h4>
            <p class="text-muted mb-4 small">Invoice: <?= $data['kode_invoice']; ?></p>
            <hr>
            
            <form method="POST" class="text-start">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Pelanggan</label>
                    <input type="text" class="form-control bg-light" value="<?= $data['nama']; ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Status Laundry</label>
                    <select name="status" class="form-select shadow-sm">
                        <option value="baru" <?= ($data['status'] == 'baru') ? 'selected' : ''; ?>>🔵 BARU</option>
                        <option value="proses" <?= ($data['status'] == 'proses') ? 'selected' : ''; ?>>🟡 PROSES</option>
                        <option value="selesai" <?= ($data['status'] == 'selesai') ? 'selected' : ''; ?>>🟢 SELESAI</option>
                        <option value="diambil" <?= ($data['status'] == 'diambil') ? 'selected' : ''; ?>>⚪ DI-AMBIL</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Status Pembayaran</label>
                    <select name="status_bayar" class="form-select shadow-sm">
                        <option value="belum_bayar" <?= ($data['status_bayar'] == 'belum_bayar') ? 'selected' : ''; ?>>❌ Belum Bayar</option>
                        <option value="dibayar" <?= ($data['status_bayar'] == 'dibayar') ? 'selected' : ''; ?>>✅ Sudah Lunas</option>
                    </select>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" name="update" class="btn btn-primary fw-bold">Simpan Perubahan</button>
                    <a href="transaksi.php" class="btn btn-light border">Batal</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>