<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?= $data['judul']; ?> | Aplikasi Siswa</title>

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

        /* --- Toggle Switch Styles --- */
        .toggle-container {
            display: flex;
            position: relative;
            background: #eef0f8;
            border-radius: 30px;
            margin-bottom: 30px;
            padding: 5px;
            width: 100%;
        }

        .toggle-btn {
            flex: 1;
            text-align: center;
            padding: 10px 0;
            cursor: pointer;
            z-index: 1;
            font-weight: 600;
            color: #6c757d;
            transition: color 0.3s;
            user-select: none;
        }

        .toggle-btn.active {
            color: #fff;
        }

        .toggle-bg {
            position: absolute;
            top: 5px;
            left: 5px;
            width: calc(50% - 5px);
            height: calc(100% - 10px);
            background: linear-gradient(45deg, #435ebe, #384f9e);
            border-radius: 25px;
            transition: transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .toggle-container.slide-right .toggle-bg {
            transform: translateX(100%);
        }

        /* --- Slider Form Styles --- */
        .slider-wrapper {
            overflow: hidden;
            width: 100%;
        }

        .slider-inner {
            display: flex;
            width: 200%;
            transition: transform 0.4s ease-in-out;
        }

        .form-panel {
            width: 50%;
            padding: 0 5px;
            flex-shrink: 0;
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

        .text-center.font-weight-light {
            font-size: 0.95rem;
            color: #6c757d;
            margin-top: 24px;
            text-align: center;
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
                <h4 id="loginTitle">Login Aplikasi</h4>
                <h6>Silakan masuk untuk melanjutkan</h6>

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

                <div class="toggle-container slide-right" id="toggleContainer">
                    <div class="toggle-bg"></div>
                    <div class="toggle-btn" onclick="switchForm('guru')">Guru</div>
                    <div class="toggle-btn active" onclick="switchForm('siswa')">Siswa</div>
                </div>

                <div class="slider-wrapper">
                    <div class="slider-inner" id="sliderInner" style="transform: translateX(-50%);">

                        <div class="form-panel">
                            <form method="POST" action="<?= BASEURL; ?>/guru/prosesLogin">
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-account"></i>
                                        </span>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Username Guru" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-lock"></i>
                                        </span>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                    </div>
                                </div>
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me_guru">
                                    <label class="form-check-label" for="remember_me_guru">Remember Me</label>
                                </div>
                                <button type="submit" name="login_guru" class="btn btn-success">Login sebagai Guru</button>
                            </form>
                        </div>

                        <div class="form-panel">
                            <form method="POST" action="<?= BASEURL; ?>/authSiswa/prosesLoginSiswa">
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

                                <!-- CAPTCHA Section -->
                                <div class="mb-4 p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #435ebe;">
                                    <label class="form-label mb-3" style="font-weight: 600; color: #435ebe;">
                                        <i class="mdi mdi-security"></i> Verifikasi Keamanan
                                    </label>
                                    <div class="captcha-question mb-3" id="captchaQuestion" style="font-size: 1.1rem; font-weight: 600; color: #222; padding: 12px; background-color: #fff; border-radius: 6px; text-align: center;">
                                        <!-- Soal akan ditampilkan di sini -->
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #435ebe; color: #fff;">
                                            <i class="mdi mdi-numeric"></i>
                                        </span>
                                        <input type="text" class="form-control" id="captcha_answer" name="captcha_answer" placeholder="Masukkan jawaban" required>
                                        <input type="hidden" id="captcha_hash" name="captcha_hash" value="">
                                    </div>
                                </div>

                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me_siswa">
                                    <label class="form-check-label" for="remember_me_siswa">Remember Me</label>
                                </div>
                                <button type="submit" name="login_siswa" class="btn btn-success">Login sebagai Siswa</button>
                            </form>
                        </div>

                    </div>
                </div>

                <div class="text-center font-weight-light mt-4">
                    Belum punya akun? <a href="#">Hubungi Admin</a>
                </div>
            </div>

            <div class="auth-bg">
                <img src="<?= BASEURL; ?>/assets/images/bg_login.jpg" alt="Gambar Latar Login">
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Generate dan tampilkan CAPTCHA saat halaman dimuat
        function generateCaptcha() {
            const num1 = Math.floor(Math.random() * 10) + 1;
            const num2 = Math.floor(Math.random() * 10) + 1;
            const operation = Math.random() > 0.5 ? '+' : '-';

            let jawaban;
            if (operation === '+') {
                jawaban = num1 + num2;
            } else {
                jawaban = num1 - num2;
            }

            const soal = `${num1} ${operation} ${num2} = ?`;

            // Tampilkan soal
            const questionElement = document.getElementById('captchaQuestion');
            if (questionElement) {
                questionElement.textContent = soal;
                console.log('CAPTCHA ditampilkan:', soal);
            } else {
                console.error('Element captchaQuestion tidak ditemukan!');
            }

            // Simpan hash jawaban (simple hash untuk keamanan dasar)
            const jawabanHash = btoa(jawaban); // Base64 encoding sebagai hash sederhana
            const hashElement = document.getElementById('captcha_hash');
            if (hashElement) {
                hashElement.value = jawabanHash;
            }
        }

        // Panggil saat DOM selesai dimuat
        window.addEventListener('load', function() {
            console.log('Page loaded, generating CAPTCHA...');
            setTimeout(generateCaptcha, 100);

            // Regenerate CAPTCHA jika form login siswa ditampilkan
            const toggleBtns = document.querySelectorAll('.toggle-btn');
            toggleBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const role = this.textContent.toLowerCase().includes('siswa') ? 'siswa' : 'guru';
                    console.log('Tombol diklik:', role);
                    if (role === 'siswa') {
                        setTimeout(generateCaptcha, 400);
                    }
                });
            });
        });

        function switchForm(role) {
            const sliderInner = document.getElementById('sliderInner');
            const toggleContainer = document.getElementById('toggleContainer');
            const btns = document.querySelectorAll('.toggle-btn');

            if (role === 'siswa') {
                sliderInner.style.transform = 'translateX(-50%)';
                toggleContainer.classList.add('slide-right');
                btns[0].classList.remove('active');
                btns[1].classList.add('active');
                setTimeout(generateCaptcha, 500);
            } else {
                sliderInner.style.transform = 'translateX(0)';
                toggleContainer.classList.remove('slide-right');
                btns[0].classList.add('active');
                btns[1].classList.remove('active');
            }
        }
    </script>
</body>

</html>