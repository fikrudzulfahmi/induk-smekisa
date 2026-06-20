<?php

class Guru extends Controller
{
    public function index()
    {
        // Redirect ke login jika diakses langsung
        header('Location: ' . BASEURL . '/guru/login');
        exit;
    }

    public function login()
    {
        // 1. Cek apakah guru sudah login. Jika sudah, langsung lempar ke dashboard
        if (isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        // 2. Siapkan data untuk View (seperti judul halaman)
        $data['judul'] = 'Login';

        // 3. PANGGIL VIEW, JANGAN GUNAKAN HEADER LOCATION REDIRECT!
        // Pastikan path 'guru/login' sesuai dengan lokasi file HTML yang kamu kirimkan.
        // Contoh jika file HTML-nya ada di: app/views/guru/login.php

        $this->view('guru/login', $data);

        // Catatan: Jika kamu memakai header dan footer terpisah, gunakan seperti ini:
        // $this->view('templates/header', $data);
        // $this->view('guru/login', $data);
        // $this->view('templates/footer');
    }

    public function prosesLogin()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validasi username tidak kosong
        if (empty($username)) {
            $_SESSION['error_login'] = 'Username tidak boleh kosong.';
            header('Location: ' . BASEURL . '/guru/login?role=guru&error=username_empty');
            exit;
        }

        // Validasi password tidak kosong
        if (empty($password)) {
            $_SESSION['error_login'] = 'Password tidak boleh kosong.';
            header('Location: ' . BASEURL . '/guru/login?role=guru&error=password_empty');
            exit;
        }

        // Panggil Guru_model
        $guruModel = $this->model('Guru_model');

        // 1. Ambil data guru berdasarkan username
        $dataGuru = $guruModel->getGuruByUsername($username);

        // 2. Verifikasi jika data guru ditemukan DAN password cocok
        if ($dataGuru && password_verify($password, $dataGuru->password)) {
            // Hapus error session jika login berhasil
            unset($_SESSION['error_login']);

            // Ambil level hak akses guru (opsional tapi sangat berguna untuk otorisasi fitur)

            // Set Session Guru
            $_SESSION['login_guru'] = true;
            $_SESSION['guru_id'] = $dataGuru->id_guru;
            $_SESSION['nama_guru'] = $dataGuru->nama_guru;
            $_SESSION['username'] = $dataGuru->username;
            $_SESSION['user_roles'] = $guruModel->getLevelsForGuru($dataGuru->id_guru); // Ambil array level untuk disimpan di session


            // ==================== TAMBAHKAN LOG DI SINI ====================
            $this->logActivity('LOGIN', "Guru/Admin bernama {$_SESSION['nama_guru']} berhasil login ke sistem.");
            // ===============================================================

            // Redirect ke halaman dashboard guru
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        } else {
            // Username atau password salah
            $_SESSION['error_login'] = 'Username atau password salah. Silakan coba lagi.';
            header('Location: ' . BASEURL . '/guru/login?role=guru&error=invalid_credentials');
            exit;
        }
    }

    // =========================================================
    // FITUR TAMPIL HALAMAN UTAMA DAFTAR GURU (Ubah dari index ke daftar)
    // =========================================================
    public function daftar()
    {
        // Pastikan hanya user yang sudah login yang bisa mengakses halaman ini
        if (!isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/guru/login');
            exit;
        }

        $data['judul'] = 'Data Guru';

        // Panggil view sesuai arsitektur MVC
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);

        // Di sini kita arahkan ke file 'daftar.php' yang ada di folder 'app/views/guru/'
        $this->view('guru/daftar', $data);

