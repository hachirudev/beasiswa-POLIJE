<?php
declare(strict_types=1);

class FileSimulasi
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function getBySimulasi(int $id_simulasi): array
    {
        $stmt = $this->db->prepare("SELECT * FROM file_simulasi WHERE id_simulasi = ? ORDER BY upload_at ASC");
        $stmt->bind_param('i', $id_simulasi);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rows;
    }

    public function insert(int $id_simulasi, string $nama_file, string $file_path): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO file_simulasi (id_simulasi, nama_file, file_path) VALUES (?, ?, ?)"
        );
        $stmt->bind_param('iss', $id_simulasi, $nama_file, $file_path);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM file_simulasi WHERE id_file = ?");
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
