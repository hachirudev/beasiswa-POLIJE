<?php
declare(strict_types=1);

class Faq
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $result = $this->db->query("SELECT * FROM faq ORDER BY created_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM faq WHERE id_pertanyaan = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ?: false;
    }

    public function insert(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO faq (id_admin, pertanyaan, jawaban) VALUES (?, ?, ?)"
        );
        $stmt->bind_param(
            'iss',
            $data['id_admin'],
            $data['pertanyaan'],
            $data['jawaban']
        );
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE faq SET pertanyaan = ?, jawaban = ? WHERE id_pertanyaan = ?"
        );
        $stmt->bind_param(
            'ssi',
            $data['pertanyaan'],
            $data['jawaban'],
            $id
        );
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM faq WHERE id_pertanyaan = ?");
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
