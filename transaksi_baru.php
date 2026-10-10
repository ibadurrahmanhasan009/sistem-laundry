<?php
session_start();
include 'config.php';
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }

// Ambil data pelanggan & paket untuk dropdown
$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY nama ASC");
$paket = mysqli_query($conn, "SELECT * FROM paket ORDER BY nama_paket ASC");

if (isset($_POST['proses'])) {
    $id_pelanggan = $_POST['id_pelanggan'];
    $id_paket     = $_POST['id_paket'];
    $berat         = $_POST['berat'];
    $tgl_masuk    = date('Y-m-d H:i:s');
    
    // 1. Ambil harga paket untuk hitung subtotal
    $p = mysqli_query($conn, "SELECT harga, estimasi_waktu FROM paket WHERE id_paket = '$id_paket'");
    $data_p = mysqli_fetch_assoc($p);
    
    $harga_paket = $data_p['harga'];
    $subtotal    = $berat * $harga_paket;
    $total_bayar = $subtotal;
    $invoice     = "LND-" . date('YmdHis');
    
    $estimasi    = $data_p['estimasi_waktu'] ?? 24;
    $batas_waktu = date('Y-m-d H:i:s', strtotime("+$estimasi hours"));

    // 2. Simpan ke tabel TRANSAKSI
    $id_user = $_SESSION['id_user'];
    $sql_t = "INSERT INTO transaksi (id_pelanggan, id_user, kode_invoice, tgl_masuk, batas_waktu, total_bayar, status, status_bayar) 
              VALUES ('$id_pelanggan', '$id_user', '$invoice', '$tgl_masuk', '$batas_waktu', '$total_bayar', 'baru', 'belum_bayar')";
    
    if (mysqli_query($conn, $sql_t)) {
        $id_baru = mysqli_insert_id($conn);
        $keterangan = $_POST['keterangan'] ?? '-'; 
        
        $sql_d = "INSERT INTO detail_transaksi (id_transaksi, id_paket, qty, keterangan, subtotal) 
                  VALUES ('$id_baru', '$id_paket', '$berat', '$keterangan', '$subtotal')";
        
        if (mysqli_query($conn, $sql_d)) {
            echo "<script>alert('Transaksi Berhasil Dibuat!'); window.location='transaksi.php';</script>";
        } else {
            die("Gagal simpan detail: " . mysqli_error($conn));
        }
    } else {
        die("Gagal simpan transaksi utama: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Baru - Bad_Laundry</title>
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
            <h4 class="fw-bold mb-0">Input Transaksi Baru</h4>
            <p class="text-muted small">Silakan isi formulir di bawah untuk mencatat pesanan baru</p>
        </div>

        <div class="row">
            <div class="col-12 col-lg-7">
                <div class="card card-custom p-4">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">PILIH PELANGGAN</label>
                            <select name="id_pelanggan" class="form-select form-select-lg" required style="font-size: 1rem;">
                                <option value="">-- Cari Pelanggan --</option>
                                <?php while($plg = mysqli_fetch_assoc($pelanggan)) : ?>
                                    <option value="<?= $plg['id_pelanggan']; ?>"><?= ucwords($plg['nama']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">PILIH LAYANAN / PAKET</label>
                            <select name="id_paket" class="form-select" required>
                                <option value="">-- Pilih Paket Laundry --</option>
                                <?php while($pkt = mysqli_fetch_assoc($paket)) : ?>
                                    <option value="<?= $pkt['id_paket']; ?>">
                                        <?= strtoupper($pkt['nama_paket']); ?> - Rp <?= number_format($pkt['harga'],0,',','.'); ?> (<?= $pkt['jenis']; ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">BERAT (KG) / QTY</label>
                                <div class="input-group">
                                    <input type="number" name="berat" step="0.1" class="form-control" placeholder="0.0" required>
                                    <span class="input-group-text bg-light">Satuan</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">KETERANGAN</label>
                                <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Putih semua / luntur">
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" name="proses" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-pill">
                                ✅ Proses Transaksi
                            </button>
                            <a href="transaksi.php" class="btn btn-outline-secondary px-4 py-2 rounded-pill">Batal</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12 col-lg-5 mt-4 mt-lg-0">
                <div class="card card-custom bg-light border-0 p-4">
                    <h6 class="fw-bold mb-3">Tips Kasir:</h6>
                    <ul class="small text-muted ps-3">
                        <li>Pastikan berat timbangan sesuai sebelum input.</li>
                        <li>Gunakan kolom keterangan untuk catatan khusus (seperti noda membandel).</li>
                        <li>Batas waktu pengambilan akan terhitung otomatis sesuai estimasi paket.</li>
                        <li>Default status transaksi adalah <b>BARU</b> dan <b>BELUM BAYAR</b>.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>