<?php

class Auth_model
{
    private $table = 'data_induk';
    private $db;

    public function __construct()
    {
        // Sesuaikan dengan inisiasi class Database di MVC Anda
        $this->db = new Database();
    }

    public function cekLoginSiswa($nis, $tgl_lhr)
    {
        // Query mencocokkan NIS dan Tanggal Lahir
        $this->db->query("SELECT * FROM " . $this->table . " WHERE no_induk = :no_induk AND tgl_lhr = :tgl_lhr");

        $this->db->bind('no_induk', $nis);
        $this->db->bind('tgl_lhr', $tgl_lhr);

        return $this->db->single();
    }
}
