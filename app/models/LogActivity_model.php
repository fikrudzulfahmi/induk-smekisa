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

    // (Opsional) Method untuk menampilkan log di halaman admin nantinya
    public function getAllLogs()
    {
        $this->db->query("SELECT * FROM " . $this->table . " ORDER BY created_at DESC");
        return $this->db->resultSet();
    }
}