        $this->view('templates/footer');
    }

    // =========================================================
    // FITUR MENYEDIAKAN DATA AJAX UNTUK DATATABLES (SERVER-SIDE)
    // =========================================================
    public function getServerSideGuru()
    {
        if (!isset($_SESSION['login_guru'])) {
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $requestData = $_POST;
        $output = $this->model('Guru_model')->getDataGuruServerSide($requestData);
        echo json_encode($output);
    }

    // =========================================================
    // FITUR TAMPIL HALAMAN TAMBAH GURU
    // =========================================================
    public function tambah()
    {
        // Pastikan hanya user yang sudah login yang bisa mengakses
        if (!isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/guru/login');
            exit;
        }

        $data['judul'] = 'Tambah Data Guru';

        // Panggil view sesuai standar MVC
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('guru/tambah', $data); // Asumsi nama file view kamu adalah tambah.php
        $this->view('templates/footer');
    }

    // =========================================================
    // FITUR PROSES INSERT DATA (DARI FORM TAMBAH)
    // =========================================================
    public function prosesTambah()
    {
        // Pastikan hanya user yang sudah login yang bisa memproses
        if (!isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/guru/login');
            exit;
        }

        // Cek apakah data berhasil ditambahkan (> 0 berarti ada baris baru di database)
        if ($this->model('Guru_model')->tambahDataGuru($_POST) > 0) {
            // Set notifikasi sukses
            Flasher::setFlash('berhasil', 'ditambahkan', 'success');
            // Redirect kembali ke halaman daftar guru
            header('Location: ' . BASEURL . '/guru/daftar');
            exit;
        } else {
            // Set notifikasi gagal
            Flasher::setFlash('gagal', 'ditambahkan', 'danger');
            // Redirect kembali ke halaman daftar guru
            header('Location: ' . BASEURL . '/guru/daftar');
            exit;
        }
    }


    // =========================================================
    // FITUR TAMPIL HALAMAN EDIT GURU
    // =========================================================
    public function edit($id)
    {
        if (!isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/guru/login');
            exit;
        }

        $data['judul'] = 'Edit Data Guru';

        // 1. Ambil data utama guru berdasarkan ID
        // (Pastikan method getGuruById sudah ada di model kamu ya)
        $data['guru'] = $this->model('Guru_model')->getGuruById($id);

        // 2. Ambil semua data level untuk ditampilkan sebagai checkbox
        $data['all_levels'] = $this->model('Guru_model')->getAllLevels();

        // 3. Ambil array ID level yang sudah dimiliki guru ini 
        // (MENGGUNAKAN METHOD BARU KAMU: getLevelIdsForGuru)
        $data['current_level_ids'] = $this->model('Guru_model')->getLevelIdsForGuru($id);

        // Panggil view
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('guru/edit', $data);
        $this->view('templates/footer');
    }

    // =========================================================
    // FITUR PROSES UPDATE DATA (DARI FORM EDIT)
    // =========================================================
    public function prosesUpdate()
    {
        if (!isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/guru/login');
            exit;
        }

        // Panggil fungsi update di model kamu
        $this->model('Guru_model')->updateDataGuru($_POST);

        // Catatan: Karena di model kamu updateDataGuru() mengembalikan rowCount()
        // dari tabel utama (guru), nilainya bisa jadi 0 jika user hanya mencentang/menghapus 
        // checkbox level tanpa mengubah teks form (nama, NIK, dll). 
        // Oleh karena itu, kita anggap proses klik "Simpan" sebagai sukses.

        Flasher::setFlash('berhasil', 'diupdate', 'success');
        header('Location: ' . BASEURL . '/guru/daftar');
        exit;
    }

    // =========================================================
    // FITUR HAPUS GURU (Opsional jika ingin dilengkapkan sekalian)
    // =========================================================

    public function hapus($id)
    {
        if (!isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/guru/login');
            exit;
        }

        if ($this->model('Guru_model')->hapusDataGuru($id) > 0) {
            // Flasher::setFlash('berhasil', 'dihapus', 'success');
            header('Location: ' . BASEURL . '/guru');
            exit;
        } else {
            // Flasher::setFlash('gagal', 'dihapus', 'danger');
            header('Location: ' . BASEURL . '/guru');
            exit;
        }
    }

    // =========================================================
    // FITUR TAMPIL HALAMAN PROFIL SAYA (GURU)
    // =========================================================
    public function profil()
    {
        // 1. Pastikan user sudah login
        if (!isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/guru/login');
            exit;
        }

        $data['judul'] = 'Profil Saya';

        // 2. Ambil ID Guru yang sedang login dari Session
        // (Pastikan saat proses login, kamu menyimpan id_guru ke dalam $_SESSION['id_guru'])
        $id_guru_login = $_SESSION['guru_id'];

        // 3. Panggil data guru dari model menggunakan method yang sudah ada
        $data['guru'] = $this->model('Guru_model')->getGuruById($id_guru_login);

        // 4. Panggil view
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);

        // Asumsi view yang kamu kirim di atas disimpan dengan nama 'profil.php' di folder 'app/views/guru/'
        $this->view('guru/profil', $data);

        $this->view('templates/footer');
    }

    // =========================================================
    // FITUR TAMPIL HALAMAN EDIT PROFIL SAYA
    // =========================================================
    public function editProfil($id)
    {
        // 1. Cek Login
        if (!isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/guru/login');
            exit;
        }

        // 2. Keamanan Ekstra: Pastikan guru HANYA bisa mengedit profilnya sendiri
        // Jika ID di URL beda dengan ID yang login, lempar kembali ke profilnya
        if ($_SESSION['guru_id'] != $id) {
            header('Location: ' . BASEURL . '/guru/profil');
            exit;
        }

        $data['judul'] = 'Edit Profil Saya';
        $data['guru'] = $this->model('Guru_model')->getGuruById($id);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        // Asumsi nama file view kamu adalah editProfil.php
        $this->view('guru/editProfil', $data);
        $this->view('templates/footer');
    }

    // =========================================================
    // FITUR PROSES UPDATE PROFIL SAYA
    // =========================================================
    public function prosesUpdateProfil()
    {
        if (!isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/guru/login');
            exit;
        }

        // Pastikan ID yang dikirim dari form (hidden input) sama dengan session
        if ($_POST['id_guru'] == $_SESSION['guru_id']) {

            // Panggil method model KHUSUS profil (yang tidak hapus level)
            if ($this->model('Guru_model')->updateProfilSaya($_POST) >= 0) {
                // Flash message sukses
                Flasher::setFlash('berhasil', 'diupdate', 'success');
            } else {
                // Flash message gagal
                Flasher::setFlash('gagal', 'diupdate', 'danger');
            }
        }

        // Redirect kembali ke halaman profil (bukan daftar guru)
        header('Location: ' . BASEURL . '/guru/profil');
        exit;
    }

    public function logout()
    {
        // Hapus session guru
        unset($_SESSION['login_guru']);
        unset($_SESSION['data_guru']);

        // Jika menggunakan token auth (remember me), kamu bisa menambahkan logic untuk 
        // menghapus cookie & memanggil method deleteAuthToken() dari Guru_model di sini.

        header('Location: ' . BASEURL . '/guru/login');
        exit;
    }
}
