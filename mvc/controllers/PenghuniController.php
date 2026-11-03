<?php
require_once 'models/PenghuniModel.php';
require_once 'models/MahasiswaModel.php';
require_once 'models/KamarModel.php';

class PenghuniController {
    private $db;
    private $penghuniModel;

    public function __construct($db) {
        $this->db = $db;
        $this->penghuniModel = new PenghuniModel($db);
    }

    public function index() {
        $search = isset($_GET['q']) && is_string($_GET['q']) ? trim($_GET['q']) : '';
        $requestedPage = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = 10;
        $totalRecords = $this->penghuniModel->countFiltered($search);
        $totalPages = max(1, (int)ceil($totalRecords / $perPage));
        $currentPage = min($requestedPage ?: 1, $totalPages);
        $offset = ($currentPage - 1) * $perPage;
        $dataPenghuni = $this->penghuniModel->getPaginated($search, $perPage, $offset);
        $controller = 'penghuni';
        require_once 'views/penghuni/index.php';
    }

    public function create() {
        $formData = [];
        $errorMessage = '';
        list($dataMahasiswa, $dataKamar) = $this->loadFormOptions();
        require_once 'views/penghuni/create.php';
    }

    private function loadFormOptions() {
        $mahasiswaModel = new MahasiswaModel($this->db);
        $kamarModel = new KamarModel($this->db);
        $kamarModel->syncAllStatuses();
        $dataMahasiswa = $mahasiswaModel->getAll();
        $dataKamar = $kamarModel->getAll();
        return [$dataMahasiswa, $dataKamar];
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formData = [
                'id_mahasiswa' => $_POST['id_mahasiswa'] ?? '',
                'id_kamar' => $_POST['id_kamar'] ?? '',
                'tanggal_masuk' => $_POST['tanggal_masuk'] ?? date('Y-m-d'),
                'status_penghuni' => $_POST['status_penghuni'] ?? 'Aktif'
            ];
            $id_mahasiswa = $formData['id_mahasiswa'];
            $id_kamar = $formData['id_kamar'];
            $tanggal_masuk = $formData['tanggal_masuk'];
            $status_penghuni = $formData['status_penghuni'];

            if (empty($id_mahasiswa) || empty($id_kamar)) {
                $errorMessage = 'Mahasiswa dan nomor kamar wajib dipilih.';
                list($dataMahasiswa, $dataKamar) = $this->loadFormOptions();
                require_once 'views/penghuni/create.php';
                return;
            }
            if ($this->penghuniModel->hasDuplicate($id_mahasiswa, $id_kamar, $tanggal_masuk, $status_penghuni)) {
                $errorMessage = 'Data penghuni yang sama sudah terdaftar.';
                list($dataMahasiswa, $dataKamar) = $this->loadFormOptions();
                require_once 'views/penghuni/create.php';
                return;
            }

            $kamarModel = new KamarModel($this->db);
            $kamar = $kamarModel->getById($id_kamar);
            $kapasitasMaksimal = (int)($kamar['kapasitas'] ?? 0);
            if ($status_penghuni === 'Aktif' && $kapasitasMaksimal > 0 &&
                $this->penghuniModel->countActiveInRoom($id_kamar) >= $kapasitasMaksimal) {
                $errorMessage = 'Kamar yang dipilih sudah penuh. Kapasitas maksimal ' . $kapasitasMaksimal . ' orang.';
                list($dataMahasiswa, $dataKamar) = $this->loadFormOptions();
                require_once 'views/penghuni/create.php';
                return;
            }

            $this->penghuniModel->insert($id_mahasiswa, $id_kamar, $tanggal_masuk, $status_penghuni);
            $kamarModel->syncStatus($id_kamar);
            header("Location: index.php?controller=penghuni&action=index");
            exit;
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? '';
        $penghuni = $this->penghuniModel->getById($id);
        
        list($dataMahasiswa, $dataKamar) = $this->loadFormOptions();
        $formData = [];

        require_once 'views/penghuni/edit.php';
    }

    public function detail() {
        $id = $_GET['id'] ?? '';
        $data = $this->penghuniModel->getDetailsById($id);
        require_once 'views/penghuni/show.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_penghuni'] ?? '';
            $formData = [
                'id_mahasiswa' => $_POST['id_mahasiswa'] ?? '',
                'id_kamar' => $_POST['id_kamar'] ?? '',
                'tanggal_masuk' => $_POST['tanggal_masuk'] ?? '',
                'status_penghuni' => $_POST['status_penghuni'] ?? 'Aktif'
            ];
            $id_mahasiswa = $formData['id_mahasiswa'];
            $id_kamar = $formData['id_kamar'];
            $tanggal_masuk = $formData['tanggal_masuk'];
            $status_penghuni = $formData['status_penghuni'];

            if (empty($id_mahasiswa) || empty($id_kamar)) {
                $errorMessage = 'Mahasiswa dan nomor kamar wajib dipilih.';
            } elseif ($this->penghuniModel->hasDuplicate($id_mahasiswa, $id_kamar, $tanggal_masuk, $status_penghuni, $id)) {
                $errorMessage = 'Data penghuni yang sama sudah terdaftar.';
            }
            if (!empty($errorMessage)) {
                $penghuni = $this->penghuniModel->getById($id);
                list($dataMahasiswa, $dataKamar) = $this->loadFormOptions();
                require_once 'views/penghuni/edit.php';
                return;
            }

            $penghuniLama = $this->penghuniModel->getById($id);
            $kamarModel = new KamarModel($this->db);
            $kamar = $kamarModel->getById($id_kamar);
            $kapasitasMaksimal = (int)($kamar['kapasitas'] ?? 0);
            if ($status_penghuni === 'Aktif' && $kapasitasMaksimal > 0 &&
                $this->penghuniModel->countActiveInRoom($id_kamar, $id) >= $kapasitasMaksimal) {
                $errorMessage = 'Kamar yang dipilih sudah penuh. Kapasitas maksimal ' . $kapasitasMaksimal . ' orang.';
                $penghuni = $penghuniLama;
                list($dataMahasiswa, $dataKamar) = $this->loadFormOptions();
                require_once 'views/penghuni/edit.php';
                return;
            }

            $this->penghuniModel->update($id, $id_mahasiswa, $id_kamar, $tanggal_masuk, $status_penghuni);
            if (!empty($penghuniLama['id_kamar'])) {
                $kamarModel->syncStatus($penghuniLama['id_kamar']);
            }
            $kamarModel->syncStatus($id_kamar);
            header("Location: index.php?controller=penghuni&action=index");
            exit;
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? '';
        $penghuni = $this->penghuniModel->getById($id);
        $this->penghuniModel->delete($id);
        if (!empty($penghuni['id_kamar'])) {
            $kamarModel = new KamarModel($this->db);
            $kamarModel->syncStatus($penghuni['id_kamar']);
        }
        header("Location: index.php?controller=penghuni&action=index");
    }
}
?>