<?php
// ▼▼▼ Tambahkan use statement di atas class ▼▼▼
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

use Dompdf\Dompdf;
use Dompdf\Options;


class Siswa extends Controller
{
    public function index()
    {
        // Cek session login
        if (!isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/guru/login');
            exit;
        }

        // Hanya kirim judul, tidak perlu ambil data siswa di sini
        $data['judul'] = 'Data Induk Siswa';
        $data['current_page'] = 'siswa';

        // Tampilkan view
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('siswa/index', $data);
        $this->view('templates/footer');
    }
    public function getServerSideSiswa()
    {
        // Panggil method di model dan kirimkan request
        $data = $this->model('Siswa_model')->getDataSiswaServerSide($_POST);

        // Set header sebagai JSON
        header('Content-Type: application/json');

        // Outputkan data dalam format JSON
        echo json_encode($data);
    }
    public function detail($id)
    {
        // 1. Pastikan pengguna sudah login
        if (!isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/guru/login');
            exit;
        }

        // 2. Siapkan data untuk dikirim ke view
        $data['judul'] = 'Detail Siswa';

        // 3. Panggil method di model untuk mengambil data siswa berdasarkan ID
        $data['siswa'] = $this->model('Siswa_model')->getDetailSiswaById($id);

        // 1. Ambil data log mentah dari database
        $log_mentah = $this->model('Siswa_model')->getLogEditSiswa($id);

        // 2. Format ulang array agar nama kolom menjadi key
        $log_edit = [];
        foreach ($log_mentah as $log) {
            // Ubah object stdClass dari database menjadi array murni
            $log_array = (array) $log;

            // Karena query diurutkan DESC (terbaru), ambil yang paling baru saja
            if (!isset($log_edit[$log_array['kolom_diubah']])) {
                $log_edit[$log_array['kolom_diubah']] = $log_array;
            }
        }

        // 3. Masukkan ke data untuk dikirim ke view
        $data['log_edit'] = $log_edit;

        // 4. Muat file view dan kirimkan datanya
        $this->view('siswa/detail', $data);
    }

    public function edit($id)
    {
        if (!Auth::checkRole('admin')) { /* ... (blokir akses) ... */
        }

        $data['judul'] = 'Edit Data Siswa';
        $data['siswa'] = $this->model('Siswa_model')->getSiswaById($id);
        $data['jurusan'] = $this->model('Jurusan_model')->getAllJurusan();
        $data['rombel'] = $this->model('Rombel_model')->getAllRombel();
        // TAMBAHKAN INI UNTUK MENGAMBIL DATA STATUS
        $data['status_list'] = $this->model('Status_model')->getAllStatus();

        $this->view('siswa/edit', $data);
    }

    public function prosesUpdate()
    {
        if (!Auth::checkRole('admin')) {
            exit;
        }

        // Ambil id_induk terlebih dahulu dari data yang disubmit agar bisa dipakai di blok if maupun else
        $id_siswa = $_POST['id_induk'] ?? null;

        if ($this->model('Siswa_model')->updateDataSiswa($_POST) > 0) {

            // ==================== TAMBAHKAN LOG DI SINI ====================
            // Mengambil nama siswa dari input form jika ada
            $nama_siswa = $_POST['nama_siswa'] ?? 'Siswa';
            $nisn = $_POST['nisn'] ?? '-';

            $this->logActivity('UPDATE', "Admin berhasil memperbarui data induk siswa: {$nama_siswa} (NISN: {$nisn}).");
            // ===============================================================

            Flasher::setFlash('Data Siswa', 'berhasil diubah', 'success');

            // Arahkan ke halaman anggota rombel yang sesuai
            header('Location: ' . BASEURL . '/siswa/edit/' . $id_siswa);
            exit;
        } else {
            Flasher::setFlash('Data Siswa', 'gagal diubah', 'error');
            // Jika gagal, tetap kembali ke halaman edit siswa tersebut
            header('Location: ' . BASEURL . '/siswa/edit/' . $id_siswa);
            exit;
        }
    }

    // di file app/controllers/Siswa.php

