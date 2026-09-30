<?php
$namaSekolah = (isset($data['profil']->nama_sekolah) && $data['profil']->nama_sekolah)
    ? $data['profil']->nama_sekolah
    : 'Aplikasi Induk Siswa';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?= $data['judul']; ?> | Portal Murid</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="shortcut icon" href="<?= BASEURL; ?>/assets/images/logo/favicon.svg" type="image/x-icon" />
    <link rel="icon" href="<?= BASEURL; ?>/assets/images/favicon.png" type="image/png">

    <style>
        /* =========================================================
           TEMA LOGIN — disamakan dengan landing page
           NAVY (GRADASI) · PUTIH · AKSEN ORANGE → KUNING
           ========================================================= */
        :root {
            --navy-900: #061530;
            --navy-800: #0a1f44;
            --navy-700: #102d5e;
            --blue-600: #1b4fa8;
            --blue-500: #2563eb;
            --blue-400: #3b82f6;
            --blue-100: #dbe7fb;
            --blue-50: #f2f7ff;

            --orange-500: #f97316;
            --orange-400: #fb923c;
            --yellow-400: #facc15;

            --ink: #0c1a33;
            --muted: #5c6d8f;
            --line: #e5ecf8;

            --grad-navy: linear-gradient(135deg, #061530 0%, #0a1f44 38%, #143a7a 72%, #1b4fa8 100%);
            --grad-blue: linear-gradient(135deg, #0a1f44 0%, #1b4fa8 55%, #3b82f6 100%);
            --grad-accent: linear-gradient(135deg, #f97316 0%, #fb923c 45%, #facc15 100%);
        }

        body.auth-body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: var(--grad-navy);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--ink);
            position: relative;
            overflow-x: hidden;
        }

        /* Dekorasi lingkaran (sama gaya dengan hero landing) */
        body.auth-body::after {
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

        body.auth-body::before {
            content: '';
            position: absolute;
            left: -180px;
            bottom: -200px;
            width: 520px;
            height: 520px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.30) 0%, rgba(59, 130, 246, 0) 70%);
            pointer-events: none;
        }

        /* ---------- KARTU UTAMA ---------- */
        .auth-card {
            position: relative;
            z-index: 1;
            display: flex;
            width: 100%;
            max-width: 940px;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 70px rgba(6, 21, 48, 0.45);
        }

        /* ---------- PANEL BRANDING (kiri) ---------- */
        .auth-brand {
            flex: 0 0 360px;
            background: var(--grad-blue);
            color: #fff;
            padding: 38px 32px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .auth-brand::after {
            content: '';
            position: absolute;
            right: -110px;
            bottom: -120px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.35) 0%, rgba(249, 115, 22, 0) 70%);
        }

        .brand-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            align-self: flex-start;
            color: #eaf1ff;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 6px 14px;
            border-radius: 50px;
            border: 1.5px solid rgba(255, 255, 255, 0.45);
            background: rgba(255, 255, 255, 0.08);
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .brand-back:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: #fff;
            color: #fff;
        }

        .brand-logo {
            margin: 26px 0 14px;
        }

        .brand-logo img {
            height: 52px;
            width: auto;
            filter: drop-shadow(0 6px 14px rgba(0, 0, 0, 0.25));
        }

        .auth-brand h2 {
            color: #fff;
            font-weight: 800;
            font-size: 1.45rem;
            line-height: 1.25;
            margin: 0 0 6px;
            letter-spacing: -0.2px;
        }

        .brand-tag {
            color: rgba(255, 255, 255, 0.76);
            font-size: 0.9rem;
            margin-bottom: 18px;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            align-self: flex-start;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.24);
            padding: 7px 16px;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #eaf1ff;
            margin-bottom: 22px;
        }

        .brand-badge i {
            color: var(--yellow-400);
            font-size: 1.05rem;
        }

        .brand-features {
            list-style: none;
            padding: 0;
            margin: auto 0 0;
            position: relative;
            z-index: 1;
        }

        .brand-features li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: rgba(255, 255, 255, 0.86);
            font-size: 0.88rem;
            margin-bottom: 12px;
            line-height: 1.45;
        }

        .brand-features i {
            color: var(--yellow-400);
            font-size: 1.05rem;
            margin-top: 1px;
        }

        /* ---------- PANEL FORM (kanan) ---------- */
        .auth-form-panel {
            flex: 1;
            padding: 38px 36px;
        }

        .auth-form-panel h4 {
            font-weight: 800;
            color: var(--navy-800);
            margin-bottom: 4px;
        }

        .auth-form-panel .sub {
            color: var(--muted);
            font-size: 0.9rem;
            margin-bottom: 22px;
        }

        .form-label {
            font-weight: 600;
            color: var(--navy-800);
            font-size: 0.88rem;
            margin-bottom: 6px;
        }

        .form-control,
        .input-group-text {
            border: 1.5px solid var(--line);
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 0.96rem;
            color: var(--ink);
            background-color: #fff;
        }

        .input-group-text {
            background: var(--blue-50);
            color: var(--blue-600);
            border-right: none;
            font-size: 1.15rem;
            padding: 12px 14px;
        }

        .form-control {
            border-left: none;
        }

        .form-control:focus {
            border-color: var(--blue-500);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.14);
            color: var(--ink);
            background-color: #fff;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--blue-500);
            border-right: none;
        }

        .form-check-input:checked {
            background-color: var(--blue-600);
            border-color: var(--blue-600);
        }

        .form-check-label {
            color: var(--muted);
            font-size: 0.9rem;
        }

        /* ---------- TOMBOL CTA (orange → kuning, teks navy) ---------- */
        .btn-cta {
            width: 100%;
            background: var(--grad-accent);
            color: var(--navy-900);
            border: none;
            font-weight: 800;
            padding: 13px 24px;
            border-radius: 50px;
            font-size: 1rem;
            box-shadow: 0 10px 24px rgba(249, 115, 22, 0.32);
            transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
        }

        .btn-cta:hover,
        .btn-cta:focus {
            color: var(--navy-900);
            filter: brightness(1.06);
            transform: translateY(-2px);
            box-shadow: 0 16px 32px rgba(249, 115, 22, 0.42);
        }

        .btn-cta:disabled {
            filter: grayscale(0.5);
            opacity: 0.7;
            transform: none;
            box-shadow: none;
            cursor: not-allowed;
        }

        .auth-alt {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: var(--muted);
        }

        .auth-alt a {
            color: var(--blue-600);
            font-weight: 700;
            text-decoration: none;
        }

        .auth-alt a:hover {
            color: var(--orange-500);
        }

        /* ---------- CAPTCHA ---------- */
        .captcha-box {
            background: var(--blue-50);
            border: 1px solid var(--blue-100);
            border-left: 4px solid var(--blue-600);
            border-radius: 12px;
            padding: 16px;
        }

        .captcha-box .captcha-label {
            font-weight: 700;
            color: var(--blue-600);
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
        }

        .captcha-question {
            background: #fff;
            border: 1px dashed var(--blue-100);
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            font-weight: 800;
            font-size: 1.15rem;
            color: var(--navy-800);
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .captcha-box .input-group-text {
            background: var(--blue-600);
            color: #fff;
            border-color: var(--blue-600);
            border-right: none;
        }

        /* ---------- ALERT ---------- */
        .alert {
            border: none;
            border-radius: 12px;
            font-size: 0.9rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 14px 16px;
        }

        .alert i {
            font-size: 1.15rem;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .alert-danger {
            background: #fdecec;
            color: #86181d;
            border-left: 4px solid #dc3545;
        }

        .alert-warning {
            background: #fff8e1;
            color: #7a4b00;
            border-left: 4px solid var(--orange-500);
        }

        .alert strong {
            display: block;
            font-weight: 800;
            margin-bottom: 2px;
        }

        /* ---------- HEADER RINGKAS (mobile) ---------- */
        .auth-mobile-head {
            display: none;
            align-items: center;
            gap: 12px;
            padding: 18px 20px;
            background: var(--grad-blue);
            color: #fff;
        }

        .auth-mobile-head img {
            height: 36px;
            width: auto;
        }

        .auth-mobile-head .nama {
            font-weight: 800;
            font-size: 0.98rem;
            line-height: 1.2;
        }

        .auth-mobile-head .kecil {
            font-size: 0.76rem;
            color: rgba(255, 255, 255, 0.78);
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 991px) {
            body.auth-body {
                padding: 16px;
                align-items: flex-start;
            }

            .auth-card {
                flex-direction: column;
                margin: 12px 0;
            }

            .auth-brand {
                display: none;
            }

            .auth-mobile-head {
                display: flex;
            }

            .auth-form-panel {
                padding: 26px 20px 30px;
            }
        }
    </style>
</head>

<body class="auth-body">
    <div class="auth-card">
        <!-- ===== Panel branding (desktop) ===== -->
        <aside class="auth-brand">
            <a href="<?= BASEURL; ?>/landing" class="brand-back">
                <i class="mdi mdi-arrow-left"></i> Kembali ke Beranda
            </a>
            <div class="brand-logo">
                <img src="<?= BASEURL; ?>/assets/images/logo_smki.png" alt="Logo Sekolah"
                     onerror="this.style.display='none'">
            </div>
            <h2><?= htmlspecialchars($namaSekolah); ?></h2>
            <p class="brand-tag">Sistem Informasi Data Induk Siswa</p>
            <span class="brand-badge"><i class="mdi mdi-school-outline"></i> Portal Murid</span>
            <ul class="brand-features">
                <li><i class="mdi mdi-check-circle"></i> Lihat data induk &amp; identitas pribadi</li>
                <li><i class="mdi mdi-check-circle"></i> Pantau data akademik dan rombel</li>
                <li><i class="mdi mdi-check-circle"></i> Ajukan perbaikan data bila ada kekeliruan</li>
            </ul>
        </aside>

        <!-- ===== Panel form ===== -->
        <section class="auth-form-panel">
            <!-- Header ringkas untuk mobile -->
            <div class="auth-mobile-head">
                <img src="<?= BASEURL; ?>/assets/images/logo_smki.png" alt="Logo Sekolah"
                     onerror="this.style.display='none'">
                <div>
                    <div class="nama"><?= htmlspecialchars($namaSekolah); ?></div>
                    <div class="kecil">Portal Murid</div>
                </div>
            </div>

            <a href="<?= BASEURL; ?>/landing" class="brand-back d-inline-flex d-lg-none mb-3"
               style="color: var(--blue-600); border-color: var(--blue-100); background: var(--blue-50);">
                <i class="mdi mdi-arrow-left"></i> Beranda
            </a>

            <h4>Login Murid</h4>
            <p class="sub">Masuk dengan NIS dan tanggal lahir Anda.</p>

            <!-- Alert Error -->
            <?php if (isset($_SESSION['error_login'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorAlert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div>
                        <strong>PERHATIAN!</strong>
                        <div><?= htmlspecialchars($_SESSION['error_login']); ?></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error_login']); ?>
            <?php endif; ?>

            <!-- Alert Lockout (rate limiting) -->
            <?php if (!empty($data['lockout'])): ?>
                <div class="alert alert-warning fade show" role="alert">
                    <i class="bi bi-shield-lock-fill"></i>
                    <div>
                        <strong>AKUN TERKUNCI SEMENTARA</strong>
                        <div>Terlalu banyak percobaan gagal. Silakan coba lagi dalam <?= $data['lockout']; ?> menit.</div>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASEURL; ?>/authSiswa/prosesLoginSiswa">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($data['csrf_token'] ?? ''); ?>">

                <div class="mb-3">
                    <label class="form-label" for="nis">Nomor Induk Siswa (NIS)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="mdi mdi-card-account-details"></i></span>
                        <input type="text" class="form-control" id="nis" name="nis" placeholder="Contoh: 2024001" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="tgl_lahir">Tanggal Lahir</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required>
                    </div>
                </div>

                <!-- CAPTCHA (server-side) -->
                <div class="captcha-box mb-3">
                    <div class="captcha-label"><i class="mdi mdi-security"></i> Verifikasi Keamanan</div>
                    <div class="captcha-question"><?= htmlspecialchars($data['captcha_soal'] ?? ''); ?></div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="mdi mdi-numeric"></i></span>
                        <input type="text" class="form-control" id="captcha_answer" name="captcha_answer"
                               placeholder="Masukkan jawaban" required>
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me_siswa">
                    <label class="form-check-label" for="remember_me_siswa">Ingat saya</label>
                </div>

                <button type="submit" name="login_siswa" class="btn btn-cta"
                        <?= !empty($data['lockout']) ? 'disabled' : ''; ?>>
                    Login sebagai Murid
                </button>
            </form>

            <div class="auth-alt">
                Guru / Admin? <a href="<?= BASEURL; ?>/guru/login">Masuk sebagai Guru</a>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // CAPTCHA dibuat & diverifikasi di server (AuthSiswa::login / prosesLoginSiswa).
        window.addEventListener('load', function() {
            console.log('Halaman login murid dimuat.');
        });
    </script>
</body>

</html>
