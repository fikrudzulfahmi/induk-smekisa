<?php

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $db_name = DB_NAME;

    private $dbh; // Database Handler
    private $stmt; // Statement

    public function __construct()
    {
        // Data Source Name
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;

        $option = [
            PDO::ATTR_PERSISTENT => true, // Menjaga koneksi database agar terus terbuka
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // Mode error
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $option);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // Method untuk menjalankan query yang sudah disiapkan
    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    // Method untuk binding data (untuk WHERE, SET, dll) agar aman
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Eksekusi statement yang sudah di-prepare
    public function execute()
    {
        $this->stmt->execute();
    }

    // Ambil semua hasil query sebagai array object
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Ambil satu hasil query
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Menghitung berapa baris data yang berubah di database
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        // Memanggil method lastInsertId() dari objek koneksi PDO ($this->dbh)
        return $this->dbh->lastInsertId();
    }

    // --- TAMBAHKAN 3 METHOD INI UNTUK TRANSAKSI ---

    public function beginTransaction()
    {
        // Sesuaikan $this->dbh dengan nama variabel PDO (koneksi) di file Database.php Anda. 
        // Terkadang namanya $this->conn atau $this->pdo
        return $this->dbh->beginTransaction();
    }

    public function commit()
    {
        return $this->dbh->commit();
    }

    public function rollBack()
    {
        return $this->dbh->rollBack();
    }
}
