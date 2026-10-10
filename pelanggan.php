<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'config.php';

// Fitur Pencarian Cerdas (Nama & No HP)
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$sql = "SELECT * FROM pelanggan 
        WHERE nama LIKE '%$search%' 
        OR REPLACE(nama, ' ', '') LIKE '%$search%' 
        OR no_hp LIKE '%$search%' 
        ORDER BY nama ASC";

$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan - Bad_Laundry</title>
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
        #nav-pelanggan { background: #2b2e32; color: white !important; border-left: 4px solid #0d6efd; }
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
                <h4 class="fw-bold mb-0">Manajemen Pelanggan</h4>
                <p class="text-muted small mb-0">Total: <?= mysqli_num_rows($query); ?> pelanggan terdaftar</p>
            </div>
            <a href="tambah_pelanggan.php" class="btn btn-primary px-4 rounded-pill shadow-sm fw-bold">
                + Tambah Pelanggan
            </a>
        </div>

        <div class="card card-custom mb-4">
            <div class="p-3">
                <form action="" method="GET" class="row g-2">
                    <div class="col-12 col-md-9">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama atau no handphone..." value="<?= $search; ?>">
                    </div>
                    <div class="col-12 col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-dark w-100">Cari</button>
                        <?php if($search != ''): ?>
                            <a href="pelanggan.php" class="btn btn-outline-secondary w-100">Reset</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-custom">
            <div class="p-0"> <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Nama Pelanggan</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($row = mysqli_fetch_assoc($query)) : 
                            ?>
                            <tr>
                                <td class="ps-4"><?= $no++; ?></td>
                                <td><div class="fw-bold"><?= ucwords($row['nama']); ?></div></td>
                                <td><span class="badge bg-light text-dark border"><?= $row['no_hp']; ?></span></td>
                                <td><small class="text-muted"><?= $row['alamat']; ?></small></td>
                                <td class="text-center pe-4">
                                    <div class="btn-group">
                                        <a href="edit_pelanggan.php?id=<?= $row['id_pelanggan']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="hapus_pelanggan.php?id=<?= $row['id_pelanggan']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus pelanggan ini?')">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if(mysqli_num_rows($query) == 0) : ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Data pelanggan tidak ditemukan.</td>
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