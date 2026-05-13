<?php
declare(strict_types=1);

class Beasiswa
{
    private mysqli $db;

    private const STATUS_SQL = "CASE
        WHEN tgl_buka > CURDATE() THEN 'belum_dibuka'
        WHEN tgl_tutup < CURDATE() THEN 'ditutup'
        ELSE 'dibuka'
    END AS status_pendaftaran_computed";

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $sql = "SELECT b.*, " . self::STATUS_SQL . ",
                COALESCE(m.nama_mitra, a.nama_admin) AS nama_uploader,
                (SELECT GROUP_CONCAT(t.nama_tag SEPARATOR ', ') FROM beasiswa_tag bt JOIN tag t ON bt.id_tag = t.id_tag WHERE bt.id_beasiswa = b.id_beasiswa) AS tag_names
                FROM beasiswa b
                LEFT JOIN mitra m ON b.id_mitra = m.id_mitra
                LEFT JOIN admin a ON b.id_admin = a.id_admin
                ORDER BY b.upload_at DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getVerified(): array
    {
        $sql = "SELECT b.*, " . self::STATUS_SQL . ",
                COALESCE(m.nama_mitra, a.nama_admin) AS nama_uploader,
                (SELECT GROUP_CONCAT(t.nama_tag SEPARATOR ', ') FROM beasiswa_tag bt JOIN tag t ON bt.id_tag = t.id_tag WHERE bt.id_beasiswa = b.id_beasiswa) AS tag_names
                FROM beasiswa b
                LEFT JOIN mitra m ON b.id_mitra = m.id_mitra
                LEFT JOIN admin a ON b.id_admin = a.id_admin
                WHERE b.status_verifikasi = 'terverifikasi'
                ORDER BY b.upload_at DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): array|false
    {
        $sql = "SELECT b.*, " . self::STATUS_SQL . ",
                COALESCE(m.nama_mitra, a.nama_admin) AS nama_uploader,
                (SELECT GROUP_CONCAT(t.nama_tag SEPARATOR ', ') FROM beasiswa_tag bt JOIN tag t ON bt.id_tag = t.id_tag WHERE bt.id_beasiswa = b.id_beasiswa) AS tag_names
                FROM beasiswa b
                LEFT JOIN mitra m ON b.id_mitra = m.id_mitra
                LEFT JOIN admin a ON b.id_admin = a.id_admin
                WHERE b.id_beasiswa = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: false;
    }

    public function getByMitra(int $id_mitra): array
    {
        $sql = "SELECT b.*, " . self::STATUS_SQL . ",
                (SELECT GROUP_CONCAT(t.nama_tag SEPARATOR ', ') FROM beasiswa_tag bt JOIN tag t ON bt.id_tag = t.id_tag WHERE bt.id_beasiswa = b.id_beasiswa) AS tag_names
                FROM beasiswa b WHERE b.id_mitra = ? ORDER BY b.upload_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id_mitra);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getPendingVerifikasi(): array
    {
        $sql = "SELECT b.*, " . self::STATUS_SQL . ",
                COALESCE(m.nama_mitra, a.nama_admin) AS nama_uploader,
                (SELECT GROUP_CONCAT(t.nama_tag SEPARATOR ', ') FROM beasiswa_tag bt JOIN tag t ON bt.id_tag = t.id_tag WHERE bt.id_beasiswa = b.id_beasiswa) AS tag_names
                FROM beasiswa b
                LEFT JOIN mitra m ON b.id_mitra = m.id_mitra
                LEFT JOIN admin a ON b.id_admin = a.id_admin
                WHERE b.status_verifikasi = 'pending'
                ORDER BY b.upload_at ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insert(array $data): int|false
    {
        $stmt = $this->db->prepare(
            "INSERT INTO beasiswa (nama_beasiswa, nama_penyelenggara, deskripsi_singkat, deskripsi_lengkap,
             informasi_beasiswa, link_pendaftaran, poster_url, tgl_buka, tgl_tutup,
             status_pendaftaran, status_verifikasi, id_mitra, id_admin)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            'sssssssssssii',
            $data['nama_beasiswa'],
            $data['nama_penyelenggara'],
            $data['deskripsi_singkat'],
            $data['deskripsi_lengkap'],
            $data['informasi_beasiswa'],
            $data['link_pendaftaran'],
            $data['poster_url'],
            $data['tgl_buka'],
            $data['tgl_tutup'],
            $data['status_pendaftaran'],
            $data['status_verifikasi'],
            $data['id_mitra'],
            $data['id_admin']
        );

        if ($stmt->execute()) {
            $id = (int) $this->db->insert_id;
            $stmt->close();
            return $id;
        }

        $stmt->close();
        return false;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE beasiswa SET nama_beasiswa = ?, nama_penyelenggara = ?, deskripsi_singkat = ?,
             deskripsi_lengkap = ?, informasi_beasiswa = ?, link_pendaftaran = ?, poster_url = ?,
             tgl_buka = ?, tgl_tutup = ?, status_pendaftaran = ?
             WHERE id_beasiswa = ?"
        );

