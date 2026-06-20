<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType; // Untuk format teks

use Dompdf\Dompdf;
use Dompdf\Options;

class Rombel extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/guru/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Data Rombel';
        $this->view('rombel/index', $data);
    }

    public function getFilterOptions()
    {
        $data = [
            'rombel'  => $this->model('Rombel_model')->getListRombel(),
            'jurusan' => $this->model('Rombel_model')->getListJurusan()
        ];

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function getListRombel()
    {
        $this->db->query("SELECT DISTINCT nama_rombel FROM rombel ORDER BY nama_rombel");
        return $this->db->resultSet();
    }

    public function getListJurusan()
    {
        $this->db->query("SELECT DISTINCT jurusan FROM jurusan ORDER BY jurusan");
        return $this->db->resultSet();
    }

    public function getServerSideRombel()
    {
        $data = $this->model('Rombel_model')->getDataRombelServerSide($_POST);
        header('Content-Type: application/json');
        echo json_encode($data);
    }


    public function tambah()
    {
        if (!Auth::checkRole('admin')) {
            $this->block();
        }
        $data['judul'] = 'Tambah Rombel';
        $data['jurusan'] = $this->model('Jurusan_model')->getAllJurusan();
        $data['guru'] = $this->model('Guru_model')->getAllGuru();
        $this->view('rombel/tambah', $data);
    }

    public function prosesTambah()
    {
        if (!Auth::checkRole('admin')) {
            $this->block();
        }
        if ($this->model('Rombel_model')->tambahDataRombel($_POST) > 0) {
            Flasher::setFlash('Rombel', 'berhasil ditambahkan', 'success');
        } else {
            Flasher::setFlash('Rombel', 'gagal ditambahkan', 'error');
        }
        header('Location: ' . BASEURL . '/rombel');
        exit;
    }

    public function edit($id)
    {
        if (!Auth::checkRole('admin')) {
            $this->block();
        }
        $data['judul'] = 'Edit Rombel';
        $data['rombel'] = $this->model('Rombel_model')->getRombelById($id);
        $data['jurusan'] = $this->model('Jurusan_model')->getAllJurusan();
        $data['guru'] = $this->model('Guru_model')->getAllGuru();
        $this->view('rombel/edit', $data);
    }

    public function prosesUpdate()
    {
        if (!Auth::checkRole('admin')) {
            $this->block();
        }
        if ($this->model('Rombel_model')->updateDataRombel($_POST) > 0) {
            Flasher::setFlash('Rombel', 'berhasil diubah', 'success');
        } else {
            Flasher::setFlash('Rombel', 'gagal diubah', 'error');
        }
        header('Location: ' . BASEURL . '/rombel');
        exit;
    }

    public function hapus($id)
    {
        if (!Auth::checkRole('admin')) {
            $this->block();
        }
        if ($this->model('Rombel_model')->hapusDataRombel($id) > 0) {
            Flasher::setFlash('Rombel', 'berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Rombel', 'gagal dihapus', 'error');
        }
        header('Location: ' . BASEURL . '/rombel');
        exit;
    }

    private function block()
    {
        Flasher::setFlash('Akses Ditolak', 'Anda tidak memiliki izin.', 'error');
        header('Location: ' . BASEURL . '/dashboard');
        exit;
    }
    // di dalam class Rombel

    public function anggota($id)
    {
        $data['judul'] = 'Anggota Rombel';
        // Ambil detail data rombel (untuk judul halaman)
        $data['rombel'] = $this->model('Rombel_model')->getRombelById($id);
        // Ambil semua siswa yang ada di rombel tersebut
        $data['siswa'] = $this->model('Siswa_model')->getSiswaByRombelId($id);

        $this->view('rombel/anggota', $data);
    }

    public function cetak_pdf($id)
    {
        // 1. Ambil data yang diperlukan
        $data['rombel'] = $this->model('Rombel_model')->getRombelByIdWithDetails($id);
        $data['siswa'] = $this->model('Siswa_model')->getSiswaByRombelId($id);
        $data['bulan'] = "........................."; // Anda bisa menambahkan logika untuk bulan
        $tahun_pelajaran_aktif = $this->model('Rombel_model')->getActiveTahunPelajaran();
        $data['tp'] = $tahun_pelajaran_aktif ? $tahun_pelajaran_aktif->tp : 'Data TP Tidak Ditemukan'; // Ambil kolom 'tp'

        // === TAMBAHAN PENTING ===
        $mengundurkan = $this->model('Siswa_model')
            ->getMengundurkanDiriByRombel($id);

        $data['map_keluar'] = [];
        foreach ($mengundurkan as $md) {
            $data['map_keluar'][$md->id_induk] = $md->tgl_mengundurkan_diri;
        }
        // 2. Render view HTML ke dalam sebuah variabel
        // Kita butuh helper untuk ini, tambahkan method renderView() di Controller.php
        $html = $this->renderView('rombel/presensi_template', $data);

        // 3. Konfigurasi Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Izinkan untuk memuat gambar/CSS eksternal jika ada

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // 4. Set Ukuran Kertas & Orientasi
        $dompdf->setPaper([0, 0, 595.28, 935.43], 'portrait');

        // 5. Render HTML menjadi PDF
        $dompdf->render();

        // 6. Output PDF ke Browser
        // Argumen kedua false agar PDF ditampilkan di browser, bukan langsung di-download
        $nama_rombel = $data['rombel']->nama_rombel ?? 'Tidak Diketahui';

        // ==================== TAMBAHKAN LOG DI SINI ====================
        $this->logActivity('PRINT', "Admin menampilkan / mencetak PDF Daftar Hadir untuk Rombel: {$nama_rombel} (ID: {$id}).");
        // ===============================================================

        $dompdf->stream("Daftar Hadir - " . $nama_rombel . ".pdf", array("Attachment" => false));
        exit();
    }

    public function cetakRekap()
    {
        // Ambil tahun pelajaran aktif
        $tahun_pelajaran_aktif = $this->model('Rombel_model')->getActiveTahunPelajaran();
        $data['tp'] = $tahun_pelajaran_aktif ? $tahun_pelajaran_aktif->tp : 'Tidak Ditemukan';

        // Ambil data rombel beserta jumlah siswa
        $allRombel = $this->model('Rombel_model')->getAllRombelWithStudentCounts();

        // Kelompokkan data berdasarkan tingkat (10, 11, 12)
        $groupedRombel = [
            '10' => [],
            '11' => [],
            '12' => []
        ];

        foreach ($allRombel as $rombel) {
            if (strpos($rombel->nama_rombel, '10 ') === 0) {
                $groupedRombel['10'][] = $rombel;
            } elseif (strpos($rombel->nama_rombel, '11 ') === 0) {
                $groupedRombel['11'][] = $rombel;
            } elseif (strpos($rombel->nama_rombel, '12 ') === 0) {
                $groupedRombel['12'][] = $rombel;
            }
        }
        $data['groupedRombel'] = $groupedRombel;

        // --- Proses Generate PDF ---
        // Setup Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Jika ada gambar eksternal
        $dompdf = new Dompdf($options);

        // Load view template ke dalam buffer
        ob_start();
        // Buat file view baru: rekap_rombel_template.php
        $this->view('rombel/rekap_rombel_template', $data, true);
        $html = ob_get_clean();

        $dompdf->loadHtml($html);

        // (Opsional) Atur ukuran kertas dan orientasi
        $dompdf->setPaper([0, 0, 595.28, 935.43], 'portrait');

        // Render HTML sebagai PDF
        $dompdf->render();

        // Nama file PDF yang akan di-download
        $filename = "Rekap Rombel TP " . str_replace('/', '-', $data['tp']) . ".pdf";

        // ==================== TAMBAHKAN LOG DI SINI ====================
        $this->logActivity('PRINT', "Admin menampilkan / mencetak PDF Rekapitulasi Seluruh Rombel untuk Tahun Pelajaran: {$data['tp']}.");
        // ===============================================================

        // Output PDF ke browser untuk di-download
        $dompdf->stream($filename, ["Attachment" => false]);
        exit();
    }

    public function exportAnggotaExcel($id)
    {
        // 1. Validasi ID & Autorisasi (Sama seperti sebelumnya)
        if (!is_numeric($id) || $id <= 0) { /* ... handle error ... */
        }
        // if (!Auth::checkRole(['admin', 'walas'])) { /* ... handle error ... */ }


        // 2. Ambil data dari model (Sama seperti sebelumnya)
        $rombel = $this->model('Rombel_model')->getRombelByIdWithDetails($id);
        if (!$rombel) { /* ... handle error ... */
        }

        $siswa_list = $this->model('Siswa_model')->getSiswaAktifByRombelId($id);
        $tahun_pelajaran_aktif = $this->model('Rombel_model')->getActiveTahunPelajaran();
        $tp = $tahun_pelajaran_aktif ? $tahun_pelajaran_aktif->tp : date('Y') . '/' . (date('Y') + 1);
        $nama_rombel = $rombel->nama_rombel ?? 'Kelas Tidak Diketahui';
        $nama_walas = $rombel->nama_guru ?? '-';

        // --- MULAI PERUBAHAN: Gunakan PhpSpreadsheet ---

        // 3. Buat objek Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Daftar Alamat Siswa'); // Nama sheet

        // 4. Set Header Laporan (Ubah Range Merge)
        $lastHeaderColumn = 'I'; // Kolom terakhir sekarang I (karena tambah 1 kolom)
        $sheet->mergeCells('A1:' . $lastHeaderColumn . '1'); // Ubah H jadi I
        $sheet->setCellValue('A1', 'DAFTAR ALAMAT SISWA TAHUN PELAJARAN ' . $tp);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:' . $lastHeaderColumn . '2'); // Ubah H jadi I
        $sheet->setCellValue('A2', 'SEKOLAH MENENGAH KEJURUAN ISLAM 1 BLITAR');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:' . $lastHeaderColumn . '3'); // Ubah H jadi I
        $sheet->setCellValue('A3', 'KELAS : ' . $nama_rombel . '     Wali Kelas : ' . $nama_walas);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getRowDimension(3)->setRowHeight(20);

        // 5. Set Header Kolom Tabel (Tambahkan JK)
        $headerRow = 5;
        // Tambahkan 'JK' setelah No Induk
        $headers = ['NO', 'NAMA', 'NO INDUK', 'JK', 'ALAMAT', 'No HP', 'Nama Ayah', 'Nama Ibu', 'TANDA TANGAN'];
        $sheet->fromArray($headers, NULL, 'A' . $headerRow);

        // Styling Header Kolom (Ubah Range)
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9EAD3']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A' . $headerRow . ':' . $lastHeaderColumn . $headerRow)->applyFromArray($headerStyle); // Ubah H jadi I
        $sheet->getRowDimension($headerRow)->setRowHeight(25);

        // 6. Isi Data Siswa (Tambahkan JK)
        $rowNum = $headerRow + 1;
        $no = 1;
        if (!empty($siswa_list)) {
            foreach ($siswa_list as $siswa) {
                $jk = ($siswa->jenis_kelamin == 'Laki-Laki') ? 'L' : (($siswa->jenis_kelamin == 'Perempuan') ? 'P' : '-'); // Singkat L/P

                $sheet->setCellValue('A' . $rowNum, $no++);
                $sheet->setCellValue('B' . $rowNum, $siswa->nama_siswa);
                $sheet->setCellValueExplicit('C' . $rowNum, $siswa->no_induk, DataType::TYPE_STRING);
                $sheet->setCellValue('D' . $rowNum, $jk); // Tambah JK
                $sheet->setCellValue('E' . $rowNum, $siswa->alamat);
                $sheet->setCellValueExplicit('F' . $rowNum, $siswa->no_hp, DataType::TYPE_STRING);
                $sheet->setCellValue('G' . $rowNum, $siswa->nama_ayah);
                $sheet->setCellValue('H' . $rowNum, $siswa->nama_ibu);
                $sheet->setCellValue('I' . $rowNum, ''); // Tanda Tangan

                // Styling Baris Data (Ubah Range)
                $sheet->getStyle('A' . $rowNum . ':' . $lastHeaderColumn . $rowNum)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('A' . $rowNum . ':' . $lastHeaderColumn . $rowNum)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // JK Tengah
                $sheet->getStyle('C' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('F' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $rowNum++;
            }
        } else {
            // Jika tidak ada siswa (Ubah Range Merge)
            $sheet->mergeCells('A' . $rowNum . ':' . $lastHeaderColumn . $rowNum); // Ubah H jadi I
            $sheet->setCellValue('A' . $rowNum, 'Tidak ada siswa aktif di rombel ini.');
            // ... (style sama) ...
        }

        // 7. Atur Lebar Kolom (Tambahkan JK)
        $sheet->getColumnDimension('A')->setWidth(5);  // No
        $sheet->getColumnDimension('B')->setWidth(30); // Nama
        $sheet->getColumnDimension('C')->setWidth(15); // No Induk
        $sheet->getColumnDimension('D')->setWidth(5);  // JK
        $sheet->getColumnDimension('E')->setWidth(40); // Alamat
        $sheet->getColumnDimension('F')->setWidth(15); // No HP
        $sheet->getColumnDimension('G')->setWidth(20); // Nama Ayah
        $sheet->getColumnDimension('H')->setWidth(20); // Nama Ibu
        $sheet->getColumnDimension('I')->setWidth(15); // Tanda Tangan

        // Wrap text untuk kolom alamat (Ubah Range)
        $sheet->getStyle('E' . ($headerRow + 1) . ':E' . ($rowNum - 1))->getAlignment()->setWrapText(true);

        // 8. Siapkan Writer dan Header HTTP untuk Download File .xlsx
        $writer = new Xlsx($spreadsheet);
        $nama_rombel_safe = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nama_rombel);
        $filename = 'Daftar_Alamat_Siswa_' . $nama_rombel_safe . '_' . date('Ymd') . '.xlsx'; // Ganti ekstensi

        // Header HTTP
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // Tipe konten XLSX
        header('Content-Disposition: attachment; filename="' . urlencode($filename) . '"');
        header('Cache-Control: max-age=0');

        // ==================== TAMBAHKAN LOG DI SINI ====================
        $this->logActivity('EXPORT', "Admin mengekspor Daftar Alamat Siswa ke Excel (.xlsx) untuk Rombel: {$nama_rombel} (ID: {$id}) TP {$tp}.");
        // ===============================================================

        // 9. Simpan ke Output PHP
        $writer->save('php://output');
        exit;
    }
    /**
     * Menangkap request POST Kenaikan Kelas
     */
    public function aksiNaikKelas()
    {
        // --- 1. NYALAKAN DEBUGGER SEMENTARA ---
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        // --------------------------------------
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $siswaDipilih = $_POST['id_siswa'] ?? []; // Array dari checkbox
            $dariRombel   = $_POST['dari_rombel'];
            $keRombel     = $_POST['ke_rombel'];

            // 1. PANGGIL METHOD TAHUN AJARAN AKTIF
            $ta = $this->model('Rombel_model')->getActiveTahunPelajaran();

            // 2. CEK APAKAH TAHUN AJARAN DITEMUKAN
            if (!$ta) {
                // Jika error/tidak ditemukan, kembalikan ke halaman sebelumnya
                Flasher::setFlash('Gagal', 'Tahun ajaran aktif tidak ditemukan di database!', 'danger');

                // ==================== TAMBAHKAN LOG DI SINI ====================
                // Mencatat kegagalan fatal karena sistem belum memiliki TP aktif
                $this->logActivity('UPDATE', "Admin gagal memproses kenaikan kelas karena Tahun Pelajaran aktif belum dikonfigurasi di database.");
                // ===============================================================

                header('Location: ' . BASEURL . '/rombel/kenaikan');
                exit;
            }

            // GANTI 'tahun' dengan nama kolom yang menyimpan angka 2025 di tabel Anda
            $taAktif = $ta->tp;

            if (empty($siswaDipilih)) {
                Flasher::setFlash('Gagal', 'Tidak ada siswa yang dipilih!', 'danger');
                header('Location: ' . BASEURL . '/rombel/kenaikan'); // Sesuaikan route-nya
                exit;
            }

            // Hitung jumlah siswa yang dicentang untuk keperluan statistik log
            $jumlahSiswa = count($siswaDipilih);

            // Eksekusi Model
            $proses = $this->model('Rombel_model')->prosesNaikKelas($siswaDipilih, $dariRombel, $keRombel, $taAktif);

            // ==================== TAMBAHKAN LOG DI SINI ====================
            if ($proses) {
                Flasher::setFlash('Berhasil', 'Siswa terpilih berhasil dinaikkan kelas.', 'success');

                // Log sukses dengan mencatat volume data dan target rombel
                $this->logActivity('UPDATE', "Admin berhasil menaikkan kelas sebanyak {$jumlahSiswa} siswa dari Rombel ID: {$dariRombel} ke Rombel ID: {$keRombel} untuk Tahun Pelajaran {$taAktif}.");
            } else {
                Flasher::setFlash('Gagal', 'Terjadi kesalahan pada sistem saat menaikkan kelas.', 'danger');

                // Log gagal apabila query mengalami kegagalan di tingkat database
                $this->logActivity('UPDATE', "Admin gagal menaikkan kelas {$jumlahSiswa} siswa dari Rombel ID: {$dariRombel} ke Rombel ID: {$keRombel}. Terjadi kesalahan internal sistem.");
            }
            // ===============================================================

            header('Location: ' . BASEURL . '/rombel');
            exit;
        }
    }

    /**
     * Menangkap request POST Kelulusan
     */
    public function aksiLulus()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $siswaDipilih = $_POST['id_siswa'] ?? []; // Array dari checkbox
            $dariRombel   = $_POST['dari_rombel'];

            // 1. AMBIL DATA TAHUN PELAJARAN DENGAN AMAN
            $ta = $this->model('Rombel_model')->getActiveTahunPelajaran();

            // Cek apakah tahun ajaran ditemukan untuk mencegah fatal error (property on null)
            if (!$ta) {
                Flasher::setFlash('Gagal', 'Tahun ajaran aktif tidak ditemukan di database!', 'danger');

                // Log kegagalan sistem karena konfigurasi TP kosong
                $this->logActivity('UPDATE', "Admin gagal memproses kelulusan karena Tahun Pelajaran aktif belum dikonfigurasi di database.");

                header('Location: ' . BASEURL . '/rombel/kelulusan');
                exit;
            }

            $taAktif = $ta->tp;

            if (empty($siswaDipilih)) {
                Flasher::setFlash('Gagal', 'Tidak ada siswa yang dipilih!', 'danger');
                header('Location: ' . BASEURL . '/rombel/kelulusan'); // Sesuaikan route-nya
                exit;
            }

            // Hitung jumlah siswa terpilih untuk keperluan deskripsi log
            $jumlahSiswa = count($siswaDipilih);

            // Eksekusi Model
            $proses = $this->model('Rombel_model')->prosesLulus($siswaDipilih, $dariRombel, $taAktif);

            // ==================== TAMBAHKAN LOG DI SINI ====================
            if ($proses) {
                Flasher::setFlash('Berhasil', 'Siswa terpilih telah dinyatakan Lulus.', 'success');

                // Log sukses kelulusan massal dengan mencatat jumlah data, ID rombel asal, dan tahun pelajaran
                $this->logActivity('UPDATE', "Admin berhasil meluluskan sebanyak {$jumlahSiswa} siswa dari Rombel ID: {$dariRombel} pada Tahun Pelajaran {$taAktif}.");
            } else {
                Flasher::setFlash('Gagal', 'Terjadi kesalahan sistem saat memproses kelulusan.', 'danger');

                // Log gagal apabila query bermasalah di tingkat basis data
                $this->logActivity('UPDATE', "Admin gagal meluluskan {$jumlahSiswa} siswa dari Rombel ID: {$dariRombel}. Terjadi kesalahan internal database sistem.");
            }
            // ===============================================================

            header('Location: ' . BASEURL . '/rombel');
            exit;
        }
    }

    // Menampilkan halaman form kenaikan kelas
    public function kenaikan()
    {
        $data['judul'] = 'Kenaikan Kelas';
        $data['rombel'] = $this->model('Rombel_model')->getAllRombel(); // Ambil semua data rombel untuk dropdown

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('rombel/kenaikan', $data);
        $this->view('templates/footer');
    }

    // Menampilkan halaman form kelulusan
    public function kelulusan()
    {
        $data['judul'] = 'Kelulusan Siswa';
        // Ambil semua data rombel (Atau kalau mau difilter khusus kelas XII juga bisa)
        $data['rombel'] = $this->model('Rombel_model')->getAllRombel();

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('rombel/kelulusan', $data);
        $this->view('templates/footer');
    }

    // Method untuk AJAX: Mengambil siswa berdasarkan ID Rombel


    public function getSiswaByRombel($id_rombel)
    {
        // Pastikan hanya melayani request jika id_rombel ada isinya
        if (!empty($id_rombel)) {
            $siswa = $this->model('Siswa_model')->getSiswaByRombel($id_rombel);
            echo json_encode($siswa);
        } else {
            echo json_encode([]); // Kembalikan array kosong jika gagal/kosong
        }
    }

    public function history()
    {
        $data['judul'] = 'History Rombel';
        $data['history'] = $this->model('Rombel_model')->getHistoryRombel();

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('rombel/history', $data);
        $this->view('templates/footer');
    }
}
