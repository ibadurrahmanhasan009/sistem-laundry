<?php
session_start();
include 'config.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Ambil ID dari URL
if (!isset($_GET['id'])) {
    header("Location: pelanggan.php");
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data pelanggan tidak ditemukan!'); window.location='pelanggan.php';</script>";
    exit;
}

// Proses Update
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $hp   = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $almt = mysqli_real_escape_string($conn, $_POST['alamat']);

    $update = mysqli_query($conn, "UPDATE pelanggan SET nama='$nama', no_hp='$hp', alamat='$almt' WHERE id_pelanggan='$id'");
    
    if ($update) {
        echo "<script>alert('Data Pelanggan Berhasil Diperbarui!'); window.location='pelanggan.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelanggan - Bad_Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #1c1e21; color: white; z-index: 1000; }
        .sidebar .nav-link { color: #adb5bd; padding: 12px 25px; transition: 0.2s; }
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
        <div class="mb-4">
            <h4 class="fw-bold mb-0 text-dark">Edit Data Pelanggan</h4>
            <p class="text-muted small">Perbarui data kontak atau alamat pelanggan Mitra Haidar Call.</p>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <div class="card card-custom p-4 border-0">
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control form-control-lg border-2 shadow-sm" value="<?= $data['nama']; ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Nomor WhatsApp / HP</label>
                            <div class="input-group input-group-lg shadow-sm">
                                <span class="input-group-text bg-light border-2">+62</span>
                                <input type="number" name="no_hp" class="form-control border-2" value="<?= $data['no_hp']; ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control border-2 shadow-sm" rows="4" required><?= $data['alamat']; ?></textarea>
                        </div>

                        <div class="mt-2 d-grid d-md-flex gap-2">
                            <button type="submit" name="update" class="btn btn-primary px-5 py-3 fw-bold shadow rounded-pill">
                                ✅ Simpan Perubahan
                            </button>
                            <a href="pelanggan.php" class="btn btn-light px-4 py-3 rounded-pill text-secondary border">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card card-custom p-4 bg-primary text-white border-0 shadow-lg">
                    <h5 class="fw-bold mb-3">Penting!</h5>
                    <p class="small mb-4" style="opacity: 0.9;">
                        Pastikan Nomor HP sudah benar untuk keperluan pengiriman notifikasi atau konfirmasi laundry selesai nantinya.
                    </p>
                    <div class="bg-white bg-opacity-10 p-3 rounded-3">
                        <p class="small mb-0">💡 Perubahan alamat pelanggan akan langsung diaplikasikan pada label pengiriman atau nota cetak terbaru.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>