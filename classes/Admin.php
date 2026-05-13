<?php
declare(strict_types=1);

require_once __DIR__ . '/User.php';

class Admin extends User
{
    public function login(string $email, string $password): array|false
    {
        $user = $this->findByEmail('admin', $email);

        if ($user && $this->verifyPassword($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }

        return false;
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM admin WHERE id_admin = ?");
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
            "UPDATE admin SET nama_admin = ?, jabatan = ?, telepon = ?, email = ?
             WHERE id_admin = ?"
        );

        $stmt->bind_param(
            'ssssi',
            $data['nama_admin'],
            $data['jabatan'],
            $data['telepon'],
            $data['email'],
            $id
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function getDashboardStats(): array
    {
        $stats = [
            'total_beasiswa'      => 0,
            'belum_dibuka'        => 0,
            'dibuka'              => 0,
            'ditutup'             => 0,
            'pending_verifikasi'  => 0,
            'pending_review'      => 0,
        ];

        // Total beasiswa
        $row = $this->db->query("SELECT COUNT(*) AS total FROM beasiswa")->fetch_assoc();
        $stats['total_beasiswa'] = (int) $row['total'];

        // Status pendaftaran computed counts
        $result = $this->db->query(
            "SELECT
                SUM(CASE WHEN tgl_buka > CURDATE() THEN 1 ELSE 0 END) AS belum_dibuka,
                SUM(CASE WHEN tgl_buka <= CURDATE() AND tgl_tutup >= CURDATE() THEN 1 ELSE 0 END) AS dibuka,
                SUM(CASE WHEN tgl_tutup < CURDATE() THEN 1 ELSE 0 END) AS ditutup
             FROM beasiswa"
        );
        $row = $result->fetch_assoc();
        $stats['belum_dibuka'] = (int) ($row['belum_dibuka'] ?? 0);
        $stats['dibuka'] = (int) ($row['dibuka'] ?? 0);
        $stats['ditutup'] = (int) ($row['ditutup'] ?? 0);

        // Pending verifikasi beasiswa
        $row = $this->db->query("SELECT COUNT(*) AS total FROM beasiswa WHERE status_verifikasi = 'pending'")->fetch_assoc();
        $stats['pending_verifikasi'] = (int) $row['total'];

        // Pending review simulasi
        $row = $this->db->query("SELECT COUNT(*) AS total FROM hasil_simulasi WHERE status_simulasi = 'pending'")->fetch_assoc();
        $stats['pending_review'] = (int) $row['total'];

        return $stats;
    }
}
