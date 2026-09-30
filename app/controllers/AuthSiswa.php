<?php

class AuthSiswa extends Controller
{

    // Tambahkan method index ini
    public function index()
    {
        // Jika sudah login siswa, redirect ke portal siswa
        if (isset($_SESSION['login_siswa'])) {
            header('Location: ' . BASEURL . '/portal_siswa');
            exit;
        }
        // Jika belum login, tampilkan halaman login murid terpisah
        header('Location: ' . BASEURL . '/authSiswa/login');
        exit;
    }

    /**
     * Halaman login murid (terpisah dari login guru).
     */
    public function login()
    {
        // Jika sudah login siswa, langsung ke portal
        if (isset($_SESSION['login_siswa'])) {
            header('Location: ' . BASEURL . '/portal_siswa');
            exit;
        }

        // ===== CSRF Token =====
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $data['csrf_token'] = $_SESSION['csrf_token'];

        // ===== CAPTCHA Server-Side =====
        // Soal & jawaban disimpan di session (tidak dikirim ke browser),
        // jadi tidak bisa dibaca/didecode seperti btoa() sebelumnya.
        $num1 = random_int(1, 10);
        $num2 = random_int(1, 10);
        $isPlus = (random_int(0, 1) === 1);
        $data['captcha_soal'] = $isPlus ? "$num1 + $num2 = ?" : "$num1 - $num2 = ?";
        $_SESSION['captcha_jawaban'] = $isPlus ? ($num1 + $num2) : ($num1 - $num2);

        // ===== Rate Limiting: cek status lockout =====
        $data['lockout'] = null;
        if (!empty($_SESSION['login_lockout']) && $_SESSION['login_lockout'] > time()) {
            $sisaMenit = (int)ceil(($_SESSION['login_lockout'] - time()) / 60);
            $data['lockout'] = $sisaMenit;
        } else {
            unset($_SESSION['login_lockout']);
        }

        $data['judul'] = 'Login Murid';

        // Data profil sekolah untuk identitas halaman login (disamakan dgn landing page)
        try {
            $data['profil'] = $this->model('ProfilSekolah_model')->getProfil();
        } catch (\Throwable $e) {
            $data['profil'] = null;
        }

        $this->view('portal_siswa/login', $data);
    }

    public function prosesLoginSiswa()
    {
        $nis = $_POST['nis'] ?? '';
        $tgl_lhr = $_POST['tgl_lahir'] ?? '';
        $captcha_answer = $_POST['captcha_answer'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';

        // ===== 1. Validasi CSRF Token =====
        if (empty($csrf_token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
            $_SESSION['error_login'] = 'Sesi tidak valid. Silakan refresh halaman dan coba lagi.';
            header('Location: ' . BASEURL . '/authSiswa/login');
            exit;
        }

        // ===== 2. Rate Limiting (anti bruteforce) =====
        // Batasi 5x percobaan gagal per 15 menit (per NIS + per IP)
        $rateKey = 'login_rate_' . md5($nis . '|' . ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'));
        $now = time();
        if (isset($_SESSION[$rateKey])) {
            $rate = $_SESSION[$rateKey];
            if ($rate['count'] >= 5 && ($now - $rate['first_fail']) < 900) {
                $sisaMenit = (int)ceil((900 - ($now - $rate['first_fail'])) / 60);
                $_SESSION['login_lockout'] = $now + (900 - ($now - $rate['first_fail']));
                $_SESSION['error_login'] = "Terlalu banyak percobaan gagal. Coba lagi dalam $sisaMenit menit.";
                header('Location: ' . BASEURL . '/authSiswa/login');
                exit;
            }
            // Reset jika sudah lewat 15 menit
            if (($now - $rate['first_fail']) >= 900) {
                unset($_SESSION[$rateKey]);
            }
        }

        // ===== 3. Validasi CAPTCHA (server-side, jawaban di session) =====
        if (empty($captcha_answer) || !isset($_SESSION['captcha_jawaban'])) {
            $_SESSION['error_login'] = 'CAPTCHA tidak valid. Silakan refresh halaman dan coba lagi.';
            header('Location: ' . BASEURL . '/authSiswa/login');
            exit;
        }
        // Verifikasi jawaban terhadap nilai yang disimpan di server (bukan dari client)
        if ((int)$captcha_answer !== (int)$_SESSION['captcha_jawaban']) {
            // Catat percobaan gagal
            $_SESSION[$rateKey] = $_SESSION[$rateKey] ?? ['count' => 0, 'first_fail' => $now];
            $_SESSION[$rateKey]['count']++;
            $_SESSION[$rateKey]['first_fail'] = $_SESSION[$rateKey]['first_fail'] ?? $now;
            unset($_SESSION['captcha_jawaban']); // paksa soal baru
            $_SESSION['error_login'] = 'Jawaban CAPTCHA salah. Silakan coba lagi.';
            header('Location: ' . BASEURL . '/authSiswa/login');
            exit;
        }
        // CAPTCHA benar — buang jawaban agar tidak bisa dipakai ulang
        unset($_SESSION['captcha_jawaban']);

        // Validasi NIS dan tanggal lahir tidak kosong
        if (empty($nis)) {
            $_SESSION['error_login'] = 'NIS tidak boleh kosong.';
            header('Location: ' . BASEURL . '/authSiswa/login');
            exit;
        }

        if (empty($tgl_lhr)) {
            $_SESSION['error_login'] = 'Tanggal lahir tidak boleh kosong.';
            header('Location: ' . BASEURL . '/authSiswa/login');
            exit;
        }

        // Panggil method di Auth_model
        $dataSiswa = $this->model('Auth_model')->cekLoginSiswa($nis, $tgl_lhr);

        if ($dataSiswa) {
            // Hapus error session jika login berhasil
            unset($_SESSION['error_login']);
            // Reset rate limit pada login sukses
            unset($_SESSION[$rateKey], $_SESSION['login_lockout']);

            // ===== 4. Regenerasi Session ID (anti session fixation) =====
            session_regenerate_id(true);

            // Set Session Siswa
            $_SESSION['login_siswa'] = true;
            $_SESSION['data_siswa'] = [
                'id_induk'   => $dataSiswa->id_induk,
                'nama_siswa' => $dataSiswa->nama_siswa,
                'no_induk'   => $dataSiswa->no_induk
            ];

            // Redirect ke halaman portal siswa
            header('Location: ' . BASEURL . '/portal_siswa');
            exit;
        } else {
            // Jika tidak ditemukan data siswa — catat percobaan gagal
            $_SESSION[$rateKey] = $_SESSION[$rateKey] ?? ['count' => 0, 'first_fail' => $now];
            $_SESSION[$rateKey]['count']++;
            $_SESSION[$rateKey]['first_fail'] = $_SESSION[$rateKey]['first_fail'] ?? $now;
            $_SESSION['error_login'] = 'NIS atau tanggal lahir tidak terdaftar di sistem. Silakan periksa kembali.';
            header('Location: ' . BASEURL . '/authSiswa/login');
            exit;
        }
    }

    public function logoutSiswa()
    {
        // Hapus session khusus siswa
        unset($_SESSION['login_siswa']);
        unset($_SESSION['data_siswa']);

        header('Location: ' . BASEURL . '/authSiswa/login');
        exit;
    }
}
