<?php
require_once 'models/AdminModel.php';

class AdminController {
    private $db;
    private $adminModel;

    public function __construct($db) {
        $this->db = $db;
        $this->adminModel = new AdminModel($db);
    }

    public function index() {
        $search = isset($_GET['q']) && is_string($_GET['q']) ? trim($_GET['q']) : '';
        $requestedPage = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = 10;
        $totalRecords = $this->adminModel->countFiltered($search);
        $totalPages = max(1, (int)ceil($totalRecords / $perPage));
        $currentPage = min($requestedPage ?: 1, $totalPages);
        $offset = ($currentPage - 1) * $perPage;
        $dataAdmin = $this->adminModel->getPaginated($search, $perPage, $offset);
        $controller = 'admin';

        require_once 'views/admin/index.php';
    }

    public function create() {
        $formData = [];
        require_once 'views/admin/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formData = [
                'id_admin' => trim($_POST['id_admin'] ?? ''),
                'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
                'username' => trim($_POST['username'] ?? ''),
                'email' => trim($_POST['email'] ?? '')
            ];
            if (!preg_match('/\A[0-9]+\z/', $formData['id_admin'])) {
                $errorMessage = 'ID Admin hanya boleh berisi angka.';
                require_once 'views/admin/create.php';
                return;
            }

            $duplicate = $this->adminModel->findDuplicate(
                $formData['id_admin'],
                $formData['username'],
                $formData['email']
            );
            if ($duplicate !== null) {
                $errorMessage = $this->adminDuplicateMessage($duplicate);
                require_once 'views/admin/create.php';
                return;
            }

            $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
            $query = "INSERT INTO tb_admin (id_admin, nama_lengkap, username, password, email) VALUES (:id_admin, :nama, :username, :password, :email)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'id_admin' => $formData['id_admin'],
                'nama' => $formData['nama_lengkap'],
                'username' => $formData['username'],
                'password' => $password,
                'email' => $formData['email']
            ]);

            header("Location: index.php?controller=admin&action=index");
            exit();
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $query = "SELECT * FROM tb_admin WHERE id_admin = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['id' => $id]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin) {
                $formData = [];
                require_once 'views/admin/edit.php';
                return;
            }
        }
        header("Location: index.php?controller=admin&action=index");
        exit();
    }

    public function detail() {
        $id = $_GET['id'] ?? null;
        $data = null;
        if ($id !== null) {
            $stmt = $this->db->prepare("SELECT id_admin, nama_lengkap, username, email FROM tb_admin WHERE id_admin = :id");
            $stmt->execute(['id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        require_once 'views/admin/show.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_lama = $_POST['id_lama'] ?? null;
            $formData = [
                'id_admin' => trim($_POST['id_admin'] ?? ''),
                'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
                'username' => trim($_POST['username'] ?? ''),
                'email' => trim($_POST['email'] ?? '')
            ];
            if (!preg_match('/\A[0-9]+\z/', $formData['id_admin'])) {
                $stmt = $this->db->prepare("SELECT * FROM tb_admin WHERE id_admin = :id");
                $stmt->execute(['id' => $id_lama]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                $errorMessage = 'ID Admin hanya boleh berisi angka.';
                require_once 'views/admin/edit.php';
                return;
            }

            $duplicate = $this->adminModel->findDuplicate(
                $formData['id_admin'],
                $formData['username'],
                $formData['email'],
                $id_lama
            );
            if ($duplicate !== null) {
                $stmt = $this->db->prepare("SELECT * FROM tb_admin WHERE id_admin = :id");
                $stmt->execute(['id' => $id_lama]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                $errorMessage = $this->adminDuplicateMessage($duplicate);
                require_once 'views/admin/edit.php';
                return;
            }

            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $query = "UPDATE tb_admin SET id_admin = :id_admin, nama_lengkap = :nama, username = :username, password = :password, email = :email WHERE id_admin = :id_lama";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    'id_admin' => $formData['id_admin'],
                    'nama' => $formData['nama_lengkap'],
                    'username' => $formData['username'],
                    'password' => $password,
                    'email' => $formData['email'],
                    'id_lama' => $id_lama
                ]);
            } else {
                $query = "UPDATE tb_admin SET id_admin = :id_admin, nama_lengkap = :nama, username = :username, email = :email WHERE id_admin = :id_lama";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    'id_admin' => $formData['id_admin'],
                    'nama' => $formData['nama_lengkap'],
                    'username' => $formData['username'],
                    'email' => $formData['email'],
                    'id_lama' => $id_lama
                ]);
            }

            header("Location: index.php?controller=admin&action=index");
            exit();
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $query = "DELETE FROM tb_admin WHERE id_admin = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['id' => $id]);
        }

        header("Location: index.php?controller=admin&action=index");
        exit();
    }

    private function adminDuplicateMessage($duplicate) {
        if ($duplicate === 'id') {
            return 'ID Admin sudah terdaftar.';
        }
        if ($duplicate === 'username') {
            return 'Username sudah digunakan oleh admin lain.';
        }
        return 'Email sudah digunakan oleh admin lain.';
    }
}
?>