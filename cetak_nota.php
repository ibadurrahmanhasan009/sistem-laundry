<?php
include 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("ID Transaksi tidak valid.");
}

// QUERY VERSI AMAN: Kita fokus ke Transaksi dan Pelanggan dulu
$sql = "SELECT transaksi.*, 
               pelanggan.nama AS nama_pelanggan, 
               pelanggan.no_hp,
               paket.nama_paket, 
               paket.harga AS harga_paket,
               detail_transaksi.qty AS jumlah
        FROM transaksi 
        LEFT JOIN pelanggan ON transaksi.id_pelanggan = pelanggan.id_pelanggan 
        LEFT JOIN detail_transaksi ON transaksi.id_transaksi = detail_transaksi.id_transaksi
        LEFT JOIN paket ON detail_transaksi.id_paket = paket.id_paket 
        WHERE transaksi.id_transaksi = $id";

$query = mysqli_query($conn, $sql);

// Tambahkan ini untuk cek apakah query error atau tidak
if (!$query) {
    die("Kesalahan Database: " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($query);

// Jika masih tidak ditemukan, kita coba query simpel tanpa join detail
if (!$data) {
    $sql_simpel = "SELECT transaksi.*, pelanggan.nama AS nama_pelanggan 
                   FROM transaksi 
                   LEFT JOIN pelanggan ON transaksi.id_pelanggan = pelanggan.id_pelanggan 
                   WHERE transaksi.id_transaksi = $id";
    $query_simpel = mysqli_query($conn, $sql_simpel);
    $data = mysqli_fetch_assoc($query_simpel);
    
    if (!$data) {
        die("Data dengan ID $id benar-benar tidak ada di tabel transaksi!");
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota_<?= $data['kode_invoice']; ?></title>
    <style>
        @media print {
            @page { margin: 0; }
            body { margin: 0.5cm; }
        }
        body { 
            font-family: 'Courier New', Courier, monospace; 
            width: 300px; 
            font-size: 12px; 
            color: #000;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        hr { border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        .info-table td { vertical-align: top; padding: 2px 0; }
        .total-table td { padding: 3px 0; }
        .footer { margin-top: 20px; font-size: 10px; }
    </style>
</head>
<body onload="window.print()">

    <div class="center">
        <h3 style="margin-bottom: 5px;">BAD LAUNDRY</h3>
        <p style="margin: 0;">Jl. Mbay_Nagekeo, Hobo</p>
        <p style="margin: 0;">WA: 0851-3747-4756</p>
    </div>

    <hr>

   <table class="info-table">
    <tr>
        <td>No. Nota</td>
        <td>: <?= $data['kode_invoice']; ?></td>
    </tr>
    <tr>
        <td>Pelanggan</td>
        <td>: <?= $data['nama_pelanggan']; ?></td>
    </tr>
    <tr>
        <td>No. HP</td>
        <td>: <?= $data['no_hp'] ? $data['no_hp'] : '-'; ?></td>
    </tr>
    <tr>
        <td>Tgl Masuk</td>
        <td>: <?= date('d/m/Y H:i', strtotime($data['tgl_masuk'])); ?></td>
    </tr>
</table>

    <hr>

  <hr>
  <hr>
   <table>
        <thead>
            <tr align="left">
                <th>Layanan</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong><?= isset($data['nama_paket']) ? $data['nama_paket'] : 'Layanan Laundry'; ?></strong><br>
                    <small><?= isset($data['jumlah']) ? $data['jumlah'] : '1'; ?> x Rp <?= number_format(isset($data['harga_paket']) ? $data['harga_paket'] : $data['total_bayar']); ?></small>
                </td>
                <td class="right">
                    Rp <?= number_format($data['total_bayar']); ?>
                </td>
            </tr>
        </tbody>
    </table>

    <hr>

    <div class="center">
        <strong>STATUS: <?= strtoupper($data['status_bayar']); ?></strong><br>
        <p class="footer">
            Periksa kembali cucian Anda.<br>
            Barang yang tidak diambil dalam 30 hari<br>
            di luar tanggung jawab kami.<br><br>
            --- TERIMA KASIH ---
        </p>
    </div>

</body>
</html>