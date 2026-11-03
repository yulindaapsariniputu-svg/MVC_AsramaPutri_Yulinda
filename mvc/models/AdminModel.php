<?php
class AdminModel {
    private $conn;
    private $table = "tb_admin";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getByUsername($username) {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPaginated($search, $limit, $offset) {
        $query = "SELECT * FROM " . $this->table . "
                  WHERE id_admin LIKE :id_search OR nama_lengkap LIKE :name_search
                     OR username LIKE :username_search OR email LIKE :email_search
                  ORDER BY id_admin DESC
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $term = '%' . $search . '%';
        $stmt->bindValue(':id_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':name_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':username_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':email_search', $term, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFiltered($search) {
        $query = "SELECT COUNT(*) FROM " . $this->table . "
                  WHERE id_admin LIKE :id_search OR nama_lengkap LIKE :name_search
                     OR username LIKE :username_search OR email LIKE :email_search";
        $stmt = $this->conn->prepare($query);
        $term = '%' . $search . '%';
        $stmt->execute([
            'id_search' => $term,
            'name_search' => $term,
            'username_search' => $term,
            'email_search' => $term
        ]);
        return (int)$stmt->fetchColumn();
    }

    public function findDuplicate($id, $username, $email, $excludeId = null) {
        $query = "SELECT id_admin, username, email
                  FROM " . $this->table . "
                  WHERE (id_admin = :id_match OR username = :username_match
                     OR (:email_check <> '' AND email = :email_match))";
        $params = [
            'id_match' => $id,
            'username_match' => $username,
            'email_check' => $email,
            'email_match' => $email
        ];
        if ($excludeId !== null) {
            $query .= " AND id_admin <> :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        $query .= " LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        $duplicate = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$duplicate) {
            return null;
        }
        if ($duplicate['id_admin'] === $id) {
            return 'id';
        }
        if ($duplicate['username'] === $username) {
            return 'username';
        }
        return 'email';
    }
}
?>