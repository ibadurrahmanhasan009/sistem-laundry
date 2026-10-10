<?php
session_start();
include 'config.php';

// Cek Login & Role (Gue sesuaikan biar aman)
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$tgl_awal  = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d');

// Query ambil data yang sudah dibayar saja
$query = mysqli_query($conn, "SELECT transaksi.*, pelanggan.nama 
                              FROM transaksi 
                              JOIN pelanggan ON transaksi.id_pelanggan = pelanggan.id_pelanggan 
                              WHERE tgl_masuk BETWEEN '$tgl_awal 00:00:00' AND '$tgl_akhir 23:59:59'
                              AND status_bayar = 'dibayar'
                              ORDER BY tgl_masuk ASC");

$total_pendapatan = 0;
$jumlah_transaksi = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - Bad_Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #1c1e21; color: white; z-index: 1000; }
        .sidebar .nav-link { color: #adb5bd; padding: 12px 25px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #2b2e32; color: white; border-left: 4px solid #0d6efd; }
        .main-content { margin-left: 260px; padding: 30px; transition: 0.3s; }
        .mobile-nav { display: none; background: #1c1e21; color: white; padding: 10px 20px; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background: white; }
        .stats-card { background: linear-gradient(45deg, #0d6efd, #0043a8); color: white; border-radius: 15px; padding: 20px; }
        
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; padding: 15px; }
            .mobile-nav { display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1050; }
        }

        /* Styling khusus cetak */
        @media print {
            .sidebar, .mobile-nav, .filter-section, .btn-print { display: none !important; }
            .main-content { margin-left: 0 !important; padding: 0 !important; }
            .card-custom { box-shadow: none !important; border: 1px solid #ddd !important; }
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        </div>

</body>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h4 class="fw-bold mb-0">Laporan Keuangan</h4>
                <p class="text-muted small mb-0">Periode: <?= date('d M Y', strtotime($tgl_awal)); ?> - <?= date('d M Y', strtotime($tgl_akhir)); ?></p>
            </div>
            <button onclick="window.print()" class="btn btn-dark px-4 rounded-pill shadow-sm fw-bold btn-print">
                🖨️ Cetak Laporan
            </button>
        </div>

        <div class="card card-custom mb-4 filter-section">
            <div class="p-3">
                <form method="GET" class="row g-2 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="small fw-bold text-muted">DARI TANGGAL</label>
                        <input type="date" name="tgl_awal" class="form-control" value="<?= $tgl_awal; ?>">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="small fw-bold text-muted">SAMPAI TANGGAL</label>
                        <input type="date" name="tgl_akhir" class="form-control" value="<?= $tgl_akhir; ?>">
                    </div>
                    <div class="col-12 col-md-4">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Filter Laporan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="stats-card shadow-sm mb-3 mb-md-0">
                    <small class="text-uppercase fw-bold" style="opacity: 0.8;">Total Pendapatan (Lunas)</small>
                    <h2 class="fw-bold mb-0 mt-1" id="display-total">Rp 0</h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-custom p-3 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold">JUMLAH TRANSAKSI</small>
                        <h2 class="fw-bold mb-0 text-dark"><?= $jumlah_transaksi; ?></h2>
                    </div>
                    <div class="text-primary" style="font-size: 2rem;">🧾</div>
                </div>
            </div>
        </div>

        <div class="card card-custom">
            <div class="p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Tgl Selesai</th>
                                <th>Invoice</th>
                                <th>Pelanggan</th>
                                <th class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            while($row = mysqli_fetch_assoc($query)) : 
                                $total_pendapatan += $row['total_bayar'];
                            ?>
                            <tr>
                                <td class="ps-4"><?= $no++; ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tgl_masuk'])); ?></td>
                                <td class="fw-bold"><?= $row['kode_invoice']; ?></td>
                                <td><?= ucwords($row['nama']); ?></td>
                                <td class="text-end pe-4 fw-bold text-primary">Rp <?= number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php endwhile; ?>
                            
                            <?php if($jumlah_transaksi == 0) : ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Tidak ada pendapatan pada periode ini.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <th colspan="4" class="ps-4 py-3">GRAND TOTAL</th>
                                <th class="text-end pe-4 py-3">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('display-total').innerText = "Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?>";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>