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
        /* =========================================================
           TEMA: NAVY — BIRU (GRADASI), PUTIH, AKSEN ORANGE → KUNING
           ========================================================= */
        :root {
            /* Biru navy */
            --navy-900: #061530;
            --navy-800: #0a1f44;
            --navy-700: #102d5e;
            --blue-600: #1b4fa8;
            --blue-500: #2563eb;
            --blue-400: #3b82f6;
            --blue-100: #dbe7fb;
            --blue-50: #f2f7ff;

            /* Aksen orange → kuning */
            --orange-500: #f97316;
            --orange-400: #fb923c;
            --yellow-400: #facc15;

            /* Netral */
            --ink: #0c1a33;
            --muted: #5c6d8f;
            --line: #e5ecf8;

            /* Gradasi utama */
            --grad-navy: linear-gradient(135deg, #061530 0%, #0a1f44 38%, #143a7a 72%, #1b4fa8 100%);
            --grad-blue: linear-gradient(135deg, #0a1f44 0%, #1b4fa8 55%, #3b82f6 100%);
            --grad-accent: linear-gradient(135deg, #f97316 0%, #fb923c 45%, #facc15 100%);

            /* Kompatibilitas nama variabel lama */
            --brand: #1b4fa8;
            --brand-dark: #0a1f44;
            --brand-soft: #f2f7ff;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 84px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--ink);
            background: #ffffff;
            -webkit-font-smoothing: antialiased;
        }

        /* ---------- NAVBAR ---------- */
        .navbar-landing {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--line);
            border-top: 4px solid transparent;
            border-image: var(--grad-accent) 1;
            box-shadow: 0 6px 24px rgba(10, 31, 68, 0.06);
        }
        .navbar-landing .navbar-brand {
            font-weight: 800;
            color: var(--navy-800);
            letter-spacing: -0.2px;
        }
        .navbar-landing .navbar-brand img {
            height: 34px;
            width: auto;
            margin-right: 8px;
        }
        .navbar-landing .btn-outline-navy {
            border: 1.5px solid var(--blue-600);
            color: var(--blue-600);
            font-weight: 600;
            background: transparent;
        }
        .navbar-landing .btn-outline-navy:hover {
            background: var(--blue-600);
            color: #fff;
        }

        /* ---------- TOMBOL ---------- */
        .btn-hero {
            padding: 12px 28px;
            font-weight: 700;
            border-radius: 50px;
            transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
        }
        /* CTA utama: gradasi orange → kuning, teks navy */
        .btn-cta {
            background: var(--grad-accent);
            color: var(--navy-900);
            border: none;
            box-shadow: 0 10px 24px rgba(249, 115, 22, 0.32);
        }
        .btn-cta:hover,
        .btn-cta:focus {
            color: var(--navy-900);
            filter: brightness(1.06);
            transform: translateY(-2px);
            box-shadow: 0 16px 32px rgba(249, 115, 22, 0.42);
        }
        /* Sekunder di atas latar gelap */
        .btn-ghost {
            background: transparent;
            border: 1.5px solid rgba(255, 255, 255, 0.55);
            color: #fff;
        }
        .btn-ghost:hover,
        .btn-ghost:focus {
            background: rgba(255, 255, 255, 0.14);
            border-color: #fff;
            color: #fff;
            transform: translateY(-2px);
        }
        /* Sekunder di atas latar terang */
        .btn-outline-navy {
            background: transparent;
            border: 1.5px solid var(--blue-600);
            color: var(--blue-600);
            font-weight: 700;
        }
        .btn-outline-navy:hover {
            background: var(--blue-600);
            color: #fff;
        }

        /* ---------- HERO ---------- */
        .hero {
            background: var(--grad-navy);
            color: #fff;
            padding: 104px 0 96px;
            position: relative;
            overflow: hidden;
        }
        .hero::after {
            content: '';
            position: absolute;
            right: -160px;
            top: -170px;
            width: 540px;
            height: 540px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.38) 0%, rgba(249, 115, 22, 0) 68%);
            pointer-events: none;
        }
        .hero::before {
            content: '';
            position: absolute;
            left: -190px;
            bottom: -210px;
            width: 580px;
            height: 580px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.45) 0%, rgba(59, 130, 246, 0) 70%);
            pointer-events: none;
        }
        .hero .hero-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 58px 58px;
            -webkit-mask-image: radial-gradient(circle at 28% 18%, #000 0%, transparent 72%);
            mask-image: radial-gradient(circle at 28% 18%, #000 0%, transparent 72%);
            pointer-events: none;
        }
        .hero .container {
            position: relative;
            z-index: 2;
        }
        .hero h1 {
            font-weight: 800;
            font-size: clamp(2rem, 4.2vw, 2.75rem);
            line-height: 1.2;
            letter-spacing: -0.5px;
        }
        .hero h1 .accent {
            background: var(--grad-accent);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
        }
        .hero p.lead {
            font-size: 1.12rem;
            color: rgba(255, 255, 255, 0.86);
            max-width: 620px;
        }
        .hero .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.22);
            padding: 7px 18px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 22px;
            color: #eaf1ff;
        }
        .hero .hero-badge i {
            color: var(--yellow-400);
            font-size: 1.05rem;
        }
        .hero-stats {
            display: flex;
            gap: 0;
            margin-top: 48px;
            flex-wrap: wrap;
        }
        .hero-stats .stat {
            text-align: left;
            min-width: 130px;
            padding: 0 28px;
            border-left: 1px solid rgba(255, 255, 255, 0.16);
        }
        .hero-stats .stat:first-child {
            padding-left: 0;
            border-left: none;
        }
        .hero-stats .stat .angka {
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1.1;
            background: var(--grad-accent);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
        }
        .hero-stats .stat .label {
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.72);
            letter-spacing: 0.3px;
        }

        /* ---------- SEKSI UMUM ---------- */
        .section {
            padding: 80px 0;
        }
        .section-title {
            text-align: center;
            margin-bottom: 52px;
        }
        .section-title .kicker {
            display: inline-block;
            color: var(--orange-500);
            text-transform: uppercase;
            letter-spacing: 2.4px;
            font-size: 0.78rem;
            font-weight: 800;
        }
        .section-title .kicker::after {
            content: '';
            display: block;
            height: 3px;
            border-radius: 3px;
            background: var(--grad-accent);
            margin: 8px auto 0;
            width: 54px;
        }
        .section-title h2 {
            font-weight: 800;
            color: var(--navy-800);
            margin-top: 10px;
            letter-spacing: -0.4px;
        }

        /* ---------- FITUR ---------- */
        .fitur-card {
            position: relative;
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 30px 24px;
            height: 100%;
            background: #fff;
            overflow: hidden;
            transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
        }
        .fitur-card::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 4px;
            background: var(--grad-accent);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.28s ease;
        }
        .fitur-card:hover {
            transform: translateY(-6px);
            border-color: #cfe0f8;
            box-shadow: 0 20px 40px rgba(10, 31, 68, 0.12);
        }
        .fitur-card:hover::after {
            transform: scaleX(1);
        }
        .fitur-card .icon {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.65rem;
            margin-bottom: 18px;
            background: var(--grad-blue);
            color: #fff;
            box-shadow: 0 10px 22px rgba(27, 79, 168, 0.28);
        }
        .fitur-card h5 {
            font-weight: 700;
            color: var(--navy-800);
        }
        .fitur-card p {
            color: var(--muted);
            font-size: 0.95rem;
            margin-bottom: 0;
        }

        /* ---------- STATISTIK (latar navy) ---------- */
        .statistik {
            background: var(--grad-blue);
            position: relative;
            overflow: hidden;
        }
        .statistik::after {
            content: '';
            position: absolute;
            right: -140px;
            top: -140px;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(250, 204, 21, 0.22) 0%, rgba(250, 204, 21, 0) 70%);
            pointer-events: none;
        }
        .statistik .section-title h2 {
            color: #fff;
        }
        .statistik .section-title .kicker {
            color: var(--yellow-400);
        }
        .statistik .container {
            position: relative;
            z-index: 2;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 18px;
            padding: 30px 20px;
            text-align: center;
            backdrop-filter: blur(6px);
            box-shadow: 0 14px 34px rgba(4, 15, 38, 0.28);
            transition: transform 0.22s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card .icon {
            font-size: 2.1rem;
            background: var(--grad-accent);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
        }
        .stat-card .angka {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--navy-800);
            line-height: 1.15;
        }
        .stat-card .label {
            color: var(--muted);
            font-size: 0.88rem;
        }

        /* ---------- CARA PAKAI ---------- */
        .langkah {
            counter-reset: step;
        }
        .langkah-item {
            position: relative;
            padding-left: 78px;
            margin-bottom: 30px;
        }
        .langkah-item .nomor {
            position: absolute;
            left: 0;
            top: 0;
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: var(--grad-accent);
            color: var(--navy-900);
            font-weight: 800;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3);
        }
        .langkah-item h5 {
            font-weight: 700;
            color: var(--navy-800);
        }
        .langkah-item p {
            color: var(--muted);
            margin-bottom: 0;
        }

        /* ---------- CTA & FOOTER ---------- */
        .cta {
            background: var(--grad-navy);
            color: #fff;
            border-radius: 22px;
            padding: 50px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 26px 50px rgba(10, 31, 68, 0.28);
        }
        .cta::after {
            content: '';
            position: absolute;
            right: -110px;
            bottom: -130px;
            width: 340px;
            height: 340px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.34) 0%, rgba(249, 115, 22, 0) 70%);
            pointer-events: none;
        }
        .cta > * {
            position: relative;
            z-index: 2;
        }
        .cta h3 {
            font-weight: 800;
        }
        .cta i.mdi {
            color: var(--yellow-400);
        }
        .footer {
            background: var(--navy-900);
            color: #a9b8d4;
            padding: 52px 0 20px;
            font-size: 0.92rem;
            border-top: 4px solid transparent;
            border-image: var(--grad-accent) 1;
        }
        .footer h6 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 14px;
        }
        .footer h6 i.mdi {
            color: var(--yellow-400);
        }
        .footer a {
            color: #a9b8d4;
            text-decoration: none;
            transition: color 0.16s ease;
        }
        .footer a:hover {
            color: var(--yellow-400);
        }
        .footer .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.09);
            padding-top: 18px;
            margin-top: 32px;
            text-align: center;
            color: #8095b8;
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
                <a href="<?= BASEURL; ?>/authSiswa/login" class="btn btn-sm btn-outline-navy me-2 d-none d-md-inline-block">
                    <i class="mdi mdi-school-outline me-1"></i>Portal Murid
                </a>
                <a href="<?= BASEURL; ?>/guru/login" class="btn btn-sm btn-cta px-4">
                    <i class="mdi mdi-login me-1"></i>Portal Guru
                </a>
            </div>
        </div>
    </nav>

    <!-- ================= HERO ================= -->
    <header class="hero">
        <div class="hero-grid"></div>
        <div class="container">
            <span class="hero-badge">
                <i class="mdi mdi-shield-check-outline"></i>
                Sistem Informasi Data Induk Siswa
            </span>
            <h1>Kelola <span class="accent">Data Siswa Sekolah</span><br>dalam Satu Aplikasi</h1>
            <p class="lead mt-3">
                Aplikasi Induk membantu sekolah mencatat, mengelola, dan memantau data induk siswa,
                rombongan belajar, jurusan, hingga guru — cepat, rapi, dan terpusat.
            </p>
            <div class="d-flex gap-3 mt-4 flex-wrap">
                <a href="<?= BASEURL; ?>/guru/login" class="btn btn-cta btn-hero">
                    <i class="mdi mdi-login me-1"></i>Portal Guru
                </a>
                <a href="<?= BASEURL; ?>/authSiswa/login" class="btn btn-ghost btn-hero">
                    <i class="mdi mdi-school-outline me-1"></i>Portal Murid
                </a>
                <a href="#fitur" class="btn btn-ghost btn-hero">
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
                        <p class="opacity-75">Masuk ke portal yang sesuai — guru untuk mengelola data, murid untuk melihat data pribadi.</p>
                        <div class="d-flex gap-3 justify-content-center mt-2 flex-wrap">
                            <a href="<?= BASEURL; ?>/guru/login" class="btn btn-cta btn-hero">
                                <i class="mdi mdi-login me-1"></i>Portal Guru
                            </a>
                            <a href="<?= BASEURL; ?>/authSiswa/login" class="btn btn-ghost btn-hero">
                                <i class="mdi mdi-school-outline me-1"></i>Portal Murid
                            </a>
                        </div>
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
                        <li class="mb-2"><a href="<?= BASEURL; ?>/authSiswa/login"><i class="mdi mdi-chevron-right me-1"></i>Portal Murid</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Akun</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?= BASEURL; ?>/guru/login"><i class="mdi mdi-chevron-right me-1"></i>Masuk Guru / Admin</a></li>
                        <li class="mb-2"><a href="<?= BASEURL; ?>/authSiswa/login"><i class="mdi mdi-chevron-right me-1"></i>Portal Murid</a></li>
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
