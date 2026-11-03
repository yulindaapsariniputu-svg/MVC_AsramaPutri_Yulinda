<?php
class DashboardController {
    public function __construct($db) {
        session_start();
        if (!isset($_SESSION['login'])) {
            header("Location: index.php?controller=auth&action=formLogin");
            exit;
        }
    }

    public function index() {
        include 'views/layouts/header.php';
        include 'views/dashboard/index.php';
        include 'views/layouts/footer.php';
    }
}
?>