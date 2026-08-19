<?php
$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url_segments = explode('/', $url_path);
$current_page = $url_segments[1] ?: 'dashboard';
$seg2 = $url_segments[2] ?? '';
?>

<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="<?= BASEURL; ?>/dashboard">
                        <h3>SchoolCore</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <?php require 'sidebar_menu.php'; ?>
        </div>
    </div>
</div>

<!-- ================= MOBILE: BOTTOM NAVIGATION ================= -->
<!-- Di layar <1200px sidebar disembunyikan; menu pindah ke bawah layar.
     4 menu utama tampil langsung + tombol "Menu" untuk menu lengkap. -->
<nav class="mobile-bottom-nav" id="mobileBottomNav">
    <a href="<?= BASEURL; ?>/dashboard" class="mbn-item <?= ($current_page == 'dashboard') ? 'active' : '' ?>">
        <i class="bi bi-grid-fill"></i>
        <span>Dashboard</span>
    </a>
    <a href="<?= BASEURL; ?>/siswa/dataInduk" class="mbn-item <?= (isset($data['current_page']) && $data['current_page'] == 'dataInduk') ? 'active' : '' ?>">
        <i class="bi bi-people-fill"></i>
        <span>Data Induk</span>
    </a>
    <a href="<?= BASEURL; ?>/siswa" class="mbn-item <?= ($current_page == 'siswa') ? 'active' : '' ?>">
        <i class="bi bi-people-fill"></i>
        <span>Siswa</span>
    </a>
    <a href="<?= BASEURL; ?>/rombel" class="mbn-item <?= ($current_page == 'rombel') ? 'active' : '' ?>">
        <i class="bi bi-collection-fill"></i>
        <span>Rombel</span>
    </a>
    <button type="button" class="mbn-item mbn-menu-btn" id="mobileMenuBtn" aria-label="Menu Lainnya">
        <i class="bi bi-list"></i>
        <span>Menu</span>
    </button>
</nav>

<!-- Backdrop menu mobile -->
<div class="mobile-menu-backdrop" id="mobileMenuBackdrop"></div>

<!-- ================= MOBILE: MENU LENGKAP (bottom sheet) ================= -->
<div class="mobile-menu-sheet" id="mobileMenuSheet" aria-hidden="true">
    <div class="mobile-menu-sheet-header">
        <h6><i class="bi bi-grid-fill"></i> Menu Aplikasi</h6>
        <button type="button" class="mobile-menu-close" id="mobileMenuClose" aria-label="Tutup Menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="sidebar-menu">
        <?php require 'sidebar_menu.php'; ?>
    </div>
</div>
