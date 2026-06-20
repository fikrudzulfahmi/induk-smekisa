<?php
// Panggil class PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;


class Mutasi extends Controller
{
    private $siswaModel;
    private $jurusanModel;
    private $rombelModel;
    private $statusModel;

    public function __construct()
    {
        // // Pengecekan role admin
        // if (!Auth::checkRole('admin')) {
        //     Flasher::setFlash('Akses Ditolak', 'Anda tidak memiliki izin.', 'error');
        //     header('Location: ' . BASEURL . '/dashboard');
        //     exit;
        // }

        $this->siswaModel = $this->model('Siswa_model');
        $this->jurusanModel = $this->model('Jurusan_model');
        $this->rombelModel = $this->model('Rombel_model');
        $this->statusModel = $this->model('Status_model');
    }

    public function index()
    {
        $data['judul'] = 'Data Mutasi';
        // Menampilkan ringkasan atau dashboard mutasi (opsional)
        // Di sini kita tampilkan daftar mutasi masuk sebagai default view index
        $data['mutasi_masuk'] = $this->siswaModel->getDaftarMutasiMasuk();

        $this->view('templates/header', $data);
        $this->view('mutasi/index', $data);
        $this->view('templates/footer');
    }

    // =================================================================================
    // MUTASI MASUK
    // =================================================================================

    public function masuk()
    {
        $data['judul'] = 'Form Mutasi Masuk';
        $data['jurusan'] = $this->jurusanModel->getAllJurusan();
        $data['rombel'] = $this->rombelModel->getAllRombel();
        $data['status_list'] = $this->statusModel->getAllStatus();

        // Ambil data lama jika ada error validasi sebelumnya
        $data['old_input'] = $_SESSION['old_input'] ?? null;
        unset($_SESSION['old_input']);

        $this->view('templates/header', $data);
        $this->view('mutasi/masuk', $data);
        $this->view('templates/footer');
    }

    public function daftarMasuk()
    {
        $data['judul'] = 'Daftar Siswa Mutasi Masuk';
        $data['mutasi_masuk'] = $this->siswaModel->getDaftarMutasiMasuk();

        $this->view('templates/header', $data);
        $this->view('mutasi/daftar_masuk', $data);
        $this->view('templates/footer');
    }

