<?php
$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url_segments = explode('/', $url_path);
$current_page = $url_segments[1] ?: 'dashboard';
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
            <ul class="menu">
                <li class="sidebar-title">Menu Utama</li>

                <li class="sidebar-item <?= ($current_page == 'dashboard') ? 'active' : '' ?>">
                    <a href="<?= BASEURL; ?>/dashboard" class='sidebar-link'><i class="bi bi-grid-fill"></i><span>Dashboard</span></a>
                </li>
                <li class="sidebar-title">Data Induk</li>
                <li class="sidebar-item <?= (isset($data['current_page']) && $data['current_page'] == 'dataInduk') ? 'active' : '' ?>">
                    <a href="<?= BASEURL; ?>/siswa/dataInduk" class='sidebar-link'><i class="bi bi-people-fill"></i><span>Data Induk</span></a>
                </li>
                <li class="sidebar-title">Administrasi</li>
                <li class="sidebar-item <?= (isset($data['current_page']) && $data['current_page'] == 'siswa') ? 'active' : '' ?>">
                    <a href="<?= BASEURL; ?>/siswa" class='sidebar-link'><i class="bi bi-people-fill"></i><span>Data Siswa Aktif</span></a>
                </li>
                <li class="sidebar-item <?= ($current_page == 'rombel') ? 'active' : '' ?>">
                    <a href="<?= BASEURL; ?>/rombel" class='sidebar-link'><i class="bi bi-collection-fill"></i><span>Data Rombel</span></a>
                </li>

                <?php if (Auth::checkRole('admin') || Auth::checkRole('waka') || Auth::checkRole('kajur')) : ?>
                    <li class="sidebar-item <?= ($current_page == 'jurusan') ? 'active' : '' ?>">
                        <a href="<?= BASEURL; ?>/jurusan" class='sidebar-link'><i class="bi bi-mortarboard-fill"></i><span>Data Jurusan</span></a>
                    </li>
                <?php endif; ?>

                <?php if (Auth::checkRole('admin')) : ?>
                    <li class="sidebar-item <?= ($current_page == 'guru' && !in_array(($url_segments[2] ?? ''), ['profil', 'editProfil'])) ? 'active' : '' ?>">
                        <a href="<?= BASEURL; ?>/guru/daftar" class='sidebar-link'>
                            <i class="bi bi-person-badge-fill"></i>
                            <span>Data Guru</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?= ($current_page == 'profil') ? 'active' : '' ?>">
                        <a href="<?= BASEURL; ?>/profil" class='sidebar-link'><i class="bi bi-person-circle"></i><span>Profil Sekolah</span></a>
                    </li>

                    <li class="sidebar-item <?= ($current_page == 'log') ? 'active' : '' ?>">
                        <a href="<?= BASEURL; ?>/log" class='sidebar-link'>
                            <i class="bi bi-clock-history"></i>
                            <span>Activity Log</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (Auth::checkRole('admin') || Auth::checkRole('waka')) : ?>
                    <li class="sidebar-title">Mutasi</li>
                    <li class="sidebar-item <?= ($current_page == 'mutasi' && !in_array(($url_segments[2] ?? ''), ['profil', 'editProfil'])) ? 'active' : '' ?>">
                        <a href="<?= BASEURL; ?>/mutasi" class='sidebar-link'>
                            <i class="bi bi-arrow-left-right"></i>
                            <span>Data Mutasi</span>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="sidebar-title">Akun</li>

                <li class="sidebar-item <?= ($current_page == 'guru' && in_array(($url_segments[2] ?? ''), ['profil', 'editProfil'])) ? 'active' : '' ?>">
                    <a href="<?= BASEURL; ?>/guru/profil" class='sidebar-link'>
                        <i class="bi bi-person-circle"></i>
                        <span>Profil Saya</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="<?= BASEURL; ?>/guru/logout" class='sidebar-link'>
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>