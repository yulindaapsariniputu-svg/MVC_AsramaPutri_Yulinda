<?php
require_once 'models/PembayaranModel.php';

class PembayaranController {
    private $db;
    private $pembayaranModel;

    public function __construct($db) {
        $this->db = $db;
        $this->pembayaranModel = new PembayaranModel($db);
    }

    public function index() {
        $search = isset($_GET['q']) && is_string($_GET['q']) ? trim($_GET['q']) : '';
        $requestedPage = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = 10;
        $totalRecords = $this->pembayaranModel->countFiltered($search);
        $totalPages = max(1, (int)ceil($totalRecords / $perPage));
        $currentPage = min($requestedPage ?: 1, $totalPages);
        $offset = ($currentPage - 1) * $perPage;
        $dataPembayaran = $this->pembayaranModel->getPaginated($search, $perPage, $offset);
        $controller = 'pembayaran';
        require_once 'views/pembayaran/index.php';
    }

    public function create() {
        $formData = [];
        $errorMessage = '';
        $dataPenghuni = $this->loadResidents();
        require_once 'views/pembayaran/create.php';
    }

    private function loadResidents() {
        return $this->pembayaranModel->getResidentsWithPaymentMonths();
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $bulanBayar = $_POST['bulan_bayar'] ?? date('Y-m');
            $formData = [
                'id_penghuni' => $_POST['id_penghuni'] ?? '',
                'bulan_bayar' => is_array($bulanBayar) ? implode('-', $bulanBayar) : $bulanBayar,
                'jumlah_bayar' => $_POST['jumlah_bayar'] ?? 100000,
                'tanggal_bayar' => $_POST['tanggal_bayar'] ?? date('Y-m-d'),
                'status' => $_POST['status'] ?? 'Lunas'
            ];
            $id_penghuni = $formData['id_penghuni'];

            if (empty($id_penghuni)) {
                $errorMessage = 'Penghuni wajib dipilih.';
            } elseif ($this->pembayaranModel->hasDuplicate($id_penghuni, $formData['bulan_bayar'])) {
                $errorMessage = 'Pembayaran untuk penghuni dan bulan tersebut sudah terdaftar.';
            }
            if (!empty($errorMessage)) {
                $dataPenghuni = $this->loadResidents();
                require_once 'views/pembayaran/create.php';
                return;
            }

            $this->pembayaranModel->insert(
                $id_penghuni,
                $formData['bulan_bayar'],
                $formData['jumlah_bayar'],
                $formData['tanggal_bayar'],
                $formData['status']
            );
            header("Location: index.php?controller=pembayaran&action=index");
            exit;
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? '';
        $pembayaran = $this->pembayaranModel->getById($id);
        
        $dataPenghuni = $this->pembayaranModel->getResidentsWithPaymentMonths($id);
        $formData = [];

        require_once 'views/pembayaran/edit.php';
    }

    public function detail() {
        $id = $_GET['id'] ?? '';
        $data = $this->pembayaranModel->getDetailsById($id);
        require_once 'views/pembayaran/show.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_pembayaran'] ?? '';
            $bulanBayar = $_POST['bulan_bayar'] ?? date('Y-m');
            $formData = [
                'id_penghuni' => $_POST['id_penghuni'] ?? '',
                'bulan_bayar' => is_array($bulanBayar) ? implode('-', $bulanBayar) : $bulanBayar,
                'jumlah_bayar' => $_POST['jumlah_bayar'] ?? 100000,
                'tanggal_bayar' => $_POST['tanggal_bayar'] ?? date('Y-m-d'),
                'status' => $_POST['status'] ?? 'Lunas'
            ];
            $id_penghuni = $formData['id_penghuni'];

            if (empty($id_penghuni)) {
                $errorMessage = 'Penghuni wajib dipilih.';
            } elseif ($this->pembayaranModel->hasDuplicate($id_penghuni, $formData['bulan_bayar'], $id)) {
                $errorMessage = 'Pembayaran untuk penghuni dan bulan tersebut sudah terdaftar.';
            }
            if (!empty($errorMessage)) {
                $pembayaran = $this->pembayaranModel->getById($id);
                $dataPenghuni = $this->pembayaranModel->getResidentsWithPaymentMonths($id);
                require_once 'views/pembayaran/edit.php';
                return;
            }

            $this->pembayaranModel->update(
                $id,
                $id_penghuni,
                $formData['bulan_bayar'],
                $formData['jumlah_bayar'],
                $formData['tanggal_bayar'],
                $formData['status']
            );
            header("Location: index.php?controller=pembayaran&action=index");
            exit;
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? '';
        $this->pembayaranModel->delete($id);
        header("Location: index.php?controller=pembayaran&action=index");
    }
}
?>