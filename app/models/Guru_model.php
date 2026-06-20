<?php

class Guru_model
{
    private $table = 'guru';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function tambahDataGuru($data)
    {
        $query = "INSERT INTO guru (nama_guru, nik, nuptk, alamat, no_hp_guru, username, password) 
              VALUES (:nama_guru, :nik, :nuptk, :alamat, :no_hp_guru, :username, :password)";

        $this->db->query($query);
        $this->db->bind('nama_guru', $data['nama_guru']);
        $this->db->bind('nik', $data['nik']);
        $this->db->bind('nuptk', $data['nuptk']);
        $this->db->bind('alamat', $data['alamat']);
        $this->db->bind('no_hp_guru', $data['no_hp_guru']);
        $this->db->bind('username', $data['username']);

        // Hash password sebelum disimpan
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->db->bind('password', $hashedPassword);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getGuruByUsername($username)
    {
        $this->db->query('SELECT * FROM guru WHERE username=:username');
        $this->db->bind('username', $username);
        return $this->db->single();
    }

    public function getLevelsForGuru($guruId)
    {
        $this->db->query("SELECT l.level FROM guru_level gl
                          JOIN level l ON gl.level_id = l.id_level
                          WHERE gl.guru_id = :guru_id");
        $this->db->bind('guru_id', $guruId);

        $rows = $this->db->resultSet();
        $levels = [];
        foreach ($rows as $row) {
            $levels[] = $row->level;
        }
        return $levels; // akan menghasilkan: ['guru', 'walas']
    }

    // di file app/models/Guru_model.php
    public function createAuthToken($selector, $hashedValidator, $userId)
    {
        $expires = date('Y-m-d H:i:s', time() + 86400 * 30); // Token berlaku 30 hari

        $query = "INSERT INTO auth_tokens (selector, hashed_validator, user_id, expires)
              VALUES (:selector, :hashed_validator, :user_id, :expires)";

        $this->db->query($query);
        $this->db->bind('selector', $selector);
        $this->db->bind('hashed_validator', $hashedValidator);
        $this->db->bind('user_id', $userId);
        $this->db->bind('expires', $expires);

        $this->db->execute();
        return $this->db->rowCount();
    }
    public function deleteAuthToken($selector)
    {
        $query = "DELETE FROM auth_tokens WHERE selector = :selector";
        $this->db->query($query);
        $this->db->bind('selector', $selector);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getGuruById($id)
    {
        $this->db->query("SELECT * FROM guru WHERE id_guru = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    // di file app/models/Guru_model.php

    public function getDataGuruServerSide($request)
    {
        // Kolom yang bisa di-search dan di-sort (dari tabel guru)
        $columns = ['nik', 'nuptk', 'nama_guru', 'username', 'alamat'];

        // Query dasar (hanya dari tabel guru)
        $baseQuery = "FROM guru";
        $selectColumns = "SELECT * "; // Ambil semua kolom guru

        // Filter/Search (sama seperti sebelumnya)
        $searchQuery = "";
        if (isset($request['search']['value']) && $request['search']['value'] != '') {
            $searchValue = $request['search']['value'];
            $searchQuery = " WHERE (";
            for ($i = 0; $i < count($columns); $i++) {
                // Penting: Pastikan $columns[$i] hanya berisi nama kolom yg valid
                if (preg_match('/^[a-zA-Z0-9_]+$/', $columns[$i])) {
                    $searchQuery .= $columns[$i] . " LIKE :searchValue"; // Gunakan prepared statement
                    if ($i < count($columns) - 1) {
                        $searchQuery .= " OR ";
                    }
                }
            }
            $searchQuery .= ")";
        }

        // Query untuk menghitung total baris SETELAH difilter
        $countQueryFiltered = "SELECT COUNT(*) as total " . $baseQuery . $searchQuery;
        $this->db->query($countQueryFiltered);
        if (!empty($searchValue)) {
            $this->db->bind(':searchValue', '%' . $searchValue . '%');
        }
        $filteredRows = $this->db->single()->total;


        // Sorting (sama seperti sebelumnya, pastikan $columns valid)
        $orderQuery = "";
        if (isset($request['order']) && count($request['order'])) {
            $orderColumnIndex = intval($request['order'][0]['column']); // Konversi ke integer
            // Kolom 0='No', 1='nik', dst. Sesuaikan index dengan $columns
            if ($orderColumnIndex > 0 && $orderColumnIndex <= count($columns)) {
                $orderColumn = $columns[$orderColumnIndex - 1];
                // Validasi nama kolom lagi sebelum dimasukkan ke query
                if (preg_match('/^[a-zA-Z0-9_]+$/', $orderColumn)) {
                    $orderDir = ($request['order'][0]['dir'] === 'asc') ? 'ASC' : 'DESC'; // Validasi direction
                    $orderQuery = " ORDER BY " . $orderColumn . " " . $orderDir;
                }
            }
        }
        if (empty($orderQuery)) {
            $orderQuery = " ORDER BY id_guru ASC"; // Default order
        }


        // Pagination (sama seperti sebelumnya)
        $limitQuery = "";
        if (isset($request['start']) && $request['length'] != -1) {
            $start = intval($request['start']); // Pastikan integer
            $length = intval($request['length']); // Pastikan integer
            $limitQuery = " LIMIT " . $start . ", " . $length;
        }

        // Query final untuk mengambil data guru
        $finalQuery = $selectColumns . $baseQuery . $searchQuery . $orderQuery . $limitQuery;
        $this->db->query($finalQuery);
        // Bind search value lagi untuk query final
        if (!empty($searchValue)) {
            $this->db->bind(':searchValue', '%' . $searchValue . '%');
        }
        $data = $this->db->resultSet(); // Hasil data guru untuk halaman ini

        // --- TAMBAHAN: Ambil dan Gabungkan Level ---
        if ($data) {
            foreach ($data as $guru) {
                // MEMANGGIL method getLevelsForGuru untuk setiap guru
                $levelsArray = $this->getLevelsForGuru($guru->id_guru);
                // Menggabungkan nama level menjadi string
                $guru->level_guru = !empty($levelsArray) ? implode(', ', $levelsArray) : '-';
            }
        }

        // Total baris (tanpa filter sama sekali)
        $countQueryTotal = "SELECT COUNT(*) as total " . $baseQuery;
        $this->db->query($countQueryTotal);
        $totalRows = $this->db->single()->total;

        // Format output (sama seperti sebelumnya)
        $output = [
            "draw"            => intval($request['draw']),
            "recordsTotal"    => intval($totalRows),
            "recordsFiltered" => intval($filteredRows),
            "data"            => $data // $data sekarang sudah punya properti 'level_guru'
        ];

        return $output;
    }

    public function getAllLevels()
    {
        $this->db->query("SELECT * FROM level ORDER BY level ASC");
        return $this->db->resultSet();
    }

    // --- METHOD BARU: Mengambil ID level yang dimiliki guru ---
    public function getLevelIdsForGuru($guruId)
    {
        $this->db->query("SELECT level_id FROM guru_level WHERE guru_id = :guru_id");
        $this->db->bind('guru_id', $guruId);
        $rows = $this->db->resultSet();
        $levelIds = [];
        foreach ($rows as $row) {
            $levelIds[] = $row->level_id; // Kumpulkan hanya ID-nya
        }
        return $levelIds; // Menghasilkan array [1, 3] misalnya
    }
    public function updateDataGuru($data)
    {
        // 1. Update data dasar guru (sama seperti sebelumnya)
        $passwordUpdateQuery = "";
        if (!empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $passwordUpdateQuery = ", password = :password"; // Tambahkan koma di depan
        }

        $queryGuru = "UPDATE guru SET 
                        nama_guru = :nama_guru,
                        nik = :nik,
                        nuptk = :nuptk,
                        alamat = :alamat,
                        no_hp_guru = :no_hp_guru,
                        username = :username
                        {$passwordUpdateQuery}
                      WHERE id_guru = :id_guru";

        $this->db->query($queryGuru);
        $this->db->bind('nama_guru', $data['nama_guru']);
        $this->db->bind('nik', $data['nik'] ?? null); // Handle jika kosong
        $this->db->bind('nuptk', $data['nuptk'] ?? null);
        $this->db->bind('alamat', $data['alamat'] ?? null);
        $this->db->bind('no_hp_guru', $data['no_hp_guru'] ?? null);
        $this->db->bind('username', $data['username']);
        $this->db->bind('id_guru', $data['id_guru']);
        if (!empty($data['password'])) {
            $this->db->bind('password', $hashedPassword);
        }
        $this->db->execute();
        $guruUpdateSuccess = $this->db->rowCount(); // Simpan hasil update guru

        // 2. Update Level Guru (Hapus yang lama, insert yang baru)
        $id_guru = $data['id_guru'];
        // Ambil level yang dipilih dari form (jika tidak ada, anggap array kosong)
        $selectedLevels = $data['levels'] ?? [];

        // Hapus semua level lama untuk guru ini
        $this->db->query("DELETE FROM guru_level WHERE guru_id = :guru_id");
        $this->db->bind('guru_id', $id_guru);
        $this->db->execute();

        // Jika ada level yang dipilih, insert kembali
        if (!empty($selectedLevels)) {
            // Siapkan query insert (hanya perlu di-prepare sekali)
            $this->db->query("INSERT INTO guru_level (guru_id, level_id) VALUES (:guru_id, :level_id)");

            foreach ($selectedLevels as $level_id) {
                // Bind parameter di dalam loop
                $this->db->bind('guru_id', $id_guru);
                $this->db->bind('level_id', $level_id);
                // Execute untuk setiap level
                $this->db->execute();
            }
        }

        // Kembalikan status update data guru utama (atau bisa diubah logikanya)
        return $guruUpdateSuccess;
        // Note: rowCount() setelah DELETE/INSERT multiple mungkin tidak selalu akurat 
        // untuk menandakan keberhasilan total. Idealnya gunakan transaction.
    }
    // di file app/models/Guru_model.php

    public function hapusDataGuru($id)
    {
        $query = "DELETE FROM guru WHERE id_guru = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount();
    }
    public function hitungJumlahGuru()
    {
        $this->db->query("SELECT COUNT(*) as total FROM guru");
        return $this->db->single()->total;
    }

    public function getAllGuru()
    {
        $this->db->query("SELECT id_guru, nama_guru FROM guru ORDER BY nama_guru ASC");
        return $this->db->resultSet();
    }

    public function getWalas()
    {
        $this->db->query("SELECT id_guru, nama_guru FROM guru ORDER BY nama_guru ASC");
        return $this->db->resultSet();
    }

    // =========================================================
    // UPDATE PROFIL GURU (TANPA MENGUBAH LEVEL AKSES)
    // =========================================================
    public function updateProfilSaya($data)
    {
        $passwordUpdateQuery = "";
        if (!empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $passwordUpdateQuery = ", password = :password";
        }

        $query = "UPDATE guru SET 
                    nama_guru = :nama_guru,
                    nik = :nik,
                    nuptk = :nuptk,
                    alamat = :alamat,
                    no_hp_guru = :no_hp_guru,
                    username = :username
                    {$passwordUpdateQuery}
                  WHERE id_guru = :id_guru";

        $this->db->query($query);
        $this->db->bind('nama_guru', $data['nama_guru']);
        $this->db->bind('nik', $data['nik'] ?? null);
        $this->db->bind('nuptk', $data['nuptk'] ?? null);
        $this->db->bind('alamat', $data['alamat'] ?? null);
        $this->db->bind('no_hp_guru', $data['no_hp_guru'] ?? null);
        $this->db->bind('username', $data['username']);
        $this->db->bind('id_guru', $data['id_guru']);

        if (!empty($data['password'])) {
            $this->db->bind('password', $hashedPassword);
        }

        $this->db->execute();
        return $this->db->rowCount();
    }
}
