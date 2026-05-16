<?php
declare(strict_types=1);

abstract class User
{
    protected int $id;
    protected string $email;
    protected string $password;
    protected string $role;
    protected mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    abstract public function login(string $email, string $password): array|false;

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    protected function findByEmail(string $table, string $email): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$table} WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row ?: false;
    }

    public function updatePassword(string $table, int $id, string $newPassword): bool
    {
        $hashed = $this->hashPassword($newPassword);

        // Tentukan nama kolom primary key berdasarkan tabel
        $pkColumn = 'id_' . $table;

        $stmt = $this->db->prepare("UPDATE {$table} SET password = ? WHERE {$pkColumn} = ?");
        $stmt->bind_param('si', $hashed, $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
