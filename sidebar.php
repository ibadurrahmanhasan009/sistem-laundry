<?php
// Ambil nama file yang lagi dibuka sekarang
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
    body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
    
    /* Sidebar Desktop */
    .sidebar { width: 260px; height: 100vh; position: fixed; background: #ffffff; color: black; transition: 0.3s; z-index: 1000; border-right: 1px solid #ddd; }
    .sidebar .nav-link { color: #333; padding: 12px 25px; border-radius: 0; font-weight: 500; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #2b2e32; color: white !important; border-left: 4px solid #000000; }
    
    /* Main Content Adjustment */
    .main-content { margin-left: 260px; padding: 20px; transition: 0.3s; }
    
    /* Mobile Nav Bar */
    .mobile-nav { display: none; background: #ffffff !important; padding: 10px 20px; border-bottom: 1px solid #ddd; }
    /* Paksa teks judul mobile nav berwarna HITAM */
    .mobile-nav h6 { color: #000000 !important; font-weight: 700 !important; }

    @media (max-width: 992px) {
        .sidebar { transform: translateX(-100%); }
        .main-content { margin-left: 0; padding-top: 20px; }
        .mobile-nav { display: flex !important; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1050; }
    }

    /* Fix logo & title di sidebar agar tidak nabrak */
    .sidebar h5 { color: #000 !important; }
</style>

<div class="mobile-nav shadow-sm">
    <div class="d-flex align-items-center">
        <img src="img/MY LOGO.png" alt="Logo" height="35" class="me-2">
        <h6 class="mb-0 fw-bold text-dark">Bad_Laundry</h6>
    </div>
    <button class="btn btn-dark btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">☰ Menu</button>
</div>

<div class="sidebar d-none d-lg-block">
    <div class="text-center py-4 border-bottom">
        <img src="img/MY LOGO.png" alt="Logo" class="img-fluid mb-2" style="max-height: 80px;">
        <h5 class="fw-bold mb-0 text-dark">Bad_Laundry</h5>
    </div>
    <nav class="nav flex-column mt-3">
        <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php"> Dashboard</a>
        <a class="nav-link <?= ($current_page == 'pelanggan.php' || $current_page == 'edit_pelanggan.php') ? 'active' : ''; ?>" href="pelanggan.php">Pelanggan</a>
        <a class="nav-link <?= ($current_page == 'paket.php') ? 'active' : ''; ?>" href="paket.php"> Paket Laundry</a>
        <a class="nav-link <?= ($current_page == 'transaksi.php' || $current_page == 'transaksi_baru.php') ? 'active' : ''; ?>" href="transaksi.php">Transaksi</a>
        <a class="nav-link <?= ($current_page == 'laporan.php') ? 'active' : ''; ?>" href="laporan.php"> Laporan</a>
        <div class="mt-4 px-3">
            <a class="nav-link text-danger border border-danger rounded-pill text-center" href="logout.php">Logout</a>
        </div>
    </nav>
</div>

<div class="offcanvas offcanvas-start bg-white" tabindex="-1" id="mobileSidebar" style="width: 280px;">
    <div class="offcanvas-header border-bottom">
        <div class="d-flex align-items-center">
            <img src="img/MY LOGO.png" alt="Logo" height="40" class="me-2">
            <h5 class="offcanvas-title fw-bold text-dark">Bad_Laundry</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <nav class="nav flex-column">
            <a class="nav-link py-3 px-4 border-bottom <?= ($current_page == 'index.php') ? 'active bg-dark text-white' : 'text-dark'; ?>" href="index.php">Dashboard</a>
            <a class="nav-link py-3 px-4 border-bottom <?= ($current_page == 'pelanggan.php' || $current_page == 'edit_pelanggan.php') ? 'active bg-dark text-white' : 'text-dark'; ?>" href="pelanggan.php">Pelanggan</a>
            <a class="nav-link py-3 px-4 border-bottom <?= ($current_page == 'paket.php') ? 'active bg-dark text-white' : 'text-dark'; ?>" href="paket.php">Paket Laundry</a>
            <a class="nav-link py-3 px-4 border-bottom <?= ($current_page == 'transaksi.php' || $current_page == 'transaksi_baru.php') ? 'active bg-dark text-white' : 'text-dark'; ?>" href="transaksi.php">Transaksi</a>
            <a class="nav-link py-3 px-4 border-bottom <?= ($current_page == 'laporan.php') ? 'active bg-dark text-white' : 'text-dark'; ?>" href="laporan.php">Laporan</a>
            <a class="nav-link py-3 px-4 text-danger fw-bold" href="logout.php">Logout</a>
        </nav>
    </div>
</div>