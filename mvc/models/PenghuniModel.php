<?php
class PenghuniModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        // Menambahkan LEFT JOIN ke tb_pembayaran untuk mengambil status pembayaran terakhir/terkait
        $query = "SELECT tb_penghuni.*, 
                         COALESCE(tb_mahasiswa.nama, '-') AS nama_mahasiswa, 
                         COALESCE(tb_kamar.nomor_kamar, '-') AS nomor_kamar,
                         tb_pembayaran.status_bayar AS status_pembayaran,
                         tb_pembayaran.bulan_bayar
                  FROM tb_penghuni 
                  LEFT JOIN tb_mahasiswa ON tb_penghuni.id_mahasiswa = tb_mahasiswa.id_mahasiswa 
                  LEFT JOIN tb_kamar ON tb_penghuni.id_kamar = tb_kamar.id_kamar
                  LEFT JOIN tb_pembayaran ON tb_penghuni.id_penghuni = tb_pembayaran.id_penghuni";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginated($search, $limit, $offset) {
        $query = "SELECT tb_penghuni.*,
                         COALESCE(tb_mahasiswa.nama, '-') AS nama_mahasiswa,
                         COALESCE(tb_kamar.nomor_kamar, '-') AS nomor_kamar,
                         tb_pembayaran.status_bayar AS status_pembayaran,
                         tb_pembayaran.bulan_bayar
                  FROM tb_penghuni
                  LEFT JOIN tb_mahasiswa ON tb_penghuni.id_mahasiswa = tb_mahasiswa.id_mahasiswa
                  LEFT JOIN tb_kamar ON tb_penghuni.id_kamar = tb_kamar.id_kamar
                  LEFT JOIN tb_pembayaran ON tb_penghuni.id_penghuni = tb_pembayaran.id_penghuni
                  WHERE tb_mahasiswa.nama LIKE :name_search OR tb_kamar.nomor_kamar LIKE :room_search
                     OR tb_penghuni.status_penghuni LIKE :resident_status_search OR tb_penghuni.tanggal_masuk LIKE :date_search
                     OR tb_pembayaran.status_bayar LIKE :payment_status_search OR tb_pembayaran.bulan_bayar LIKE :month_search
                  ORDER BY tb_penghuni.id_penghuni DESC
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $term = '%' . $search . '%';
        $stmt->bindValue(':name_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':room_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':resident_status_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':date_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':payment_status_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':month_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFiltered($search) {
        $query = "SELECT COUNT(*)
                  FROM tb_penghuni
                  LEFT JOIN tb_mahasiswa ON tb_penghuni.id_mahasiswa = tb_mahasiswa.id_mahasiswa
                  LEFT JOIN tb_kamar ON tb_penghuni.id_kamar = tb_kamar.id_kamar
                  LEFT JOIN tb_pembayaran ON tb_penghuni.id_penghuni = tb_pembayaran.id_penghuni
                  WHERE tb_mahasiswa.nama LIKE :name_search OR tb_kamar.nomor_kamar LIKE :room_search
                     OR tb_penghuni.status_penghuni LIKE :resident_status_search OR tb_penghuni.tanggal_masuk LIKE :date_search
                     OR tb_pembayaran.status_bayar LIKE :payment_status_search OR tb_pembayaran.bulan_bayar LIKE :month_search";
        $stmt = $this->db->prepare($query);
        $term = '%' . $search . '%';
        $stmt->execute([
            'name_search' => $term,
            'room_search' => $term,
            'resident_status_search' => $term,
            'date_search' => $term,
            'payment_status_search' => $term,
            'month_search' => $term
        ]);
        return (int)$stmt->fetchColumn();
    }

    public function hasDuplicate($id_mahasiswa, $id_kamar, $tanggal_masuk, $status_penghuni, $excludeId = null) {
        $query = "SELECT 1 FROM tb_penghuni
                  WHERE id_mahasiswa = :id_mahasiswa AND id_kamar = :id_kamar
                    AND tanggal_masuk = :tanggal_masuk AND status_penghuni = :status_penghuni";
        $params = [
            'id_mahasiswa' => $id_mahasiswa,
            'id_kamar' => $id_kamar,
            'tanggal_masuk' => $tanggal_masuk,
            'status_penghuni' => $status_penghuni
        ];
        if ($excludeId !== null) {
            $query .= " AND id_penghuni <> :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        $query .= " LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return (bool)$stmt->fetchColumn();
    }

    public function countActiveInRoom($id_kamar, $excludeId = null) {
        $query = "SELECT COUNT(*) FROM tb_penghuni
                  WHERE id_kamar = :id_kamar AND status_penghuni = 'Aktif'";
        $params = ['id_kamar' => $id_kamar];
        if ($excludeId !== null) {
            $query .= " AND id_penghuni <> :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getById($id) {
        $query = "SELECT * FROM tb_penghuni WHERE id_penghuni = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDetailsById($id) {
        $query = "SELECT tb_penghuni.*,
                         COALESCE(tb_mahasiswa.nama, '-') AS nama_mahasiswa,
                         COALESCE(tb_mahasiswa.nim, '-') AS nim,
                         COALESCE(tb_kamar.nomor_kamar, '-') AS nomor_kamar
                  FROM tb_penghuni
                  LEFT JOIN tb_mahasiswa ON tb_penghuni.id_mahasiswa = tb_mahasiswa.id_mahasiswa
                  LEFT JOIN tb_kamar ON tb_penghuni.id_kamar = tb_kamar.id_kamar
                  WHERE tb_penghuni.id_penghuni = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($id_mahasiswa, $id_kamar, $tanggal_masuk, $status_penghuni) {
        $query = "INSERT INTO tb_penghuni (id_mahasiswa, id_kamar, tanggal_masuk, status_penghuni) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id_mahasiswa, $id_kamar, $tanggal_masuk, $status_penghuni]);
    }

    public function update($id, $id_mahasiswa, $id_kamar, $tanggal_masuk, $status_penghuni) {
        $query = "UPDATE tb_penghuni SET id_mahasiswa = ?, id_kamar = ?, tanggal_masuk = ?, status_penghuni = ? WHERE id_penghuni = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id_mahasiswa, $id_kamar, $tanggal_masuk, $status_penghuni, $id]);
    }

    public function delete($id) {
        $query = "DELETE FROM tb_penghuni WHERE id_penghuni = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>