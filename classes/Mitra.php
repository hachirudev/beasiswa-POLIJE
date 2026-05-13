<?php
declare(strict_types=1);

require_once __DIR__ . '/User.php';

class Mitra extends User
{
    public function login(string $email, string $password): array|false
    {
        $user = $this->findByEmail('mitra', $email);

        if ($user && $this->verifyPassword($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }

        return false;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO mitra (nama_mitra, bidang_usaha, telepon, website, email, password)
             VALUES (?, ?, ?, ?, ?, ?)"
        );

        $hashedPassword = $this->hashPassword($data['password']);

        $stmt->bind_param(
            'ssssss',
            $data['nama_mitra'],
            $data['bidang_usaha'],
            $data['telepon'],
            $data['website'],
            $data['email'],
            $hashedPassword
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM mitra WHERE id_mitra = ?");
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
            "UPDATE mitra SET nama_mitra = ?, bidang_usaha = ?, telepon = ?, website = ?, email = ?
             WHERE id_mitra = ?"
        );

        $stmt->bind_param(
            'sssssi',
            $data['nama_mitra'],
            $data['bidang_usaha'],
            $data['telepon'],
            $data['website'],
            $data['email'],
            $id
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function getAll(): array
    {
        $result = $this->db->query("SELECT * FROM mitra ORDER BY nama_mitra ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM mitra WHERE id_mitra = ?");
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function getDashboardStats(int $id_mitra): array
    {
        $stats = [
            'jumlah_beasiswa'  => 0,
            'beasiswa_pending' => 0,
            'total_simulasi'   => 0,
        ];

        // Jumlah beasiswa milik mitra
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM beasiswa WHERE id_mitra = ?");
        $stmt->bind_param('i', $id_mitra);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stats['jumlah_beasiswa'] = (int) $row['total'];
        $stmt->close();

        // Beasiswa pending verifikasi milik mitra
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM beasiswa WHERE id_mitra = ? AND status_verifikasi = 'pending'");
        $stmt->bind_param('i', $id_mitra);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stats['beasiswa_pending'] = (int) $row['total'];
        $stmt->close();

        // Total simulasi pada beasiswa milik mitra
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total FROM simulasi s
             JOIN beasiswa b ON s.id_beasiswa = b.id_beasiswa
             WHERE b.id_mitra = ?"
        );
        $stmt->bind_param('i', $id_mitra);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stats['total_simulasi'] = (int) $row['total'];
        $stmt->close();

        return $stats;
    }
}
