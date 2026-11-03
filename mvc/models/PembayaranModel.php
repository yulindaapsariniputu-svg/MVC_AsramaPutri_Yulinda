<?php
class PembayaranModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        // Menggunakan nama tabel dengan awalan tb_ yang sesuai dengan database Anda
        $query = "SELECT tb_pembayaran.*, 
                         tb_pembayaran.status_bayar AS status,
                         COALESCE(tb_mahasiswa.nama, '-') AS nama_mahasiswa, 
                         COALESCE(tb_kamar.nomor_kamar, '-') AS nomor_kamar 
                  FROM tb_pembayaran 
                  LEFT JOIN tb_penghuni ON tb_pembayaran.id_penghuni = tb_penghuni.id_penghuni 
                  LEFT JOIN tb_mahasiswa ON tb_penghuni.id_mahasiswa = tb_mahasiswa.id_mahasiswa 
                  LEFT JOIN tb_kamar ON tb_penghuni.id_kamar = tb_kamar.id_kamar";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginated($search, $limit, $offset) {
        $query = "SELECT tb_pembayaran.*,
                         tb_pembayaran.status_bayar AS status,
                         COALESCE(tb_mahasiswa.nama, '-') AS nama_mahasiswa,
                         COALESCE(tb_kamar.nomor_kamar, '-') AS nomor_kamar
                  FROM tb_pembayaran
                  LEFT JOIN tb_penghuni ON tb_pembayaran.id_penghuni = tb_penghuni.id_penghuni
                  LEFT JOIN tb_mahasiswa ON tb_penghuni.id_mahasiswa = tb_mahasiswa.id_mahasiswa
                  LEFT JOIN tb_kamar ON tb_penghuni.id_kamar = tb_kamar.id_kamar
                  WHERE tb_mahasiswa.nama LIKE :name_search OR tb_kamar.nomor_kamar LIKE :room_search
                     OR tb_pembayaran.bulan_bayar LIKE :month_search OR tb_pembayaran.tanggal_bayar LIKE :date_search
                     OR tb_pembayaran.status_bayar LIKE :status_search
                  ORDER BY tb_pembayaran.id_pembayaran DESC
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $term = '%' . $search . '%';
        $stmt->bindValue(':name_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':room_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':month_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':date_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':status_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFiltered($search) {
        $query = "SELECT COUNT(*)
                  FROM tb_pembayaran
                  LEFT JOIN tb_penghuni ON tb_pembayaran.id_penghuni = tb_penghuni.id_penghuni
                  LEFT JOIN tb_mahasiswa ON tb_penghuni.id_mahasiswa = tb_mahasiswa.id_mahasiswa
                  LEFT JOIN tb_kamar ON tb_penghuni.id_kamar = tb_kamar.id_kamar
                  WHERE tb_mahasiswa.nama LIKE :name_search OR tb_kamar.nomor_kamar LIKE :room_search
                     OR tb_pembayaran.bulan_bayar LIKE :month_search OR tb_pembayaran.tanggal_bayar LIKE :date_search
                     OR tb_pembayaran.status_bayar LIKE :status_search";
        $stmt = $this->db->prepare($query);
        $term = '%' . $search . '%';
        $stmt->execute([
            'name_search' => $term,
            'room_search' => $term,
            'month_search' => $term,
            'date_search' => $term,
            'status_search' => $term
        ]);
        return (int)$stmt->fetchColumn();
    }

    public function getResidentsWithPaymentMonths($excludePaymentId = null) {
        $paymentExclusion = '';
        $params = [];
        if ($excludePaymentId !== null) {
            $paymentExclusion = ' AND p.id_pembayaran <> :exclude_payment_id';
            $params['exclude_payment_id'] = $excludePaymentId;
        }

        $query = "SELECT tb_penghuni.id_penghuni,
                         COALESCE(tb_mahasiswa.nama, '-') AS nama_mahasiswa,
                         COALESCE(tb_kamar.nomor_kamar, '-') AS nomor_kamar,
                         COALESCE((
                             SELECT GROUP_CONCAT(DISTINCT p.bulan_bayar ORDER BY p.bulan_bayar SEPARATOR ',')
                             FROM tb_pembayaran p
                             WHERE p.id_penghuni = tb_penghuni.id_penghuni" . $paymentExclusion . "
                         ), '') AS bulan_terbayar
                  FROM tb_penghuni
                  LEFT JOIN tb_mahasiswa ON tb_penghuni.id_mahasiswa = tb_mahasiswa.id_mahasiswa
                  LEFT JOIN tb_kamar ON tb_penghuni.id_kamar = tb_kamar.id_kamar
                  ORDER BY tb_mahasiswa.nama";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hasDuplicate($id_penghuni, $bulan_bayar, $excludeId = null) {
        $query = "SELECT 1 FROM tb_pembayaran
                  WHERE id_penghuni = :id_penghuni AND bulan_bayar = :bulan_bayar";
        $params = ['id_penghuni' => $id_penghuni, 'bulan_bayar' => $bulan_bayar];
        if ($excludeId !== null) {
            $query .= " AND id_pembayaran <> :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        $query .= " LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return (bool)$stmt->fetchColumn();
    }

    public function getById($id) {
        $query = "SELECT *, status_bayar AS status FROM tb_pembayaran WHERE id_pembayaran = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDetailsById($id) {
        $query = "SELECT tb_pembayaran.*,
                         COALESCE(tb_mahasiswa.nama, '-') AS nama_mahasiswa,
                         COALESCE(tb_mahasiswa.nim, '-') AS nim,
                         COALESCE(tb_kamar.nomor_kamar, '-') AS nomor_kamar
                  FROM tb_pembayaran
                  LEFT JOIN tb_penghuni ON tb_pembayaran.id_penghuni = tb_penghuni.id_penghuni
                  LEFT JOIN tb_mahasiswa ON tb_penghuni.id_mahasiswa = tb_mahasiswa.id_mahasiswa
                  LEFT JOIN tb_kamar ON tb_penghuni.id_kamar = tb_kamar.id_kamar
                  WHERE tb_pembayaran.id_pembayaran = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($id_penghuni, $bulan_bayar, $jumlah_bayar, $tanggal_bayar, $status) {
        $query = "INSERT INTO tb_pembayaran (id_penghuni, bulan_bayar, jumlah_bayar, tanggal_bayar, status_bayar) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id_penghuni, $bulan_bayar, $jumlah_bayar, $tanggal_bayar, $status]);
    }

    public function update($id, $id_penghuni, $bulan_bayar, $jumlah_bayar, $tanggal_bayar, $status) {
        $query = "UPDATE tb_pembayaran SET id_penghuni = ?, bulan_bayar = ?, jumlah_bayar = ?, tanggal_bayar = ?, status_bayar = ? WHERE id_pembayaran = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id_penghuni, $bulan_bayar, $jumlah_bayar, $tanggal_bayar, $status, $id]);
    }

    public function delete($id) {
        $query = "DELETE FROM tb_pembayaran WHERE id_pembayaran = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>