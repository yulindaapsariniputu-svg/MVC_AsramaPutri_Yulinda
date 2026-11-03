<?php
class MahasiswaModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM tb_mahasiswa");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginated($search, $limit, $offset) {
        $query = "SELECT * FROM tb_mahasiswa
                  WHERE nim LIKE :nim_search OR nama LIKE :name_search OR prodi LIKE :program_search
                     OR no_hp LIKE :phone_search
                  ORDER BY id_mahasiswa DESC
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $term = '%' . $search . '%';
        $stmt->bindValue(':nim_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':name_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':program_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':phone_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFiltered($search) {
        $query = "SELECT COUNT(*) FROM tb_mahasiswa
                  WHERE nim LIKE :nim_search OR nama LIKE :name_search OR prodi LIKE :program_search
                     OR no_hp LIKE :phone_search";
        $stmt = $this->db->prepare($query);
        $term = '%' . $search . '%';
        $stmt->execute([
            'nim_search' => $term,
            'name_search' => $term,
            'program_search' => $term,
            'phone_search' => $term
        ]);
        return (int)$stmt->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tb_mahasiswa WHERE id_mahasiswa = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findDuplicate($nim, $nama, $prodi, $no_hp, $email, $excludeId = null) {
        $query = "SELECT nim, nama, prodi, no_hp, email
                  FROM tb_mahasiswa
                  WHERE (nim = :nim OR (
                      nama = :nama AND prodi = :prodi AND no_hp = :no_hp AND email = :email
                  ))";
        $params = [
            'nim' => $nim,
            'nama' => $nama,
            'prodi' => $prodi,
            'no_hp' => $no_hp,
            'email' => $email
        ];

        if ($excludeId !== null) {
            $query .= " AND id_mahasiswa <> :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        $query .= " LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $duplicate = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$duplicate) {
            return null;
        }

        return $duplicate['nim'] === $nim ? 'nim' : 'data';
    }

    public function insert($nim, $nama, $prodi, $no_hp, $email) {
        try {
            $stmt = $this->db->prepare("INSERT INTO tb_mahasiswa (nim, nama, prodi, no_hp, email) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nim, $nama, $prodi, $no_hp, $email]);
        } catch (PDOException $e) {
            $stmt = $this->db->prepare("INSERT INTO tb_mahasiswa (nim, nama, prodi, no_hp) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nim, $nama, $prodi, $no_hp]);
        }
    }

    public function update($id, $nim, $nama, $prodi, $no_hp, $email) {
        try {
            $stmt = $this->db->prepare("UPDATE tb_mahasiswa SET nim = ?, nama = ?, prodi = ?, no_hp = ?, email = ? WHERE id_mahasiswa = ?");
            $stmt->execute([$nim, $nama, $prodi, $no_hp, $email, $id]);
        } catch (PDOException $e) {
            $stmt = $this->db->prepare("UPDATE tb_mahasiswa SET nim = ?, nama = ?, prodi = ?, no_hp = ? WHERE id_mahasiswa = ?");
            $stmt->execute([$nim, $nama, $prodi, $no_hp, $id]);
        }
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tb_mahasiswa WHERE id_mahasiswa = ?");
        $stmt->execute([$id]);
    }
}
?>