<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        /* Mengatur variabel warna persis seperti Mazer */
        :root {
            --bg-color: #f2f7ff;
            --card-bg: #ffffff;
            --primary: #435ebe;
            --primary-hover: #394fa3;
            --text-dark: #25396f;
            --text-muted: #7c8db5;
            --border-color: #edf2f7;
            --success: #198754;
            --info-bg: #e5f9f6;
            --info-text: #399d93;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            padding: 30px 20px;
        }

        /* Container Pembungkus (Max width agar rapi di desktop & full di mobile) */
        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        /* Header Title */
        .page-header {
            margin-bottom: 30px;
        }

        .burger-icon {
            color: var(--primary);
            font-size: 28px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 15px;
        }

        .page-header h3 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .page-header p {
            color: var(--text-muted);
            font-size: 15px;
        }

        /* Card Global */
        .card {
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-body {
            padding: 30px;
        }

        /* Card Profil (Rata Tengah) */
        .profile-section {
            text-align: center;
        }

        .avatar {
            width: 70px;
            height: 70px;
            background-color: var(--primary);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 600;
            margin: 0 auto 15px auto;
        }

        .profile-name {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .profile-id {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 25px;
        }

        /* Tombol */
        .btn-primary {
            display: block;
            width: 100%;
            background-color: var(--primary);
            color: #fff;
            text-decoration: none;
            padding: 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-primary i {
            margin-right: 8px;
        }

        /* Header di dalam Card */
        .card-header-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        /* List Group (Info Akademik) */
        .list-group {
            list-style: none;
        }

        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
            font-size: 15px;
            color: var(--text-muted);
        }

        .list-group-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .list-group-item .value {
            font-weight: 700;
            color: var(--text-dark);
        }

        /* Badge Status */
        .badge-success {
            background-color: var(--success);
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        /* Alert Notifikasi */
        .alert-info {
            background-color: var(--info-bg);
            color: var(--info-text);
            padding: 15px 20px;
            border-radius: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-info i {
            font-size: 18px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="page-header">
            <a href="#" class="burger-icon"><i class="bi bi-justify"></i></a>
            <h3>Dashboard Siswa</h3>
            <p>Ringkasan informasi data diri dan akademik Anda.</p>
        </div>
        <?php if (isset($_SESSION['error_dashboard'])) : ?>
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert" style="border-radius: 8px;">
                <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.2rem;"></i>
                <div>
                    <strong>Akses Ditolak!</strong> <?= $_SESSION['error_dashboard']; ?>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error_dashboard']); // Hapus session setelah ditampilkan agar tidak muncul terus 
            ?>
        <?php endif; ?>
        <div class="card">
            <div class="card-body profile-section">
                <div class="avatar">
                    <?= strtoupper(substr($_SESSION['data_siswa']['nama_siswa'], 0, 1)); ?>
                </div>
                <div class="profile-name">
                    Halo, <?= htmlspecialchars(strtoupper($_SESSION['data_siswa']['nama_siswa'])); ?>!
                </div>
                <div class="profile-id">
                    Nomor Induk: <?= htmlspecialchars($_SESSION['data_siswa']['no_induk']); ?>
                </div>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTokenSiswa">
                    <i class="bi bi-person-lines-fill"></i> Lihat Detail Data
                </button>


            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="card-header-title mb-3">Info Akademik Singkat</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item border-0 px-0 d-flex justify-content-between">
                        <span>Status Siswa</span>
                        <span class="badge-success">Aktif</span>
                    </li>
                    <li class="list-group-item border-0 px-0 d-flex justify-content-between">
                        <span>Kelas</span>
                        <span class="value"><?= htmlspecialchars($data['siswa']->nama_rombel ?? 'Data tidak tersedia'); ?></span>
                    </li>
                    <li class="list-group-item border-0 px-0 d-flex justify-content-between">
                        <span>Jurusan</span>
                        <span class="value"><?= htmlspecialchars($data['siswa']->jurusan ?? 'Data tidak tersedia'); ?></span>
                    </li>
                    <li class="list-group-item border-0 px-0 d-flex justify-content-between">
                        <span>Tahun Masuk</span>
                        <span class="value"><?= 'Tahun ' . date('Y', strtotime(htmlspecialchars($data['siswa']->diterima_tgl ?? 'Data tidak tersedia'))); ?></span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="padding-top: 25px; padding-bottom: 25px;">
                <div class="card-header-title">Pusat Informasi</div>
                <div class="alert-info">
                    <i class="bi bi-info-circle"></i>
                    <span>Pastikan data detail Anda sudah benar. Jika ada kesalahan, segera hubungi pihak Tata Usaha.</span>
                </div>
            </div>
        </div>
        <a href="<?= BASEURL; ?>/login/logout" class="btn-primary" align="center" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </a>
    </div>
    <div class="modal fade" id="modalTokenSiswa" tabindex="-1" aria-labelledby="modalTokenSiswaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="<?= BASEURL; ?>/portal_siswa/detail/<?= TokenHelper::generateToken($_SESSION['data_siswa']['id_induk']); ?>" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTokenSiswaLabel">Keamanan Data Pribadi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">Halaman selanjutnya berisi data pribadi. Silakan masukkan <strong>Token Akses</strong> Anda untuk melanjutkan.</p>
                        <div class="form-group">
                            <input type="text" name="token_input" class="form-control text-center" placeholder="Ketik Token Anda di sini..." required autocomplete="off" style="font-size: 1.2rem; letter-spacing: 2px;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Verifikasi Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>