    public function tambah()
    {
        if (!Auth::checkRole('admin')) {
            Flasher::setFlash('Akses Ditolak', 'Anda tidak memiliki izin.', 'error');
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        $data['judul'] = 'Tambah Data Siswa';
        // Ambil data untuk dropdown
        $data['jurusan'] = $this->model('Jurusan_model')->getAllJurusan();
        $data['rombel'] = $this->model('Rombel_model')->getAllRombel();
        $data['status_list'] = $this->model('Status_model')->getAllStatus();

        $this->view('siswa/tambah', $data);
    }

    public function prosesTambah()
    {
        if (!Auth::checkRole('admin')) {
            exit;
        }

        if ($this->model('Siswa_model')->tambahDataSiswa($_POST) > 0) {

            // ==================== TAMBAHKAN LOG DI SINI ====================
            // Mengambil nama siswa dan NISN dari data POST form yang diinput
            $nama_siswa = $_POST['nama_siswa'] ?? 'Siswa Baru';
            $nisn = $_POST['nisn'] ?? '-';

            $this->logActivity('CREATE', "Admin berhasil menambahkan data siswa baru: {$nama_siswa} (NISN: {$nisn}).");
            // ===============================================================

            Flasher::setFlash('Data Siswa', 'berhasil ditambahkan', 'success');
            header('Location: ' . BASEURL . '/siswa');
            exit;
        } else {
            Flasher::setFlash('Data Siswa', 'gagal ditambahkan', 'error');
            header('Location: ' . BASEURL . '/siswa');
            exit;
        }
    }

    public function nominatifOptions()
    {
        $data['judul'] = 'Opsi Export Nominatif';
        // Ambil data jurusan untuk dropdown
        $data['jurusan'] = $this->model('Jurusan_model')->getAllJurusan();
        $this->view('siswa/nominatif_options', $data);
    }

    public function exportNominatif()
    {
        // Pastikan request adalah POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Flasher::setFlash('Error', 'Metode request tidak valid.', 'error');
            header('Location: ' . BASEURL . '/siswa/nominatifOptions');
            exit;
        }

        // Ambil data dari form POST dan validasi
        $id_jurusan = $_POST['id_jurusan'] ?? null;
        $tingkat = $_POST['tingkat'] ?? null;

        // JIKA VALIDASI GAGAL (Contoh: tingkat kosong)
        if (empty($id_jurusan) || empty($tingkat)) {
            Flasher::setFlash('Error', 'Mohon pilih Jurusan dan Tingkat.', 'error');

            // Siapkan data untuk dikirim kembali ke view
            $data['judul'] = 'Opsi Export Nominatif';
            $data['jurusan'] = $this->model('Jurusan_model')->getAllJurusan();

            // ▼▼▼ INI BAGIAN PENTING ▼▼▼
            // Kirim semua data POST lama ke view agar form bisa "ingat"
            $data['old_input'] = $_POST;

            // Tampilkan lagi view form-nya, jangan redirect
            return $this->view('siswa/nominatif_options', $data);
        }

        // 1. Ambil Data yang Diperlukan
        $jurusan = $this->model('Jurusan_model')->getJurusanById($id_jurusan);
        $siswaList = $this->model('Siswa_model')->getSiswaByJurusanTingkatForNominatif($id_jurusan, $tingkat);
        $tahun_pelajaran_aktif = $this->model('Rombel_model')->getActiveTahunPelajaran();
        $tp = $tahun_pelajaran_aktif ? $tahun_pelajaran_aktif->tp : 'N/A';
        $namaSekolah = "SEKOLAH MENENGAH KEJURUAN ISLAM 1 BLITAR";
        $alamatSekolah = "Jl. Musi no. 2 Blitar Telp. (0342) 802137, 806835 Fax. 806835";
        $namaKepsek = "Drs. H. GIGIH WIDIYANTO";

        if (!$jurusan) {
            Flasher::setFlash('Error', 'Data Jurusan tidak ditemukan.', 'error');
            header('Location: ' . BASEURL . '/siswa/nominatifOptions');
            exit;
        }

        // 2. Buat Objek Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Sesuaikan judul sheet (gunakan $tingkat yang sudah divalidasi)
        $sheetTitle = 'Nominatif ' . substr($jurusan->jurusan, 0, 15) . ' - ' . $tingkat;
        $sheet->setTitle(substr($sheetTitle, 0, 31));

        // 3. Buat Header File
        // ... (Kode header Excel seperti sebelumnya) ...
        $sheet->mergeCells('A1:L1')->setCellValue('A1', 'LEMBAGA PENDIDIKAN MA\'ARIF NU');
        $sheet->mergeCells('A2:L2')->setCellValue('A2', strtoupper($namaSekolah));
        $sheet->mergeCells('A3:L3')->setCellValue('A3', $alamatSekolah);
        $sheet->mergeCells('A4:L4')->setCellValue('A4', 'DAFTAR NOMINATIF CALON PESERTA UJIAN NASIONAL');
        $sheet->mergeCells('A5:L5')->setCellValue('A5', 'TAHUN PELAJARAN : ' . $tp);
        $sheet->mergeCells('A6:L6')->setCellValue('A6', 'TINGKAT : ' . $tingkat); // Tambahkan info tingkat

        // Styling Header Utama
        $headerStyle = [
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:A6')->applyFromArray($headerStyle);
        $sheet->getStyle('A2')->getFont()->setSize(14);

        // Info Sekolah Tambahan
        $startInfoRow = 8; // Baris mulai info sekolah
        $sheet->setCellValue('D' . $startInfoRow, 'NAMA SEKOLAH')->setCellValue('E' . $startInfoRow, ': ' . $namaSekolah);
        $sheet->setCellValue('D' . ($startInfoRow + 1), 'STATUS SEKOLAH')->setCellValue('E' . ($startInfoRow + 1), ': SWASTA');
        $sheet->setCellValue('D' . ($startInfoRow + 2), 'NAMA KEPALA SEKOLAH')->setCellValue('E' . ($startInfoRow + 2), ': ' . $namaKepsek);
        $sheet->setCellValue('D' . ($startInfoRow + 3), 'ALAMAT SEKOLAH')->setCellValue('E' . ($startInfoRow + 3), ': ' . $alamatSekolah);

        // Info Konsentrasi Keahlian
        $sheet->setCellValue('B' . ($startInfoRow + 6), 'KONSENTRASI KEAHLIAN : ' . $jurusan->jurusan);
        $sheet->getStyle('B' . ($startInfoRow + 6))->getFont()->setBold(true);

        // 4. Buat Header Tabel Data
        $headerRow = $startInfoRow + 8; // Sesuaikan baris header tabel
        $headers = [
            'A' => 'NO.',
            'B' => 'NAMA SISWA',
            'C' => 'NOMOR INDUK',
            'D' => 'TEMPAT, TANGGAL LAHIR',
            'E' => 'ALAMAT',
            'F' => 'L/P',
            'G' => 'NAMA AYAH',
            'H' => '', // Kolom kosong
            'I' => 'IJAZAH SMP/MTs',
            'J' => 'NOMOR IJAZAH',
            'K' => 'TAHUN LULUS',
            'L' => 'NO.'
        ];
        foreach ($headers as $col => $title) {
            $sheet->setCellValue($col . $headerRow, $title);
        }
        $sheet->getStyle('A' . $headerRow . ':L' . $headerRow)->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(25);

        // 5. Isi Data Siswa
        $rowNum = $headerRow + 1;
        $no = 1;
        foreach ($siswaList as $siswa) {
            $sheet->setCellValue('A' . $rowNum, $no);
            $sheet->setCellValue('B' . $rowNum, $siswa->nama_siswa);
            $sheet->setCellValueExplicit('C' . $rowNum, $siswa->no_induk, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); // Pastikan No Induk sebagai teks
            $ttl = ($siswa->tmpt_lhr ?? '-') . ', ' . tanggal_indo($siswa->tgl_lhr ?? '');
            $sheet->setCellValue('D' . $rowNum, $ttl);
            $alamatLengkap = ($siswa->alamat ?? '-') . ' RT ' . ($siswa->rt ?? '-') . ' RW ' . ($siswa->rw ?? '-') . ' Dsn ' . ($siswa->dusun ?? '-') . ' Ds/Kel ' . ($siswa->desa ?? '-') . ' Kec ' . ($siswa->kec ?? '-');
            $sheet->setCellValue('E' . $rowNum, $alamatLengkap);
            $sheet->setCellValue('F' . $rowNum, ($siswa->jenis_kelamin == 'Laki-Laki') ? 'L' : (($siswa->jenis_kelamin == 'Perempuan') ? 'P' : '-'));
            $sheet->setCellValue('G' . $rowNum, $siswa->nama_ayah ?? '-');
            $sheet->setCellValue('H' . $rowNum, '');
            $sheet->setCellValue('I' . $rowNum, $siswa->pend_sebelumnya ?? '-');
            $sheet->setCellValueExplicit('J' . $rowNum, $siswa->seri_ijazah_smp ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); // No Ijazah sebagai teks
            $sheet->setCellValue('K' . $rowNum, $siswa->th_ijazah_smp ?? '-');
            $sheet->setCellValue('L' . $rowNum, $no);

            $sheet->getStyle('A' . $rowNum . ':L' . $rowNum)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('A' . $rowNum . ':L' . $rowNum)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Tahun lulus center
            $sheet->getStyle('L' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $rowNum++;
            $no++;
        }

        // 6. Atur Lebar Kolom
        foreach (range('A', 'L') as $col) {
            if ($col != 'E') { // Jangan autosize alamat
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }
        $sheet->getColumnDimension('E')->setWidth(50); // Atur lebar alamat manual
        $sheet->getStyle('E1:E' . $rowNum)->getAlignment()->setWrapText(true); // Aktifkan wrap text untuk alamat

        // 7. Siapkan Output File Excel
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Pastikan $tingkat digunakan di nama file
        $filename = 'Nominatif - ' . $jurusan->jurusan . ' - ' . $tingkat . ' - TP ' . str_replace('/', '-', $tp) . '.xlsx';

        // ==================== TAMBAHKAN LOG DI SINI ====================
        // Mencatat aktivitas download/export data siswa massal
        $nama_jurusan = $jurusan->jurusan ?? 'Unknown';
        $total_siswa = count($siswaList);
        $this->logActivity('EXPORT', "Mengekspor data Nominatif Siswa (Jurusan: {$nama_jurusan}, Tingkat: {$tingkat}, TP: {$tp}) ke format Excel. Total: {$total_siswa} siswa.");
        // ===============================================================

        // --- PERBAIKAN PENTING DI SINI ---
        // Bersihkan semua output buffer secara menyeluruh untuk mencegah spasi/error ikut terunduh
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // Kirim header ke browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        // Jika Anda menggunakan IE9, tambahkan ini:
        header('Cache-Control: max-age=1');

        // Simpan output ke browser
        $writer->save('php://output');
        exit;
    }

    public function searchSiswaAktif()
    {
        // Bersihkan output buffer
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        try {
            // --- PERUBAHAN DI SINI: Ganti $_GET menjadi $_POST ---
            $keyword = $_POST['searchTerm'] ?? '';

            // Debugging: Jika keyword kosong, hentikan dan kirim error (opsional, untuk cek)
            // if (empty($keyword)) { echo json_encode(['results' => []]); exit; }

            // Panggil Model
            $siswaList = $this->model('Siswa_model')->searchSiswaAktif($keyword);

            $results = [];
            if ($siswaList) {
                foreach ($siswaList as $siswa) {
                    // Pastikan konversi ke array aman
                    $s = (array) $siswa;

                    $id = $s['id_induk'] ?? 0;
                    $nama = $s['nama_siswa'] ?? '-';
                    $nis = $s['no_induk'] ?? '-';
                    $rombel = $s['nama_rombel'] ?? 'Tanpa Kelas';

                    $results[] = [
                        "id" => $id,
                        "text" => $nama . ' (' . $rombel . ') - ' . $nis
                    ];
                }
            }

            echo json_encode(['results' => $results]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }

        exit;
    }

    public function cetakBukuInduk($id_siswa)
    {
        // 1. Ambil data yang diperlukan (Sama seperti sebelumnya)
        $siswa_data = $this->model('Siswa_model')->getSiswaById($id_siswa);
        if (!$siswa_data) {
            Flasher::setFlash('Error', 'Data siswa tidak ditemukan.', 'error');
            header('Location: ' . BASEURL . '/siswa');
            exit;
        }
        $mutasi_data = $this->model('Siswa_model')->getMutasiSiswa($id_siswa);
        $no_absen = $this->model('Siswa_model')->hitungNomorAbsen($siswa_data->id_induk, $siswa_data->rombel);
        $no_induk_display = '-'; // Logika ambil 5 digit depan (sama seperti sebelumnya)
        if (!empty($siswa_data->no_induk)) {
            $parts = explode('/', $siswa_data->no_induk);
            $digits = preg_replace('/\D/', '', $parts[0]);
            if ($digits !== '') $no_induk_display = substr($digits, 0, 5);
        }

        // Siapkan data untuk view PDF
        $data_pdf = [
            'siswa' => $siswa_data,
            'mutasi' => $mutasi_data,
            'no_absen' => $no_absen,
            'no_induk_display' => $no_induk_display
        ];

        // ▼▼▼ BAGIAN YANG DIUBAH (Mirip referensi) ▼▼▼
        // 2. Render view HTML ke dalam sebuah variabel menggunakan helper
        $html = $this->renderView('siswa/pdf_buku_induk', $data_pdf);
        // ▲▲▲ AKHIR BAGIAN YANG DIUBAH ▲▲▲

        // 3. Konfigurasi Dompdf (Sama seperti referensi)
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // 4. Set Ukuran Kertas & Orientasi (Sama seperti referensi)
        $dompdf->setPaper([0, 0, 595.28, 935.43], 'potrait');

        // 5. Render HTML menjadi PDF (Sama seperti referensi)
        $dompdf->render();

        // 6. Output PDF ke Browser (inline, bukan download)
        $nama_file = "Buku_Induk_" . preg_replace('/[^A_Za-z0-9_\-]/', '_', $siswa_data->nama_siswa) . ".pdf";

        // ==================== TAMBAHKAN LOG DI SINI ====================
        // Log dicatat tepat sebelum stream dilempar ke browser pengguna
        $nama_siswa = $siswa_data->nama_siswa ?? 'Siswa';
        $nisn = $siswa_data->nisn ?? '-';
        $rombel = $siswa_data->rombel ?? '-';

        $this->logActivity('PRINT', "Mencetak dokumen Buku Induk Siswa: {$nama_siswa} (NISN: {$nisn}, Rombel/Kelas: {$rombel}).");
        // ===============================================================

        // Gunakan Attachment => false agar tampil di browser
        $dompdf->stream($nama_file, array("Attachment" => false));
        exit(); // Hentikan eksekusi
    }

    public function exportExcelLengkap()
    {
        // 1. Antisipasi kehabisan memori & waktu eksekusi
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        if (!Auth::checkRole('admin')) {
            /* ... handle akses ditolak ... */
            exit;
        }

        $siswaData = $this->model('Siswa_model')->getAllSiswaLengkap();

        if (!$siswaData) {
            $siswaData = [];
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa Lengkap');

        // Header kolom (Total tepat 98 Kolom)
        $headers = [
            'No',
            'Nama',
            'Nama Panggilan',
            'Nomor Induk',
            'NISN',
            'NIK',
            'NKK',
            'Nomor Akta Kelahiran',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Kewarganegaraan',
            'Anak Ke',
            'Jumlah Saudara Kandung',
            'Jumlah Saudara Tiri',
            'Jumlah Saudara Angkat',
            'Yatim/Piatu/Yatim Piatu',
            'Bahasa Sehari-hari',
            'Alamat',
            'Dusun',
            'RT',
            'RW',
            'Desa/Kel',
            'Kecamatan',
            'Kabupaten',
            'Kode Pos',
            'Provinsi',
            'Nomor Telepon Rumah',
            'Nomor Handphone',
            'Tinggal Bersama',
            'Jarak Rumah',
            'Waktu Tempuh',
            'Transportasi',
            'Golongan Darah',
            'Penyakit',
            'Kelainan Jasmani',
            'Tinggi Badan',
            'Berat Badan',
            'Asal SD',
            'NPSN SD',
            'Pendidikan Sebelumnya',
            'Asal SMP',
            'Alamat SMP',
            'NPSN SMP',
            'Nomor Seri Ijazah SMP',
            'Tanggal Ijazah SMP',
            'Tahun Ijazah SMP',
            'Lama Belajar SMP',
            'Tingkat',
            'Bidang Keahlian',
            'Program Keahlian',
            'Konsentrasi Keahlian',
            'Diterima Tanggal',
            'Nama Ayah',
            'NIK Ayah',
            'Tempat Lahir Ayah',
            'Tanggal Lahir Ayah',
            'Agama Ayah',
            'Kewarganegaraan Ayah',
            'Pendidikan Ayah',
            'Pekerjaan Ayah',
            'Penghasilan Ayah',
            'Alamat Ayah',
            'No. HP Ayah',
            'Hidup/Mati Ayah',
            'Nama Ibu',
            'NIK Ibu',
            'Tempat Lahir Ibu',
            'Tanggal Lahir Ibu',
            'Agama Ibu',
            'Kewarganegaraan Ibu',
            'Pendidikan Ibu',
            'Pekerjaan Ibu',
            'Penghasilan Ibu',
            'Alamat Ibu',
            'No. HP Ibu',
            'Hidup/Mati Ibu',
            'Nama Wali',
            'NIK Wali',
            'Tempat Lahir Wali',
            'Tanggal Lahir Wali',
            'Agama Wali',
            'Kewarganegaraan Wali',
            'Pendidikan Wali',
            'Pekerjaan Wali',
            'Penghasilan Wali',
            'Alamat Wali',
            'No. HP Wali',
            'Kesenian',
            'Olahraga',
            'Organisasi',
            'Cita-cita',
            'Lain-lain',
            'Status Siswa',
            'Kelas',
            'Created At',
            'Updated At'
        ];

        // Tulis Header menggunakan abjad
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }

        // Isi Data Siswa 
        $rowNum = 2;
        $no = 1;

        foreach ($siswaData as $siswa) {
            $c = 'A'; // Mulai dari kolom A setiap baris baru

            // Kita gunakan isset() sebagai "Sabuk Pengaman" agar tidak fatal error jika ada data null
            $sheet->setCellValue($c++ . $rowNum, $no++);
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->nama_siswa) ? $siswa->nama_siswa : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->nama_panggilan) ? $siswa->nama_panggilan : '');

            // Format string untuk NIK, NISN, No HP (agar angka tidak hilang/berubah jadi E+)
            $sheet->setCellValueExplicit($c++ . $rowNum, isset($siswa->no_induk) ? $siswa->no_induk : '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($c++ . $rowNum, isset($siswa->nisn) ? $siswa->nisn : '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($c++ . $rowNum, isset($siswa->nik) ? $siswa->nik : '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($c++ . $rowNum, isset($siswa->nkk) ? $siswa->nkk : '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($c++ . $rowNum, isset($siswa->no_akta) ? $siswa->no_akta : '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            $sheet->setCellValue($c++ . $rowNum, isset($siswa->jenis_kelamin) ? $siswa->jenis_kelamin : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->tmpt_lhr) ? $siswa->tmpt_lhr : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->tgl_lhr) ? $siswa->tgl_lhr : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->agama) ? $siswa->agama : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->kewarganegaraan) ? $siswa->kewarganegaraan : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->anak_ke) ? $siswa->anak_ke : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->jml_sdr_kandung) ? $siswa->jml_sdr_kandung : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->jml_sdr_tiri) ? $siswa->jml_sdr_tiri : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->jml_sdr_angkat) ? $siswa->jml_sdr_angkat : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->yatim_piatu) ? $siswa->yatim_piatu : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->bahasa) ? $siswa->bahasa : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->alamat) ? $siswa->alamat : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->dusun) ? $siswa->dusun : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->rt) ? $siswa->rt : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->rw) ? $siswa->rw : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->desa) ? $siswa->desa : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->kec) ? $siswa->kec : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->kab) ? $siswa->kab : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->kd_pos) ? $siswa->kd_pos : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->provinsi) ? $siswa->provinsi : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->no_tlp) ? $siswa->no_tlp : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->no_hp) ? $siswa->no_hp : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->tinggal_bersama) ? $siswa->tinggal_bersama : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->jarak_rumah) ? $siswa->jarak_rumah : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->wkt_tempuh) ? $siswa->wkt_tempuh : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->transportasi) ? $siswa->transportasi : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->gol_darah) ? $siswa->gol_darah : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->penyakit) ? $siswa->penyakit : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->kelainan_jasmani) ? $siswa->kelainan_jasmani : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->tb) ? $siswa->tb : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->bb) ? $siswa->bb : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->asal_sd) ? $siswa->asal_sd : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->npsn_sd) ? $siswa->npsn_sd : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->pend_sebelumnya) ? $siswa->pend_sebelumnya : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->asal_smp) ? $siswa->asal_smp : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->alamat_smp) ? $siswa->alamat_smp : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->npsn_smp) ? $siswa->npsn_smp : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->seri_ijazah_smp) ? $siswa->seri_ijazah_smp : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->tgl_ijazah_smp) ? $siswa->tgl_ijazah_smp : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->th_ijazah_smp) ? $siswa->th_ijazah_smp : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->lama_belajar_smp) ? $siswa->lama_belajar_smp : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->tingkat) ? $siswa->tingkat : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->bid_keahlian) ? $siswa->bid_keahlian : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->prog_keahlian) ? $siswa->prog_keahlian : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->jurusan) ? $siswa->jurusan : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->diterima_tgl) ? $siswa->diterima_tgl : '');

            // Data Ayah
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->nama_ayah) ? $siswa->nama_ayah : '');
            $sheet->setCellValueExplicit($c++ . $rowNum, isset($siswa->nik_ayah) ? $siswa->nik_ayah : '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->tmpt_lhr_ayah) ? $siswa->tmpt_lhr_ayah : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->tgl_lhr_ayah) ? $siswa->tgl_lhr_ayah : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->agama_ayah) ? $siswa->agama_ayah : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->kewarganegaraan_ayah) ? $siswa->kewarganegaraan_ayah : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->pend_ayah) ? $siswa->pend_ayah : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->pekerjaan_ayah) ? $siswa->pekerjaan_ayah : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->penghasilan_ayah) ? $siswa->penghasilan_ayah : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->alamat_ayah) ? $siswa->alamat_ayah : '');
            $sheet->setCellValueExplicit($c++ . $rowNum, isset($siswa->hp_ayah) ? $siswa->hp_ayah : '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->hidup_mati_ayah) ? $siswa->hidup_mati_ayah : '');

            // Data Ibu
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->nama_ibu) ? $siswa->nama_ibu : '');
            $sheet->setCellValueExplicit($c++ . $rowNum, isset($siswa->nik_ibu) ? $siswa->nik_ibu : '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->tmpt_lhr_ibu) ? $siswa->tmpt_lhr_ibu : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->tgl_lhr_ibu) ? $siswa->tgl_lhr_ibu : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->agama_ibu) ? $siswa->agama_ibu : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->kewarganegaraan_ibu) ? $siswa->kewarganegaraan_ibu : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->pend_ibu) ? $siswa->pend_ibu : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->pekerjaan_ibu) ? $siswa->pekerjaan_ibu : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->penghasilan_ibu) ? $siswa->penghasilan_ibu : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->alamat_ibu) ? $siswa->alamat_ibu : '');
            $sheet->setCellValueExplicit($c++ . $rowNum, isset($siswa->hp_ibu) ? $siswa->hp_ibu : '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->hidup_mati_ibu) ? $siswa->hidup_mati_ibu : '');

            // Data Wali
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->nama_wali) ? $siswa->nama_wali : '');
            $sheet->setCellValueExplicit($c++ . $rowNum, isset($siswa->nik_wali) ? $siswa->nik_wali : '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->tmpt_lhr_wali) ? $siswa->tmpt_lhr_wali : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->tgl_lhr_wali) ? $siswa->tgl_lhr_wali : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->agama_wali) ? $siswa->agama_wali : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->kewarganegaraan_wali) ? $siswa->kewarganegaraan_wali : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->pend_wali) ? $siswa->pend_wali : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->pekerjaan_wali) ? $siswa->pekerjaan_wali : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->penghasilan_wali) ? $siswa->penghasilan_wali : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->alamat_wali) ? $siswa->alamat_wali : '');
            $sheet->setCellValueExplicit($c++ . $rowNum, isset($siswa->hp_wali) ? $siswa->hp_wali : '', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            // Lain-lain & Status
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->kesenian) ? $siswa->kesenian : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->olahraga) ? $siswa->olahraga : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->organisasi) ? $siswa->organisasi : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->cita_cita) ? $siswa->cita_cita : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->lain_lain) ? $siswa->lain_lain : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->nama_status_siswa) ? $siswa->nama_status_siswa : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->nama_rombel) ? $siswa->nama_rombel : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->created_at_induk) ? $siswa->created_at_induk : '');
            $sheet->setCellValue($c++ . $rowNum, isset($siswa->updated_at_induk) ? $siswa->updated_at_induk : '');

            $rowNum++;
        }

        // Siapkan Writer dan Header Download
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'data_siswa_lengkap_' . date('YmdHis') . '.xlsx';

        // Log Aktivitas
        $total_data = count($siswaData);
        $this->logActivity('EXPORT', "Admin melakukan ekspor seluruh database (Data Siswa Lengkap) ke format Excel. Total: {$total_data} baris data diekspor.");

        // MEMBERSIHKAN BUFFER AGAR FILE TIDAK CORRUPT
        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($filename) . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function exportExcelInduk()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);
        if (!Auth::checkRole('admin')) { /* ... handle akses ditolak ... */
            exit;
        }

        $siswaData = $this->model('Siswa_model')->getAllSiswaInduk();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa Lengkap');

        // Header kolom (Sesuai urutan Anda, termasuk HP)
        $headers = [
            'No',
            'Nama',
            'Nama Panggilan',
            'Nomor Induk',
            'NISN',
            'NIK',
            'NKK',
            'Nomor Akta Kelahiran',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Kewarganegaraan',
            'Anak Ke',
            'Jumlah Saudara Kandung',
            'Jumlah Saudara Tiri',
            'Jumlah Saudara Angkat',
            'Yatim/Piatu/Yatim Piatu',
            'Bahasa Sehari-hari',
            'Alamat',
            'Dusun',
            'RT',
            'RW',
            'Desa/Kel',
            'Kecamatan',
            'Kabupaten',
            'Kode Pos',
            'Provinsi',
            'Nomor Telepon Rumah',
            'Nomor Handphone',
            'Tinggal Bersama',
            'Jarak Rumah',
            'Waktu Tempuh',
            'Transportasi',
            'Golongan Darah',
            'Penyakit',
            'Kelainan Jasmani',
            'Tinggi Badan',
            'Berat Badan',
            'Asal SD',
            'NPSN SD',
            'Pendidikan Sebelumnya',
            'Asal SMP',
            'Alamat SMP',
            'NPSN SMP',
            'Nomor Seri Ijazah SMP',
            'Tanggal Ijazah SMP',
            'Tahun Ijazah SMP',
            'Lama Belajar SMP',
            'Tingkat',
            'Bidang Keahlian',
            'Program Keahlian',
            'Konsentrasi Keahlian',
            'Diterima Tanggal',
            'Nama Ayah',
            'NIK Ayah',
            'Tempat Lahir Ayah',
            'Tanggal Lahir Ayah',
            'Agama Ayah',
            'Kewarganegaraan Ayah',
            'Pendidikan Ayah',
            'Pekerjaan Ayah',
            'Penghasilan Ayah',
            'Alamat Ayah',
            'No. HP Ayah', // Ditambahkan
            'Hidup/Mati Ayah',
            'Nama Ibu',
            'NIK Ibu',
            'Tempat Lahir Ibu',
            'Tanggal Lahir Ibu',
            'Agama Ibu',
            'Kewarganegaraan Ibu',
            'Pendidikan Ibu',
            'Pekerjaan Ibu',
            'Penghasilan Ibu',
            'Alamat Ibu',
            'No. HP Ibu', // Ditambahkan
            'Hidup/Mati Ibu',
            'Nama Wali',
            'NIK Wali',
            'Tempat Lahir Wali',
            'Tanggal Lahir Wali',
            'Agama Wali',
            'Kewarganegaraan Wali',
            'Pendidikan Wali',
            'Pekerjaan Wali',
            'Penghasilan Wali',
            'Alamat Wali',
            'No. HP Wali', // Ditambahkan
            'Kesenian',
            'Olahraga',
            'Organisasi',
            'Cita-cita',
            'Lain-lain',
            'Status Siswa',
            'Tahun Lulus',
            'Kelas',
            'Created At',
            'Updated At'
        ]; // Total 97 kolom sekarang

        // Tulis Header
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }

        // Isi Data Siswa (Sesuaikan urutan dengan header baru)
        $rowNum = 2;
        $no = 1;
        foreach ($siswaData as $siswa) {
            $col = 'A';
            $sheet->setCellValue($col++ . $rowNum, $no++); // No
            $sheet->setCellValue($col++ . $rowNum, $siswa->nama_siswa);
            $sheet->setCellValue($col++ . $rowNum, $siswa->nama_panggilan);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->no_induk, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->nisn, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->nik, DataType::TYPE_STRING); // Sesuaikan nama kolom jika beda
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->nkk, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->no_akta, DataType::TYPE_STRING);
            $sheet->setCellValue($col++ . $rowNum, $siswa->jenis_kelamin);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tmpt_lhr);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tgl_lhr);
            $sheet->setCellValue($col++ . $rowNum, $siswa->agama);
            $sheet->setCellValue($col++ . $rowNum, $siswa->kewarganegaraan);
            $sheet->setCellValue($col++ . $rowNum, $siswa->anak_ke);
            $sheet->setCellValue($col++ . $rowNum, $siswa->jml_sdr_kandung);
            $sheet->setCellValue($col++ . $rowNum, $siswa->jml_sdr_tiri);
            $sheet->setCellValue($col++ . $rowNum, $siswa->jml_sdr_angkat);
            $sheet->setCellValue($col++ . $rowNum, $siswa->yatim_piatu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->bahasa);
            $sheet->setCellValue($col++ . $rowNum, $siswa->alamat);
            $sheet->setCellValue($col++ . $rowNum, $siswa->dusun);
            $sheet->setCellValue($col++ . $rowNum, $siswa->rt);
            $sheet->setCellValue($col++ . $rowNum, $siswa->rw);
            $sheet->setCellValue($col++ . $rowNum, $siswa->desa);
            $sheet->setCellValue($col++ . $rowNum, $siswa->kec);
            $sheet->setCellValue($col++ . $rowNum, $siswa->kab);
            $sheet->setCellValue($col++ . $rowNum, $siswa->kd_pos);
            $sheet->setCellValue($col++ . $rowNum, $siswa->provinsi);
            $sheet->setCellValue($col++ . $rowNum, $siswa->no_tlp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->no_hp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tinggal_bersama);
            $sheet->setCellValue($col++ . $rowNum, $siswa->jarak_rumah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->wkt_tempuh);
            $sheet->setCellValue($col++ . $rowNum, $siswa->transportasi);
            $sheet->setCellValue($col++ . $rowNum, $siswa->gol_darah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->penyakit);
            $sheet->setCellValue($col++ . $rowNum, $siswa->kelainan_jasmani);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tb);
            $sheet->setCellValue($col++ . $rowNum, $siswa->bb);
            $sheet->setCellValue($col++ . $rowNum, $siswa->asal_sd);
            $sheet->setCellValue($col++ . $rowNum, $siswa->npsn_sd);
            $sheet->setCellValue($col++ . $rowNum, $siswa->pend_sebelumnya);
            $sheet->setCellValue($col++ . $rowNum, $siswa->asal_smp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->alamat_smp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->npsn_smp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->seri_ijazah_smp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tgl_ijazah_smp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->th_ijazah_smp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->lama_belajar_smp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tingkat);
            $sheet->setCellValue($col++ . $rowNum, $siswa->bid_keahlian);
            $sheet->setCellValue($col++ . $rowNum, $siswa->prog_keahlian);
            $sheet->setCellValue($col++ . $rowNum, $siswa->jurusan); // Nama jurusan dari JOIN
            $sheet->setCellValue($col++ . $rowNum, $siswa->diterima_tgl);
            $sheet->setCellValue($col++ . $rowNum, $siswa->nama_ayah);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->nik_ayah, DataType::TYPE_STRING);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tmpt_lhr_ayah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tgl_lhr_ayah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->agama_ayah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->kewarganegaraan_ayah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->pend_ayah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->pekerjaan_ayah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->penghasilan_ayah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->alamat_ayah);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->hp_ayah, DataType::TYPE_STRING); // HP Ayah as Text
            $sheet->setCellValue($col++ . $rowNum, $siswa->hidup_mati_ayah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->nama_ibu);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->nik_ibu, DataType::TYPE_STRING);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tmpt_lhr_ibu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tgl_lhr_ibu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->agama_ibu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->kewarganegaraan_ibu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->pend_ibu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->pekerjaan_ibu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->penghasilan_ibu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->alamat_ibu);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->hp_ibu, DataType::TYPE_STRING); // HP Ibu as Text
            $sheet->setCellValue($col++ . $rowNum, $siswa->hidup_mati_ibu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->nama_wali);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->nik_wali, DataType::TYPE_STRING);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tmpt_lhr_wali);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tgl_lhr_wali);
            $sheet->setCellValue($col++ . $rowNum, $siswa->agama_wali);
            $sheet->setCellValue($col++ . $rowNum, $siswa->kewarganegaraan_wali);
            $sheet->setCellValue($col++ . $rowNum, $siswa->pend_wali);
            $sheet->setCellValue($col++ . $rowNum, $siswa->pekerjaan_wali);
            $sheet->setCellValue($col++ . $rowNum, $siswa->penghasilan_wali);
            $sheet->setCellValue($col++ . $rowNum, $siswa->alamat_wali);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->hp_wali, DataType::TYPE_STRING); // HP Wali as Text
            $sheet->setCellValue($col++ . $rowNum, $siswa->kesenian);
            $sheet->setCellValue($col++ . $rowNum, $siswa->olahraga);
            $sheet->setCellValue($col++ . $rowNum, $siswa->organisasi);
            $sheet->setCellValue($col++ . $rowNum, $siswa->cita_cita);
            $sheet->setCellValue($col++ . $rowNum, $siswa->col . $rowNum, $siswa->lain_lain);
            $sheet->setCellValue($col++ . $rowNum, $siswa->nama_status_siswa); // Gunakan alias baru
            $sheet->setCellValue($col++ . $rowNum, $siswa->tahun_lulus);
            $sheet->setCellValue($col++ . $rowNum, $siswa->nama_rombel);
            $sheet->setCellValue($col++ . $rowNum, $siswa->created_at_induk); // Sesuaikan nama kolom jika beda
            $sheet->setCellValue($col++ . $rowNum, $siswa->updated_at_induk); // Sesuaikan nama kolom jika beda

            $rowNum++;
        }

        // Auto size columns (opsional, bisa memakan waktu jika data banyak)
        // foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
        //     $sheet->getColumnDimension($columnID)->setAutoSize(true);
        // }

        // Siapkan Writer dan Header Download
        $writer = new Xlsx($spreadsheet);
        $filename = 'data_induk_lengkap_' . date('YmdHis') . '.xlsx';

        // ==================== TAMBAHKAN LOG DI SINI ====================
        // Menghitung jumlah record yang berhasil diekspor untuk informasi audit
        $total_data = count($siswaData);
        $this->logActivity('EXPORT', "Admin melakukan ekspor seluruh data Buku Induk Siswa (Master) ke format Excel. Total: {$total_data} baris data diekspor.");
        // ===============================================================

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($filename) . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function exportExcelKeluar()
    {
        if (!Auth::checkRole('admin')) { /* ... handle akses ditolak ... */
            exit;
        }

        $siswaData = $this->model('Siswa_model')->getAllSiswaKeluar();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa Lengkap');

        // Header kolom (Sesuai urutan Anda, termasuk HP)
        $headers = [
            'No',
            'Nama',
            'Nama Panggilan',
            'Nomor Induk',
            'NISN',
            'NIK',
            'NKK',
            'Nomor Akta Kelahiran',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Kewarganegaraan',
            'Anak Ke',
            'Jumlah Saudara Kandung',
            'Jumlah Saudara Tiri',
            'Jumlah Saudara Angkat',
            'Yatim/Piatu/Yatim Piatu',
            'Bahasa Sehari-hari',
            'Alamat',
            'Dusun',
            'RT',
            'RW',
            'Desa/Kel',
            'Kecamatan',
            'Kabupaten',
            'Kode Pos',
            'Provinsi',
            'Nomor Telepon Rumah',
            'Nomor Handphone',
            'Tinggal Bersama',
            'Jarak Rumah',
            'Waktu Tempuh',
            'Transportasi',
            'Golongan Darah',
            'Penyakit',
            'Kelainan Jasmani',
            'Tinggi Badan',
            'Berat Badan',
            'Asal SD',
            'NPSN SD',
            'Pendidikan Sebelumnya',
            'Asal SMP',
            'Alamat SMP',
            'NPSN SMP',
            'Nomor Seri Ijazah SMP',
            'Tanggal Ijazah SMP',
            'Tahun Ijazah SMP',
            'Lama Belajar SMP',
            'Tingkat',
            'Bidang Keahlian',
            'Program Keahlian',
            'Konsentrasi Keahlian',
            'Diterima Tanggal',
            'Nama Ayah',
            'NIK Ayah',
            'Tempat Lahir Ayah',
            'Tanggal Lahir Ayah',
            'Agama Ayah',
            'Kewarganegaraan Ayah',
            'Pendidikan Ayah',
            'Pekerjaan Ayah',
            'Penghasilan Ayah',
            'Alamat Ayah',
            'No. HP Ayah', // Ditambahkan
            'Hidup/Mati Ayah',
            'Nama Ibu',
            'NIK Ibu',
            'Tempat Lahir Ibu',
            'Tanggal Lahir Ibu',
            'Agama Ibu',
            'Kewarganegaraan Ibu',
            'Pendidikan Ibu',
            'Pekerjaan Ibu',
            'Penghasilan Ibu',
            'Alamat Ibu',
            'No. HP Ibu', // Ditambahkan
            'Hidup/Mati Ibu',
            'Nama Wali',
            'NIK Wali',
            'Tempat Lahir Wali',
            'Tanggal Lahir Wali',
            'Agama Wali',
            'Kewarganegaraan Wali',
            'Pendidikan Wali',
            'Pekerjaan Wali',
            'Penghasilan Wali',
            'Alamat Wali',
            'No. HP Wali', // Ditambahkan
            'Kesenian',
            'Olahraga',
            'Organisasi',
            'Cita-cita',
            'Lain-lain',
            'Status Siswa',
            'Kelas',
            'Created At',
            'Updated At'
        ]; // Total 96 kolom sekarang

        // Tulis Header
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }

        // Isi Data Siswa (Sesuaikan urutan dengan header baru)
        $rowNum = 2;
        $no = 1;
        foreach ($siswaData as $siswa) {
            $col = 'A';
            $sheet->setCellValue($col++ . $rowNum, $no++); // No
            $sheet->setCellValue($col++ . $rowNum, $siswa->nama_siswa);
            $sheet->setCellValue($col++ . $rowNum, $siswa->nama_panggilan);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->no_induk, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->nisn, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->nik, DataType::TYPE_STRING); // Sesuaikan nama kolom jika beda
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->nkk, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->no_akta, DataType::TYPE_STRING);
            $sheet->setCellValue($col++ . $rowNum, $siswa->jenis_kelamin);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tmpt_lhr);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tgl_lhr);
            $sheet->setCellValue($col++ . $rowNum, $siswa->agama);
            $sheet->setCellValue($col++ . $rowNum, $siswa->kewarganegaraan);
            $sheet->setCellValue($col++ . $rowNum, $siswa->anak_ke);
            $sheet->setCellValue($col++ . $rowNum, $siswa->jml_sdr_kandung);
            $sheet->setCellValue($col++ . $rowNum, $siswa->jml_sdr_tiri);
            $sheet->setCellValue($col++ . $rowNum, $siswa->jml_sdr_angkat);
            $sheet->setCellValue($col++ . $rowNum, $siswa->yatim_piatu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->bahasa);
            $sheet->setCellValue($col++ . $rowNum, $siswa->alamat);
            $sheet->setCellValue($col++ . $rowNum, $siswa->dusun);
            $sheet->setCellValue($col++ . $rowNum, $siswa->rt);
            $sheet->setCellValue($col++ . $rowNum, $siswa->rw);
            $sheet->setCellValue($col++ . $rowNum, $siswa->desa);
            $sheet->setCellValue($col++ . $rowNum, $siswa->kec);
            $sheet->setCellValue($col++ . $rowNum, $siswa->kab);
            $sheet->setCellValue($col++ . $rowNum, $siswa->kd_pos);
            $sheet->setCellValue($col++ . $rowNum, $siswa->provinsi);
            $sheet->setCellValue($col++ . $rowNum, $siswa->no_tlp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->no_hp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tinggal_bersama);
            $sheet->setCellValue($col++ . $rowNum, $siswa->jarak_rumah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->wkt_tempuh);
            $sheet->setCellValue($col++ . $rowNum, $siswa->transportasi);
            $sheet->setCellValue($col++ . $rowNum, $siswa->gol_darah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->penyakit);
            $sheet->setCellValue($col++ . $rowNum, $siswa->kelainan_jasmani);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tb);
            $sheet->setCellValue($col++ . $rowNum, $siswa->bb);
            $sheet->setCellValue($col++ . $rowNum, $siswa->asal_sd);
            $sheet->setCellValue($col++ . $rowNum, $siswa->npsn_sd);
            $sheet->setCellValue($col++ . $rowNum, $siswa->pend_sebelumnya);
            $sheet->setCellValue($col++ . $rowNum, $siswa->asal_smp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->alamat_smp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->npsn_smp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->seri_ijazah_smp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tgl_ijazah_smp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->th_ijazah_smp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->lama_belajar_smp);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tingkat);
            $sheet->setCellValue($col++ . $rowNum, $siswa->bid_keahlian);
            $sheet->setCellValue($col++ . $rowNum, $siswa->prog_keahlian);
            $sheet->setCellValue($col++ . $rowNum, $siswa->jurusan); // Nama jurusan dari JOIN
            $sheet->setCellValue($col++ . $rowNum, $siswa->diterima_tgl);
            $sheet->setCellValue($col++ . $rowNum, $siswa->nama_ayah);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->nik_ayah, DataType::TYPE_STRING);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tmpt_lhr_ayah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tgl_lhr_ayah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->agama_ayah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->kewarganegaraan_ayah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->pend_ayah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->pekerjaan_ayah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->penghasilan_ayah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->alamat_ayah);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->hp_ayah, DataType::TYPE_STRING); // HP Ayah as Text
            $sheet->setCellValue($col++ . $rowNum, $siswa->hidup_mati_ayah);
            $sheet->setCellValue($col++ . $rowNum, $siswa->nama_ibu);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->nik_ibu, DataType::TYPE_STRING);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tmpt_lhr_ibu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tgl_lhr_ibu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->agama_ibu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->kewarganegaraan_ibu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->pend_ibu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->pekerjaan_ibu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->penghasilan_ibu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->alamat_ibu);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->hp_ibu, DataType::TYPE_STRING); // HP Ibu as Text
            $sheet->setCellValue($col++ . $rowNum, $siswa->hidup_mati_ibu);
            $sheet->setCellValue($col++ . $rowNum, $siswa->nama_wali);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->nik_wali, DataType::TYPE_STRING);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tmpt_lhr_wali);
            $sheet->setCellValue($col++ . $rowNum, $siswa->tgl_lhr_wali);
            $sheet->setCellValue($col++ . $rowNum, $siswa->agama_wali);
            $sheet->setCellValue($col++ . $rowNum, $siswa->kewarganegaraan_wali);
            $sheet->setCellValue($col++ . $rowNum, $siswa->pend_wali);
            $sheet->setCellValue($col++ . $rowNum, $siswa->pekerjaan_wali);
            $sheet->setCellValue($col++ . $rowNum, $siswa->penghasilan_wali);
            $sheet->setCellValue($col++ . $rowNum, $siswa->alamat_wali);
            $sheet->setCellValueExplicit($col++ . $rowNum, $siswa->hp_wali, DataType::TYPE_STRING); // HP Wali as Text
            $sheet->setCellValue($col++ . $rowNum, $siswa->kesenian);
            $sheet->setCellValue($col++ . $rowNum, $siswa->olahraga);
            $sheet->setCellValue($col++ . $rowNum, $siswa->organisasi);
            $sheet->setCellValue($col++ . $rowNum, $siswa->cita_cita);
            $sheet->setCellValue($col++ . $rowNum, $siswa->lain_lain);
            $sheet->setCellValue($col++ . $rowNum, $siswa->nama_status_siswa); // Gunakan alias baru
            $sheet->setCellValue($col++ . $rowNum, $siswa->nama_rombel);
            $sheet->setCellValue($col++ . $rowNum, $siswa->created_at_induk); // Sesuaikan nama kolom jika beda
            $sheet->setCellValue($col++ . $rowNum, $siswa->updated_at_induk); // Sesuaikan nama kolom jika beda

            $rowNum++;
        }

        // Auto size columns (opsional, bisa memakan waktu jika data banyak)
        // foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
        //     $sheet->getColumnDimension($columnID)->setAutoSize(true);
        // }

        // Siapkan Writer dan Header Download
        $writer = new Xlsx($spreadsheet);
        $filename = 'data_siswa_keluar_' . date('YmdHis') . '.xlsx';

        // ==================== TAMBAHKAN LOG DI SINI ====================
        // Menghitung jumlah data siswa keluar yang diekspor
        $total_data = count($siswaData);
        $this->logActivity('EXPORT', "Admin melakukan ekspor data Siswa Keluar / Mutasi ke format Excel. Total: {$total_data} baris data diekspor.");
        // ===============================================================

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($filename) . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function cetakCoverRaport($id_siswa)
    {
        // 1. Validasi ID & Ambil data siswa (termasuk nama rombel dari JOIN)
        if (empty($id_siswa) || !is_numeric($id_siswa)) {
            Flasher::setFlash('Error', 'ID Siswa tidak valid.', 'error');
            header('Location: ' . BASEURL . '/rombel'); // Sesuaikan redirect
            exit;
        }

        $siswa_data = $this->model('Siswa_model')->getSiswaById($id_siswa);
        if (!$siswa_data) {
            Flasher::setFlash('Error', 'Data siswa tidak ditemukan.', 'error');
            header('Location: ' . BASEURL . '/rombel'); // Sesuaikan redirect
            exit;
        }

        // 2. Ambil data sekolah (sudah termasuk versi_erapor string)
        $sekolah_data = $this->model('ProfilSekolah_model')->getProfil();
        if (!$sekolah_data) {
            // Beri pesan error yang lebih informatif jika profil belum diisi
            die("Error: Data profil sekolah belum lengkap atau belum diisi di database.");
        }

        $no_absen = $this->model('Siswa_model')->hitungNomorAbsen($siswa_data->id_induk, $siswa_data->rombel);

        // 3. Siapkan data untuk view PDF
        $data_pdf = [
            'siswa' => $siswa_data,
            'sekolah' => $sekolah_data,
            'no_absen' => $no_absen
        ];

        // 4. Render view HTML ke dalam variabel
        $html = $this->renderView('siswa/pdf_cover_raport', $data_pdf);

        // 5. Konfigurasi Dompdf
        $options = new Options();
        // Aktifkan remote agar bisa load gambar dari URL (BASEURL)
        $options->set('isRemoteEnabled', true);
        // Set font default (Book Antiqua umum untuk rapor)
        $options->set('defaultFont', 'Book Antiqua');
        // Tambahkan base path jika gambar tidak muncul (opsional)
        // $options->set('chroot', FCPATH); // FCPATH adalah path ke folder public Anda
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // 6. Set Ukuran Kertas & Orientasi (A4 Portrait)
        $dompdf->setPaper('A4', 'portrait');

        // 7. Render HTML menjadi PDF
        $dompdf->render();

        // 8. Output PDF ke Browser (inline, bukan download)
        // Buat nama file yang aman
        $nama_file_safe = preg_replace('/[^A-Za-z0-9_\-]/', '_', $siswa_data->nama_siswa);
        $nama_file = "Cover_Rapor_" . $nama_file_safe . ".pdf";

        // ==================== TAMBAHKAN LOG DI SINI ====================
        // Mencatat log aktivitas cetak cover rapor dengan menyertakan nama dan NISN siswa
        $identitas_siswa = $siswa_data->nama_siswa . " (NISN: " . ($siswa_data->nisn ?? '-') . ")";
        $this->logActivity('PRINT', "Admin mencetak / menampilkan PDF Cover Rapor untuk siswa: {$identitas_siswa}.");
        // ===============================================================

        $dompdf->stream($nama_file, array("Attachment" => false));
        exit(); // Hentikan script setelah PDF dikirim
    }

    // Menampilkan halaman View Data Induk
    public function dataInduk()
    {
        $data['judul'] = 'Buku Induk Siswa';
        $data['current_page'] = 'dataInduk';

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('siswa/data_induk', $data); // Nanti kita buat file view-nya
        $this->view('templates/footer');
    }

    // Method untuk AJAX DataTables Server-Side
    public function getSiswaAjax()
    {
        // Tangkap request dari DataTables
        $start = $_POST['start'] ?? 0;
        $length = $_POST['length'] ?? 10;
        $searchValue = $_POST['search']['value'] ?? '';
        $orderColumn = $_POST['order'][0]['column'] ?? 0;
        $orderDir = $_POST['order'][0]['dir'] ?? 'desc';

        // Panggil 3 fungsi dari model
        $dataSiswa = $this->model('Siswa_model')->getSiswaSSP($start, $length, $searchValue, $orderColumn, $orderDir);
        $recordsFiltered = $this->model('Siswa_model')->countSiswaFiltered($searchValue);
        $recordsTotal = $this->model('Siswa_model')->countSiswaTotal();

        // Format data untuk dikembalikan ke DataTables
        $data = [];
        $no = $start + 1;

        foreach ($dataSiswa as $row) {
            // Membuat Badge Status
            $badgeColor = 'secondary';
            if ($row->id_status == 1) $badgeColor = 'success'; // Aktif
            elseif ($row->id_status == 4) $badgeColor = 'info'; // Lulus
            // Tambahkan logika warna lain jika ada id_status untuk Pindah/Keluar

            $statusBadge = "<span class='badge bg-{$badgeColor}'>" . ($row->status ?? 'Tidak Diketahui') . "</span>";

            // Membuat Tombol Aksi
            $btnAksi = "
                <a href='" . BASEURL . "/siswa/detail/" . $row->id_induk . "' class='btn btn-sm btn-primary'><i class='bi bi-eye'></i> Detail</a>
            ";

            // Susun array per baris (harus sesuai urutan kolom tabel)
            $nestedData = [];
            $nestedData[] = $no++;
            $nestedData[] = htmlspecialchars($row->no_induk);
            $nestedData[] = htmlspecialchars($row->nama_siswa);
            $nestedData[] = htmlspecialchars($row->nama_rombel ?? '-');
            $nestedData[] = $statusBadge;
            $nestedData[] = $btnAksi;

            $data[] = $nestedData;
        }

        // Outputkan dalam bentuk JSON standar DataTables
        $json_data = [
            "draw"            => intval($_POST['draw'] ?? 1),
            "recordsTotal"    => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data"            => $data
        ];

        echo json_encode($json_data);
    }

    public function importExcel()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_excel'])) {

            // Tangkap ID dari form hidden view anggota.php
            $id_rombel = $_POST['id_rombel'] ?? null;
            $id_komp_keahlian = $_POST['id_komp_keahlian'] ?? null;

            if (empty($id_rombel)) {
                Flasher::setFlash('Gagal', 'ID Rombel tidak ditemukan. Pastikan Anda mengimpor dari dalam detail kelas.', 'danger');
                header('Location: ' . BASEURL . '/rombel');
                exit;
            }

            $file_mimes = ['application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

            if (isset($_FILES['file_excel']['name']) && in_array($_FILES['file_excel']['type'], $file_mimes)) {

                $arr_file = explode('.', $_FILES['file_excel']['name']);
                $extension = end($arr_file);

                if ('csv' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }

                $spreadsheet = $reader->load($_FILES['file_excel']['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                // --- ARRAY MAPPING (TANPA KOMP_KEAHLIAN, BERGESER KE KIRI, BERAKHIR DI CM) ---
                $kolomMapping = [
                    'A'  => 'nama_siswa',
                    'B'  => 'nama_panggilan',
                    'C'  => 'no_induk',
                    'D'  => 'nisn',
                    'E'  => 'nik',
                    'F'  => 'nkk',
                    'G'  => 'no_akta',
                    'H'  => 'jenis_kelamin',
                    'I'  => 'tmpt_lhr',
                    'J'  => 'tgl_lhr',
                    'K'  => 'agama',
                    'L'  => 'kewarganegaraan',
                    'M'  => 'suku',
                    'N'  => 'anak_ke',
                    'O'  => 'jml_sdr_kandung',
                    'P'  => 'jml_sdr_tiri',
                    'Q'  => 'jml_sdr_angkat',
                    'R'  => 'yatim_piatu',
                    'S'  => 'bahasa',
                    'T'  => 'alamat',
                    'U'  => 'rt',
                    'V'  => 'rw',
                    'W'  => 'dusun',
                    'X'  => 'desa',
                    'Y'  => 'kec',
                    'Z'  => 'kab',
                    'AA' => 'kd_pos',
                    'AB' => 'provinsi',
                    'AC' => 'no_tlp',
                    'AD' => 'no_hp',
                    'AE' => 'tinggal_bersama',
                    'AF' => 'jarak_rumah',
                    'AG' => 'wkt_tempuh',
                    'AH' => 'transportasi',
                    'AI' => 'gol_darah',
                    'AJ' => 'penyakit',
                    'AK' => 'kelainan_jasmani',
                    'AL' => 'tb',
                    'AM' => 'bb',
                    'AN' => 'asal_sd',
                    'AO' => 'npsn_sd',
                    'AP' => 'pend_sebelumnya',
                    'AQ' => 'asal_smp',
                    'AR' => 'alamat_smp',
                    'AS' => 'npsn_smp',
                    'AT' => 'tgl_ijazah_smp',
                    'AU' => 'th_ijazah_smp',
                    'AV' => 'lama_belajar_smp',
                    'AW' => 'seri_ijazah_smp',
                    'AX' => 'tingkat',
                    // Kolom AY langsung loncat ke diterima_tgl
                    'AY' => 'diterima_tgl',
                    'AZ' => 'nama_ayah',
                    'BA' => 'nik_ayah',
                    'BB' => 'tmpt_lhr_ayah',
                    'BC' => 'tgl_lhr_ayah',
                    'BD' => 'agama_ayah',
                    'BE' => 'kewarganegaraan_ayah',
                    'BF' => 'pend_ayah',
                    'BG' => 'pekerjaan_ayah',
                    'BH' => 'penghasilan_ayah',
                    'BI' => 'alamat_ayah',
                    'BJ' => 'hp_ayah',
                    'BK' => 'hidup_mati_ayah',
                    'BL' => 'nama_ibu',
                    'BM' => 'nik_ibu',
                    'BN' => 'tmpt_lhr_ibu',
                    'BO' => 'tgl_lhr_ibu',
                    'BP' => 'agama_ibu',
                    'BQ' => 'kewarganegaraan_ibu',
                    'BR' => 'pend_ibu',
                    'BS' => 'pekerjaan_ibu',
                    'BT' => 'penghasilan_ibu',
                    'BU' => 'alamat_ibu',
                    'BV' => 'hp_ibu',
                    'BW' => 'hidup_mati_ibu',
                    'BX' => 'nama_wali',
                    'BY' => 'nik_wali',
                    'BZ' => 'tmpt_lhr_wali',
                    'CA' => 'tgl_lhr_wali',
                    'CB' => 'agama_wali',
                    'CC' => 'kewarganegaraan_wali',
                    'CD' => 'pend_wali',
                    'CE' => 'pekerjaan_wali',
                    'CF' => 'penghasilan_wali',
                    'CG' => 'alamat_wali',
                    'CH' => 'hp_wali',
                    'CI' => 'kesenian',
                    'CJ' => 'olahraga',
                    'CK' => 'organisasi',
                    'CL' => 'cita_cita',
                    'CM' => 'lain_lain'
                ];

                $dataImport = [];
                $barisPertama = true;

                foreach ($sheetData as $row) {
                    // Lewati baris pertama (Header/Judul Kolom Excel)
                    if ($barisPertama) {
                        $barisPertama = false;
                        continue;
                    }

                    // Jika nama siswa kosong, anggap baris kosong dan lewati
                    if (empty($row['A'])) {
                        continue;
                    }

                    $dataSiswa = [];
                    foreach ($kolomMapping as $abjadExcel => $kolomDB) {
                        $dataSiswa[$kolomDB] = $row[$abjadExcel] ?? null;
                    }

                    // --- SUNTIKKAN DATA OTOMATIS KE DATABASE ---
                    $dataSiswa['rombel'] = $id_rombel;

                    // Masukkan id_jurusan ke dalam kolom komp_keahlian milik tabel data_induk
                    $dataSiswa['komp_keahlian'] = $id_komp_keahlian;

                    $dataSiswa['id_status'] = 1;

                    $dataImport[] = $dataSiswa;
                }

                // Panggil model untuk eksekusi query insert batch
                $hasil = $this->model('Siswa_model')->importDataSiswa($dataImport);

                // 2. --- LOGIKA ALERT BARU BERDASARKAN HASIL MODEL + LOG ACTIVITY ---
                if ($hasil['error_db'] !== null) {
                    // Jika ada error struktur kolom atau database
                    Flasher::setFlash('Gagal Import', 'Struktur kolom salah/Data kepanjangan. Info sistem: ' . $hasil['error_db'], 'danger');

                    // Log error database
                    $this->logActivity('IMPORT', "Admin gagal melakukan import Excel ke Rombel ID: {$id_rombel}. Masalah basis data: {$hasil['error_db']}");
                } elseif ($hasil['berhasil'] > 0) {
                    // Jika ada yang berhasil masuk
                    $pesan = $hasil['berhasil'] . ' Data siswa berhasil disimpan.';
                    $logPesan = "Admin berhasil mengimpor berkas Excel ke Rombel ID: {$id_rombel}. Total data masuk: {$hasil['berhasil']} baris.";

                    if (count($hasil['duplikat']) > 0) {
                        // Jika berhasil sebagian, tapi ada yang kembar
                        $pesan .= ' Namun, ' . count($hasil['duplikat']) . ' data dilewati karena NISN/No Induk sudah terdaftar.';
                        $logPesan .= " Dilewati (Duplikat): " . count($hasil['duplikat']) . " data.";
                    }

                    Flasher::setFlash('Berhasil', $pesan, 'success');

                    // Log sukses (termasuk catatan jika ada data duplikat)
                    $this->logActivity('IMPORT', $logPesan);
                } else {
                    // Jika 0 yang berhasil masuk
                    if (count($hasil['duplikat']) > 0) {
                        // Gagal karena semua data di Excel sudah ada di Database
                        Flasher::setFlash('Gagal', 'Semua data di file Excel sudah terdaftar di sistem (Nomor Induk / NISN Duplikat).', 'warning');

                        // Log gagal karena seluruh isi berkas duplikat
                        $this->logActivity('IMPORT', "Admin mencoba mengimpor data ke Rombel ID: {$id_rombel}, tetapi seluruh data (" . count($hasil['duplikat']) . " baris) dilewati karena duplikat.");
                    } else {
                        // Gagal karena Excelnya memang kosong
                        Flasher::setFlash('Gagal', 'Tidak ada data baru yang diproses. Pastikan file Excel tidak kosong.', 'danger');

                        // Log gagal berkas kosong
                        $this->logActivity('IMPORT', "Admin mencoba mengimpor data ke Rombel ID: {$id_rombel}, proses dibatalkan karena berkas Excel kosong / tidak valid.");
                    }
                }

                // 3. Redirect kembali ke halaman rombel
                header('Location: ' . BASEURL . '/rombel/detail/' . $id_rombel);
                exit;
            } else {
                Flasher::setFlash('Error', 'Format file tidak didukung. Gunakan .xlsx', 'danger');

                // Log gagal format file tidak sesuai
                $this->logActivity('IMPORT', "Admin gagal mengimpor berkas ke Rombel ID: " . ($id_rombel ?? 'Tidak Diketahui') . ". Ekstensi/Mime-type berkas ditolak oleh sistem.");

                $id_rombel = $_POST['id_rombel'] ?? null;
                if ($id_rombel) {
                    header('Location: ' . BASEURL . '/rombel/detail/' . $id_rombel);
                } else {
                    header('Location: ' . BASEURL . '/rombel');
                }
                exit;
            }
        }
    }

    public function downloadTemplate()
    {
        // Panggil class Spreadsheet dari library yang sudah terpasang
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Array daftar nama kolom (Tanpa rombel, komp_keahlian, dan id_status)
        $headers = [
            'nama_siswa',
            'nama_panggilan',
            'no_induk',
            'nisn',
            'nik',
            'nkk',
            'no_akta',
            'jenis_kelamin',
            'tmpt_lhr',
            'tgl_lhr',
            'agama',
            'kewarganegaraan',
            'suku',
            'anak_ke',
            'jml_sdr_kandung',
            'jml_sdr_tiri',
            'jml_sdr_angkat',
            'yatim_piatu',
            'bahasa',
            'alamat',
            'rt',
            'rw',
            'dusun',
            'desa',
            'kec',
            'kab',
            'kd_pos',
            'provinsi',
            'no_tlp',
            'no_hp',
            'tinggal_bersama',
            'jarak_rumah',
            'wkt_tempuh',
            'transportasi',
            'gol_darah',
            'penyakit',
            'kelainan_jasmani',
            'tb',
            'bb',
            'asal_sd',
            'npsn_sd',
            'pend_sebelumnya',
            'asal_smp',
            'alamat_smp',
            'npsn_smp',
            'tgl_ijazah_smp',
            'th_ijazah_smp',
            'lama_belajar_smp',
            'seri_ijazah_smp',
            'tingkat',
            'diterima_tgl',
            'nama_ayah',
            'nik_ayah',
            'tmpt_lhr_ayah',
            'tgl_lhr_ayah',
            'agama_ayah',
            'kewarganegaraan_ayah',
            'pend_ayah',
            'pekerjaan_ayah',
            'penghasilan_ayah',
            'alamat_ayah',
            'hp_ayah',
            'hidup_mati_ayah',
            'nama_ibu',
            'nik_ibu',
            'tmpt_lhr_ibu',
            'tgl_lhr_ibu',
            'agama_ibu',
            'kewarganegaraan_ibu',
            'pend_ibu',
            'pekerjaan_ibu',
            'penghasilan_ibu',
            'alamat_ibu',
            'hp_ibu',
            'hidup_mati_ibu',
            'nama_wali',
            'nik_wali',
            'tmpt_lhr_wali',
            'tgl_lhr_wali',
            'agama_wali',
            'kewarganegaraan_wali',
            'pend_wali',
            'pekerjaan_wali',
            'penghasilan_wali',
            'alamat_wali',
            'hp_wali',
            'kesenian',
            'olahraga',
            'organisasi',
            'cita_cita',
            'lain_lain'
        ];

        // Looping untuk menyusun Header dari abjad A sampai CM di baris ke-1
        $kolomAbjad = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($kolomAbjad . '1', $header);

            // Set agar baris pertama menjadi bold (tebal) untuk membedakan header
            $sheet->getStyle($kolomAbjad . '1')->getFont()->setBold(true);

            // Auto size kolom agar rapi (opsional, tergantung preferensi)
            $sheet->getColumnDimension($kolomAbjad)->setAutoSize(true);

            $kolomAbjad++;
        }

        // Set properties untuk mendownload file sebagai .xlsx
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        $fileName = 'Template_Import_Data_Siswa.xlsx';

        // Header HTTP untuk memicu proses unduhan
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        // Tulis output ke browser
        $writer->save('php://output');
        exit;
    }

    public function apiSiswaPkl()
    {
        // ==========================================
        // 1. PENGATURAN CORS (Cross-Origin)
        // ==========================================
        header('Access-Control-Allow-Origin: *'); // Akan lebih aman jika diganti jadi 'https://siswa-pkl.ingintau.my.id'
        header('Access-Control-Allow-Methods: GET, OPTIONS'); // WAJIB tambahkan OPTIONS
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-API-KEY'); // WAJIB daftarkan X-API-KEY

        // ==========================================
        // 2. TANGKAP PREFLIGHT REQUEST DARI BROWSER
        // ==========================================
        // Jika browser cuma nanya izin (OPTIONS), langsung beri status OK (200) lalu hentikan.
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        // Set tipe konten untuk response sebenarnya
        header('Content-Type: application/json');

        // ==========================================
        // 3. SISTEM KEAMANAN: CEK API KEY
        // ==========================================
        $secretKey = "TUsmekisa1968";

        // PHP membaca custom header 'X-API-KEY' sebagai 'HTTP_X_API_KEY'
        $requestApiKey = isset($_SERVER['HTTP_X_API_KEY']) ? $_SERVER['HTTP_X_API_KEY'] : '';

        // Jika kunci tidak cocok atau kosong, TENDANG!
        if ($requestApiKey !== $secretKey) {
            http_response_code(401); // Kode 401: Unauthorized
            echo json_encode([
                'status' => 'error',
                'message' => 'Akses Ditolak! API Key tidak valid atau tidak ditemukan.'
            ]);
            exit;
        }
        // ==========================================

        // Jika kunci cocok, baru jalankan model
        $data = $this->model('Siswa_model')->getSiswaForPkl();

        // Format output JSON
        if ($data) {
            echo json_encode([
                'status' => 'success',
                'data' => $data
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data siswa PKL tidak ditemukan',
                'data' => []
            ]);
        }

        exit;
    }
}
