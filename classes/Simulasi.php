<?php
declare(strict_types=1);

class Simulasi
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function store(array $simulasi, array $ortu, array $files): int|false
    {
        $this->db->begin_transaction();

        try {
            // 1. INSERT simulasi
            $stmt = $this->db->prepare(
                "INSERT INTO simulasi (id_mahasiswa, id_beasiswa, tgl_submit, prestasi, motivasi,
                 ikut_organisasi, status_beasiswa_lain, aktif_kuliah)
                 VALUES (?, ?, NOW(), ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param(
                'iissiii',
                $simulasi['id_mahasiswa'],
                $simulasi['id_beasiswa'],
                $simulasi['prestasi'],
                $simulasi['motivasi'],
                $simulasi['ikut_organisasi'],
                $simulasi['status_beasiswa_lain'],
                $simulasi['aktif_kuliah']
            );

            if (!$stmt->execute()) {
                throw new Exception('Gagal menyimpan data simulasi.');
            }

            $id_simulasi = (int) $this->db->insert_id;
            $stmt->close();

            // 2. INSERT data_orang_tua
            $stmt = $this->db->prepare(
                "INSERT INTO data_orang_tua (id_simulasi, nama_ortu, penghasilan_ortu, pekerjaan_ortu, jml_tanggungan, sktm)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param(
                'isdsii',
                $id_simulasi,
                $ortu['nama_ortu'],
                $ortu['penghasilan_ortu'],
                $ortu['pekerjaan_ortu'],
                $ortu['jml_tanggungan'],
                $ortu['sktm']
            );

            if (!$stmt->execute()) {
                throw new Exception('Gagal menyimpan data orang tua.');
            }
            $stmt->close();

            // 3. INSERT file_simulasi (loop)
            if (!empty($files)) {
                $stmt = $this->db->prepare(
                    "INSERT INTO file_simulasi (id_simulasi, nama_file, file_path) VALUES (?, ?, ?)"
                );

                foreach ($files as $file) {
                    $stmt->bind_param('iss', $id_simulasi, $file['nama_file'], $file['file_path']);
                    if (!$stmt->execute()) {
                        throw new Exception('Gagal menyimpan file simulasi.');
                    }
                }
                $stmt->close();
            }

            // 4. INSERT hasil_simulasi (status pending, is_read FALSE)
            $stmt = $this->db->prepare(
                "INSERT INTO hasil_simulasi (id_simulasi, status_simulasi, is_read) VALUES (?, 'pending', FALSE)"
            );
            $stmt->bind_param('i', $id_simulasi);

            if (!$stmt->execute()) {
                throw new Exception('Gagal menyimpan hasil simulasi.');
            }
            $stmt->close();

            $this->db->commit();
            return $id_simulasi;

        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    public function getByMahasiswa(int $id_mahasiswa): array
    {
        $stmt = $this->db->prepare(
            "SELECT s.*, b.nama_beasiswa, b.nama_penyelenggara,
                    hs.status_simulasi, hs.skor, hs.is_read
             FROM simulasi s
             JOIN beasiswa b ON s.id_beasiswa = b.id_beasiswa
             LEFT JOIN hasil_simulasi hs ON s.id_simulasi = hs.id_simulasi
             WHERE s.id_mahasiswa = ?
             ORDER BY s.created_at DESC"
        );
        $stmt->bind_param('i', $id_mahasiswa);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public function getAll(): array
    {
        $result = $this->db->query(
            "SELECT s.*, m.nama, m.NIM, b.nama_beasiswa,
                    hs.status_simulasi, hs.skor
             FROM simulasi s
             JOIN mahasiswa m ON s.id_mahasiswa = m.id_mahasiswa
             JOIN beasiswa b ON s.id_beasiswa = b.id_beasiswa
             LEFT JOIN hasil_simulasi hs ON s.id_simulasi = hs.id_simulasi
             ORDER BY s.created_at DESC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM simulasi WHERE id_simulasi = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: false;
    }

    public function getPending(): array
    {
        $result = $this->db->query(
            "SELECT s.*, m.nama, m.NIM, b.nama_beasiswa
             FROM simulasi s
             JOIN mahasiswa m ON s.id_mahasiswa = m.id_mahasiswa
             JOIN beasiswa b ON s.id_beasiswa = b.id_beasiswa
             JOIN hasil_simulasi hs ON s.id_simulasi = hs.id_simulasi
             WHERE hs.status_simulasi = 'pending'
             ORDER BY s.created_at ASC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDetailLengkap(int $id): array|false
    {
        // Simulasi + mahasiswa + beasiswa
        $stmt = $this->db->prepare(
            "SELECT s.*, m.nama, m.NIM, m.email AS email_mahasiswa, m.IPK, m.semester,
                    p.nama_prodi, p.nama_jurusan,
                    b.nama_beasiswa, b.nama_penyelenggara
             FROM simulasi s
             JOIN mahasiswa m ON s.id_mahasiswa = m.id_mahasiswa
             JOIN prodi p ON m.id_prodi = p.id_prodi
             JOIN beasiswa b ON s.id_beasiswa = b.id_beasiswa
             WHERE s.id_simulasi = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$data) {
            return false;
        }

        // Data orang tua
        $stmt = $this->db->prepare("SELECT * FROM data_orang_tua WHERE id_simulasi = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $data['orang_tua'] = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        // File simulasi
        $stmt = $this->db->prepare("SELECT * FROM file_simulasi WHERE id_simulasi = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $data['files'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Hasil simulasi
        $stmt = $this->db->prepare("SELECT * FROM hasil_simulasi WHERE id_simulasi = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $data['hasil'] = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $data;
    }

    public function countByMitra(int $id_mitra): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(s.id_simulasi) as total
             FROM simulasi s
             JOIN beasiswa b ON s.id_beasiswa = b.id_beasiswa
             WHERE b.id_mitra = ?"
        );
        $stmt->bind_param('i', $id_mitra);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        return (int) $result['total'];
    }
}
