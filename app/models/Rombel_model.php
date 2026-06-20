<?php

class Rombel_model
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getDataRombelServerSide($request)
    {
        $namaRombel = $request['nama_rombel'] ?? '';
        $jurusan    = $request['jurusan'] ?? '';
        $tingkat    = $request['tingkat'] ?? '';

        $columns = ['r.nama_rombel', 'r.tingkat', 'j.jurusan', 'g.nama_guru'];

        $baseQuery = "FROM rombel r
                  LEFT JOIN jurusan j ON r.id_jurusan = j.id_jurusan
                  LEFT JOIN guru g ON r.id_walas = g.id_guru";

        /* ================= FILTER DROPDOWN ================= */
        $where = [];

        if (!empty($namaRombel)) {
            $where[] = "r.nama_rombel = :nama_rombel";
        }

        if (!empty($jurusan)) {
            $where[] = "j.jurusan = :jurusan";
        }

        if (!empty($tingkat)) {
            $where[] = "r.tingkat = :tingkat";
        }

        /* ================= SEARCH DATATABLES ================= */
        if (!empty($request['search']['value'])) {
            $searchValue = $request['search']['value'];
            $searchPart = [];

            foreach ($columns as $col) {
                $searchPart[] = "$col LIKE :search";
            }

            $where[] = "(" . implode(" OR ", $searchPart) . ")";
        }

        /* ================= FINAL WHERE ================= */
        $whereQuery = '';
        if (!empty($where)) {
            $whereQuery = " WHERE " . implode(" AND ", $where);
        }

        /* ================= TOTAL ROWS ================= */
        $this->db->query("SELECT COUNT(*) as total " . $baseQuery);
        $totalRows = $this->db->single()->total;

        /* ================= FILTERED ROWS ================= */
        $this->db->query("SELECT COUNT(*) as total " . $baseQuery . $whereQuery);

        if (!empty($namaRombel)) {
            $this->db->bind('nama_rombel', $namaRombel);
        }
        if (!empty($jurusan)) {
            $this->db->bind('jurusan', $jurusan);
        }
        if (!empty($tingkat)) {
            $this->db->bind('tingkat', $tingkat);
        }
        if (!empty($request['search']['value'])) {
            $this->db->bind('search', '%' . $request['search']['value'] . '%');
        }

        $filteredRows = $this->db->single()->total;

        /* ================= ORDER ================= */
        $orderColumnIndex = $request['order'][0]['column'];
        $orderColumn = $columns[$orderColumnIndex];
        $orderDir = $request['order'][0]['dir'];

        $orderQuery = " ORDER BY $orderColumn $orderDir";

        /* ================= PAGINATION ================= */
        $limitQuery = " LIMIT " . intval($request['start']) . ", " . intval($request['length']);

        /* ================= DATA QUERY ================= */
        $this->db->query(
            "SELECT r.id_rombel, r.nama_rombel, r.tingkat,
                j.jurusan as konsentrasi_keahlian,
                g.nama_guru as wali_kelas,
                (SELECT COUNT(id_induk)
                 FROM data_induk
                 WHERE rombel = r.id_rombel AND id_status = 1) as jumlah_anggota
         " . $baseQuery . $whereQuery . $orderQuery . $limitQuery
        );

        if (!empty($namaRombel)) {
            $this->db->bind('nama_rombel', $namaRombel);
        }
        if (!empty($jurusan)) {
            $this->db->bind('jurusan', $jurusan);
        }
        if (!empty($tingkat)) {
            $this->db->bind('tingkat', $tingkat);
        }
        if (!empty($request['search']['value'])) {
            $this->db->bind('search', '%' . $request['search']['value'] . '%');
        }

        $data = $this->db->resultSet();

        return [
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($totalRows),
            "recordsFiltered" => intval($filteredRows),
            "data" => $data
        ];
    }


    public function getRombelById($id)
    {
        $this->db->query("SELECT * FROM rombel WHERE id_rombel = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
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

    public function tambahDataRombel($data)
    {
        $query = "INSERT INTO rombel (nama_rombel, tingkat, id_jurusan, id_walas) VALUES (:nama_rombel, :tingkat, :id_jurusan, :id_walas)";
        $this->db->query($query);
        $this->db->bind('nama_rombel', $data['nama_rombel']);
        $this->db->bind('tingkat', $data['tingkat']);
        $this->db->bind('id_jurusan', $data['jurusan']);
        $this->db->bind('id_walas', $data['wali_kelas']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateDataRombel($data)
    {
        $query = "UPDATE rombel SET nama_rombel = :nama_rombel, tingkat = :tingkat, id_jurusan = :id_jurusan, id_walas = :id_walas WHERE id_rombel = :id_rombel";
        $this->db->query($query);
        $this->db->bind('nama_rombel', $data['nama_rombel']);
        $this->db->bind('tingkat', $data['tingkat']);
        $this->db->bind('id_jurusan', $data['jurusan']);
        $this->db->bind('id_walas', $data['wali_kelas']);
        $this->db->bind('id_rombel', $data['id_rombel']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusDataRombel($id)
    {
        $query = "DELETE FROM rombel WHERE id_rombel = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // di file app/models/Rombel_model.php

    public function getRombelByIdWithDetails($id)
    {
        $this->db->query("SELECT r.*, g.nama_guru 
                          FROM rombel r
                          LEFT JOIN guru g ON r.id_walas = g.id_guru 
                          WHERE r.id_rombel = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getAllRombel()
    {
        $this->db->query("SELECT * FROM rombel ORDER BY nama_rombel ASC");
        return $this->db->resultSet();
    }

    public function getActiveTahunPelajaran()
    {
        // Sesuaikan query ini dengan struktur tabel tahun pelajaran Anda
        $this->db->query("SELECT tp FROM tp WHERE status = 'Aktif' LIMIT 1");
        return $this->db->single();
    }

    public function getAllRombelWithStudentCounts()
    {
        // Query ini mengambil data rombel, jurusan, dan menghitung jumlah L/P
        // Siswa dengan status 'Aktif' (id_status = 1) yang dihitung
        // Rombel dengan 0 siswa tidak akan ditampilkan
        $query = "SELECT 
                    r.id_rombel, 
                    r.nama_rombel, 
                    j.jurusan AS nama_jurusan,
                    COUNT(di.id_induk) AS total_siswa,
                    SUM(CASE WHEN di.jenis_kelamin = 'Laki-Laki' THEN 1 ELSE 0 END) AS jumlah_laki,
                    SUM(CASE WHEN di.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END) AS jumlah_perempuan
                  FROM rombel r
                  LEFT JOIN jurusan j ON r.id_jurusan = j.id_jurusan
                  LEFT JOIN data_induk di ON r.id_rombel = di.rombel AND di.id_status = 1
                  GROUP BY r.id_rombel, r.nama_rombel, j.jurusan
                  HAVING total_siswa > 0 -- INI LOGIKA TAMBAHANNYA
                  ORDER BY r.nama_rombel ASC";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    /**
     * Proses Kenaikan Kelas Massal
     */
    public function prosesNaikKelas($dataSiswa, $dariRombel, $keRombel, $tahunAjaran)
    {
        try {
            $this->db->beginTransaction();

            foreach ($dataSiswa as $id_siswa) {
                // 1. Update rombel di tabel data_induk
                $this->db->query("UPDATE data_induk SET rombel = :ke_rombel WHERE id_induk = :id_siswa");
                $this->db->bind(':ke_rombel', $keRombel);
                $this->db->bind(':id_siswa', $id_siswa);
                $this->db->execute();

                // 2. Catat jejak di tabel history_rombel
                $this->db->query("INSERT INTO history_rombel (id_siswa, dari_rombel, ke_rombel, tahun_ajaran, status, created_at) 
                                  VALUES (:id_siswa, :dari_rombel, :ke_rombel, :tahun_ajaran, 'Naik', NOW())");
                $this->db->bind(':id_siswa', $id_siswa);
                $this->db->bind(':dari_rombel', $dariRombel);
                $this->db->bind(':ke_rombel', $keRombel);
                $this->db->bind(':tahun_ajaran', $tahunAjaran);
                $this->db->execute();
            }

            $this->db->commit();
            return true; // Berhasil
        } catch (Exception $e) {
            $this->db->rollBack();
            return false; // Gagal
        }
    }

    /**
     * Proses Kelulusan Massal
     */
    public function prosesLulus($dataSiswa, $dariRombel, $tahunAjaran)
    {
        try {
            $this->db->beginTransaction();

            foreach ($dataSiswa as $id_siswa) {
                // 1. Update status siswa menjadi 4 (Lulus). Rombel bisa dibiarkan atau di-set NULL agar tidak muncul di kelas aktif.
                $this->db->query("UPDATE data_induk SET id_status = 4, rombel = NULL WHERE id_induk = :id_siswa");
                $this->db->bind(':id_siswa', $id_siswa);
                $this->db->execute();

                // 2. Catat jejak kelulusan di tabel history_rombel (ke_rombel dibiarkan NULL)
                $this->db->query("INSERT INTO history_rombel (id_siswa, dari_rombel, ke_rombel, tahun_ajaran, status, created_at) 
                                  VALUES (:id_siswa, :dari_rombel, NULL, :tahun_ajaran, 'Lulus', NOW())");
                $this->db->bind(':id_siswa', $id_siswa);
                $this->db->bind(':dari_rombel', $dariRombel);
                $this->db->bind(':tahun_ajaran', $tahunAjaran);
                $this->db->execute();
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getHistoryRombel()
    {
        // JOIN 2 kali ke tabel rombel: r1 untuk kelas asal, r2 untuk kelas tujuan
        $query = "SELECT h.*, 
                         di.nama_siswa, di.no_induk, 
                         r1.nama_rombel AS kelas_asal, 
                         r2.nama_rombel AS kelas_tujuan
                  FROM history_rombel h
                  LEFT JOIN data_induk di ON h.id_siswa = di.id_induk
                  LEFT JOIN rombel r1 ON h.dari_rombel = r1.id_rombel
                  LEFT JOIN rombel r2 ON h.ke_rombel = r2.id_rombel
                  ORDER BY h.created_at DESC"; // Urutkan dari yang terbaru

        $this->db->query($query);
        return $this->db->resultSet();
    }
}
