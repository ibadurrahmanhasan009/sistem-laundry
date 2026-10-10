<?php
include 'config.php';
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }

$query = mysqli_query($conn, "SELECT * FROM paket ORDER BY jenis DESC, nama_paket ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Paket - Bad_Laundry</title>
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
                <h4 class="fw-bold mb-0">Daftar Paket Laundry</h4>
                <p class="text-muted small mb-0">Atur layanan dan harga laundry lo</p>
            </div>
            <a href="tambah_paket.php" class="btn btn-primary px-4 rounded-pill shadow-sm fw-bold">
                + Tambah Paket
            </a>
        </div>

        <div class="card card-custom">
            <div class="p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Nama Paket</th>
                                <th>Jenis</th>
                                <th>Harga / Satuan</th>
                                <th>Estimasi</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($query)) : 
                                $badge_color = ($row['jenis'] == 'kiloan') ? 'bg-info' : 'bg-warning text-dark';
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?= $row['nama_paket']; ?></div>
                                </td>
                                <td>
                                    <span class="badge <?= $badge_color; ?> rounded-pill px-3 text-uppercase" style="font-size: 0.7rem;">
                                        <?= $row['jenis']; ?>
                                    </span>
                                </td>
                                <td class="fw-bold">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="text-muted"><i class="bi bi-clock"></i> <?= $row['estimasi_waktu']; ?> Jam</span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group shadow-sm">
                                        <a href="edit_paket.php?id=<?= $row['id_paket']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                       <a href="hapus_paket.php?id=<?= $row['id_paket']; ?>" 
   class="btn btn-sm btn-outline-danger" 
   onclick="return confirm('Yakin mau hapus paket ini? Data yang dihapus tidak bisa dikembalikan!')">
   Hapus
</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if(mysqli_num_rows($query) == 0) : ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada paket laundry.</td>
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