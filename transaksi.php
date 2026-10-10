<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'config.php';

// --- LOGIKA FILTER & SEARCH ---
$keyword = $_POST['keyword'] ?? '';
$status_filter = $_POST['status_filter'] ?? '';

$condition = " WHERE 1=1 ";
if ($keyword != "") {
    $condition .= " AND (transaksi.kode_invoice LIKE '%$keyword%' OR pelanggan.nama LIKE '%$keyword%') ";
}
if ($status_filter != "") {
    $condition .= " AND transaksi.status = '$status_filter' ";
}

// Ambil data transaksi dengan JOIN pelanggan
$query = mysqli_query($conn, "SELECT transaksi.*, pelanggan.nama 
                              FROM transaksi 
                              JOIN pelanggan ON transaksi.id_pelanggan = pelanggan.id_pelanggan 
                              $condition
                              ORDER BY transaksi.tgl_masuk DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi - Bad_Laundry</title>
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
        
        /* Badge Status dinamis */
        .bg-baru { background-color: #cfe2ff; color: #084298; }
        .bg-proses { background-color: #fff3cd; color: #856404; }
        .bg-selesai { background-color: #d1e7dd; color: #0f5132; }
        .bg-diambil { background-color: #e2e3e5; color: #41464b; }

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
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h4 class="fw-bold mb-0">Riwayat Transaksi</h4>
                <p class="text-muted small mb-0">Kelola semua pesanan laundry pelanggan</p>
            </div>
            <a href="transaksi_baru.php" class="btn btn-primary px-4 rounded-pill shadow-sm fw-bold">
                + Transaksi Baru
            </a>
        </div>

        <div class="card card-custom mb-4">
            <div class="p-3">
                <form method="POST" class="row g-2">
                    <div class="col-12 col-md-5">
                        <input type="text" name="keyword" class="form-control" placeholder="Cari Nama Pelanggan atau Invoice..." value="<?= $keyword; ?>">
                    </div>
                    <div class="col-12 col-md-3">
                        <select name="status_filter" class="form-select">
                            <option value="">-- Semua Status --</option>
                            <option value="baru" <?= ($status_filter == 'baru') ? 'selected' : ''; ?>>Baru</option>
                            <option value="proses" <?= ($status_filter == 'proses') ? 'selected' : ''; ?>>Proses</option>
                            <option value="selesai" <?= ($status_filter == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                            <option value="diambil" <?= ($status_filter == 'diambil') ? 'selected' : ''; ?>>Diambil</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-dark w-100 fw-bold">Cari / Filter</button>
                        <?php if($keyword != "" || $status_filter != ""): ?>
                            <a href="transaksi.php" class="btn btn-outline-danger w-100 fw-bold">Reset</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-custom">
            <div class="p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($query) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($query)) : 
                                    $status_class = "bg-" . strtolower($row['status']);
                                ?>
                                <tr>
                                    <td>
                                        <a href="detail_transaksi.php?id=<?= $row['id_transaksi']; ?>" class="fw-bold text-decoration-none">
                                            <?= $row['kode_invoice']; ?>
                                        </a>
                                    </td>
                                    <td><?= ucwords($row['nama']); ?></td>
                                    <td class="small text-muted"><?= date('d/m/Y', strtotime($row['tgl_masuk'])); ?></td>
                                    <td class="fw-bold text-dark">Rp <?= number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                                    <td>
                                        <span class="badge <?= $status_class; ?> rounded-pill px-3">
                                            <?= strtoupper($row['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                       <div class="btn-group shadow-sm rounded">
    <a href="detail_transaksi.php?id=<?= $row['id_transaksi']; ?>" class="btn btn-sm btn-outline-primary" title="Lihat Detail">👁️</a>
    <a href="cetak_nota.php?id=<?= $row['id_transaksi']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Cetak Nota">🖨️</a>
    <a href="update_status.php?id=<?= $row['id_transaksi']; ?>" class="btn btn-sm btn-dark fw-bold">Update</a>
    <a href="hapus_transaksi.php?id=<?= $row['id_transaksi']; ?>" 
       class="btn btn-sm btn-danger" 
       onclick="return confirm('Yakin ingin menghapus transaksi ini? Semua detail transaksi juga akan terhapus!')">
       🗑️
    </a>
</div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted small">Data tidak ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>