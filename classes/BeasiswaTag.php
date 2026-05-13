<?php
declare(strict_types=1);

class BeasiswaTag
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function insertBulk(int $id_beasiswa, array $tag_ids): bool
    {
        if (empty($tag_ids)) {
            return true;
        }

        $placeholders = implode(',', array_fill(0, count($tag_ids), '(?, ?)'));
        $sql = "INSERT INTO beasiswa_tag (id_beasiswa, id_tag) VALUES {$placeholders}";
        $stmt = $this->db->prepare($sql);

        $types = '';
        $values = [];
        foreach ($tag_ids as $id_tag) {
            $types .= 'ii';
            $values[] = $id_beasiswa;
            $values[] = $id_tag;
        }

        $stmt->bind_param($types, ...$values);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function deleteByBeasiswa(int $id_beasiswa): bool
    {
        $stmt = $this->db->prepare("DELETE FROM beasiswa_tag WHERE id_beasiswa = ?");
        $stmt->bind_param('i', $id_beasiswa);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function getByBeasiswa(int $id_beasiswa): array
    {
        $stmt = $this->db->prepare(
            "SELECT t.* FROM tag t
             JOIN beasiswa_tag bt ON t.id_tag = bt.id_tag
             WHERE bt.id_beasiswa = ?
             ORDER BY t.kategori_tag ASC, t.nama_tag ASC"
        );
        $stmt->bind_param('i', $id_beasiswa);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rows;
    }
}
