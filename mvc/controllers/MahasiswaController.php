<?php
require_once 'models/MahasiswaModel.php';

class MahasiswaController {
    private $model;

    public function __construct($db) {
        $this->model = new MahasiswaModel($db);
    }

    public function index() {
        $search = isset($_GET['q']) && is_string($_GET['q']) ? trim($_GET['q']) : '';
        $requestedPage = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = 10;
        $totalRecords = $this->model->countFiltered($search);
        $totalPages = max(1, (int)ceil($totalRecords / $perPage));
        $currentPage = min($requestedPage ?: 1, $totalPages);
        $offset = ($currentPage - 1) * $perPage;
        $dataMahasiswa = $this->model->getPaginated($search, $perPage, $offset);
        $controller = 'mahasiswa';
        require_once 'views/mahasiswa/index.php';
    }

    public function create() {
        $formData = [];
        require_once 'views/mahasiswa/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formData = [
                'nim' => trim($_POST['nim'] ?? ''),
                'nama' => trim($_POST['nama'] ?? ''),
                'prodi' => trim($_POST['prodi'] ?? ''),
                'no_hp' => trim($_POST['no_hp'] ?? ''),
                'email' => trim($_POST['email'] ?? '')
            ];
            $duplicate = $this->model->findDuplicate(
                $formData['nim'],
                $formData['nama'],
                $formData['prodi'],
                $formData['no_hp'],
                $formData['email']
            );

            if ($duplicate !== null) {
                $errorMessage = $duplicate === 'nim'
                    ? 'NIM sudah terdaftar. Gunakan NIM yang berbeda.'
                    : 'Data mahasiswa yang sama sudah terdaftar.';
                require_once 'views/mahasiswa/create.php';
                return;
            }

            $this->model->insert(
                $formData['nim'],
                $formData['nama'],
                $formData['prodi'],
                $formData['no_hp'],
                $formData['email']
            );
            header("Location: index.php?controller=mahasiswa&action=index");
            exit;
        }
    }

    // Diubah memanggil show.php sesuai file yang ada di folder views/mahasiswa/
    public function detail() {
        $id = $_GET['id'];
        $mhs = $this->model->getById($id);
        require_once 'views/mahasiswa/show.php';
    }

    public function edit() {
        $id = $_GET['id'];
        $mhs = $this->model->getById($id);
        require_once 'views/mahasiswa/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_mahasiswa'] ?? '';
            $formData = [
                'nim' => trim($_POST['nim'] ?? ''),
                'nama' => trim($_POST['nama'] ?? ''),
                'prodi' => trim($_POST['prodi'] ?? ''),
                'no_hp' => trim($_POST['no_hp'] ?? ''),
                'email' => trim($_POST['email'] ?? '')
            ];
            $duplicate = $this->model->findDuplicate(
                $formData['nim'],
                $formData['nama'],
                $formData['prodi'],
                $formData['no_hp'],
                $formData['email'],
                $id
            );

            if ($duplicate !== null) {
                $mhs = $this->model->getById($id);
                $errorMessage = $duplicate === 'nim'
                    ? 'NIM sudah digunakan oleh mahasiswa lain.'
                    : 'Data mahasiswa yang sama sudah terdaftar.';
                require_once 'views/mahasiswa/edit.php';
                return;
            }

            $this->model->update(
                $id,
                $formData['nim'],
                $formData['nama'],
                $formData['prodi'],
                $formData['no_hp'],
                $formData['email']
            );
            header("Location: index.php?controller=mahasiswa&action=index");
            exit;
        }
    }

    public function delete() {
        $id = $_GET['id'];
        $this->model->delete($id);
        header("Location: index.php?controller=mahasiswa&action=index");
    }
}
?>