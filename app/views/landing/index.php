<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?= isset($data['profil']->nama_sekolah) ? $data['profil']->nama_sekolah : 'Aplikasi Induk'; ?> | Aplikasi Siswa</title>
    <meta name="description" content="Sistem informasi data induk siswa <?= isset($data['profil']->nama_sekolah) ? $data['profil']->nama_sekolah : ''; ?> — pengelolaan data siswa, rombel, jurusan, dan guru dalam satu aplikasi." />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="<?= BASEURL; ?>/assets/images/logo/favicon.svg" type="image/x-icon" />
    <link rel="icon" href="<?= BASEURL; ?>/assets/images/favicon.png" type="image/png">

    <style>
        :root {
            --brand: #435ebe;
            --brand-dark: #3649a8;
            --brand-soft: #eef1fb;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
            background: #ffffff;
        }

        /* ---------- NAVBAR ---------- */
        .navbar-landing {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #eef0f5;
        }
        .navbar-landing .navbar-brand {
            font-weight: 700;
            color: var(--brand);
        }
        .navbar-landing .navbar-brand img {
            height: 34px;
            width: auto;
            margin-right: 8px;
        }

        /* ---------- HERO ---------- */
        .hero {
            background: linear-gradient(135deg, #435ebe 0%, #6a8cff 55%, #9db4ff 100%);
            color: #fff;
            padding: 96px 0 88px;
            position: relative;
            overflow: hidden;
        }
        .hero::after {
            content: '';
            position: absolute;
            right: -120px;
            top: -120px;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }
        .hero::before {
            content: '';
            position: absolute;
            left: -80px;
            bottom: -140px;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
        }
        .hero .container {
            position: relative;
            z-index: 2;
        }
        .hero h1 {
            font-weight: 800;
            font-size: 2.6rem;
            line-height: 1.2;
        }
        .hero p.lead {
            font-size: 1.15rem;
            opacity: 0.95;
            max-width: 620px;
        }
        .hero .btn-hero {
            padding: 12px 28px;
            font-weight: 600;
            border-radius: 50px;
        }
        .hero .btn-light {
            background: #fff;
            color: var(--brand);
            border: none;
        }
        .hero .btn-outline-light {
            border-color: rgba(255, 255, 255, 0.7);
        }
        .hero .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.28);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }
        .hero-stats {
            display: flex;
            gap: 40px;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        .hero-stats .stat {
            text-align: center;
            min-width: 100px;
        }
        .hero-stats .stat .angka {
            font-size: 1.9rem;
            font-weight: 800;
        }
        .hero-stats .stat .label {
            font-size: 0.85rem;
            opacity: 0.85;
        }

        /* ---------- SEKSI UMUM ---------- */
        .section {
            padding: 72px 0;
        }
        .section-title {
            text-align: center;
            margin-bottom: 48px;
        }
        .section-title .kicker {
            color: var(--brand);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.8rem;
            font-weight: 700;
        }
        .section-title h2 {
            font-weight: 800;
            margin-top: 6px;
        }

        /* ---------- FITUR ---------- */
        .fitur-card {
            border: 1px solid #eef0f5;
            border-radius: 14px;
            padding: 28px 22px;
            height: 100%;
            background: #fff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .fitur-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(67, 94, 190, 0.12);
        }
        .fitur-card .icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 16px;
            background: var(--brand-soft);
            color: var(--brand);
        }
        .fitur-card h5 {
            font-weight: 700;
        }
        .fitur-card p {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 0;
        }

        /* ---------- STATISTIK ---------- */
        .statistik {
            background: var(--brand-soft);
        }
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 28px 20px;
            text-align: center;
            box-shadow: 0 4px 14px rgba(67, 94, 190, 0.08);
        }
        .stat-card .icon {
            font-size: 2.2rem;
            color: var(--brand);
        }
        .stat-card .angka {
            font-size: 2.2rem;
            font-weight: 800;
            color: #23243a;
        }
        .stat-card .label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* ---------- CARA PAKAI ---------- */
        .langkah {
            counter-reset: step;
        }
        .langkah-item {
            position: relative;
            padding-left: 76px;
            margin-bottom: 28px;
        }
        .langkah-item .nomor {
            position: absolute;
            left: 0;
            top: 0;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--brand);
            color: #fff;
            font-weight: 800;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .langkah-item h5 {
            font-weight: 700;
        }
        .langkah-item p {
            color: #6c757d;
            margin-bottom: 0;
        }

        /* ---------- CTA & FOOTER ---------- */
        .cta {
            background: linear-gradient(135deg, #23243a 0%, #435ebe 100%);
            color: #fff;
            border-radius: 18px;
            padding: 48px 40px;
            text-align: center;
        }
        .cta h3 {
            font-weight: 800;
        }
        .footer {
            background: #23243a;
            color: #b9bcc7;
            padding: 48px 0 20px;
            font-size: 0.92rem;
        }
        .footer h6 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 14px;
        }
        .footer a {
            color: #b9bcc7;
            text-decoration: none;
        }
        .footer a:hover {
            color: #fff;
        }
        .footer .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 16px;
            margin-top: 32px;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg navbar-landing sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= BASEURL; ?>/landing">
                <img src="<?= BASEURL; ?>/assets/images/logo_smki.png" alt="Logo"
                     onerror="this.style.display='none'">
                <?= isset($data['profil']->nama_sekolah) ? htmlspecialchars($data['profil']->nama_sekolah) : 'Aplikasi Induk'; ?>
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="<?= BASEURL; ?>/guru/login" class="btn btn-sm btn-outline-primary me-2 d-none d-md-inline-block">
                    <i class="mdi mdi-school-outline me-1"></i>Portal Siswa
                </a>
                <a href="<?= BASEURL; ?>/guru/login" class="btn btn-sm btn-primary px-4">
                    <i class="mdi mdi-login me-1"></i>Masuk
                </a>
            </div>
        </div>
    </nav>

    <!-- ================= HERO ================= -->
    <header class="hero">
        <div class="container">
            <span class="hero-badge">
                <i class="mdi mdi-shield-check-outline"></i>
                Sistem Informasi Data Induk Siswa
            </span>
            <h1>Kelola Data Siswa Sekolah<br>dalam Satu Aplikasi</h1>
            <p class="lead mt-3">
                Aplikasi Induk membantu sekolah mencatat, mengelola, dan memantau data induk siswa,
                rombongan belajar, jurusan, hingga guru — cepat, rapi, dan terpusat.
            </p>
            <div class="d-flex gap-3 mt-4 flex-wrap">
                <a href="<?= BASEURL; ?>/guru/login" class="btn btn-light btn-hero">
                    <i class="mdi mdi-login me-1"></i>Masuk Aplikasi
                </a>
                <a href="#fitur" class="btn btn-outline-light btn-hero">
                    <i class="mdi mdi-information-outline me-1"></i>Lihat Fitur
                </a>
            </div>
            <div class="hero-stats">
                <div class="stat">
                    <div class="angka"><?= number_format((int)$data['total_siswa'], 0, ',', '.'); ?></div>
                    <div class="label">Siswa Aktif</div>
                </div>
                <div class="stat">
                    <div class="angka"><?= number_format((int)$data['total_rombel'], 0, ',', '.'); ?></div>
                    <div class="label">Rombel</div>
                </div>
                <div class="stat">
                    <div class="angka"><?= number_format((int)$data['total_jurusan'], 0, ',', '.'); ?></div>
                    <div class="label">Jurusan</div>
                </div>
                <div class="stat">
                    <div class="angka"><?= number_format((int)$data['total_guru'], 0, ',', '.'); ?></div>
                    <div class="label">Guru &amp; Staf</div>
                </div>
            </div>
        </div>
    </header>

    <!-- ================= FITUR ================= -->
    <section class="section" id="fitur">
        <div class="container">
            <div class="section-title">
                <span class="kicker">Fitur Unggulan</span>
                <h2>Semua Kebutuhan Data Sekolah</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="fitur-card">
                        <div class="icon"><i class="mdi mdi-account-group-outline"></i></div>
                        <h5>Data Induk Siswa</h5>
                        <p>98 kolom data lengkap: identitas, orang tua/wali, alamat, asal sekolah, kesehatan, hingga prestasi — dalam satu profil siswa.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="fitur-card">
                        <div class="icon"><i class="mdi mdi-table-edit"></i></div>
                        <h5>Import &amp; Export Excel</h5>
                        <p>Input massal lewat template Excel dan unduh data kapan saja. Tanggal dinormalisasi otomatis agar tidak berubah menjadi angka.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="fitur-card">
                        <div class="icon"><i class="mdi mdi-google-classroom"></i></div>
                        <h5>Rombel &amp; Jurusan</h5>
                        <p>Atur rombongan belajar per tahun ajaran, pindahkan antar rombel, dan kelola kompetensi keahlian dengan mudah.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="fitur-card">
                        <div class="icon"><i class="mdi mdi-file-document-multiple-outline"></i></div>
                        <h5>Cetak Dokumen Resmi</h5>
                        <p>Generate PDF: cover rapor, data siswa, dan laporan — siap cetak untuk keperluan administrasi sekolah.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="fitur-card">
                        <div class="icon"><i class="mdi mdi-magnify"></i></div>
                        <h5>Pencarian &amp; Filter Cepat</h5>
                        <p>Temukan siswa berdasarkan nama, NISN, NIK, rombel, atau jurusan dengan pencarian instan dan filter berlapis.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="fitur-card">
                        <div class="icon"><i class="mdi mdi-shield-lock-outline"></i></div>
                        <h5>Hak Akses Terkontrol</h5>
                        <p>Role Admin, Waka, dan Kajur dengan izin berbeda. Aktivitas pengguna tercatat dalam log audit.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= STATISTIK ================= -->
    <section class="section statistik" id="statistik">
        <div class="container">
            <div class="section-title">
                <span class="kicker">Statistik</span>
                <h2>Data Sekolah Saat Ini</h2>
            </div>
            <div class="row g-4">
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="icon"><i class="mdi mdi-account-group-outline"></i></div>
                        <div class="angka"><?= number_format((int)$data['total_siswa'], 0, ',', '.'); ?></div>
                        <div class="label">Siswa Aktif</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="icon"><i class="mdi mdi-google-classroom"></i></div>
                        <div class="angka"><?= number_format((int)$data['total_rombel'], 0, ',', '.'); ?></div>
                        <div class="label">Rombongan Belajar</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="icon"><i class="mdi mdi-school-outline"></i></div>
                        <div class="angka"><?= number_format((int)$data['total_jurusan'], 0, ',', '.'); ?></div>
                        <div class="label">Kompetensi Keahlian</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="icon"><i class="mdi mdi-account-tie-outline"></i></div>
                        <div class="angka"><?= number_format((int)$data['total_guru'], 0, ',', '.'); ?></div>
                        <div class="label">Guru &amp; Staf</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= CARA PAKAI ================= -->
    <section class="section" id="cara-pakai">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="section-title text-lg-start mb-4" style="text-align:left">
                        <span class="kicker">Cara Pakai</span>
                        <h2>Mulai dalam 3 Langkah</h2>
                    </div>
                    <div class="langkah">
                        <div class="langkah-item">
                            <div class="nomor">1</div>
                            <h5>Masuk dengan Akun Guru</h5>
                            <p>Gunakan akun yang diberikan admin sekolah untuk masuk ke aplikasi.</p>
                        </div>
                        <div class="langkah-item">
                            <div class="nomor">2</div>
                            <h5>Import atau Isi Data Siswa</h5>
                            <p>Unggah template Excel untuk input massal, atau isi manual lewat form data induk.</p>
                        </div>
                        <div class="langkah-item">
                            <div class="nomor">3</div>
                            <h5>Kelola, Cetak, dan Pantau</h5>
                            <p>Atur rombel, cetak dokumen, dan pantau perkembangan data siswa kapan saja.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="cta">
                        <i class="mdi mdi-rocket-launch-outline" style="font-size:3rem"></i>
                        <h3 class="mt-3">Siap Mengelola Data Sekolah?</h3>
                        <p class="opacity-75">Masuk ke aplikasi dan mulai kelola data induk siswa dengan lebih rapi.</p>
                        <a href="<?= BASEURL; ?>/guru/login" class="btn btn-light btn-hero mt-2">
                            <i class="mdi mdi-login me-1"></i>Masuk Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-5">
                    <h6><i class="mdi mdi-school me-1"></i><?= isset($data['profil']->nama_sekolah) ? htmlspecialchars($data['profil']->nama_sekolah) : 'Aplikasi Induk'; ?></h6>
                    <p class="mb-1">
                        <?php if (isset($data['profil']->alamat) && $data['profil']->alamat) : ?>
                            <i class="mdi mdi-map-marker-outline me-1"></i>
                            <?= htmlspecialchars($data['profil']->alamat); ?>
                            <?= isset($data['profil']->kelurahan) ? ', ' . htmlspecialchars($data['profil']->kelurahan) : ''; ?>
                            <?= isset($data['profil']->kecamatan) ? ', ' . htmlspecialchars($data['profil']->kecamatan) : ''; ?>
                            <?= isset($data['profil']->kota) ? ', ' . htmlspecialchars($data['profil']->kota) : ''; ?>
                        <?php endif; ?>
                    </p>
                    <?php if (isset($data['profil']->telepon) && $data['profil']->telepon) : ?>
                        <p class="mb-1"><i class="mdi mdi-phone-outline me-1"></i><?= htmlspecialchars($data['profil']->telepon); ?></p>
                    <?php endif; ?>
                    <?php if (isset($data['profil']->email) && $data['profil']->email) : ?>
                        <p class="mb-1"><i class="mdi mdi-email-outline me-1"></i><?= htmlspecialchars($data['profil']->email); ?></p>
                    <?php endif; ?>
                    <?php if (isset($data['profil']->website) && $data['profil']->website) : ?>
                        <p class="mb-1"><i class="mdi mdi-web me-1"></i><?= htmlspecialchars($data['profil']->website); ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-3">
                    <h6>Menu Cepat</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?= BASEURL; ?>/landing"><i class="mdi mdi-chevron-right me-1"></i>Beranda</a></li>
                        <li class="mb-2"><a href="#fitur"><i class="mdi mdi-chevron-right me-1"></i>Fitur</a></li>
                        <li class="mb-2"><a href="#statistik"><i class="mdi mdi-chevron-right me-1"></i>Statistik</a></li>
                        <li class="mb-2"><a href="<?= BASEURL; ?>/guru/login"><i class="mdi mdi-chevron-right me-1"></i>Portal Siswa</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Akun</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?= BASEURL; ?>/guru/login"><i class="mdi mdi-chevron-right me-1"></i>Masuk Guru / Admin</a></li>
                        <li class="mb-2"><a href="<?= BASEURL; ?>/guru/login"><i class="mdi mdi-chevron-right me-1"></i>Portal Siswa</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                &copy; <?= date('Y'); ?>
                <?= isset($data['profil']->nama_sekolah) ? htmlspecialchars($data['profil']->nama_sekolah) : 'Aplikasi Induk'; ?>
                — Aplikasi Induk Siswa. Hak cipta dilindungi.
            </div>
        </div>
    </footer>

</body>

</html>
