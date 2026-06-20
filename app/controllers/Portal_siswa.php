<?php

class Portal_siswa extends Controller
{

    public function index()
    {
        // Proteksi Halaman: Jika session siswa belum ada, tendang kembali ke login
        if (!isset($_SESSION['login_siswa'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        // Hapus status verifikasi token saat siswa kembali ke dashboard utama
        if (isset($_SESSION['token_verified'])) {
            unset($_SESSION['token_verified']);
        }

        $id = $_SESSION['data_siswa']['id_induk']; // Ambil ID siswa dari session

        $data['judul'] = 'Dashboard Siswa';
        $data['siswa'] = $this->model('Siswa_model')->getDetailSiswaById($id);

        // Tampilkan halaman portal siswa
        $this->view('portal_siswa/index', $data);
    }

    public function detail()
    {
        // 1. Pastikan pengguna sudah login
        if (!isset($_SESSION['login_siswa'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        $id_induk = $_SESSION['data_siswa']['id_induk'];

        // Ambil data siswa berdasarkan ID untuk ditampilkan di halaman detail nanti
        $data['siswa'] = $this->model('Siswa_model')->getDetailSiswaById($id_induk);
        $data['mutasi'] = $this->model('Siswa_model')->getMutasiSiswa($id_induk);

        // ======================================================================
        // 2. VALIDASI TOKEN (Menggunakan kombinasi Session dan POST)
        // ======================================================================

        // Cek apakah sebelumnya sudah lolos verifikasi token (misal: setelah update data)
        if (!isset($_SESSION['token_verified']) || $_SESSION['token_verified'] !== true) {

            // Jika belum ada session terverifikasi, baru kita cek input POST dari Pop-up Modal
            $token_input = $_POST['token_input'] ?? '';

            // Validasi dari database
            $profil = $this->model('ProfilSekolah_model')->getProfil();
            $pin_database = ($profil && isset($profil->token)) ? $profil->token : '';

            if (empty($token_input) || $token_input !== $pin_database) {
                // Jika PIN kosong atau tidak cocok, tendang kembali ke dashboard
                $_SESSION['error_dashboard'] = 'Token / PIN yang Anda masukkan SALAH!';
                header('Location: ' . BASEURL . '/portal_siswa');
                exit;
            }

            // JIKA LOLOS VALIDASI POST: Simpan status verifikasi ke session
            $_SESSION['token_verified'] = true;
        }
        // ======================================================================

        // 3. Jika lolos (baik via session maupun baru input POST), TAMPILKAN DETAIL
        $data['judul'] = 'Detail Siswa';

        // Ekstrak data mutasi agar variabel sebaris di view Anda kemarin tidak error
        $data['mutasi_jenis'] = $data['mutasi']->jenis_mutasi ?? '-';
        $data['mutasi_tgl']   = $data['mutasi']->tgl_mutasi ?? '';
        $data['mutasi_from']  = $data['mutasi']->pindahan_dari ?? '-';
        $data['mutasi_to']    = $data['mutasi']->sekolah_tujuan ?? '-';
        $data['mutasi_alasan'] = $data['mutasi']->alasan_pindah ?? '-';

        // Muat file view halaman rahasia tersebut
        $this->view('portal_siswa/detail', $data);
    }

    // ==========================================
    // METHOD UNTUK MENAMPILKAN HALAMAN EDIT
    // ==========================================
    public function edit()
    {
        // 1. Pastikan pengguna sudah login
        if (!isset($_SESSION['login_siswa'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        $id_induk = $_SESSION['data_siswa']['id_induk'];

        // Ambil data siswa untuk mengisi value pada form edit
        $data['siswa'] = $this->model('Siswa_model')->getDetailSiswaById($id_induk);

        // Ambil data mutasi (jika diperlukan untuk ditampilkan statusnya di form)
        $data['mutasi'] = $this->model('Siswa_model')->getMutasiSiswa($id_induk);

        // Anda juga bisa menambahkan validasi PIN/Token di sini jika ingin halamannya diamankan lagi 
        // (sama seperti method detail). Jika tidak, bisa dilewati.

        $data['judul'] = 'Edit Data Siswa';

        // Muat file view form edit
        $this->view('portal_siswa/edit', $data);
    }

    // ==========================================
    // METHOD UNTUK MEMPROSES SUBMIT FORM EDIT
    // ==========================================
    public function update()
    {
        // Pastikan pengguna sudah login
        if (!isset($_SESSION['login_siswa'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        $id_induk = $_SESSION['data_siswa']['id_induk'];

        // Cek apakah ada request POST dari form
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Panggil method updateDataSiswa di model
            // Kita kirimkan $id_induk dan seluruh isi form ($_POST)
            if ($this->model('Siswa_model')->updateDataSiswaPortal($id_induk, $_POST) > 0) {
                // Jika data berhasil diupdate di database
                // (Sesuaikan dengan sistem Notifikasi/Flasher yang Anda gunakan)
                $_SESSION['pesan_sukses'] = 'Data berhasil diperbarui!';
                header('Location: ' . BASEURL . '/portal_siswa/detail'); // Redirect kembali ke detail
                exit;
            } else {
                // Jika gagal atau tidak ada data yang berubah (user simpan tapi tidak ubah apa-apa)
                $_SESSION['pesan_info'] = 'Tidak ada perubahan data yang disimpan.';
                header('Location: ' . BASEURL . '/portal_siswa/detail');
                exit;
            }
        }
    }
}
