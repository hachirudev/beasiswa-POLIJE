<?php
declare(strict_types=1);

class Database
{
    private static ?Database $instance = null;
    private mysqli $conn;

    private string $host = 'sql301.infinityfree.com';
    private string $user = 'if0_41934259';
    private string $pass = 'qpA2gDl1mdOU2i';
    private string $db = 'if0_41934259_db_beasiswa';

    private function __construct()
    {
        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->db
        );

        if ($this->conn->connect_error) {
            die('Koneksi database gagal: ' . $this->conn->connect_error);
        }

        $this->conn->set_charset('utf8mb4');
    }

    public static function getInstance(): static
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function getConnection(): mysqli
    {
        return $this->conn;
    }

    private function __clone()
    {
    }
    public function __wakeup(): void
    {
    }
}