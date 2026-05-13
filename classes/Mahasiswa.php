<?php
declare(strict_types=1);

require_once __DIR__ . '/User.php';

class Mahasiswa extends User
{
    public function login(string $email, string $password): array|false
    {
        $user = $this->findByEmail('mahasiswa', $email);

        if ($user && $this->verifyPassword($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }

        return false;
    }

    public function register(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO mahasiswa (NIM, nama, id_prodi, semester, angkatan, IPK, jenis_kelamin, email, password)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $hashedPassword = $this->hashPassword($data['password']);

        $stmt->bind_param(
            'ssiiidsss',
            $data['NIM'],
            $data['nama'],
            $data['id_prodi'],
            $data['semester'],
            $data['angkatan'],
            $data['IPK'],
            $data['jenis_kelamin'],
            $data['email'],
            $hashedPassword
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM mahasiswa WHERE id_mahasiswa = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row ?: false;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE mahasiswa SET nama = ?, id_prodi = ?, semester = ?, angkatan = ?, IPK = ?, jenis_kelamin = ?, email = ?
             WHERE id_mahasiswa = ?"
        );

        $stmt->bind_param(
            'siiidssi',
            $data['nama'],
            $data['id_prodi'],
            $data['semester'],
            $data['angkatan'],
            $data['IPK'],
            $data['jenis_kelamin'],
            $data['email'],
            $id
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function getAll(): array
    {
        $result = $this->db->query("SELECT * FROM mahasiswa ORDER BY nama ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllWithProdi(): array
    {
        $result = $this->db->query(
            "SELECT m.*, p.nama_prodi, p.nama_jurusan
             FROM mahasiswa m
             JOIN prodi p ON m.id_prodi = p.id_prodi
             ORDER BY m.nama ASC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findByNIM(string $nim): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM mahasiswa WHERE NIM = ?");
        $stmt->bind_param('s', $nim);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row ?: false;
    }
}
