<?php
declare(strict_types=1);

class DataOrangTua
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function getBySimulasi(int $id_simulasi): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM data_orang_tua WHERE id_simulasi = ?");
        $stmt->bind_param('i', $id_simulasi);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ?: false;
    }

    public function insert(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO data_orang_tua (id_simulasi, nama_ortu, penghasilan_ortu, pekerjaan_ortu, jml_tanggungan, sktm)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            'isdsii',
            $data['id_simulasi'],
            $data['nama_ortu'],
            $data['penghasilan_ortu'],
            $data['pekerjaan_ortu'],
            $data['jml_tanggungan'],
            $data['sktm']
        );
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
