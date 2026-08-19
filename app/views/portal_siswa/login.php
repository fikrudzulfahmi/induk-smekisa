<!DOCTYPE html>
<html lang="en">

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
        body,
        html {
            height: 100%;
            margin: 0;
            background: rgb(252, 252, 252);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .auth-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .auth-content {
            display: flex;
            width: 100%;
            max-width: 900px;
            border-radius: 15px;
            background-color: #ffffff;
            box-shadow: 0 8px 25px rgba(67, 94, 190, 0.1);
            overflow: hidden;
            flex-wrap: nowrap;
        }

        .form-container {
            flex: 0 0 480px;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
        }

        .form-container h4 {
            font-weight: 700;
            color: #435ebe;
            margin-bottom: 10px;
            text-align: center;
        }

        .form-container h6 {
            font-weight: 400;
            color: #6c757d;
            text-align: center;
            margin-bottom: 25px;
        }

        /* --- Tombol Kembali ke Beranda --- */
        .btn-back-beranda {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #435ebe;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 14px;
            padding: 6px 12px;
            border-radius: 50px;
            background: #eef1fb;
            transition: background 0.2s ease, color 0.2s ease;
            align-self: flex-start;
        }

        .btn-back-beranda:hover {
            background: #435ebe;
            color: #fff;
        }

        .btn-back-beranda i {
            font-size: 1.1rem;
        }

        /* --- Input Styles --- */
        .input-group-text {
            background-color: #eef0f8;
            color: #435ebe;
            border: none;
            border-radius: 8px 0 0 8px;
            min-width: 45px;
            justify-content: center;
            font-size: 1.2rem;
        }

        .form-control {
            border-radius: 0 8px 8px 0;
            border: 1px solid #435ebe;
            font-size: 1rem;
            padding: 12px 16px;
            color: #222;
        }

        .form-control:focus {
            border-color: #384f9e;
            box-shadow: 0 0 8px rgba(67, 94, 190, 0.4);
            outline: none;
        }

        .btn-success {
            background: linear-gradient(45deg, #435ebe, #384f9e);
            border: none;
            font-weight: 600;
            padding: 14px;
            border-radius: 10px;
            font-size: 1.1rem;
            width: 100%;
            box-shadow: 0 6px 12px rgba(67, 94, 190, 0.3);
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(45deg, #384f9e, #435ebe);
            box-shadow: 0 12px 25px rgba(56, 79, 158, 0.5);
            transform: translateY(-2px);
        }

        .text-center.font-weight-light a {
            color: #435ebe;
            font-weight: 600;
            text-decoration: none;
        }

        .text-center.font-weight-light a:hover {
            color: #384f9e;
        }

        .auth-bg {
            flex: 1;
            display: none;
            border-radius: 0 12px 12px 0;
            overflow: hidden;
        }

        .auth-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        @media (min-width: 992px) {
            .auth-bg {
                display: block;
            }
        }

        @media (max-width: 991px) {
            .auth-content {
                flex-direction: column;
                border-radius: 0;
                box-shadow: none;
            }

            .form-container {
                max-width: 400px;
                margin: 0 auto;
                border-radius: 12px;
                box-shadow: 0 12px 25px rgba(67, 94, 190, 0.1);
                background: #fff;
            }

            .auth-bg {
                display: none !important;
            }
        }

        /* Alert Styles */
        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
            font-size: 0.95rem;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 15px 15px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert-danger strong {
            font-weight: 700;
            display: block;
            margin-bottom: 4px;
        }

        .alert-danger i {
            font-size: 1.3rem;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .btn-close {
            color: #721c24;
            position: absolute;
            right: 15px;
            top: 15px;
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-content">
            <div class="form-container">
                <a href="<?= BASEURL; ?>/landing" class="btn-back-beranda">
                    <i class="mdi mdi-arrow-left"></i> Kembali ke Beranda
                </a>
                <h4 id="loginTitle">Login Murid</h4>
                <h6>Portal Siswa — silakan masuk untuk melanjutkan</h6>

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
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="mdi mdi-card-account-details"></i>
                            </span>
                            <input type="text" class="form-control" id="nis" name="nis" placeholder="Nomor Induk Siswa (NIS)" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="mdi mdi-calendar"></i>
                            </span>
                            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required>
                        </div>
                    </div>

                    <!-- CAPTCHA Section (server-side — soal dari PHP, jawaban diverifikasi di server) -->
                    <div class="mb-4 p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #435ebe;">
                        <label class="form-label mb-3" style="font-weight: 600; color: #435ebe;">
                            <i class="mdi mdi-security"></i> Verifikasi Keamanan
                        </label>
                        <div class="captcha-question mb-3" style="font-size: 1.1rem; font-weight: 600; color: #222; padding: 12px; background-color: #fff; border-radius: 6px; text-align: center;">
                            <?= htmlspecialchars($data['captcha_soal'] ?? ''); ?>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text" style="background-color: #435ebe; color: #fff;">
                                <i class="mdi mdi-numeric"></i>
                            </span>
                            <input type="text" class="form-control" id="captcha_answer" name="captcha_answer" placeholder="Masukkan jawaban" required>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me_siswa">
                        <label class="form-check-label" for="remember_me_siswa">Remember Me</label>
                    </div>
                    <button type="submit" name="login_siswa" class="btn btn-success" <?= !empty($data['lockout']) ? 'disabled' : ''; ?>>Login sebagai Murid</button>
                </form>

                <div class="text-center font-weight-light mt-4">
                    Guru / Admin? <a href="<?= BASEURL; ?>/guru/login">Masuk sebagai Guru</a>
                </div>
            </div>

            <div class="auth-bg">
                <img src="<?= BASEURL; ?>/assets/images/bg_login.jpg" alt="Gambar Latar Login">
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // CAPTCHA kini dibuat & diverifikasi di server (AuthSiswa::login / prosesLoginSiswa).
        // Tidak ada lagi btoa() client-side yang bisa dibaca dari DevTools.
        window.addEventListener('load', function() {
            console.log('Halaman login murid dimuat.');
        });
    </script>

</body>

</html>
