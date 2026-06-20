<?php

class Jurusan_model
{
    private $table = 'jurusan';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllJurusan()
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY jurusan ASC');
        return $this->db->resultSet();
    }

    public function getJurusanById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id_jurusan = :id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function tambahDataJurusan($data)
    {
        $query = "INSERT INTO jurusan (jurusan) VALUES (:jurusan)";
        $this->db->query($query);
        $this->db->bind('jurusan', $data['jurusan']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateDataJurusan($data)
    {
        $query = "UPDATE jurusan SET jurusan = :jurusan WHERE id_jurusan = :id";
        $this->db->query($query);
        $this->db->bind('jurusan', $data['jurusan']);
        $this->db->bind('id', $data['id_jurusan']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusDataJurusan($id)
    {
        $query = "DELETE FROM jurusan WHERE id_jurusan = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getDataJurusanServerSide($request)
    {
        // Kolom yang akan ditampilkan
        $columns = ['id_jurusan', 'jurusan'];

        // Query dasar
        $baseQuery = "FROM {$this->table}";

        // Filter/Search
        $searchQuery = "";
        if (isset($request['search']['value']) && $request['search']['value'] != '') {
            $searchValue = $request['search']['value'];
            $searchQuery = " WHERE (";
            for ($i = 0; $i < count($columns); $i++) {
                $searchQuery .= $columns[$i] . " LIKE '%" . $searchValue . "%'";
                if ($i < count($columns) - 1) {
                    $searchQuery .= " OR ";
                }
            }
            $searchQuery .= ")";
        }

        // Hitung total baris
        $this->db->query("SELECT COUNT(*) as total " . $baseQuery);
        $totalRows = $this->db->single()->total;

        // Hitung total baris setelah difilter
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
        $this->db->query("SELECT * " . $baseQuery . $searchQuery . $orderQuery . $limitQuery);
        $data = $this->db->resultSet();

        // Format output untuk DataTables
        return [
            "draw"            => intval($request['draw']),
            "recordsTotal"    => intval($totalRows),
            "recordsFiltered" => intval($filteredRows),
            "data"           => $data
        ];
    }
}
