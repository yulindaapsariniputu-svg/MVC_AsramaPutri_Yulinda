<?php
class KamarModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM tb_kamar");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function syncAllStatuses() {
        $query = "UPDATE tb_kamar AS kamar
                  SET status = CASE
                      WHEN kamar.kapasitas > 0 AND (
                          SELECT COUNT(*)
                          FROM tb_penghuni AS penghuni
                          WHERE penghuni.id_kamar = kamar.id_kamar
                            AND penghuni.status_penghuni = 'Aktif'
                      ) >= kamar.kapasitas THEN 'Penuh'
                      ELSE 'Tersedia'
                  END";
        $this->db->exec($query);
    }

    public function syncStatus($id_kamar) {
        $query = "UPDATE tb_kamar AS kamar
                  SET status = CASE
                      WHEN kamar.kapasitas > 0 AND (
                          SELECT COUNT(*)
                          FROM tb_penghuni AS penghuni
                          WHERE penghuni.id_kamar = kamar.id_kamar
                            AND penghuni.status_penghuni = 'Aktif'
                      ) >= kamar.kapasitas THEN 'Penuh'
                      ELSE 'Tersedia'
                  END
                  WHERE kamar.id_kamar = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_kamar]);
    }

    public function getPaginated($search, $limit, $offset) {
        $query = "SELECT * FROM tb_kamar
                  WHERE nomor_kamar LIKE :room_search OR status LIKE :status_search
                  ORDER BY id_kamar DESC
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $term = '%' . $search . '%';
        $stmt->bindValue(':room_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':status_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFiltered($search) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM tb_kamar WHERE nomor_kamar LIKE :room_search OR status LIKE :status_search");
        $term = '%' . $search . '%';
        $stmt->execute(['room_search' => $term, 'status_search' => $term]);
        return (int)$stmt->fetchColumn();
    }

    public function hasDuplicate($nomor_kamar, $excludeId = null) {
        $query = "SELECT 1 FROM tb_kamar WHERE nomor_kamar = :nomor_kamar";
        $params = ['nomor_kamar' => $nomor_kamar];
        if ($excludeId !== null) {
            $query .= " AND id_kamar <> :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        $query .= " LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return (bool)$stmt->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tb_kamar WHERE id_kamar = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($nomor_kamar, $kapasitas, $harga_per_bulan) {
        try {
            $stmt = $this->db->prepare("INSERT INTO tb_kamar (nomor_kamar, kapasitas, harga_per_bulan, status) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nomor_kamar, $kapasitas, $harga_per_bulan, 'Tersedia']);
        } catch (PDOException $e) {
            // Cek jika error karena duplikat nomor kamar (Error code 23000 / 1062)
            if ($e->getCode() == 23000) {
                echo "<script>alert('Gagal: Nomor " . htmlspecialchars($nomor_kamar) . " sudah terdaftar di dalam database!'); window.history.back();</script>";
                exit;
            } else {
                throw $e;
            }
        }
    }

    public function update($id, $nomor_kamar, $kapasitas, $harga_per_bulan) {
        try {
            $stmt = $this->db->prepare("UPDATE tb_kamar SET nomor_kamar = ?, kapasitas = ?, harga_per_bulan = ? WHERE id_kamar = ?");
            $stmt->execute([$nomor_kamar, $kapasitas, $harga_per_bulan, $id]);
            $this->syncStatus($id);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "<script>alert('Gagal: Nomor " . htmlspecialchars($nomor_kamar) . " sudah digunakan oleh kamar lain!'); window.history.back();</script>";
                exit;
            } else {
                throw $e;
            }
        }
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tb_kamar WHERE id_kamar = ?");
        $stmt->execute([$id]);
    }
}
?>