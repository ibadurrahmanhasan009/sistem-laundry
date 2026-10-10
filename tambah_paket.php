<?php
session_start();
include 'config.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama_paket = mysqli_real_escape_string($conn, $_POST['nama_paket']);
    $jenis      = mysqli_real_escape_string($conn, $_POST['jenis']);
    $harga      = mysqli_real_escape_string($conn, $_POST['harga']);
    $estimasi   = mysqli_real_escape_string($conn, $_POST['estimasi_waktu']);

    $insert = mysqli_query($conn, "INSERT INTO paket (nama_paket, jenis, harga, estimasi_waktu) 
                                   VALUES ('$nama_paket', '$jenis', '$harga', '$estimasi')");

    if ($insert) {
        echo "<script>alert('Paket Baru Berhasil Ditambahkan!'); window.location='paket.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah paket: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Paket - Bad_Laundry</title>
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
            <h4 class="fw-bold mb-0">Tambah Paket Baru</h4>
            <p class="text-muted small">Buat layanan laundry baru untuk pelanggan lo.</p>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <div class="card card-custom p-4">
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Nama Paket</label>
                            <input type="text" name="nama_paket" class="form-control form-control-lg border-2" placeholder="Contoh: Cuci Karpet Ekspres" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Jenis Paket</label>
                            <select name="jenis" class="form-select form-select-lg border-2" required>
                                <option value="" selected disabled>-- Pilih Jenis --</option>
                                <option value="kiloan">Kiloan (Berat/Kg)</option>
                                <option value="satuan">Satuan (Per-Pcs)</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold text-secondary text-uppercase">Harga (Rp)</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-2">Rp</span>
                                    <input type="number" name="harga" class="form-control border-2" placeholder="10000" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold text-secondary text-uppercase">Estimasi Jam</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="estimasi_waktu" class="form-control border-2" placeholder="24" required>
                                    <span class="input-group-text bg-light border-2">Jam</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2 d-grid d-md-flex gap-2">
                            <button type="submit" name="simpan" class="btn btn-primary px-5 py-3 fw-bold rounded-pill shadow">
                                ➕ Tambah Paket
                            </button>
                            <a href="paket.php" class="btn btn-outline-secondary px-4 py-3 rounded-pill">Batal</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card card-custom border-0 bg-success bg-opacity-10 p-4">
                    <h6 class="fw-bold text-success mb-3">Informasi:</h6>
                    <ul class="small text-muted mb-0 ps-3">
                        <li class="mb-2"><strong>Kiloan:</strong> Biasanya untuk cuci lipat atau cuci setrika biasa.</li>
                        <li class="mb-2"><strong>Satuan:</strong> Untuk barang spesial seperti bed cover, sepatu, atau jas.</li>
                        <li><strong>Estimasi:</strong> Gunakan hitungan jam (Contoh: 1 hari = 24 jam).</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>s