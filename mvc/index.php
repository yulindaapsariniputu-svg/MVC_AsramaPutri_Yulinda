<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'formLogin';

if ($controller == 'auth') {
    require_once 'controllers/AuthController.php';
    $ctrl = new AuthController($db);
} elseif ($controller == 'dashboard') {
    require_once 'controllers/DashboardController.php';
    $ctrl = new DashboardController($db);
} elseif ($controller == 'mahasiswa') {
    require_once 'controllers/MahasiswaController.php';
    $ctrl = new MahasiswaController($db);
} elseif ($controller == 'kamar') {
    require_once 'controllers/KamarController.php';
    $ctrl = new KamarController($db);
} elseif ($controller == 'penghuni') {
    require_once 'controllers/PenghuniController.php';
    $ctrl = new PenghuniController($db);
} elseif ($controller == 'pembayaran') {
    require_once 'controllers/PembayaranController.php';
    $ctrl = new PembayaranController($db);
} elseif ($controller == 'admin') {
    require_once 'controllers/AdminController.php';
    $ctrl = new AdminController($db);
} else {
    die("Controller tidak ditemukan!");
}

if (method_exists($ctrl, $action)) {
    $ctrl->$action();
} else {
    die("Action tidak ditemukan!");
}
?>