<?php
require_once 'models/KamarModel.php';

class KamarController {
    private $kamarModel;

    public function __construct($db) {
        $this->kamarModel = new KamarModel($db);
    }

    public function index() {
        $this->kamarModel->syncAllStatuses();
        $search = isset($_GET['q']) && is_string($_GET['q']) ? trim($_GET['q']) : '';
        $requestedPage = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = 10;
        $totalRecords = $this->kamarModel->countFiltered($search);
        $totalPages = max(1, (int)ceil($totalRecords / $perPage));
        $currentPage = min($requestedPage ?: 1, $totalPages);
        $offset = ($currentPage - 1) * $perPage;
        $dataKamar = $this->kamarModel->getPaginated($search, $perPage, $offset);
        $controller = 'kamar';
        require_once 'views/kamar/index.php';
    }

    public function create() {
        $formData = [];
        require_once 'views/kamar/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formData = [
                'nomor_kamar' => isset($_POST['nomor_kamar']) && is_string($_POST['nomor_kamar']) ? trim($_POST['nomor_kamar']) : '',
                'kapasitas' => $_POST['kapasitas'] ?? '',
                'harga_per_bulan' => $_POST['harga_per_bulan'] ?? $_POST['harga'] ?? 100000
            ];
            if (!preg_match('/\A[0-9]+\z/', $formData['nomor_kamar'])) {
                $errorMessage = 'Nomor kamar wajib diisi dengan angka saja.';
                require_once 'views/kamar/create.php';
                return;
            }
            if ($this->kamarModel->hasDuplicate($formData['nomor_kamar'])) {
                $errorMessage = 'Nomor kamar sudah terdaftar. Silakan pilih nomor lain.';
                require_once 'views/kamar/create.php';
                return;
            }

            $this->kamarModel->insert($formData['nomor_kamar'], $formData['kapasitas'], $formData['harga_per_bulan']);
            header("Location: index.php?controller=kamar&action=index");
            exit;
        }
    }

    public function edit() {
        $id = $_GET['id'];
        $kamar = $this->kamarModel->getById($id);
        $formData = [];
        require_once 'views/kamar/edit.php';
    }

    public function detail() {
        $id = $_GET['id'] ?? '';
        $data = $this->kamarModel->getById($id);
        require_once 'views/kamar/show.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_kamar'] ?? '';
            $formData = [
                'nomor_kamar' => isset($_POST['nomor_kamar']) && is_string($_POST['nomor_kamar']) ? trim($_POST['nomor_kamar']) : '',
                'kapasitas' => $_POST['kapasitas'] ?? '',
                'harga_per_bulan' => $_POST['harga_per_bulan'] ?? $_POST['harga'] ?? 100000
            ];
            if (!preg_match('/\A[0-9]+\z/', $formData['nomor_kamar'])) {
                $kamar = $this->kamarModel->getById($id);
                $errorMessage = 'Nomor kamar wajib diisi dengan angka saja.';
                require_once 'views/kamar/edit.php';
                return;
            }
            if ($this->kamarModel->hasDuplicate($formData['nomor_kamar'], $id)) {
                $kamar = $this->kamarModel->getById($id);
                $errorMessage = 'Nomor kamar sudah digunakan oleh kamar lain.';
                require_once 'views/kamar/edit.php';
                return;
            }

            $this->kamarModel->update($id, $formData['nomor_kamar'], $formData['kapasitas'], $formData['harga_per_bulan']);
            header("Location: index.php?controller=kamar&action=index");
            exit;
        }
    }

    public function delete() {
        $id = $_GET['id'];
        $this->kamarModel->delete($id);
        header("Location: index.php?controller=kamar&action=index");
    }
}
?>