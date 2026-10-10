<?php
session_start();
include 'config.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// 1. Ambil data lama berdasarkan ID
if (!isset($_GET['id'])) {
    header("Location: paket.php");
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query_lama = mysqli_query($conn, "SELECT * FROM paket WHERE id_paket = '$id'");
$data = mysqli_fetch_assoc($query_lama);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='paket.php';</script>";
    exit;
}

// 2. Proses Update
if (isset($_POST['update'])) {
    $nama_paket = mysqli_real_escape_string($conn, $_POST['nama_paket']);
    $jenis      = mysqli_real_escape_string($conn, $_POST['jenis']);
    $harga      = mysqli_real_escape_string($conn, $_POST['harga']);
    $estimasi   = mysqli_real_escape_string($conn, $_POST['estimasi_waktu']);

    $update = mysqli_query($conn, "UPDATE paket SET 
                nama_paket = '$nama_paket', 
                jenis = '$jenis', 
                harga = '$harga', 
                estimasi_waktu = '$estimasi' 
                WHERE id_paket = '$id'");

    if ($update) {
        echo "<script>alert('Data Paket Berhasil Diupdate!'); window.location='paket.php';</script>";
    } else {
        echo "<script>alert('Gagal update data: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Paket - Bad_Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #1c1e21; color: white; z-index: 1000; transition: 0.3s; }
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
            <h4 class="fw-bold mb-0 text-dark">Edit Paket Laundry</h4>
            <p class="text-muted small">Update data layanan dengan teliti ya bro.</p>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <div class="card card-custom p-4 border-0">
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">NAMA PAKET</label>
                            <input type="text" name="nama_paket" class="form-control form-control-lg border-2 shadow-sm" value="<?= $data['nama_paket']; ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">JENIS LAYANAN</label>
                            <select name="jenis" class="form-select form-select-lg border-2 shadow-sm" required>
                                <option value="kiloan" <?= ($data['jenis'] == 'kiloan') ? 'selected' : ''; ?>>Kiloan (Berat)</option>
                                <option value="satuan" <?= ($data['jenis'] == 'satuan') ? 'selected' : ''; ?>>Satuan (Pcs)</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold text-secondary">HARGA TERBARU</label>
                                <div class="input-group input-group-lg shadow-sm">
                                    <span class="input-group-text bg-light border-2">Rp</span>
                                    <input type="number" name="harga" class="form-control border-2" value="<?= $data['harga']; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold text-secondary">ESTIMASI SELESAI</label>
                                <div class="input-group input-group-lg shadow-sm">
                                    <input type="number" name="estimasi_waktu" class="form-control border-2" value="<?= $data['estimasi_waktu']; ?>" required>
                                    <span class="input-group-text bg-light border-2">Jam</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2 d-grid d-md-flex gap-2">
                            <button type="submit" name="update" class="btn btn-primary px-5 py-3 fw-bold shadow rounded-pill">
                                ✅ Simpan Perubahan
                            </button>
                            <a href="paket.php" class="btn btn-light px-4 py-3 rounded-pill text-secondary border">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card card-custom p-4 bg-primary text-white border-0 shadow-lg">
                    <h5 class="fw-bold mb-3">Tips Edit Data</h5>
                    <p class="small mb-4" style="opacity: 0.9;">
                        Hati-hati saat mengubah <strong>Jenis Paket</strong>. Pastikan sudah sesuai dengan perhitungan operasional (Kiloan vs Satuan).
                    </p>
                    <div class="bg-white bg-opacity-10 p-3 rounded-3">
                        <small class="d-block mb-2 italic"><em>Info Sistem:</em></small>
                        <p class="small mb-0">Perubahan ini akan langsung muncul di halaman <strong>Transaksi Baru</strong> segera setelah lo klik simpan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>