    public function prosesMasuk()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Flasher::setFlash('Error', 'Metode request tidak valid.', 'error');
            header('Location: ' . BASEURL . '/mutasi/masuk');
            exit;
        }

        // Tentukan ID status untuk "Aktif" (misal 1)
        $ID_STATUS_AKTIF = 1;
        $_POST['id_status'] = $ID_STATUS_AKTIF;

        // 1. Simpan data siswa baru
        $newSiswaId = $this->siswaModel->tambahDataSiswa($_POST);

        if ($newSiswaId) {
            // 2. Simpan log mutasi masuk
            try {
                $dataMutasi = [
                    'tgl_diterima' => $_POST['tgl_diterima'],
                    'asal_sekolah' => $_POST['asal_sekolah'],
                    'alasan_pindah' => $_POST['alasan_pindah']
                ];
                $this->siswaModel->tambahDataMutasiMasuk($newSiswaId, $dataMutasi);

                Flasher::setFlash('Siswa Mutasi Masuk', 'berhasil ditambahkan', 'success');
                header('Location: ' . BASEURL . '/mutasi/daftarMasuk');
                exit;
            } catch (Exception $e) {
                Flasher::setFlash('Siswa berhasil dibuat,', 'tapi log mutasi gagal dicatat: ' . $e->getMessage(), 'warning');
                header('Location: ' . BASEURL . '/mutasi/masuk');
                exit;
            }
        } else {
            Flasher::setFlash('Data Siswa', 'gagal ditambahkan', 'error');
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASEURL . '/mutasi/masuk');
            exit;
        }
    }

    public function detail($id)
    {
        $data['judul'] = 'Detail Mutasi';
        $data['siswa'] = $this->siswaModel->getDetailSiswaById($id);

        $this->view('templates/header', $data);
        $this->view('mutasi/detail', $data);
        $this->view('templates/footer');
    }

    // =================================================================================
    // FORM MUTASI KELUAR / MENGUNDURKAN DIRI (SHARED)
    // =================================================================================

    public function keluar()
    {
        $data['judul'] = 'Form Mutasi Keluar / Mengundurkan Diri';
        // Tidak perlu data siswa spesifik di sini karena menggunakan Select2 AJAX di view
        $this->view('templates/header', $data);
        $this->view('mutasi/keluar', $data);
        $this->view('templates/footer');
    }

    /**
     * Memproses form Mutasi Keluar ATAU Mengundurkan Diri
     */
    public function prosesKeluar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Flasher::setFlash('Error', 'Metode request tidak valid.', 'error');
            header('Location: ' . BASEURL . '/mutasi/keluar');
            exit;
        }

        $id_siswa = $_POST['id_siswa'];
        $jenis_aksi = $_POST['jenis_aksi']; // 'keluar' atau 'undur_diri'
        $dataForm = $_POST;

        $berhasil = false;
        $pesanSukses = '';
        $redirectUrl = BASEURL . '/mutasi/keluar'; // Default redirect jika gagal

        try {
            if ($jenis_aksi == 'keluar') {
                if ($this->siswaModel->mutasiKeluar($id_siswa, $dataForm) > 0) {
                    $berhasil = true;
                    $pesanSukses = 'Siswa telah diproses mutasi keluar.';
                    $redirectUrl = BASEURL . '/mutasi/daftarKeluar';
                }
            } elseif ($jenis_aksi == 'undur_diri') {
                if ($this->siswaModel->mengundurkanDiri($id_siswa, $dataForm) > 0) {
                    $berhasil = true;
                    $pesanSukses = 'Siswa telah dicatat mengundurkan diri.';
                    $redirectUrl = BASEURL . '/mutasi/daftarMengundurkanDiri';
                }
            } else {
                throw new Exception("Jenis aksi tidak valid.");
            }
        } catch (Exception $e) {
            Flasher::setFlash('Error', 'Terjadi kesalahan: ' . $e->getMessage(), 'error');
            header('Location: ' . BASEURL . '/mutasi/keluar');
            exit;
        }

        if ($berhasil) {
            Flasher::setFlash('Berhasil', $pesanSukses, 'success');
        } else {
            Flasher::setFlash('Gagal', 'Gagal memproses data siswa. Pastikan data valid.', 'error');
            $redirectUrl = BASEURL . '/mutasi/keluar';
        }

        header('Location: ' . $redirectUrl);
        exit;
    }

    // =================================================================================
    // MANAJEMEN MUTASI KELUAR
    // =================================================================================

    public function daftarKeluar()
    {
        $data['judul'] = 'Daftar Siswa Mutasi Keluar';
        $data['mutasi_keluar'] = $this->siswaModel->getDaftarMutasiKeluar();

        $this->view('templates/header', $data);
        $this->view('mutasi/daftar_keluar', $data);
        $this->view('templates/footer');
    }

    public function editKeluar($id_log)
    {
        $data['judul'] = 'Edit Data Mutasi Keluar';
        // Mengambil data log mutasi keluar berdasarkan ID log
        $data['log'] = $this->siswaModel->getMutasiKeluarById($id_log);

        if (!$data['log']) {
            Flasher::setFlash('Error', 'Data mutasi keluar tidak ditemukan.', 'error');
            header('Location: ' . BASEURL . '/mutasi/daftarKeluar');
            exit;
        }

        $this->view('templates/header', $data);
        $this->view('mutasi/edit_keluar', $data);
        $this->view('templates/footer');
    }

    public function prosesUpdateKeluar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/mutasi/daftarKeluar');
            exit;
        }

        if ($this->siswaModel->updateMutasiKeluar($_POST) > 0) {
            Flasher::setFlash('Berhasil', 'Data mutasi keluar telah diperbarui.', 'success');
        } else {
            Flasher::setFlash('Gagal', 'Gagal memperbarui data atau tidak ada perubahan.', 'warning');
        }

        header('Location: ' . BASEURL . '/mutasi/daftarKeluar');
        exit;
    }

    public function hapusKeluar($id_log)
    {
        // PERINGATAN: Menghapus log mutasi keluar seharusnya mengembalikan status siswa menjadi AKTIF?
        // Logic ini idealnya ada di dalam model hapusMutasiKeluar
        if ($this->siswaModel->hapusMutasiKeluar($id_log) > 0) {
            Flasher::setFlash('Berhasil', 'Data mutasi keluar telah dihapus.', 'success');
        } else {
            Flasher::setFlash('Gagal', 'Gagal menghapus data mutasi keluar.', 'error');
        }
        header('Location: ' . BASEURL . '/mutasi/daftarKeluar');
        exit;
    }

    // =================================================================================
    // MANAJEMEN MENGUNDURKAN DIRI
    // =================================================================================

    public function daftarMengundurkanDiri()
    {
        $data['judul'] = 'Daftar Siswa Mengundurkan Diri';
        $data['mengundurkan_diri'] = $this->siswaModel->getDaftarMengundurkanDiri();

        $this->view('templates/header', $data);
        $this->view('mutasi/daftar_mengundurkan_diri', $data);
        $this->view('templates/footer');
    }

    public function editMengundurkanDiri($id_log)
    {
        $data['judul'] = 'Edit Data Mengundurkan Diri';
        // Mengambil data log mengundurkan diri berdasarkan ID log
        $data['log'] = $this->siswaModel->getMengundurkanDiriById($id_log);

        if (!$data['log']) {
            Flasher::setFlash('Error', 'Data mengundurkan diri tidak ditemukan.', 'error');
            header('Location: ' . BASEURL . '/mutasi/daftarMengundurkanDiri');
            exit;
        }

        $this->view('templates/header', $data);
        $this->view('mutasi/edit_mengundurkan_diri', $data); // Pastikan view ini ada
        $this->view('templates/footer');
    }

    public function prosesUpdateMengundurkanDiri()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/mutasi/daftarMengundurkanDiri');
            exit;
        }

        if ($this->siswaModel->updateMengundurkanDiri($_POST) > 0) {
            Flasher::setFlash('Berhasil', 'Data mengundurkan diri telah diperbarui.', 'success');
        } else {
            Flasher::setFlash('Gagal', 'Gagal memperbarui data atau tidak ada perubahan.', 'warning');
        }

        header('Location: ' . BASEURL . '/mutasi/daftarMengundurkanDiri');
        exit;
    }

    public function hapusMengundurkanDiri($id_log)
    {
        // PERINGATAN: Menghapus log undur diri seharusnya mengembalikan status siswa menjadi AKTIF?
        // Logic ini idealnya ada di dalam model hapusMengundurkanDiri
        if ($this->siswaModel->hapusMengundurkanDiri($id_log) > 0) {
            Flasher::setFlash('Berhasil', 'Data mengundurkan diri telah dihapus.', 'success');
        } else {
            Flasher::setFlash('Gagal', 'Gagal menghapus data mengundurkan diri.', 'error');
        }
        header('Location: ' . BASEURL . '/mutasi/daftarMengundurkanDiri');
        exit;
    }

    public function exportExcelMasuk()
    {
        if (!Auth::checkRole('admin')) {
            exit;
        }

        $dataMutasi = $this->siswaModel->getDaftarMutasiMasuk();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- Header Judul ---
        $sheet->setCellValue('A1', 'DATA SISWA MUTASI MASUK');
        $sheet->mergeCells('A1:H1'); // Gabungkan sel judul
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // --- Header Kolom (Baris 3) ---
        $headers = ['No', 'Sekolah Yang Dituju', 'Nama Siswa', 'NIS', 'Rombel', 'Asal Sekolah', 'Tanggal Diterima', 'Alasan Pindah'];
        $columnIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($columnIndex . '3', $header);
            $sheet->getStyle($columnIndex . '3')->getFont()->setBold(true);
            $sheet->getStyle($columnIndex . '3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $columnIndex++;
        }

        // --- Isi Data (Mulai Baris 4) ---
        $rowNum = 4;
        $no = 1;
        foreach ($dataMutasi as $row) {
            // Ubah akses array ['...'] menjadi object ->...
            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, 'SMK ISLAM 1 BLITAR');
            $sheet->setCellValue('C' . $rowNum, $row->nama_siswa);
            $sheet->setCellValueExplicit('D' . $rowNum, $row->no_induk, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $rowNum, $row->nama_rombel);
            $sheet->setCellValue('F' . $rowNum, $row->asal_sekolah);
            $sheet->setCellValue('G' . $rowNum, date('d-m-Y', strtotime($row->tgl_diterima)));
            $sheet->setCellValue('H' . $rowNum, $row->alasan_pindah);
            $rowNum++;
        }

        // --- Styling Table (Border) ---
        $lastRow = $rowNum - 1;
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A3:H' . $lastRow)->applyFromArray($styleArray);

        // --- Auto Size Column ---
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // --- Output File ---
        $filename = 'Laporan_Mutasi_Masuk_' . date('Ymd_His');
        $this->downloadExcel($spreadsheet, $filename);
    }



    public function exportExcelKeluar()
    {
        if (!Auth::checkRole('admin')) {
            exit;
        }

        $dataMutasi = $this->siswaModel->getDaftarMutasiKeluar();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul
        $sheet->setCellValue('A1', 'DATA SISWA MUTASI KELUAR');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header
        $headers = ['No', 'Nama Siswa', 'NIS', 'Rombel', 'Tanggal Keluar', 'Sekolah Tujuan', 'Sekolah Asal', 'Alasan Keluar'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '3', $h);
            $sheet->getStyle($col . '3')->getFont()->setBold(true);
            $sheet->getStyle($col . '3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $col++;
        }

        // --- ISI DATA (BAGIAN YANG DIPERBAIKI) ---
        $rowNum = 4;
        $no = 1;
        foreach ($dataMutasi as $row) {
            // Ganti akses array ['key'] menjadi object ->key
            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, $row->nama_siswa);
            $sheet->setCellValueExplicit('C' . $rowNum, $row->no_induk, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('D' . $rowNum, $row->nama_rombel);
            $sheet->setCellValue('E' . $rowNum, date('d-m-Y', strtotime($row->tgl_keluar)));
            $sheet->setCellValue('F' . $rowNum, $row->sekolah_tujuan);
            $sheet->setCellValue('G' . $rowNum, 'SMK ISLAM 1 BLITAR');
            $sheet->setCellValue('H' . $rowNum, $row->alasan_keluar);
            $rowNum++;
        }

        // Finishing (Border, AutoSize, Download)
        // Pastikan method finishExcel sudah ada di paling bawah controller (seperti saran sebelumnya)
        // Jika belum ada helper finishExcel, gunakan kode styling manual
        $this->finishExcel($spreadsheet, 'A', 'H', $rowNum, 'Laporan_Mutasi_Keluar_' . date('Ymd_His'));
    }

    private function finishExcel($spreadsheet, $startCol, $endCol, $lastRowIndex, $filename)
    {
        $sheet = $spreadsheet->getActiveSheet();
        $lastRow = $lastRowIndex - 1; // Baris terakhir data

        // 1. Set Border untuk seluruh tabel
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        // Terapkan border dari Header (baris 3) sampai data terakhir
        $sheet->getStyle($startCol . '3:' . $endCol . $lastRow)->applyFromArray($styleArray);

        // 2. Auto Size Kolom (agar lebar kolom otomatis pas)
        foreach (range($startCol, $endCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 3. Panggil fungsi download
        $this->downloadExcel($spreadsheet, $filename);
    }
    public function exportExcelMengundurkanDiri()
    {
        if (!Auth::checkRole('admin')) {
            exit;
        }

        $dataMutasi = $this->siswaModel->getDaftarMengundurkanDiri();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul
        $sheet->setCellValue('A1', 'DATA SISWA MENGUNDURKAN DIRI');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header
        $headers = ['No', 'Nama Siswa', 'NIS', 'Rombel', 'Sekolah Asal', 'Tanggal Pengunduran Diri', 'Alasan'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '3', $h);
            $sheet->getStyle($col . '3')->getFont()->setBold(true);
            $sheet->getStyle($col . '3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $col++;
        }

        // --- ISI DATA (BAGIAN YANG DIPERBAIKI) ---
        $rowNum = 4;
        $no = 1;
        foreach ($dataMutasi as $row) {
            // Ganti akses array ['key'] menjadi object ->key
            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, $row->nama_siswa);
            $sheet->setCellValueExplicit('C' . $rowNum, $row->no_induk, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('D' . $rowNum, $row->nama_rombel);
            $sheet->setCellValue('E' . $rowNum, 'SMK ISLAM 1 BLITAR');
            $sheet->setCellValue('F' . $rowNum, date('d-m-Y', strtotime($row->tgl_mengundurkan_diri)));
            $sheet->setCellValue('G' . $rowNum, $row->alasan_mengundurkan_diri);
            $rowNum++;
        }

        // Finishing
        $this->finishExcel($spreadsheet, 'A', 'G', $rowNum, 'Laporan_Mengundurkan_Diri_' . date('Ymd_His'));
    }

    /**
     * Helper Private Function untuk memaksa download file Excel
     */
    private function downloadExcel($spreadsheet, $filename)
    {
        $writer = new Xlsx($spreadsheet);

        // Bersihkan buffer output agar file tidak rusak
        if (ob_get_contents()) ob_end_clean();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
