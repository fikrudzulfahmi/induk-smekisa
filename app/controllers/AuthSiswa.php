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

        $data['judul'] = 'Login Murid';
        $this->view('portal_siswa/login', $data);
    }

    public function prosesLoginSiswa()
    {
        $nis = $_POST['nis'] ?? '';
        $tgl_lhr = $_POST['tgl_lahir'] ?? '';
        $captcha_answer = $_POST['captcha_answer'] ?? '';
        $captcha_hash = $_POST['captcha_hash'] ?? '';

        // Validasi CAPTCHA terlebih dahulu
        if (empty($captcha_answer) || empty($captcha_hash)) {
            $_SESSION['error_login'] = 'CAPTCHA tidak valid. Silakan refresh halaman dan coba lagi.';
            header('Location: ' . BASEURL . '/authSiswa/login');
            exit;
        }

        // Verifikasi jawaban CAPTCHA
        $expected_answer = base64_decode($captcha_hash);
        if ((int)$captcha_answer !== (int)$expected_answer) {
            $_SESSION['error_login'] = 'Jawaban CAPTCHA salah. Silakan coba lagi.';
            header('Location: ' . BASEURL . '/authSiswa/login');
            exit;
        }

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
            // Jika tidak ditemukan data siswa
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
