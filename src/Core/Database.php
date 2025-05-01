<?php

declare(strict_types=1);

class Database
{
    private mysqli $conn;
    public function __construct(
        private string $host,
        private string $user,
        private string $pass,
        private string $db
    ) {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if ($this->conn->connect_error) {
            throw new RuntimeException('db eRROR:' . $this->conn->connect_error);
        }
        $this->conn->set_charset('utf8mb4');
    }

    public function getConnection(): mysqli
    {
        return $this->conn;
    }
}