        $stmt->bind_param(
            'ssssssssssi',
            $data['nama_beasiswa'],
            $data['nama_penyelenggara'],
            $data['deskripsi_singkat'],
            $data['deskripsi_lengkap'],
            $data['informasi_beasiswa'],
            $data['link_pendaftaran'],
            $data['poster_url'],
            $data['tgl_buka'],
            $data['tgl_tutup'],
            $data['status_pendaftaran'],
            $id
        );

        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM beasiswa WHERE id_beasiswa = ?");
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function updateStatusVerifikasi(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE beasiswa SET status_verifikasi = ? WHERE id_beasiswa = ?");
        $stmt->bind_param('si', $status, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function search(string $keyword): array
    {
        $like = '%' . $keyword . '%';
        $sql = "SELECT b.*, " . self::STATUS_SQL . ",
                COALESCE(m.nama_mitra, a.nama_admin) AS nama_uploader,
                (SELECT GROUP_CONCAT(t.nama_tag SEPARATOR ', ') FROM beasiswa_tag bt JOIN tag t ON bt.id_tag = t.id_tag WHERE bt.id_beasiswa = b.id_beasiswa) AS tag_names
                FROM beasiswa b
                LEFT JOIN mitra m ON b.id_mitra = m.id_mitra
                LEFT JOIN admin a ON b.id_admin = a.id_admin
                WHERE b.status_verifikasi = 'terverifikasi'
                AND (b.nama_beasiswa LIKE ? OR b.nama_penyelenggara LIKE ?)
                ORDER BY b.upload_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss', $like, $like);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function filterByTag(int $id_tag): array
    {
        $sql = "SELECT b.*, " . self::STATUS_SQL . ",
                COALESCE(m.nama_mitra, a.nama_admin) AS nama_uploader,
                (SELECT GROUP_CONCAT(t.nama_tag SEPARATOR ', ') FROM beasiswa_tag bt JOIN tag t ON bt.id_tag = t.id_tag WHERE bt.id_beasiswa = b.id_beasiswa) AS tag_names
                FROM beasiswa b
                JOIN beasiswa_tag bt ON b.id_beasiswa = bt.id_beasiswa
                LEFT JOIN mitra m ON b.id_mitra = m.id_mitra
                LEFT JOIN admin a ON b.id_admin = a.id_admin
                WHERE bt.id_tag = ? AND b.status_verifikasi = 'terverifikasi'
                ORDER BY b.upload_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id_tag);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function filterByStatusPendaftaran(string $status): array
    {
        $sql = "SELECT b.*, " . self::STATUS_SQL . ",
                COALESCE(m.nama_mitra, a.nama_admin) AS nama_uploader,
                (SELECT GROUP_CONCAT(t.nama_tag SEPARATOR ', ') FROM beasiswa_tag bt JOIN tag t ON bt.id_tag = t.id_tag WHERE bt.id_beasiswa = b.id_beasiswa) AS tag_names
                FROM beasiswa b
                LEFT JOIN mitra m ON b.id_mitra = m.id_mitra
                LEFT JOIN admin a ON b.id_admin = a.id_admin
                WHERE b.status_verifikasi = 'terverifikasi'
                HAVING status_pendaftaran_computed = ?
                ORDER BY b.upload_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $status);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getWithTags(int $id): array|false
    {
        $beasiswa = $this->getById($id);
        if (!$beasiswa) {
            return false;
        }

        $stmt = $this->db->prepare(
            "SELECT t.* FROM tag t
             JOIN beasiswa_tag bt ON t.id_tag = bt.id_tag
             WHERE bt.id_beasiswa = ?
             ORDER BY t.kategori_tag ASC, t.nama_tag ASC"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $beasiswa['tags'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $beasiswa;
    }
    public function searchAdvanced(array $filters, int $page = 1, int $perPage = 6): array
    {
        $sql = "SELECT b.*, " . self::STATUS_SQL . ",
                COALESCE(m.nama_mitra, a.nama_admin) AS nama_uploader,
                (SELECT GROUP_CONCAT(t.nama_tag SEPARATOR ', ') FROM beasiswa_tag bt JOIN tag t ON bt.id_tag = t.id_tag WHERE bt.id_beasiswa = b.id_beasiswa) AS tag_names
                FROM beasiswa b
                LEFT JOIN mitra m ON b.id_mitra = m.id_mitra
                LEFT JOIN admin a ON b.id_admin = a.id_admin
                WHERE b.status_verifikasi = 'terverifikasi'";

        $params = [];
        $types = '';

        if (!empty($filters['q'])) {
            $sql .= " AND (b.nama_beasiswa LIKE ? OR b.nama_penyelenggara LIKE ? OR b.deskripsi_singkat LIKE ?)";
            $q = '%' . $filters['q'] . '%';
            $params[] = $q;
            $params[] = $q;
            $params[] = $q;
            $types .= 'sss';
        }

        if (!empty($filters['year'])) {
            $sql .= " AND YEAR(b.tgl_buka) = ?";
            $params[] = $filters['year'];
            $types .= 'i';
        }

        if (!empty($filters['month'])) {
            $sql .= " AND MONTH(b.tgl_buka) = ?";
            $params[] = $filters['month'];
            $types .= 'i';
        }

        if (!empty($filters['tags']) && is_array($filters['tags'])) {
            $tagCount = count($filters['tags']);
            $placeholders = implode(',', array_fill(0, $tagCount, '?'));
            $sql .= " AND (SELECT COUNT(DISTINCT id_tag) FROM beasiswa_tag WHERE id_beasiswa = b.id_beasiswa AND id_tag IN ($placeholders)) = ?";
            foreach ($filters['tags'] as $tag) {
                $params[] = (int) $tag;
                $types .= 'i';
            }
            $params[] = $tagCount;
            $types .= 'i';
        }

        $countSql = "SELECT COUNT(*) as total FROM ($sql) AS count_table";
        if (!empty($params)) {
            $stmtCount = $this->db->prepare($countSql);
            $stmtCount->bind_param($types, ...$params);
            $stmtCount->execute();
            $totalRows = (int) $stmtCount->get_result()->fetch_assoc()['total'];
            $stmtCount->close();
        } else {
            $totalRows = (int) $this->db->query($countSql)->fetch_assoc()['total'];
        }

        $totalPages = $totalRows > 0 ? (int) ceil($totalRows / $perPage) : 1;
        $offset = ($page - 1) * $perPage;

        $sql .= " ORDER BY b.upload_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        $types .= 'ii';

        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return [
            'data' => $data,
            'total_pages' => $totalPages,
            'current_page' => $page,
            'total_rows' => $totalRows
        ];
    }
}
