<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'config.php';

// Ambil data ringkasan
$total_pelanggan = mysqli_num_rows(mysqli_query($conn, "SELECT id_pelanggan FROM pelanggan"));
$transaksi_baru = mysqli_num_rows(mysqli_query($conn, "SELECT id_transaksi FROM transaksi WHERE status='baru'"));
$pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_bayar) as total FROM transaksi WHERE status_bayar='dibayar'"));

// QUERY DATA UNTUK GRAFIK (7 Hari Terakhir)
$label_grafik = [];
$data_grafik = [];
for ($i = 6; $i >= 0; $i--) {
    $tgl = date('Y-m-d', strtotime("-$i days"));
    $label_grafik[] = date('d M', strtotime($tgl));
    
    $sql_grafik = mysqli_query($conn, "SELECT SUM(total_bayar) as total FROM transaksi 
                                       WHERE DATE(tgl_masuk) = '$tgl' AND status_bayar='dibayar'");
    $res_grafik = mysqli_fetch_assoc($sql_grafik);
    $data_grafik[] = $res_grafik['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Bad Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #ffffff; color: black; transition: 0.3s; z-index: 1000; border-right: 1px solid #ddd; }
        .sidebar .nav-link { color: #333; padding: 12px 25px; border-radius: 0; font-weight: 500; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #2b2e32; color: white !important; border-left: 4px solid #000000; }
        .main-content { margin-left: 260px; padding: 20px; transition: 0.3s; }
        .mobile-nav { display: none; background: #ffffff; padding: 10px 20px; border-bottom: 1px solid #ddd; }
        .card-box { border: none; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        .chart-container { position: relative; height: 300px; width: 100%; }
        
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; padding-top: 20px; }
            .mobile-nav { display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1050; }
        }
        .sidebar h5{
            color: #000
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        </div>

</body>

    <div class="main-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold">Selamat Datang, <?= $_SESSION['nama'] ?? 'Admin'; ?> </h4>
                <span class="badge bg-white text-dark shadow-sm p-2 px-3 rounded-pill border"><?= date('l, d M Y'); ?></span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card card-box p-3 bg-primary text-white">
                        <small class="opacity-75">Total Pelanggan</small>
                        <h2 class="fw-bold mb-0"><?= $total_pelanggan; ?></h2>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card card-box p-3 bg-warning text-dark">
                        <small class="opacity-75">Cucian Baru (Antrian)</small>
                        <h2 class="fw-bold mb-0"><?= $transaksi_baru; ?></h2>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-4">
                    <div class="card card-box p-3 bg-success text-white">
                        <small class="opacity-75">Total Pendapatan</small>
                        <h2 class="fw-bold mb-0">Rp <?= number_format($pendapatan['total'] ?? 0, 0, ',', '.'); ?></h2>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-xl-8">
                    <div class="card card-box p-4 bg-white">
                        <h5 class="fw-bold mb-4">Statistik Pendapatan (7 Hari Terakhir)</h5>
                        <div class="chart-container">
                            <canvas id="incomeChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="card card-box p-4 h-100 bg-white">
                        <h5 class="fw-bold mb-3 text-center">Aksi Cepat</h5>
                        <div class="d-grid gap-3">
                            <a href="transaksi_baru.php" class="btn btn-primary py-3 fw-bold rounded-pill shadow-sm">Input Transaksi</a>
                            <a href="tambah_pelanggan.php" class="btn btn-outline-dark py-3 rounded-pill">Tambah Pelanggan</a>
                            <a href="laporan.php" class="btn btn-light py-3 border rounded-pill"> Lihat Laporan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ctx = document.getElementById('incomeChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($label_grafik); ?>,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: <?= json_encode($data_grafik); ?>,
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>