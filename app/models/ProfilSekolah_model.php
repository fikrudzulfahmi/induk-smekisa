<?php

class ProfilSekolah_model
{
    private $table = 'profil_sekolah';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Mengambil data profil sekolah.
     * @return object|false Data profil atau false.
     */
    public function getProfil()
    {
        // Query biasa tanpa JOIN versi
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = 1 LIMIT 1");
        return $this->db->single();
    }

    /**
     * Memperbarui data profil sekolah.
     * @param array $data Data dari form update.
     * @return int Jumlah baris yang terpengaruh.
     */
    public function updateProfil($data)
    {
        $query = "UPDATE " . $this->table . " SET 
                    nama_sekolah = :nama_sekolah,
                    npsn = :npsn,
                    nss = :nss,
                    alamat = :alamat,
                    kode_pos = :kode_pos,
                    telepon = :telepon,
                    kelurahan = :kelurahan,
                    kecamatan = :kecamatan,
                    kota = :kota,
                    provinsi = :provinsi,
                    website = :website,
                    email = :email,
                    nama_kepsek = :nama_kepsek,
                    nip_kepsek = :nip_kepsek,
                    versi_erapor = :versi_erapor, -- Update kolom teks versi
                    logo_sekolah = :logo_sekolah ,
                    token = :token
                  WHERE id = 1";

        $this->db->query($query);

        // Bind semua data
        $this->db->bind('nama_sekolah', $data['nama_sekolah'] ?? null);
        $this->db->bind('npsn', $data['npsn'] ?? null);
        $this->db->bind('nss', $data['nss'] ?? null);
        $this->db->bind('alamat', $data['alamat'] ?? null);
        $this->db->bind('kode_pos', $data['kode_pos'] ?? null);
        $this->db->bind('telepon', $data['telepon'] ?? null);
        $this->db->bind('kelurahan', $data['kelurahan'] ?? null);
        $this->db->bind('kecamatan', $data['kecamatan'] ?? null);
        $this->db->bind('kota', $data['kota'] ?? null);
        $this->db->bind('provinsi', $data['provinsi'] ?? null);
        $this->db->bind('website', $data['website'] ?? null);
        $this->db->bind('email', $data['email'] ?? null);
        $this->db->bind('nama_kepsek', $data['nama_kepsek'] ?? null);
        $this->db->bind('nip_kepsek', $data['nip_kepsek'] ?? null);
        $this->db->bind('versi_erapor', $data['versi_erapor'] ?? 'v?.?.?'); // Bind versi sebagai teks
        $this->db->bind('logo_sekolah', $data['logo_sekolah'] ?? null);
        $this->db->bind('token', $data['token'] ?? null);

        $this->db->execute();
        return $this->db->rowCount();
    }
}
