<?php

class LogActivity_model
{
    private $table = 'activity_logs';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Method untuk menyimpan log baru
    public function insertLog($data)
    {
        $query = "INSERT INTO " . $this->table . " 
                  (user_id, nama_user, role, action, description) 
                  VALUES 
                  (:user_id, :nama_user, :role, :action, :description)";

        $this->db->query($query);

        $this->db->bind('user_id', $data['user_id']);
        $this->db->bind('nama_user', $data['nama_user']);
        $this->db->bind('role', $data['role']);
        $this->db->bind('action', $data['action']);
        $this->db->bind('description', $data['description']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    // Method komplit Server-Side untuk DataTables (Disesuaikan dengan logic Guru_model Anda)
    public function getDataLogServerSide($request)
    {
        // Kolom yang bisa di-search dan di-sort (sesuai kolom di tabel activity_logs)
        $columns = ['created_at', 'nama_user', 'role', 'action', 'description'];

        // Query dasar
        $baseQuery = "FROM " . $this->table;
        $selectColumns = "SELECT * ";

        // Filter/Search
        $searchQuery = "";
        $searchValue = '';
        if (isset($request['search']['value']) && $request['search']['value'] != '') {
            $searchValue = $request['search']['value'];
            $searchQuery = " WHERE (";
            for ($i = 0; $i < count($columns); $i++) {
                if (preg_match('/^[a-zA-Z0-9_]+$/', $columns[$i])) {
                    $searchQuery .= $columns[$i] . " LIKE :searchValue";
                    if ($i < count($columns) - 1) {
                        $searchQuery .= " OR ";
                    }
                }
            }
            $searchQuery .= ")";
        }

        // Hitung total baris SETELAH difilter
        $countQueryFiltered = "SELECT COUNT(*) as total " . $baseQuery . $searchQuery;
        $this->db->query($countQueryFiltered);
        if (!empty($searchValue)) {
            $this->db->bind(':searchValue', '%' . $searchValue . '%');
        }
        $filteredRows = $this->db->single()->total;

        // Sorting
        $orderQuery = "";
        if (isset($request['order']) && count($request['order'])) {
            $orderColumnIndex = intval($request['order'][0]['column']);
            if ($orderColumnIndex >= 0 && $orderColumnIndex < count($columns)) {
                $orderColumn = $columns[$orderColumnIndex];
                if (preg_match('/^[a-zA-Z0-9_]+$/', $orderColumn)) {
                    $orderDir = ($request['order'][0]['dir'] === 'asc') ? 'ASC' : 'DESC';
                    $orderQuery = " ORDER BY " . $orderColumn . " " . $orderDir;
                }
            }
        }
        if (empty($orderQuery)) {
            $orderQuery = " ORDER BY created_at DESC"; // Default log terbaru di atas
        }

        // Pagination
        $limitQuery = "";
        if (isset($request['start']) && $request['length'] != -1) {
            $start = intval($request['start']);
            $length = intval($request['length']);
            $limitQuery = " LIMIT " . $start . ", " . $length;
        }

        // Query final untuk mengambil data log
        $finalQuery = $selectColumns . $baseQuery . $searchQuery . $orderQuery . $limitQuery;
        $this->db->query($finalQuery);
        if (!empty($searchValue)) {
            $this->db->bind(':searchValue', '%' . $searchValue . '%');
        }
        $data = $this->db->resultSet();

        // Total baris (tanpa filter sama sekali)
        $countQueryTotal = "SELECT COUNT(*) as total " . $baseQuery;
        $this->db->query($countQueryTotal);
        $totalRows = $this->db->single()->total;

        // Format output JSON DataTables
        return [
            "draw"            => intval($request['draw']),
            "recordsTotal"    => intval($totalRows),
            "recordsFiltered" => intval($filteredRows),
            "data"            => $data
        ];
    }
}
