<?php

class Siswa_model
{
    private $table = 'data_induk';
    private $db;

    public function __construct()
    {
        // Instansiasi kelas Database
        $this->db = new Database;
    }

    public function getAllSiswa()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }
    public function getDetailSiswaById($id)
    {
        // Query ini mengambil semua kolom dari data_induk (di.*)
        // dan kolom nama spesifik dari tabel lain
        $query = "SELECT di.*, j.jurusan, j.prog_keahlian, j.bid_keahlian, r.nama_rombel, s.status 
                  FROM data_induk di
                  LEFT JOIN jurusan j ON di.komp_keahlian = j.id_jurusan
                  LEFT JOIN rombel r ON di.rombel = r.id_rombel
                  LEFT JOIN status s ON di.id_status = s.id_status
                  WHERE di.id_induk = :id";

        $this->db->query($query);
        $this->db->bind('id', $id);

        // Menggunakan single() karena kita hanya mengharapkan satu baris data
        return $this->db->single();
    }


    public function getDataSiswaServerSide($request)
    {
        // Kolom yang ingin ditampilkan & bisa di-search
        $columns = [
            'di.nama_siswa',
            'di.no_induk',
            'di.jenis_kelamin',
            'j.jurusan',
            'r.nama_rombel',
            's.status'
        ];

        // Query dasar dengan JOIN dan filter status siswa aktif
        $baseQuery = "FROM {$this->table} di 
                  LEFT JOIN jurusan j ON di.komp_keahlian = j.id_jurusan
                  LEFT JOIN rombel r ON di.rombel = r.id_rombel
                  LEFT JOIN status s ON di.id_status = s.id_status
                  WHERE di.id_status = 1"; // <-- PENAMBAHAN KONDISI DI SINI

        // Filter/Search
        $searchQuery = "";
        if (isset($request['search']['value']) && $request['search']['value'] != '') {
            $searchValue = $request['search']['value'];
            // Menggunakan AND karena WHERE sudah dipakai untuk status
            $searchQuery = " AND (";
            for ($i = 0; $i < count($columns); $i++) {
                $searchQuery .= $columns[$i] . " LIKE '%" . $searchValue . "%'";
                if ($i < count($columns) - 1) {
                    $searchQuery .= " OR ";
                }
            }
            $searchQuery .= ")";
        }

        // Query untuk menghitung total baris setelah difilter
        $this->db->query("SELECT COUNT(*) as total " . $baseQuery . $searchQuery);
        $filteredRows = $this->db->single()->total;

        // Sorting
        $orderQuery = "";
        if (isset($request['order'])) {
            $orderColumnIndex = $request['order'][0]['column'];
            $orderColumn = $columns[$orderColumnIndex];
            $orderDir = $request['order'][0]['dir'];
            $orderQuery = " ORDER BY " . $orderColumn . " " . $orderDir;
        }

        // Pagination
        $limitQuery = "";
        if (isset($request['start']) && $request['length'] != -1) {
            $start = $request['start'];
            $length = $request['length'];
            $limitQuery = " LIMIT " . $start . ", " . $length;
        }

        // Query final untuk mengambil data
        $this->db->query("SELECT di.id_induk, di.nama_siswa, di.jenis_kelamin, di.no_induk, j.jurusan, r.nama_rombel, s.status " . $baseQuery . $searchQuery . $orderQuery . $limitQuery);
        $data = $this->db->resultSet();

        // Total baris (hanya siswa aktif)
        $this->db->query("SELECT COUNT(*) as total " . $baseQuery);
        $totalRows = $this->db->single()->total;

        // Format output
        $output = [
            "draw"            => intval($request['draw']),
            "recordsTotal"    => intval($totalRows),
            "recordsFiltered" => intval($filteredRows),
            "data"            => $data
        ];

        return $output;
    }
    public function hitungJumlahSiswa()
    {
        $this->db->query("SELECT COUNT(*) as total FROM data_induk WHERE id_status = 1");
        return $this->db->single()->total;
    }

    public function hitungJumlahRombel()
    {
        $this->db->query("SELECT COUNT(*) as total FROM rombel");
        return $this->db->single()->total;
    }

    public function hitungJumlahJurusan()
    {
        $this->db->query("SELECT COUNT(*) as total FROM jurusan");
        return $this->db->single()->total;
    }

    public function getSiswaBaruDiedit()
    {
        // Ambil 7 siswa terakhir yang diedit
        $this->db->query("SELECT di.nama_siswa, di.jenis_kelamin, di.no_induk, r.nama_rombel, di.updated_at_induk 
                      FROM data_induk di
                      LEFT JOIN rombel r ON di.rombel = r.id_rombel
                      WHERE di.updated_at_induk IS NOT NULL
                      ORDER BY di.updated_at_induk DESC
                      LIMIT 10");
        return $this->db->resultSet();
    }

    // di dalam class Siswa_model
    // di file app/models/Siswa_model.php

    public function getSiswaById($id)
    {
        // Query ini harus menggunakan klausa WHERE untuk mencari ID yang spesifik
        $this->db->query("SELECT di.*, j.jurusan, j.prog_keahlian, j.bid_keahlian, r.nama_rombel
                  FROM data_induk di
                  LEFT JOIN jurusan j ON di.komp_keahlian = j.id_jurusan
                  LEFT JOIN rombel r ON di.rombel = r.id_rombel
        WHERE id_induk = :id");

        // Pastikan ID dari parameter di-bind ke placeholder :id di query
        $this->db->bind('id', $id);

        // Ambil satu baris data saja
        return $this->db->single();
    }

    public function getAllRombel()
    {
        $this->db->query("SELECT * FROM rombel ORDER BY nama_rombel ASC");
        return $this->db->resultSet();
    }

    public function getSiswaByRombelId($id)
    {
        $this->db->query("SELECT id_induk, nama_siswa, no_induk, jenis_kelamin 
                      FROM data_induk 
                      WHERE rombel = :id_rombel 
                      AND id_status = 1
                      ORDER BY nama_siswa ASC");

        $this->db->bind('id_rombel', $id);
        return $this->db->resultSet();
    }

    // Ganti method updateDataSiswa yang lama dengan ini
    // di file app/models/Siswa_model.php

    public function updateDataSiswa($data)
    {
        // Bersihkan format titik dari nominal gaji
        $data['penghasilan_ayah'] = str_replace('.', '', $data['penghasilan_ayah'] ?? '0');
        $data['penghasilan_ibu'] = str_replace('.', '', $data['penghasilan_ibu'] ?? '0');
        $data['penghasilan_wali'] = str_replace('.', '', $data['penghasilan_wali'] ?? '0');

        // Query UPDATE lengkap Anda
        $query = "UPDATE data_induk SET 
                nama_siswa = :nama_siswa, nama_panggilan = :nama_panggilan, no_induk = :no_induk, nisn = :nisn, 
                nik = :nik, nkk = :nkk, no_akta = :no_akta, jenis_kelamin = :jenis_kelamin, 
                tmpt_lhr = :tmpt_lhr, tgl_lhr = :tgl_lhr, agama = :agama, kewarganegaraan = :kewarganegaraan, 
                anak_ke = :anak_ke, jml_sdr_kandung = :jml_sdr_kandung, jml_sdr_tiri = :jml_sdr_tiri, 
                jml_sdr_angkat = :jml_sdr_angkat, yatim_piatu = :yatim_piatu, bahasa = :bahasa, 
                alamat = :alamat, dusun = :dusun, rt = :rt, rw = :rw, desa = :desa, kec = :kec, kab = :kab, 
                kd_pos = :kd_pos, provinsi = :provinsi, no_tlp = :no_tlp, no_hp = :no_hp, tinggal_bersama = :tinggal_bersama, 
                jarak_rumah = :jarak_rumah, wkt_tempuh = :wkt_tempuh, transportasi = :transportasi, 
                gol_darah = :gol_darah, penyakit = :penyakit, kelainan_jasmani = :kelainan_jasmani, 
                tb = :tb, bb = :bb, asal_sd = :asal_sd, npsn_sd = :npsn_sd, pend_sebelumnya = :pend_sebelumnya, asal_smp = :asal_smp, 
                alamat_smp = :alamat_smp, npsn_smp = :npsn_smp, tgl_ijazah_smp = :tgl_ijazah_smp, 
                th_ijazah_smp = :th_ijazah_smp, lama_belajar_smp = :lama_belajar_smp, seri_ijazah_smp = :seri_ijazah_smp, 
                tingkat = :tingkat, komp_keahlian = :komp_keahlian, diterima_tgl = :diterima_tgl, 
                nama_ayah = :nama_ayah, nik_ayah = :nik_ayah, tmpt_lhr_ayah = :tmpt_lhr_ayah, tgl_lhr_ayah = :tgl_lhr_ayah, 
                agama_ayah = :agama_ayah, kewarganegaraan_ayah = :kewarganegaraan_ayah, pend_ayah = :pend_ayah, 
                pekerjaan_ayah = :pekerjaan_ayah, penghasilan_ayah = :penghasilan_ayah, alamat_ayah = :alamat_ayah, 
                hp_ayah = :hp_ayah, hidup_mati_ayah = :hidup_mati_ayah, 
                nama_ibu = :nama_ibu, nik_ibu = :nik_ibu, tmpt_lhr_ibu = :tmpt_lhr_ibu, tgl_lhr_ibu = :tgl_lhr_ibu, 
                agama_ibu = :agama_ibu, kewarganegaraan_ibu = :kewarganegaraan_ibu, pend_ibu = :pend_ibu, 
                pekerjaan_ibu = :pekerjaan_ibu, penghasilan_ibu = :penghasilan_ibu, alamat_ibu = :alamat_ibu, 
                hp_ibu = :hp_ibu, hidup_mati_ibu = :hidup_mati_ibu, 
                nama_wali = :nama_wali, nik_wali = :nik_wali, tmpt_lhr_wali = :tmpt_lhr_wali, tgl_lhr_wali = :tgl_lhr_wali, 
                agama_wali = :agama_wali, kewarganegaraan_wali = :kewarganegaraan_wali, pend_wali = :pend_wali, 
                pekerjaan_wali = :pekerjaan_wali, penghasilan_wali = :penghasilan_wali, alamat_wali = :alamat_wali, hp_wali = :hp_wali, 
                kesenian = :kesenian, olahraga = :olahraga, organisasi = :organisasi, cita_cita = :cita_cita, lain_lain = :lain_lain, 
                id_status = :id_status, rombel = :rombel
              WHERE id_induk = :id_induk";

        $this->db->query($query);

        // Proses bind() secara eksplisit ditambahkan fallback '??' agar aman dari error Undefined array key
        $this->db->bind('id_induk', $data['id_induk'] ?? '');
        $this->db->bind('nama_siswa', $data['nama_siswa'] ?? '');
        $this->db->bind('nama_panggilan', $data['nama_panggilan'] ?? '');
        $this->db->bind('no_induk', $data['no_induk'] ?? '');
        $this->db->bind('nisn', $data['nisn'] ?? '');
        $this->db->bind('nik', $data['nik'] ?? '');
        $this->db->bind('nkk', $data['nkk'] ?? '');
        $this->db->bind('no_akta', $data['no_akta'] ?? '');
        $this->db->bind('jenis_kelamin', $data['jenis_kelamin'] ?? '');
        $this->db->bind('tmpt_lhr', $data['tmpt_lhr'] ?? '');
        $this->db->bind('tgl_lhr', $data['tgl_lhr'] ?? '');
        $this->db->bind('agama', $data['agama'] ?? '');
        $this->db->bind('kewarganegaraan', $data['kewarganegaraan'] ?? '');
        $this->db->bind('anak_ke', $data['anak_ke'] ?? '');
        $this->db->bind('jml_sdr_kandung', $data['jml_sdr_kandung'] ?? '');
        $this->db->bind('jml_sdr_tiri', $data['jml_sdr_tiri'] ?? '');
        $this->db->bind('jml_sdr_angkat', $data['jml_sdr_angkat'] ?? '');
        $this->db->bind('yatim_piatu', $data['yatim_piatu'] ?? '');
        $this->db->bind('bahasa', $data['bahasa'] ?? '');
        $this->db->bind('alamat', $data['alamat'] ?? '');
        $this->db->bind('dusun', $data['dusun'] ?? '');
        $this->db->bind('rt', $data['rt'] ?? '');
        $this->db->bind('rw', $data['rw'] ?? '');
        $this->db->bind('desa', $data['desa'] ?? '');
        $this->db->bind('kec', $data['kecamatan'] ?? '');
        $this->db->bind('kab', $data['kab'] ?? '');
        $this->db->bind('kd_pos', $data['kd_pos'] ?? '');
        $this->db->bind('provinsi', $data['provinsi'] ?? '');
        $this->db->bind('no_tlp', $data['no_tlp'] ?? '');
        $this->db->bind('no_hp', $data['no_hp'] ?? '');
        $this->db->bind('tinggal_bersama', $data['tinggal_bersama'] ?? '');
        $this->db->bind('jarak_rumah', $data['jarak_rumah'] ?? '');
        $this->db->bind('wkt_tempuh', $data['wkt_tempuh'] ?? '');
        $this->db->bind('transportasi', $data['transportasi'] ?? '');
        $this->db->bind('gol_darah', $data['gol_darah'] ?? '');
        $this->db->bind('penyakit', $data['penyakit'] ?? '');
        $this->db->bind('kelainan_jasmani', $data['kelainan_jasmani'] ?? '');
        $this->db->bind('tb', $data['tb'] ?? '');
        $this->db->bind('bb', $data['bb'] ?? '');

        // --- INI BAGIAN YANG MENYEBABKAN ERROR ---
        $this->db->bind('asal_sd', $data['asal_sd'] ?? '');

        $this->db->bind('npsn_sd', $data['npsn_sd'] ?? '');
        $this->db->bind('pend_sebelumnya', $data['pend_sebelumnya'] ?? '');
        $this->db->bind('asal_smp', $data['asal_smp'] ?? '');
        $this->db->bind('alamat_smp', $data['alamat_smp'] ?? '');
        $this->db->bind('npsn_smp', $data['npsn_smp'] ?? '');
        $this->db->bind('tgl_ijazah_smp', $data['tgl_ijazah_smp'] ?? '');
        $this->db->bind('th_ijazah_smp', $data['th_ijazah_smp'] ?? '');
        $this->db->bind('lama_belajar_smp', $data['lama_belajar_smp'] ?? '');
        $this->db->bind('seri_ijazah_smp', $data['seri_ijazah_smp'] ?? '');
        $this->db->bind('tingkat', $data['tingkat'] ?? '');
        $this->db->bind('komp_keahlian', $data['komp_keahlian'] ?? '');
        $this->db->bind('diterima_tgl', $data['diterima_tgl'] ?? '');
        $this->db->bind('nama_ayah', $data['nama_ayah'] ?? '');
        $this->db->bind('nik_ayah', $data['nik_ayah'] ?? '');
        $this->db->bind('tmpt_lhr_ayah', $data['tmpt_lhr_ayah'] ?? '');
        $this->db->bind('tgl_lhr_ayah', $data['tgl_lhr_ayah'] ?? '');
        $this->db->bind('agama_ayah', $data['agama_ayah'] ?? '');
        $this->db->bind('kewarganegaraan_ayah', $data['kewarganegaraan_ayah'] ?? '');
        $this->db->bind('pend_ayah', $data['pend_ayah'] ?? '');
        $this->db->bind('pekerjaan_ayah', $data['pekerjaan_ayah'] ?? '');
        $this->db->bind('penghasilan_ayah', $data['penghasilan_ayah']); // Sudah diurus di atas
        $this->db->bind('alamat_ayah', $data['alamat_ayah'] ?? '');
        $this->db->bind('hp_ayah', $data['hp_ayah'] ?? '');
        $this->db->bind('hidup_mati_ayah', $data['hidup_mati_ayah'] ?? '');
        $this->db->bind('nama_ibu', $data['nama_ibu'] ?? '');
        $this->db->bind('nik_ibu', $data['nik_ibu'] ?? '');
        $this->db->bind('tmpt_lhr_ibu', $data['tmpt_lhr_ibu'] ?? '');
        $this->db->bind('tgl_lhr_ibu', $data['tgl_lhr_ibu'] ?? '');
        $this->db->bind('agama_ibu', $data['agama_ibu'] ?? '');
        $this->db->bind('kewarganegaraan_ibu', $data['kewarganegaraan_ibu'] ?? '');
        $this->db->bind('pend_ibu', $data['pend_ibu'] ?? '');
        $this->db->bind('pekerjaan_ibu', $data['pekerjaan_ibu'] ?? '');
        $this->db->bind('penghasilan_ibu', $data['penghasilan_ibu']); // Sudah diurus di atas
        $this->db->bind('alamat_ibu', $data['alamat_ibu'] ?? '');
        $this->db->bind('hp_ibu', $data['hp_ibu'] ?? '');
        $this->db->bind('hidup_mati_ibu', $data['hidup_mati_ibu'] ?? '');
        $this->db->bind('nama_wali', $data['nama_wali'] ?? '');
        $this->db->bind('nik_wali', $data['nik_wali'] ?? '');
        $this->db->bind('tmpt_lhr_wali', $data['tmpt_lhr_wali'] ?? '');
        $this->db->bind('tgl_lhr_wali', $data['tgl_lhr_wali'] ?? '');
        $this->db->bind('agama_wali', $data['agama_wali'] ?? '');
        $this->db->bind('kewarganegaraan_wali', $data['kewarganegaraan_wali'] ?? '');
        $this->db->bind('pend_wali', $data['pend_wali'] ?? '');
        $this->db->bind('pekerjaan_wali', $data['pekerjaan_wali'] ?? '');
        $this->db->bind('penghasilan_wali', $data['penghasilan_wali']); // Sudah diurus di atas
        $this->db->bind('alamat_wali', $data['alamat_wali'] ?? '');
        $this->db->bind('hp_wali', $data['hp_wali'] ?? '');
        $this->db->bind('kesenian', $data['kesenian'] ?? '');
        $this->db->bind('olahraga', $data['olahraga'] ?? '');
        $this->db->bind('organisasi', $data['organisasi'] ?? '');
        $this->db->bind('cita_cita', $data['cita_cita'] ?? '');
        $this->db->bind('lain_lain', $data['lain_lain'] ?? '');
        $this->db->bind('id_status', $data['id_status'] ?? '');
        $this->db->bind('rombel', $data['rombel'] ?? '');

        $this->db->execute();
        return $this->db->rowCount();
    }
    // di file: app/models/Siswa_model.php

    public function tambahDataSiswa($data)
    {
        // Bersihkan format titik dari nominal gaji & pastikan numerik
        $penghasilan_ayah = is_numeric(str_replace('.', '', $data['penghasilan_ayah'] ?? '0')) ? str_replace('.', '', $data['penghasilan_ayah'] ?? '0') : 0;
        $penghasilan_ibu = is_numeric(str_replace('.', '', $data['penghasilan_ibu'] ?? '0')) ? str_replace('.', '', $data['penghasilan_ibu'] ?? '0') : 0;
        $penghasilan_wali = is_numeric(str_replace('.', '', $data['penghasilan_wali'] ?? '0')) ? str_replace('.', '', $data['penghasilan_wali'] ?? '0') : 0;

        // Query INSERT (Pastikan nama kolom dan placeholder sama persis jumlahnya)
        $query = "INSERT INTO data_induk (
         nama_siswa, nama_panggilan, no_induk, nisn, nik, nkk, no_akta, jenis_kelamin, tmpt_lhr, 
         tgl_lhr, agama, kewarganegaraan, anak_ke, jml_sdr_kandung, jml_sdr_tiri, jml_sdr_angkat, 
         yatim_piatu, bahasa, alamat, dusun, rt, rw, desa, kec, kab, kd_pos, provinsi, no_tlp, no_hp, 
         tinggal_bersama, jarak_rumah, wkt_tempuh, transportasi, gol_darah, penyakit, kelainan_jasmani, 
         tb, bb, asal_sd, npsn_sd, pend_sebelumnya, asal_smp, alamat_smp, npsn_smp, seri_ijazah_smp, tgl_ijazah_smp, th_ijazah_smp, 
         lama_belajar_smp, tingkat, komp_keahlian, diterima_tgl, nama_ayah, nik_ayah, 
         tmpt_lhr_ayah, tgl_lhr_ayah, agama_ayah, kewarganegaraan_ayah, pend_ayah, pekerjaan_ayah, 
         penghasilan_ayah, alamat_ayah, hp_ayah, hidup_mati_ayah, nama_ibu, nik_ibu, tmpt_lhr_ibu, 
         tgl_lhr_ibu, agama_ibu, kewarganegaraan_ibu, pend_ibu, pekerjaan_ibu, penghasilan_ibu, 
         alamat_ibu, hp_ibu, hidup_mati_ibu, nama_wali, nik_wali, tmpt_lhr_wali, tgl_lhr_wali, 
         agama_wali, kewarganegaraan_wali, pend_wali, pekerjaan_wali, penghasilan_wali, alamat_wali, 
         hp_wali, kesenian, olahraga, organisasi, cita_cita, lain_lain, id_status, rombel
     ) VALUES (
         :nama_siswa, :nama_panggilan, :no_induk, :nisn, :nik, :nkk, :no_akta, :jenis_kelamin, :tmpt_lhr, 
         :tgl_lhr, :agama, :kewarganegaraan, :anak_ke, :jml_sdr_kandung, :jml_sdr_tiri, :jml_sdr_angkat, 
         :yatim_piatu, :bahasa, :alamat, :dusun, :rt, :rw, :desa, :kec, :kab, :kd_pos, :provinsi, :no_tlp, :no_hp, 
         :tinggal_bersama, :jarak_rumah, :wkt_tempuh, :transportasi, :gol_darah, :penyakit, :kelainan_jasmani, 
         :tb, :bb, :asal_sd, :npsn_sd, :pend_sebelumnya, :asal_smp, :alamat_smp, :npsn_smp, :seri_ijazah_smp, :tgl_ijazah_smp, :th_ijazah_smp, 
         :lama_belajar_smp, :tingkat, :komp_keahlian, :diterima_tgl, :nama_ayah, :nik_ayah, 
         :tmpt_lhr_ayah, :tgl_lhr_ayah, :agama_ayah, :kewarganegaraan_ayah, :pend_ayah, :pekerjaan_ayah, 
         :penghasilan_ayah, :alamat_ayah, :hp_ayah, :hidup_mati_ayah, :nama_ibu, :nik_ibu, :tmpt_lhr_ibu, 
         :tgl_lhr_ibu, :agama_ibu, :kewarganegaraan_ibu, :pend_ibu, :pekerjaan_ibu, :penghasilan_ibu, 
         :alamat_ibu, :hp_ibu, :hidup_mati_ibu, :nama_wali, :nik_wali, :tmpt_lhr_wali, :tgl_lhr_wali, 
         :agama_wali, :kewarganegaraan_wali, :pend_wali, :pekerjaan_wali, :penghasilan_wali, :alamat_wali, 
         :hp_wali, :kesenian, :olahraga, :organisasi, :cita_cita, :lain_lain, :id_status, :rombel
     )";

        $this->db->query($query);

        // ▼▼▼ BINDING EKSPLISIT ▼▼▼
        // Sesuaikan $data['key'] dengan 'name' di form Anda jika berbeda
        // Gunakan ?? null agar aman jika data tidak ada di $_POST

        // A. Keterangan Diri
        $this->db->bind(':nama_siswa', $data['nama_siswa'] ?? null);
        $this->db->bind(':nama_panggilan', $data['nama_panggilan'] ?? null);
        $this->db->bind(':no_induk', $data['no_induk'] ?? null);
        $this->db->bind(':nisn', $data['nisn'] ?? null);
        $this->db->bind(':nik', $data['nik_siswa'] ?? null); // Sesuaikan dengan name di form jika berbeda
        $this->db->bind(':nkk', $data['nkk'] ?? null);
        $this->db->bind(':no_akta', $data['no_akta'] ?? null);
        $this->db->bind(':jenis_kelamin', $data['jenis_kelamin'] ?? null);
        $this->db->bind(':tmpt_lhr', $data['tmpt_lhr'] ?? null);
        $this->db->bind(':tgl_lhr', !empty($data['tgl_lhr']) ? $data['tgl_lhr'] : null); // Handle tanggal kosong
        $this->db->bind(':agama', $data['agama'] ?? null);
        $this->db->bind(':kewarganegaraan', $data['kewarganegaraan'] ?? null);
        $this->db->bind(':anak_ke', $data['anak_ke'] ?? null);
        $this->db->bind(':jml_sdr_kandung', $data['jml_sdr_kandung'] ?? null);
        $this->db->bind(':jml_sdr_tiri', $data['jml_sdr_tiri'] ?? null);
        $this->db->bind(':jml_sdr_angkat', $data['jml_sdr_angkat'] ?? null);
        $this->db->bind(':yatim_piatu', $data['yatim_piatu'] ?? null);
        $this->db->bind(':bahasa', $data['bahasa'] ?? null);

        // B. Tempat Tinggal
        $this->db->bind(':alamat', $data['alamat'] ?? null);
        $this->db->bind(':dusun', $data['dusun'] ?? null);
        $this->db->bind(':rt', $data['rt'] ?? null);
        $this->db->bind(':rw', $data['rw'] ?? null);
        $this->db->bind(':desa', $data['desa'] ?? null);
        $this->db->bind(':kec', $data['kec'] ?? null); // Pastikan name di form = kec
        $this->db->bind(':kab', $data['kab'] ?? null);
        $this->db->bind(':kd_pos', $data['kd_pos'] ?? null);
        $this->db->bind(':provinsi', $data['provinsi'] ?? null);
        $this->db->bind(':no_tlp', $data['no_tlp'] ?? null);
        $this->db->bind(':no_hp', $data['no_hp'] ?? null);
        $this->db->bind(':tinggal_bersama', $data['tinggal_bersama'] ?? null);
        $this->db->bind(':jarak_rumah', $data['jarak_rumah'] ?? null);
        $this->db->bind(':wkt_tempuh', $data['wkt_tempuh'] ?? null);
        $this->db->bind(':transportasi', $data['transportasi'] ?? null);

        // C. Kesehatan
        $this->db->bind(':gol_darah', $data['gol_darah'] ?? null);
        $this->db->bind(':penyakit', $data['penyakit'] ?? null);
        $this->db->bind(':kelainan_jasmani', $data['kelainan_jasmani'] ?? null);
        $this->db->bind(':tb', $data['tb'] ?? null);
        $this->db->bind(':bb', $data['bb'] ?? null);

        // D. Pendidikan Sebelumnya
        $this->db->bind(':asal_sd', $data['asal_sd'] ?? null);
        $this->db->bind(':npsn_sd', $data['npsn_sd'] ?? null);
        $this->db->bind(':pend_sebelumnya', $data['pend_sebelumnya'] ?? null);
        $this->db->bind(':asal_smp', $data['asal_smp'] ?? null);
        $this->db->bind(':alamat_smp', $data['alamat_smp'] ?? null);
        $this->db->bind(':npsn_smp', $data['npsn_smp'] ?? null);
        $this->db->bind(':seri_ijazah_smp', $data['seri_ijazah_smp'] ?? null);
        $this->db->bind(':tgl_ijazah_smp', !empty($data['tgl_ijazah_smp']) ? $data['tgl_ijazah_smp'] : null);
        $this->db->bind(':th_ijazah_smp', $data['th_ijazah_smp'] ?? null);
        $this->db->bind(':lama_belajar_smp', $data['lama_belajar_smp'] ?? null);

        // E. Pendidikan Sekolah Ini
        $this->db->bind(':tingkat', $data['tingkat'] ?? null);
        $this->db->bind(':komp_keahlian', $data['komp_keahlian'] ?? null);
        // Jika tgl_diterima ada di $_POST (dari form mutasi), bind; jika tidak, bisa null atau tanggal default
        $this->db->bind(':diterima_tgl', !empty($data['diterima_tgl']) ? $data['diterima_tgl'] : ($data['tgl_diterima'] ?? null));

        // F. Ayah
        $this->db->bind(':nama_ayah', $data['nama_ayah'] ?? null);
        $this->db->bind(':nik_ayah', $data['nik_ayah'] ?? null);
        $this->db->bind(':tmpt_lhr_ayah', $data['tmpt_lhr_ayah'] ?? null);
        $this->db->bind(':tgl_lhr_ayah', !empty($data['tgl_lhr_ayah']) ? $data['tgl_lhr_ayah'] : null);
        $this->db->bind(':agama_ayah', $data['agama_ayah'] ?? null);
        $this->db->bind(':kewarganegaraan_ayah', $data['kewarganegaraan_ayah'] ?? null);
        $this->db->bind(':pend_ayah', $data['pend_ayah'] ?? null);
        $this->db->bind(':pekerjaan_ayah', $data['pekerjaan_ayah'] ?? null);
        $this->db->bind(':penghasilan_ayah', $penghasilan_ayah); // Gunakan variabel yang sudah dibersihkan
        $this->db->bind(':alamat_ayah', $data['alamat_ayah'] ?? null);
        $this->db->bind(':hp_ayah', $data['hp_ayah'] ?? null);
        $this->db->bind(':hidup_mati_ayah', $data['hidup_mati_ayah'] ?? null);

        // G. Ibu
        $this->db->bind(':nama_ibu', $data['nama_ibu'] ?? null);
        $this->db->bind(':nik_ibu', $data['nik_ibu'] ?? null);
        $this->db->bind(':tmpt_lhr_ibu', $data['tmpt_lhr_ibu'] ?? null);
        $this->db->bind(':tgl_lhr_ibu', !empty($data['tgl_lhr_ibu']) ? $data['tgl_lhr_ibu'] : null);
        $this->db->bind(':agama_ibu', $data['agama_ibu'] ?? null);
        $this->db->bind(':kewarganegaraan_ibu', $data['kewarganegaraan_ibu'] ?? null);
        $this->db->bind(':pend_ibu', $data['pend_ibu'] ?? null);
        $this->db->bind(':pekerjaan_ibu', $data['pekerjaan_ibu'] ?? null);
        $this->db->bind(':penghasilan_ibu', $penghasilan_ibu); // Gunakan variabel yang sudah dibersihkan
        $this->db->bind(':alamat_ibu', $data['alamat_ibu'] ?? null);
        $this->db->bind(':hp_ibu', $data['hp_ibu'] ?? null);
        $this->db->bind(':hidup_mati_ibu', $data['hidup_mati_ibu'] ?? null);

        // H. Wali
        $this->db->bind(':nama_wali', $data['nama_wali'] ?? null);
        $this->db->bind(':nik_wali', $data['nik_wali'] ?? null);
        $this->db->bind(':tmpt_lhr_wali', $data['tmpt_lhr_wali'] ?? null);
        $this->db->bind(':tgl_lhr_wali', !empty($data['tgl_lhr_wali']) ? $data['tgl_lhr_wali'] : null);
        $this->db->bind(':agama_wali', $data['agama_wali'] ?? null);
        $this->db->bind(':kewarganegaraan_wali', $data['kewarganegaraan_wali'] ?? null);
        $this->db->bind(':pend_wali', $data['pend_wali'] ?? null);
        $this->db->bind(':pekerjaan_wali', $data['pekerjaan_wali'] ?? null);
        $this->db->bind(':penghasilan_wali', $penghasilan_wali); // Gunakan variabel yang sudah dibersihkan
        $this->db->bind(':alamat_wali', $data['alamat_wali'] ?? null);
        $this->db->bind(':hp_wali', $data['hp_wali'] ?? null);

        // I. Hobi
        $this->db->bind(':kesenian', $data['kesenian'] ?? null);
        $this->db->bind(':olahraga', $data['olahraga'] ?? null);
        $this->db->bind(':organisasi', $data['organisasi'] ?? null);
        $this->db->bind(':cita_cita', $data['cita_cita'] ?? null);
        $this->db->bind(':lain_lain', $data['lain_lain'] ?? null);

        // J. Status & Rombel
        $this->db->bind(':id_status', $data['id_status'] ?? null); // Diambil dari $_POST['id_status'] = 1 di controller
        $this->db->bind(':rombel', $data['rombel'] ?? null);

        // Eksekusi query
        $this->db->execute();

        // Kembalikan ID siswa baru, bukan rowCount
        // return $this->db->rowCount(); // <-- Ganti ini
        return $this->db->lastInsertId(); // <-- Menjadi ini
    }
    public function getSiswaByJurusanTingkatForNominatif($id_jurusan, $tingkat)
    {
        // JOIN dengan tabel rombel untuk mendapatkan tingkat
        // Filter berdasarkan komp_keahlian (jurusan), status aktif, DAN rombel.tingkat
        $query = "SELECT
                di.nama_siswa, di.no_induk, di.tmpt_lhr, di.tgl_lhr,
                di.alamat, di.rt, di.rw, di.dusun, di.desa, di.kec,
                di.jenis_kelamin, di.nama_ayah, di.pend_sebelumnya,
                di.seri_ijazah_smp, di.th_ijazah_smp
              FROM data_induk di
              INNER JOIN rombel r ON di.rombel = r.id_rombel -- JOIN ke tabel rombel
              WHERE di.komp_keahlian = :id_jurusan
                AND di.id_status = 1 -- Hanya siswa aktif
                AND r.tingkat = :tingkat -- Filter berdasarkan kolom tingkat di tabel rombel
              ORDER BY di.nama_siswa ASC";

        $this->db->query($query);
        $this->db->bind('id_jurusan', $id_jurusan);
        // Bind tingkat langsung (karena nilainya 'X', 'XI', atau 'XII')
        $this->db->bind('tingkat', $tingkat);
        return $this->db->resultSet();
    }
    public function getDaftarMutasiMasuk()
    {
        // JOIN mutasi_masuk dengan data_induk untuk mendapatkan nama siswa
        $query = "SELECT 
                mm.tgl_diterima, 
                mm.asal_sekolah, 
                mm.alasan_pindah, 
                di.nama_siswa, 
                di.no_induk, 
                di.id_induk -- ID siswa untuk link detail (opsional)
                , r.nama_rombel
              FROM mutasi_masuk mm
              INNER JOIN data_induk di ON mm.id_siswa = di.id_induk
              LEFT JOIN rombel r ON di.rombel = r.id_rombel
              ORDER BY mm.tgl_diterima DESC"; // Urutkan berdasarkan tanggal terbaru

        $this->db->query($query);
        return $this->db->resultSet();
    }

    // di file: app/models/Siswa_model.php

    public function tambahDataMutasiMasuk($id_siswa, $data) // Ubah di sini: terima 2 parameter
    {
        $query = "INSERT INTO mutasi_masuk (id_siswa, tgl_diterima, alasan_pindah, asal_sekolah) 
              VALUES (:id_siswa, :tgl_diterima, :alasan_pindah, :asal_sekolah)";

        $this->db->query($query);

        // Gunakan parameter $id_siswa secara langsung
        $this->db->bind(':id_siswa', $id_siswa);

        // Gunakan array $data untuk sisanya
        $this->db->bind(':tgl_diterima', $data['tgl_diterima']);
        $this->db->bind(':alasan_pindah', $data['alasan_pindah']);
        $this->db->bind(':asal_sekolah', $data['asal_sekolah']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function searchSiswaAktif($keyword)
    {
        try {
            // Query disesuaikan dengan struktur tabel data_induk Anda
            // Pastikan nama kolom 'di.rombel' adalah foreign key ke tabel rombel
            $query = "SELECT 
                    di.id_induk, 
                    di.nama_siswa, 
                    di.no_induk, 
                    di.nisn,
                    r.nama_rombel
                  FROM data_induk di
                  LEFT JOIN rombel r ON di.rombel = r.id_rombel
                  WHERE 
                    di.id_status = 1 
                    AND (
                        di.nama_siswa LIKE :term OR 
                        di.no_induk LIKE :term OR 
                        di.nisn LIKE :term
                    )
                  ORDER BY di.nama_siswa ASC
                  LIMIT 20";

            $this->db->query($query);
            // Bind parameter
            $this->db->bind(':term', '%' . $keyword . '%');

            // Kembalikan hasil
            return $this->db->resultSet();
        } catch (PDOException $e) {
            // Tangkap error SQL dan lempar ke Controller
            throw new Exception("Database Error: " . $e->getMessage());
        }
    }
    
    // === MUTASI KELUAR (Ubah Status ke 2) ===

    /**
     * Memproses siswa mutasi keluar (mengubah status & mencatat log ke mutasi_keluar)
     */
    public function mutasiKeluar($id_siswa, $data)
    {
        $ID_STATUS_KELUAR = 2; // ID Status "Mutasi Keluar"

        // 1. Update status
        $queryUpdate = "UPDATE data_induk SET id_status = :id_status WHERE id_induk = :id_induk";
        $this->db->query($queryUpdate);
        $this->db->bind(':id_status', $ID_STATUS_KELUAR);
        $this->db->bind(':id_induk', $id_siswa);
        $this->db->execute();

        if ($this->db->rowCount() > 0) {
            // 2. Catat log ke mutasi_keluar
            $queryLog = "INSERT INTO mutasi_keluar (id_siswa, tgl_keluar, sekolah_tujuan, alasan_keluar) 
                     VALUES (:id_siswa, :tgl_keluar, :sekolah_tujuan, :alasan_keluar)";
            $this->db->query($queryLog);
            $this->db->bind('id_siswa', $id_siswa);
            $this->db->bind('tgl_keluar', $data['tgl_keluar']);
            $this->db->bind('sekolah_tujuan', $data['sekolah_tujuan']); // Ambil dari form
            $this->db->bind('alasan_keluar', $data['alasan_keluar']);
            $this->db->execute();
            return $this->db->rowCount();
        }
        return 0;
    }

    /**
     * Mengambil daftar log dari tabel mutasi_keluar
     */
    public function getDaftarMutasiKeluar()
    {
        $query = "SELECT 
                mk.id_mutasi_keluar, mk.tgl_keluar, mk.sekolah_tujuan, mk.alasan_keluar, 
                di.nama_siswa, di.no_induk, r.nama_rombel
              FROM mutasi_keluar mk
              INNER JOIN data_induk di ON mk.id_siswa = di.id_induk
              LEFT JOIN rombel r ON di.rombel = r.id_rombel
              ORDER BY mk.tgl_keluar DESC";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    /**
     * Mengambil satu data log dari tabel mutasi_keluar
     */
    public function getMutasiKeluarById($id_log)
    {
        $query = "SELECT mk.*, di.nama_siswa, di.no_induk 
              FROM mutasi_keluar mk
              INNER JOIN data_induk di ON mk.id_siswa = di.id_induk
              WHERE mk.id_mutasi_keluar = :id_log";
        $this->db->query($query);
        $this->db->bind('id_log', $id_log);
        return $this->db->single();
    }

    /**
     * Memperbarui data di tabel mutasi_keluar (status siswa TETAP 2)
     */
    public function updateMutasiKeluar($data)
    {
        // Status siswa tidak diubah lagi di sini, asumsi sudah 2
        $queryUpdateLog = "UPDATE mutasi_keluar SET 
                        tgl_keluar = :tgl_keluar,
                        sekolah_tujuan = :sekolah_tujuan,
                        alasan_keluar = :alasan_keluar
                       WHERE id_mutasi_keluar = :id_mutasi_keluar";

        $this->db->query($queryUpdateLog);
        $this->db->bind('tgl_keluar', $data['tgl_keluar']);
        $this->db->bind('sekolah_tujuan', $data['sekolah_tujuan']);
        $this->db->bind('alasan_keluar', $data['alasan_keluar']);
        $this->db->bind('id_mutasi_keluar', $data['id_mutasi_keluar']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Menghapus log dari mutasi_keluar DAN mengembalikan status siswa ke 1 (Aktif)
     */
    public function hapusMutasiKeluar($id_log)
    {
        $log = $this->getMutasiKeluarById($id_log);
        if (!$log) return 0;
        $id_siswa = $log->id_siswa;
        $ID_STATUS_AKTIF = 1;

        try {
            $this->db->beginTransaction();
            // 1. Hapus log
            $this->db->query("DELETE FROM mutasi_keluar WHERE id_mutasi_keluar = :id_log");
            $this->db->bind('id_log', $id_log);
            $this->db->execute();
            // 2. Kembalikan status
            $this->db->query("UPDATE data_induk SET id_status = :id_status WHERE id_induk = :id_siswa");
            $this->db->bind('id_status', $ID_STATUS_AKTIF);
            $this->db->bind('id_siswa', $id_siswa);
            $this->db->execute();
            $this->db->commit();
            return 1;
        } catch (Exception $e) {
            $this->db->rollBack();
            return 0;
        }
    }


    // === MENGUNDURKAN DIRI (Status Tetap 1) ===

    /**
     * Mencatat log ke tabel mengundurkan_diri (status siswa tetap 1)
     */
    public function mengundurkanDiri($id_siswa, $data)
    {
        // ASUMSI nama tabel: mengundurkan_diri
        // ASUMSI nama kolom: id_undur_diri, id_siswa, tgl_mengundurkan_diri, alasan_mengundurkan_diri
        $queryLog = "INSERT INTO mengundurkan_diri (id_siswa, tgl_mengundurkan_diri, alasan_mengundurkan_diri) 
                 VALUES (:id_siswa, :tgl_mengundurkan_diri, :alasan_mengundurkan_diri)";

        $this->db->query($queryLog);
        $this->db->bind(':id_siswa', $id_siswa);
        $this->db->bind(':tgl_mengundurkan_diri', $data['tgl_keluar']); // Ambil dari field tanggal di form
        $this->db->bind(':alasan_mengundurkan_diri', $data['alasan_keluar']); // Ambil dari field alasan di form
        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Mengambil daftar log dari tabel mengundurkan_diri
     */
    public function getDaftarMengundurkanDiri()
    {
        $query = "SELECT 
                md.id_mengundurkan_diri, md.tgl_mengundurkan_diri, md.alasan_mengundurkan_diri,
                di.nama_siswa, di.no_induk, di.id_status, ss.status, r.nama_rombel
              FROM mengundurkan_diri md
              INNER JOIN data_induk di ON md.id_siswa = di.id_induk
              JOIN status ss ON di.id_status = ss.id_status
              LEFT JOIN rombel r ON di.rombel = r.id_rombel
              ORDER BY md.tgl_mengundurkan_diri DESC";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    /**
     * Mengambil satu data log dari tabel mengundurkan_diri
     */
    public function getMengundurkanDiriById($id_log)
    {
        $query = "SELECT md.*, di.nama_siswa, di.no_induk
              FROM mengundurkan_diri md
              INNER JOIN data_induk di ON md.id_siswa = di.id_induk
              WHERE md.id_mengundurkan_diri = :id_log";
        $this->db->query($query);
        $this->db->bind('id_log', $id_log);
        return $this->db->single();
    }

    public function getMengundurkanDiriByRombel($id_rombel)
    {
        $query = "SELECT 
                md.tgl_mengundurkan_diri,
                di.id_induk
              FROM mengundurkan_diri md
              INNER JOIN data_induk di ON md.id_siswa = di.id_induk
              WHERE di.rombel = :id_rombel";

        $this->db->query($query);
        $this->db->bind('id_rombel', $id_rombel);
        return $this->db->resultSet();
    }


    /**
     * Memperbarui data di tabel mengundurkan_diri (status siswa tidak berubah)
     */
    public function updateMengundurkanDiri($data)
    {
        $queryUpdateLog = "UPDATE mengundurkan_diri SET 
                        tgl_mengundurkan_diri = :tgl_mengundurkan_diri,
                        alasan_mengundurkan_diri = :alasan_mengundurkan_diri
                       WHERE id_mengundurkan_diri = :id_mengundurkan_diri";

        $this->db->query($queryUpdateLog);
        $this->db->bind('tgl_mengundurkan_diri', $data['tgl_keluar']); // Ambil dari form
        $this->db->bind('alasan_mengundurkan_diri', $data['alasan_keluar']); // Ambil dari form
        $this->db->bind('id_mengundurkan_diri', $data['id_undur_diri']); // ID log undur diri
        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Menghapus log dari mengundurkan_diri (status siswa tidak berubah)
     */
    public function hapusMengundurkanDiri($id_log)
    {
        $this->db->query("DELETE FROM mengundurkan_diri WHERE id_mengundurkan_diri = :id_log");
        $this->db->bind('id_log', $id_log);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getMutasiSiswa($id_siswa)
    {
        $result = [
            'jenis' => '-',
            'tanggal' => '-',
            'asal' => '-',
            'tujuan' => '-',
            'alasan' => '-'
        ];

        // 1. Cek Mutasi Masuk 
        $this->db->query("SELECT * FROM mutasi_masuk WHERE id_siswa = :id ORDER BY tgl_diterima DESC LIMIT 1");
        $this->db->bind(':id', $id_siswa);
        $masuk = $this->db->single();
        if ($masuk) {
            $result['jenis'] = 'Mutasi Masuk';
            $result['tanggal'] = $masuk->tgl_diterima;
            $result['asal'] = $masuk->asal_sekolah;
            $result['alasan'] = $masuk->alasan_pindah;
            return $result; // Langsung return jika ditemukan
        }

        // 2. Jika tidak ada, cek Mutasi Keluar
        $this->db->query("SELECT * FROM mutasi_keluar WHERE id_siswa = :id ORDER BY tgl_keluar DESC LIMIT 1");
        $this->db->bind(':id', $id_siswa);
        $keluar = $this->db->single();
        if ($keluar) {
            // Cek apakah ini undur diri yang tercatat di mutasi_keluar
            $result['jenis'] = ($keluar->sekolah_tujuan == 'Mengundurkan Diri') ? 'Mengundurkan Diri' : 'Mutasi Keluar';
            $result['tanggal'] = $keluar->tgl_keluar;
            $result['tujuan'] = $keluar->sekolah_tujuan;
            $result['alasan'] = $keluar->alasan_keluar;
            return $result; // Langsung return
        }

        // 3. Jika tidak ada, cek tabel Mengundurkan Diri (jika ada)
        // Sesuaikan nama tabel dan kolom jika perlu
        $this->db->query("SELECT * FROM mengundurkan_diri WHERE id_siswa = :id ORDER BY tgl_mengundurkan_diri DESC LIMIT 1");
        $this->db->bind(':id', $id_siswa);
        $undur = $this->db->single();
        if ($undur) {
            $result['jenis'] = 'Mengundurkan Diri';
            $result['tanggal'] = $undur->tgl_mengundurkan_diri; // Sesuaikan nama kolom
            $result['tujuan'] = 'Mengundurkan Diri'; // Tujuan default
            $result['alasan'] = $undur->alasan_mengundurkan_diri; // Sesuaikan nama kolom
            return $result; // Langsung return
        }

        return $result; // Tidak ada data mutasi ditemukan
    }

    public function hitungNomorAbsen($id_siswa, $id_rombel)
    {
        // Handle jika $id_rombel kosong atau null
        if (empty($id_rombel)) {
            return '-';
        }

        // Ambil semua siswa aktif (id_status = 1) di rombel tersebut, urutkan nama
        $this->db->query("SELECT id_induk FROM data_induk 
                          WHERE rombel = :id_rombel AND id_status = 1 
                          ORDER BY nama_siswa ASC");
        $this->db->bind(':id_rombel', $id_rombel);
        $siswa_di_rombel = $this->db->resultSet();

        $no_absen = '-';
        $counter = 0;

        // Looping untuk mencari posisi siswa
        if ($siswa_di_rombel) { // Pastikan ada hasil sebelum looping
            foreach ($siswa_di_rombel as $siswa) {
                $counter++;
                if ($siswa->id_induk == $id_siswa) {
                    $no_absen = $counter;
                    break; // Hentikan loop jika siswa sudah ditemukan
                }
            }
        }

        return $no_absen;
    }

    public function getAllSiswaLengkap()
    {
        // Query dengan semua kolom termasuk HP ortu/wali
        $query = "SELECT 
                di.*,  -- Ambil semua kolom dari data_induk
                s.status AS nama_status_siswa, -- Alias baru agar tidak konflik
                r.nama_rombel, 
                j.* -- Ambil semua kolom dari jurusan
              FROM data_induk di
              LEFT JOIN status s ON di.id_status = s.id_status
              LEFT JOIN rombel r ON di.rombel = r.id_rombel
              LEFT JOIN jurusan j ON di.komp_keahlian = j.id_jurusan
              WHERE di.id_status = 1 -- Hanya siswa aktif
              ORDER BY r.id_rombel DESC, di.nama_siswa ASC"; // Urutkan Rombel DESC, Nama ASC

        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getAllSiswaInduk()
    {
        // Query dengan semua kolom termasuk HP ortu/wali
        $query = "SELECT 
                di.*,  -- Ambil semua kolom dari data_induk
                s.status AS nama_status_siswa, -- Alias baru agar tidak konflik
                r.nama_rombel, 
                j.* -- Ambil semua kolom dari jurusan
              FROM data_induk di
              LEFT JOIN status s ON di.id_status = s.id_status
              LEFT JOIN rombel r ON di.rombel = r.id_rombel
              LEFT JOIN jurusan j ON di.komp_keahlian = j.id_jurusan
              ORDER BY r.id_rombel DESC, di.nama_siswa ASC"; // Urutkan Rombel DESC, Nama ASC

        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getAllSiswaKeluar()
    {
        // Query dengan semua kolom termasuk HP ortu/wali
        $query = "SELECT 
                di.*,  -- Ambil semua kolom dari data_induk
                s.status AS nama_status_siswa, -- Alias baru agar tidak konflik
                r.nama_rombel, 
                j.* -- Ambil semua kolom dari jurusan
              FROM data_induk di
              LEFT JOIN status s ON di.id_status = s.id_status
              LEFT JOIN rombel r ON di.rombel = r.id_rombel
              LEFT JOIN jurusan j ON di.komp_keahlian = j.id_jurusan
              WHERE di.id_status != 1 -- Hanya siswa non-aktif
              ORDER BY r.id_rombel DESC, di.nama_siswa ASC"; // Urutkan Rombel DESC, Nama ASC

        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getSiswaAktifByRombelId($id_rombel)
    {
        // Ambil kolom yang dibutuhkan untuk export
        $this->db->query("SELECT 
                            id_induk, nama_siswa, no_induk, nisn, alamat, 
                            no_hp, nama_ayah, nama_ibu, jenis_kelamin
                          FROM data_induk 
                          WHERE rombel = :id_rombel AND id_status = 1 
                          ORDER BY nama_siswa ASC");
        $this->db->bind('id_rombel', $id_rombel);
        return $this->db->resultSet();
    }

    public function getSiswaByRombel($id_rombel)
    {
        // Mengambil siswa yang masih aktif (id_status = 1) berdasarkan rombel
        $this->db->query("SELECT id_induk, no_induk, nama_siswa 
                          FROM data_induk 
                          WHERE rombel = :id_rombel AND id_status = 1 
                          ORDER BY nama_siswa ASC");

        $this->db->bind('id_rombel', $id_rombel);
        return $this->db->resultSet();
    }

    // 1. Mengambil data sesuai limit, pencarian, dan urutan dari DataTables
    public function getSiswaSSP($start, $length, $searchValue, $orderColumn, $orderDir)
    {
        // Daftar kolom untuk fitur sorting DataTables (Sesuaikan urutannya dengan <th> di HTML nanti)
        // Index 0 = No, 1 = NIS, 2 = Nama, 3 = Rombel, 4 = Status
        $columns = ['d.id_induk', 'LEFT(d.no_induk, 5)', 'd.nama_siswa', 'r.nama_rombel', 's.status'];

        $query = "SELECT d.id_induk, d.no_induk, d.nama_siswa, r.nama_rombel, s.status, s.id_status 
                  FROM data_induk d 
                  LEFT JOIN rombel r ON d.rombel = r.id_rombel 
                  LEFT JOIN status s ON d.id_status = s.id_status ";

        // Fitur Pencarian (Search)
        if (!empty($searchValue)) {
            $query .= " WHERE d.nama_siswa LIKE :search OR d.no_induk LIKE :search ";
        }

        // Fitur Pengurutan (Order/Sort)
        if (isset($orderColumn) && isset($columns[$orderColumn])) {
            $query .= " ORDER BY " . $columns[$orderColumn] . " " . strtoupper($orderDir);
        } else {
            $query .= " ORDER BY d.id_induk DESC "; // Default urutan
        }

        // Fitur Pagination (Limit & Offset)
        if ($length != -1) {
            $query .= " LIMIT " . (int)$start . ", " . (int)$length;
        }

        $this->db->query($query);

        if (!empty($searchValue)) {
            $this->db->bind(':search', "%$searchValue%");
        }

        return $this->db->resultSet();
    }

    // 2. Menghitung jumlah data setelah difilter (untuk info pagination)
    public function countSiswaFiltered($searchValue)
    {
        $query = "SELECT COUNT(d.id_induk) as total 
                  FROM data_induk d 
                  LEFT JOIN rombel r ON d.rombel = r.id_rombel 
                  LEFT JOIN status s ON d.id_status = s.id_status ";

        if (!empty($searchValue)) {
            $query .= " WHERE d.nama_siswa LIKE :search OR d.no_induk LIKE :search ";
            $this->db->query($query);
            $this->db->bind(':search', "%$searchValue%");
        } else {
            $this->db->query($query);
        }

        return $this->db->single()->total;
    }

    // 3. Menghitung total seluruh data induk di database (tanpa filter)
    public function countSiswaTotal()
    {
        $this->db->query("SELECT COUNT(id_induk) as total FROM data_induk");
        return $this->db->single()->total;
    }


    public function importDataSiswa($dataImport)
    {
        $hasil = [
            'berhasil' => 0,
            'duplikat' => [],
            'error_db' => null
        ];

        foreach ($dataImport as $data) {
            $siswaAda = false;

            // Cek duplikat
            if (!empty($data['no_induk']) || !empty($data['nisn'])) {
                $this->db->query("SELECT id_induk FROM data_induk WHERE no_induk = :no_induk OR nisn = :nisn");
                $this->db->bind('no_induk', $data['no_induk']);
                $this->db->bind('nisn', $data['nisn']);
                $siswaAda = $this->db->single();
            }

            if ($siswaAda) {
                // Catat nama siswa yang dilewati karena duplikat
                $hasil['duplikat'][] = $data['nama_siswa'];
                continue; // Lewati baris ini
            }

            // Jika tidak duplikat, lakukan INSERT
            $kolom = implode(", ", array_keys($data));
            $parameter = ":" . implode(", :", array_keys($data));

            $query = "INSERT INTO data_induk ($kolom) VALUES ($parameter)";
            $this->db->query($query);

            foreach ($data as $key => $value) {
                $this->db->bind($key, $value);
            }

            try {
                $this->db->execute();
                $hasil['berhasil'] += $this->db->rowCount();
            } catch (\PDOException $e) {
                // Tangkap jika ada error kolom tidak cocok (mencegah blank screen)
                $hasil['error_db'] = $e->getMessage();
                return $hasil; // Hentikan loop dan kembalikan error
            }
        }

        return $hasil;
    }

    public function updateDataSiswaPortal($id, $data)
    {

        // ======================================================================
        // 1. CEK PERUBAHAN & CATAT LOG
        // ======================================================================
        $data_lama = $this->getDetailSiswaById($id);
        $perubahan = [];

        foreach ($data as $kolom => $nilai_baru) {
            if (isset($data_lama->$kolom)) {
                $nilai_lama = $data_lama->$kolom;

                // NORMALISASI DATA SEBELUM DIBANDINGKAN
                $str_lama = trim((string)$nilai_lama);
                $str_baru = trim((string)$nilai_baru);

                // FIX KHUSUS UNTUK TANGGAL '0000-00-00'
                if ($str_lama === '0000-00-00' && $str_baru === '') {
                    $str_lama = '';
                }

                // Bandingkan nilai yang sudah dinormalisasi
                if ($str_lama !== $str_baru) {
                    $perubahan[] = [
                        'kolom' => $kolom,
                        'lama'  => $nilai_lama,
                        'baru'  => $nilai_baru
                    ];
                }
            }
        }

        // ======================================================================
        // EKSEKUSI INSERT LOG (CUKUP SATU KALI SAJA DI SINI)
        // ======================================================================
        if (!empty($perubahan)) {
            foreach ($perubahan as $ubah) {
                // Cek apakah data persis sama pernah diinput dalam 10 detik terakhir
                $cek_dobel = "SELECT id_log FROM log_edit_siswa 
                              WHERE id_induk = :id_induk 
                              AND kolom_diubah = :kolom_diubah 
                              AND nilai_baru = :nilai_baru 
                              AND waktu_edit >= NOW() - INTERVAL 10 SECOND";

                $this->db->query($cek_dobel);
                $this->db->bind('id_induk', $id);
                $this->db->bind('kolom_diubah', $ubah['kolom']);
                $this->db->bind('nilai_baru', $ubah['baru']);
                $this->db->execute();

                // Jika tidak ada data yang sama dalam 10 detik terakhir, INSERT
                if ($this->db->rowCount() == 0) {
                    $queryLog = "INSERT INTO log_edit_siswa (id_induk, kolom_diubah, nilai_lama, nilai_baru) 
                                 VALUES (:id_induk, :kolom_diubah, :nilai_lama, :nilai_baru)";

                    $this->db->query($queryLog);
                    $this->db->bind('id_induk', $id);
                    $this->db->bind('kolom_diubah', $ubah['kolom']);
                    $this->db->bind('nilai_lama', $ubah['lama']);
                    $this->db->bind('nilai_baru', $ubah['baru']);
                    $this->db->execute();
                }
            }
        }
        // Susun query UPDATE sesuai dengan nama input dari form
        $query = "UPDATE data_induk SET 
                    nama_panggilan = :nama_panggilan,
                    tmpt_lhr = :tmpt_lhr,
                    tgl_lhr = :tgl_lhr,
                    agama = :agama,
                    kewarganegaraan = :kewarganegaraan,
                    anak_ke = :anak_ke,
                    jml_sdr_kandung = :jml_sdr_kandung,
                    jml_sdr_tiri = :jml_sdr_tiri,
                    jml_sdr_angkat = :jml_sdr_angkat,
                    yatim_piatu = :yatim_piatu,
                    bahasa = :bahasa,
                    alamat = :alamat,
                    dusun = :dusun,
                    rt = :rt,
                    rw = :rw,
                    desa = :desa,
                    kec = :kec,
                    kab = :kab,
                    provinsi = :provinsi,
                    kd_pos = :kd_pos,
                    no_hp = :no_hp,
                    no_tlp = :no_tlp,
                    tinggal_bersama = :tinggal_bersama,
                    transportasi = :transportasi,
                    jarak_rumah = :jarak_rumah,
                    wkt_tempuh = :wkt_tempuh,
                    gol_darah = :gol_darah,
                    tb = :tb,
                    bb = :bb,
                    penyakit = :penyakit,
                    kelainan_jasmani = :kelainan_jasmani,
                    asal_sd = :asal_sd,
                    asal_smp = :asal_smp,
                    alamat_smp = :alamat_smp,
                    tmpt_lhr_ayah = :tmpt_lhr_ayah,
                    tgl_lhr_ayah = :tgl_lhr_ayah,
                    hidup_mati_ayah = :hidup_mati_ayah,
                    agama_ayah = :agama_ayah,
                    pend_ayah = :pend_ayah,
                    pekerjaan_ayah = :pekerjaan_ayah,
                    penghasilan_ayah = :penghasilan_ayah,
                    hp_ayah = :hp_ayah,
                    alamat_ayah = :alamat_ayah,
                    tmpt_lhr_ibu = :tmpt_lhr_ibu,
                    tgl_lhr_ibu = :tgl_lhr_ibu,
                    hidup_mati_ibu = :hidup_mati_ibu,
                    agama_ibu = :agama_ibu,
                    pend_ibu = :pend_ibu,
                    pekerjaan_ibu = :pekerjaan_ibu,
                    penghasilan_ibu = :penghasilan_ibu,
                    hp_ibu = :hp_ibu,
                    alamat_ibu = :alamat_ibu,
                    nama_wali = :nama_wali,
                    pend_wali = :pend_wali,
                    pekerjaan_wali = :pekerjaan_wali,
                    penghasilan_wali = :penghasilan_wali,
                    hp_wali = :hp_wali,
                    alamat_wali = :alamat_wali,
                    kesenian = :kesenian,
                    olahraga = :olahraga,
                    organisasi = :organisasi
                  WHERE id_induk = :id";

        $this->db->query($query);

        // ======================================================================
        // BINDING DATA (Mengamankan input dari XSS)
        // ======================================================================

        $this->db->bind('nama_panggilan', htmlspecialchars($data['nama_panggilan'] ?? ''));
        $this->db->bind('tmpt_lhr', htmlspecialchars($data['tmpt_lhr'] ?? ''));
        $this->db->bind('tgl_lhr', htmlspecialchars($data['tgl_lhr'] ?? ''));
        $this->db->bind('agama', htmlspecialchars($data['agama'] ?? ''));
        $this->db->bind('kewarganegaraan', htmlspecialchars($data['kewarganegaraan'] ?? ''));

        // Data Saudara (gunakan default '0' atau '' jika kosong)
        $this->db->bind('anak_ke', htmlspecialchars($data['anak_ke'] ?? ''));
        $this->db->bind('jml_sdr_kandung', htmlspecialchars($data['jml_sdr_kandung'] ?? '0'));
        $this->db->bind('jml_sdr_tiri', htmlspecialchars($data['jml_sdr_tiri'] ?? '0'));
        $this->db->bind('jml_sdr_angkat', htmlspecialchars($data['jml_sdr_angkat'] ?? '0'));

        $this->db->bind('yatim_piatu', htmlspecialchars($data['yatim_piatu'] ?? ''));
        $this->db->bind('bahasa', htmlspecialchars($data['bahasa'] ?? ''));

        // Alamat
        $this->db->bind('alamat', htmlspecialchars($data['alamat'] ?? ''));
        $this->db->bind('dusun', htmlspecialchars($data['dusun'] ?? ''));
        $this->db->bind('rt', htmlspecialchars($data['rt'] ?? ''));
        $this->db->bind('rw', htmlspecialchars($data['rw'] ?? ''));
        $this->db->bind('desa', htmlspecialchars($data['desa'] ?? ''));
        $this->db->bind('kec', htmlspecialchars($data['kec'] ?? ''));
        $this->db->bind('kab', htmlspecialchars($data['kab'] ?? ''));
        $this->db->bind('provinsi', htmlspecialchars($data['provinsi'] ?? ''));
        $this->db->bind('kd_pos', htmlspecialchars($data['kd_pos'] ?? ''));

        // Kontak & Keseharian
        $this->db->bind('no_hp', htmlspecialchars($data['no_hp'] ?? ''));
        $this->db->bind('no_tlp', htmlspecialchars($data['no_tlp'] ?? ''));
        $this->db->bind('tinggal_bersama', htmlspecialchars($data['tinggal_bersama'] ?? ''));
        $this->db->bind('transportasi', htmlspecialchars($data['transportasi'] ?? ''));
        $this->db->bind('jarak_rumah', htmlspecialchars($data['jarak_rumah'] ?? ''));
        $this->db->bind('wkt_tempuh', htmlspecialchars($data['wkt_tempuh'] ?? ''));

        // Kesehatan
        $this->db->bind('gol_darah', htmlspecialchars($data['gol_darah'] ?? ''));
        $this->db->bind('tb', htmlspecialchars($data['tb'] ?? ''));
        $this->db->bind('bb', htmlspecialchars($data['bb'] ?? ''));
        $this->db->bind('penyakit', htmlspecialchars($data['penyakit'] ?? ''));
        $this->db->bind('kelainan_jasmani', htmlspecialchars($data['kelainan_jasmani'] ?? ''));

        // Pendidikan Sebelumnya
        $this->db->bind('asal_sd', htmlspecialchars($data['asal_sd'] ?? ''));
        $this->db->bind('asal_smp', htmlspecialchars($data['asal_smp'] ?? ''));
        $this->db->bind('alamat_smp', htmlspecialchars($data['alamat_smp'] ?? ''));

        // Data Ayah
        $this->db->bind('tmpt_lhr_ayah', htmlspecialchars($data['tmpt_lhr_ayah'] ?? ''));
        $this->db->bind('tgl_lhr_ayah', htmlspecialchars($data['tgl_lhr_ayah'] ?? ''));
        $this->db->bind('hidup_mati_ayah', htmlspecialchars($data['hidup_mati_ayah'] ?? ''));
        $this->db->bind('agama_ayah', htmlspecialchars($data['agama_ayah'] ?? ''));
        $this->db->bind('pend_ayah', htmlspecialchars($data['pend_ayah'] ?? ''));
        $this->db->bind('pekerjaan_ayah', htmlspecialchars($data['pekerjaan_ayah'] ?? ''));
        $this->db->bind('penghasilan_ayah', htmlspecialchars($data['penghasilan_ayah'] ?? ''));
        $this->db->bind('hp_ayah', htmlspecialchars($data['hp_ayah'] ?? ''));
        $this->db->bind('alamat_ayah', htmlspecialchars($data['alamat_ayah'] ?? ''));

        // Data Ibu
        $this->db->bind('tmpt_lhr_ibu', htmlspecialchars($data['tmpt_lhr_ibu'] ?? ''));
        $this->db->bind('tgl_lhr_ibu', htmlspecialchars($data['tgl_lhr_ibu'] ?? ''));
        $this->db->bind('hidup_mati_ibu', htmlspecialchars($data['hidup_mati_ibu'] ?? ''));
        $this->db->bind('agama_ibu', htmlspecialchars($data['agama_ibu'] ?? ''));
        $this->db->bind('pend_ibu', htmlspecialchars($data['pend_ibu'] ?? ''));
        $this->db->bind('pekerjaan_ibu', htmlspecialchars($data['pekerjaan_ibu'] ?? ''));
        $this->db->bind('penghasilan_ibu', htmlspecialchars($data['penghasilan_ibu'] ?? ''));
        $this->db->bind('hp_ibu', htmlspecialchars($data['hp_ibu'] ?? ''));
        $this->db->bind('alamat_ibu', htmlspecialchars($data['alamat_ibu'] ?? ''));

        // Data Wali
        $this->db->bind('nama_wali', htmlspecialchars($data['nama_wali'] ?? ''));
        $this->db->bind('pend_wali', htmlspecialchars($data['pend_wali'] ?? ''));
        $this->db->bind('pekerjaan_wali', htmlspecialchars($data['pekerjaan_wali'] ?? ''));
        $this->db->bind('penghasilan_wali', htmlspecialchars($data['penghasilan_wali'] ?? ''));
        $this->db->bind('hp_wali', htmlspecialchars($data['hp_wali'] ?? ''));
        $this->db->bind('alamat_wali', htmlspecialchars($data['alamat_wali'] ?? ''));

        // Perkembangan / Minat Bakat
        $this->db->bind('kesenian', htmlspecialchars($data['kesenian'] ?? ''));
        $this->db->bind('olahraga', htmlspecialchars($data['olahraga'] ?? ''));
        $this->db->bind('organisasi', htmlspecialchars($data['organisasi'] ?? ''));

        // Bind ID Utama (Untuk WHERE Clause)
        $this->db->bind('id', $id);

        // Eksekusi
        $this->db->execute();

        // Mengembalikan jumlah data yang terpengaruh
        return $this->db->rowCount();
    }

    public function getLogEditSiswa($id)
    {
        // Query untuk mengambil semua log perubahan milik siswa berdasarkan id_induk
        // ORDER BY waktu_edit DESC memastikan data yang paling baru diedit muncul di urutan atas
        $this->db->query("SELECT * FROM log_edit_siswa WHERE id_induk = :id ORDER BY waktu_edit DESC");

        // Mengikat (binding) data id_induk untuk keamanan dari SQL Injection
        $this->db->bind('id', $id);

        // Mengembalikan banyak baris data sekaligus (karena log bisa lebih dari satu)
        return $this->db->resultSet();
    }

    public function getSiswaForPkl()
    {
        // Query untuk mengambil siswa Aktif (id_status = 1), 
        // jurusan Teknik Sepeda Motor, dan tingkat XI
        $query = "SELECT 
                    di.nama_siswa AS nama, 
                    di.no_induk AS nis, 
                    di.nisn AS nisn, 
                    r.nama_rombel AS kelas, 
                    di.alamat AS alamat, 
                    di.no_hp AS nomor_ponsel
                  FROM {$this->table} di
                  LEFT JOIN jurusan j ON di.komp_keahlian = j.id_jurusan
                  LEFT JOIN rombel r ON di.rombel = r.id_rombel
                  WHERE di.id_status = 1 
                    AND j.jurusan LIKE '%Teknik Sepeda Motor%' 
                    AND r.tingkat = '11'";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    // Query untuk pencarian massal (Search)
    public function searchSiswa($keyword)
    {
        echo json_encode([
            'status' => 'success',
            'message' => 'SAY HELLO! File Siswa_model.php versi terbaru berhasil terbaca di server!',
            'keyword_yang_diterima' => $keyword
        ]);
        exit; // Menghentikan aplikasi di sini agar tidak lanjut ke query
    }

    // Query untuk mengambil 1 data spesifik (Sync)
    public function getSiswaByNis($nis)
    {
        $query = "SELECT 
                di.no_induk AS nis, 
                di.nama_siswa AS name, 
                di.tmpt_lhr AS birth_place, 
                di.tgl_lhr AS birth_date, 
                di.alamat AS address, 
                di.nama_ayah AS guardian_name, 
                di.no_hp AS guardian_phone, 
                r.nama_rombel AS rombel
              FROM {$this->table} di
              LEFT JOIN rombel r ON di.rombel = r.id_rombel
              WHERE di.no_induk = '$nis' LIMIT 1";

        $this->db->query($query);
        return $this->db->single(); // Mengembalikan 1 baris objek/array, bukan list array
    }
}
