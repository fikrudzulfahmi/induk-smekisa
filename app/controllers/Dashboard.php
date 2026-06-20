<?php

class Dashboard extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/guru/login');
            exit;
        }

        $data['judul'] = 'Dashboard';
        $data['guru'] = $this->model('Guru_model')->getGuruById($_SESSION['guru_id']);

        // --- 4 KARTU UTAMA ---
        $data['total_siswa'] = $this->model('Siswa_model')->hitungJumlahSiswa();
        $data['total_rombel'] = $this->model('Siswa_model')->hitungJumlahRombel();
        $data['total_jurusan'] = $this->model('Siswa_model')->hitungJumlahJurusan();
        // Tambahkan hitung user (Asumsi: Guru dianggap sebagai user/akun)
        $data['total_user'] = $this->model('Guru_model')->hitungJumlahGuru();

        // --- DATA REKAP TINGKAT & ROMBEL (Sesuai kode sebelumnya) ---
        $allRombel = $this->model('Rombel_model')->getAllRombelWithStudentCounts();
        $groupedRombel = ['10' => [], '11' => [], '12' => []];
        $rekap_tingkat = [
            '10' => ['laki' => 0, 'perempuan' => 0, 'total' => 0],
            '11' => ['laki' => 0, 'perempuan' => 0, 'total' => 0],
            '12' => ['laki' => 0, 'perempuan' => 0, 'total' => 0]
        ];

        foreach ($allRombel as $rombel) {
            if (strpos($rombel->nama_rombel, '10 ') === 0) $tingkat = '10';
            elseif (strpos($rombel->nama_rombel, '11 ') === 0) $tingkat = '11';
            elseif (strpos($rombel->nama_rombel, '12 ') === 0) $tingkat = '12';
            else continue;

            $groupedRombel[$tingkat][] = $rombel;
            $rekap_tingkat[$tingkat]['laki'] += $rombel->jumlah_laki;
            $rekap_tingkat[$tingkat]['perempuan'] += $rombel->jumlah_perempuan;
            $rekap_tingkat[$tingkat]['total'] += $rombel->total_siswa;
        }

        $data['groupedRombel'] = $groupedRombel;
        $data['rekap_tingkat'] = $rekap_tingkat;

        $data['siswa_diedit'] = [];
        if (Auth::checkRole('admin')) {
            $data['siswa_diedit'] = $this->model('Siswa_model')->getSiswaBaruDiedit();
        }

        $this->view('dashboard/index', $data);
    }
}
