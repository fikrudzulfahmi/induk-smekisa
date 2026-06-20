<?php

class Jurusan extends Controller
{
    public function __construct()
    {
        // Blokir akses jika belum login
        if (!isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/guru/login');
            exit;
        }
        // Pastikan hanya admin yang bisa mengakses controller ini
        if (!Auth::checkRole('admin') && !Auth::checkRole('waka') && !Auth::checkRole('kajur')) {
            Flasher::setFlash('Akses Ditolak', 'Anda tidak memiliki izin.', 'error');
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }
    }

    // Menampilkan halaman daftar jurusan
    public function index()
    {
        $data['judul'] = 'Daftar Jurusan';
        $this->view('jurusan/index', $data);
    }

    // API untuk DataTables server-side
    public function getServerSideJurusan()
    {
        $data = $this->model('Jurusan_model')->getDataJurusanServerSide($_POST);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    // Menampilkan form tambah
    public function tambah()
    {
        $data['judul'] = 'Tambah Jurusan';
        $this->view('jurusan/tambah', $data);
    }

    // Memproses data dari form tambah
    public function prosesTambah()
    {
        if ($this->model('Jurusan_model')->tambahDataJurusan($_POST) > 0) {
            Flasher::setFlash('Jurusan', 'berhasil ditambahkan', 'success');
        } else {
            Flasher::setFlash('Jurusan', 'gagal ditambahkan', 'error');
        }
        header('Location: ' . BASEURL . '/jurusan');
        exit;
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $data['judul'] = 'Edit Jurusan';
        $data['jurusan'] = $this->model('Jurusan_model')->getJurusanById($id);
        $this->view('jurusan/edit', $data);
    }

    // Memproses data dari form edit
    public function prosesUpdate()
    {
        if ($this->model('Jurusan_model')->updateDataJurusan($_POST) > 0) {
            Flasher::setFlash('Jurusan', 'berhasil diubah', 'success');
        } else {
            Flasher::setFlash('Jurusan', 'gagal diubah', 'error');
        }
        header('Location: ' . BASEURL . '/jurusan');
        exit;
    }

    // Menghapus data
    public function hapus($id)
    {
        if ($this->model('Jurusan_model')->hapusDataJurusan($id) > 0) {
            Flasher::setFlash('Jurusan', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Jurusan', 'gagal dihapus', 'error');
        }
        header('Location: ' . BASEURL . '/jurusan');
        exit;
    }

    // Mengambil semua data jurusan
    public function getAllJurusan()
    {
        $data = $this->model('Jurusan_model')->getAllJurusan();
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
