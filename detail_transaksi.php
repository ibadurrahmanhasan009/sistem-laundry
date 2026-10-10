<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';

// Ambil ID dari URL
if (!isset($_GET['id'])) {
    header("Location: transaksi.php");
    exit;
}

$id_transaksi = mysqli_real_escape_string($conn, $_GET['id']);

// 1. Query Data Utama Transaksi & Pelanggan
$query_transaksi = mysqli_query($conn, "SELECT transaksi.*, pelanggan.nama, pelanggan.no_hp, pelanggan.alamat 
    FROM transaksi 
    JOIN pelanggan ON transaksi.id_pelanggan = pelanggan.id_pelanggan 
    WHERE transaksi.id_transaksi = '$id_transaksi'");
$data = mysqli_fetch_assoc($query_transaksi);

if (!$data) {
    echo "<script>alert('Data transaksi tidak ditemukan!'); window.location='transaksi.php';</script>";
    exit;
}

// 2. Query Detail Item (Rincian Paket)
$query_detail = mysqli_query($conn, "SELECT detail_transaksi.*, paket.nama_paket, paket.harga, paket.jenis 
    FROM detail_transaksi 
    JOIN paket ON detail_transaksi.id_paket = paket.id_paket 
    WHERE detail_transaksi.id_transaksi = '$id_transaksi'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - Bad_Laundry <?= $data['kode_invoice']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #1c1e21; color: white; z-index: 1000; }
        .sidebar .nav-link { color: #adb5bd; padding: 12px 25px; transition: 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #2b2e32; color: white; border-left: 4px solid #0d6efd; }
        .main-content { margin-left: 260px; padding: 30px; transition: 0.3s; }
        .mobile-nav { display: none; background: #1c1e21; color: white; padding: 10px 20px; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background: white; overflow: hidden; }
        .invoice-header { background: #0d6efd; color: white; padding: 30px; }
        .status-badge { padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; background: rgba(255,255,255,0.25); border: 1px solid rgba(255,255,255,0.5); }
        
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; padding: 15px; }
            .mobile-nav { display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1050; }
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        </div>

</body>

    <div class="main-content">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-0">Rincian Transaksi</h4>
                <p class="text-muted small mb-0">Kelola rincian dan cetak nota pelanggan</p>
            </div>
            <a href="transaksi.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Kembali</a>
        </div>

        <div class="card-custom">
            <div class="invoice-header">
                <div class="row align-items-center">
                    <div class="col-md-7 mb-3 mb-md-0">
                        <span class="status-badge mb-2 d-inline-block"><?= $data['status']; ?></span>
                        <h2 class="fw-bold mb-1 text-white"><?= $data['kode_invoice']; ?></h2>
                        <p class="mb-0 opacity-75 small">Tanggal Masuk: <?= date('d M Y | H:i', strtotime($data['tgl_masuk'])); ?> WIB</p>
                    </div>
                    <div class="col-md-5 text-md-end">
                        <p class="mb-0 opacity-75 small text-uppercase fw-bold">Total Pembayaran</p>
                        <h2 class="fw-bold text-white mb-0">Rp <?= number_format($data['total_bayar'], 0, ',', '.'); ?></h2>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <div class="row mb-4">
                    <div class="col-md-6 border-end-md">
                        <h6 class="text-muted text-uppercase small fw-bold mb-3">Informasi Pelanggan</h6>
                        <h5 class="fw-bold mb-1 text-primary"><?= ucwords($data['nama']); ?></h5>
                        <p class="text-dark fw-semibold mb-1 small">📞 <?= $data['no_hp']; ?></p>
                        <p class="text-muted small mb-0">📍 <?= $data['alamat']; ?></p>
                    </div>
                    <div class="col-md-6 text-md-end mt-4 mt-md-0">
                        <h6 class="text-muted text-uppercase small fw-bold mb-2">Batas Waktu / Deadline</h6>
                        <h5 class="text-danger fw-bold">🕒 <?= date('d M Y', strtotime($data['batas_waktu'])); ?></h5>
                        <p class="small text-muted">Harap diselesaikan sebelum tanggal tersebut.</p>
                    </div>
                </div>

                <div class="table-responsive rounded-3 border">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Nama Paket</th>
                                <th>Jenis</th>
                                <th class="text-center">Jumlah/Qty</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end pe-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($item = mysqli_fetch_assoc($query_detail)) : ?>
                            <tr>
                                <td class="ps-3"><span class="fw-bold"><?= $item['nama_paket']; ?></span></td>
                                <td><span class="badge bg-secondary opacity-75"><?= ucfirst($item['jenis']); ?></span></td>
                                <td class="text-center fw-bold"><?= $item['qty']; ?></td>
                                <td class="text-end text-muted">Rp <?= number_format($item['harga'], 0, ',', '.'); ?></td>
                                <td class="text-end fw-bold text-dark pe-3">Rp <?= number_format($item['qty'] * $item['harga'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="table-light border-top-2">
                            <tr>
                                <td colspan="4" class="text-end fw-bold py-3">GRAND TOTAL</td>
                                <td class="text-end fw-bold text-primary pe-3 py-3" style="font-size: 1.2rem;">Rp <?= number_format($data['total_bayar'], 0, ',', '.'); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-4 d-grid d-md-flex gap-2 justify-content-md-end">
                    <a href="cetak_nota.php?id=<?= $id_transaksi; ?>" target="_blank" class="btn btn-dark px-4 py-2 rounded-pill shadow-sm">
                        🖨️ Cetak Nota (PDF)
                    </a>
                    <a href="edit_status.php?id=<?= $id_transaksi; ?>" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                        🔄 Update Status
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>