<?php
declare(strict_types=1);

class HasilSimulasi
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function getBySimulasi(int $id_simulasi): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM hasil_simulasi WHERE id_simulasi = ?");
        $stmt->bind_param('i', $id_simulasi);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ?: false;
    }

    public function insert(int $id_simulasi): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO hasil_simulasi (id_simulasi, status_simulasi, is_read) VALUES (?, 'pending', FALSE)"
        );
        $stmt->bind_param('i', $id_simulasi);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function update(int $id_simulasi, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE hasil_simulasi SET skor = ?, catatan_admin = ?, status_simulasi = ?, id_admin = ?, tgl_review = NOW(), is_read = FALSE
             WHERE id_simulasi = ?"
        );
        $stmt->bind_param(
            'dssii',
            $data['skor'],
            $data['catatan_admin'],
            $data['status_simulasi'],
            $data['id_admin'],
            $id_simulasi
        );
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function markAllAsRead(int $id_mahasiswa): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE hasil_simulasi hs
             JOIN simulasi s ON hs.id_simulasi = s.id_simulasi
             SET hs.is_read = TRUE
             WHERE s.id_mahasiswa = ? AND hs.is_read = FALSE"
        );
        $stmt->bind_param('i', $id_mahasiswa);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function countUnread(int $id_mahasiswa): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total FROM hasil_simulasi hs
             JOIN simulasi s ON hs.id_simulasi = s.id_simulasi
             WHERE s.id_mahasiswa = ? AND hs.is_read = FALSE"
        );
        $stmt->bind_param('i', $id_mahasiswa);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return (int) $row['total'];
    }
}
