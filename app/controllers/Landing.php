<?php

class Landing extends Controller
{
    public function index()
    {
        // Landing page bersifat PUBLIK — tidak ada blokir login di sini.

        // Ambil data statistik untuk section "Statistik"
        $siswaModel = $this->model('Siswa_model');
        $guruModel  = $this->model('Guru_model');
        $profil     = $this->model('ProfilSekolah_model')->getProfil();

        $data['judul']          = 'Beranda';
        $data['profil']         = $profil;
        $data['total_siswa']    = $siswaModel->hitungJumlahSiswa();
        $data['total_rombel']   = $siswaModel->hitungJumlahRombel();
        $data['total_jurusan']  = $siswaModel->hitungJumlahJurusan();
        $data['total_guru']     = $guruModel->hitungJumlahGuru();

        $this->view('landing/index', $data);
    }
}
