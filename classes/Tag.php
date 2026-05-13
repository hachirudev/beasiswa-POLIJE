<?php
declare(strict_types=1);

class Tag
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    /**
     * Mengambil semua tag dari database
     *
     * @return array
     */
    public function getAll(): array
    {
        $sql = "SELECT * FROM tag ORDER BY kategori_tag ASC, nama_tag ASC";
        $result = $this->db->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }

    public function getByKategori(string $kategori): array
    {
        $sql  = "SELECT * FROM tag WHERE kategori_tag = ? ORDER BY nama_tag ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $kategori);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): array|false
    {
        $sql  = "SELECT * FROM tag WHERE id_tag = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: false;
    }
}
