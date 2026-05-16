<?php
declare(strict_types=1);

class Pustaka
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $result = $this->db->query("SELECT * FROM pustaka ORDER BY upload_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM pustaka WHERE id_pustaka = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ?: false;
    }

    public function insert(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO pustaka (id_admin, nama_dokumen, deskripsi_dokumen, preview_dokumen, file_path)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            'issss',
            $data['id_admin'],
            $data['nama_dokumen'],
            $data['deskripsi_dokumen'],
            $data['preview_dokumen'],
            $data['file_path']
        );
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE pustaka SET nama_dokumen = ?, deskripsi_dokumen = ?, preview_dokumen = ?, file_path = ?
             WHERE id_pustaka = ?"
        );
        $stmt->bind_param(
            'ssssi',
            $data['nama_dokumen'],
            $data['deskripsi_dokumen'],
            $data['preview_dokumen'],
            $data['file_path'],
            $id
        );
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM pustaka WHERE id_pustaka = ?");
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
