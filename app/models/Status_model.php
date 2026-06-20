<?php
class Status_model
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllStatus()
    {
        $this->db->query("SELECT * FROM status ORDER BY id_status");
        return $this->db->resultSet();
    }
}
