<?php
session_start();
include 'config.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $hp   = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $almt = mysqli_real_escape_string($conn, $_POST['alamat']);

    $insert = mysqli_query($conn, "INSERT INTO pelanggan (nama, no_hp, alamat) VALUES ('$nama', '$hp', '$almt')");

    if ($insert) {
        echo "<script>alert('Data Pelanggan Berhasil Ditambahkan!'); window.location='pelanggan.php';</script>";
    } else {
        echo "<script>alert('Gagal tambah data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelanggan - Bad_Laundry</title>
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
        <div class="mb-4">
            <h4 class="fw-bold mb-0">Tambah Pelanggan Baru</h4>
            <p class="text-muted small">Pastikan data nomor HP aktif untuk mempermudah koordinasi</p>
        </div>

        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card card-custom p-4">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control form-control-lg" placeholder="Masukkan nama pelanggan" required style="font-size: 1rem;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Nomor Handphone</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">+62</span>
                                <input type="number" name="no_hp" class="form-control form-control-lg" placeholder="812xxxxx" required style="font-size: 1rem;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Jl. Raya No. XX, Kota..."></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" name="simpan" class="btn btn-primary px-4 py-2 rounded-pill fw-bold shadow-sm">
                                💾 Simpan Data
                            </button>
                            <a href="pelanggan.php" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12 col-lg-5 mt-4 mt-lg-0">
                <div class="card card-custom p-4 bg-info bg-opacity-10 border-0">
                    <h6 class="fw-bold text-info mb-3">Informasi Tambahan:</h6>
                    <p class="small text-muted mb-0">
                        Data pelanggan yang sudah disimpan akan otomatis muncul di pilihan dropdown halaman <strong>Transaksi Baru</strong>. Gunakan format nomor HP yang benar agar nantinya bisa dihubungkan ke fitur notifikasi WhatsApp jika diperlukan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>