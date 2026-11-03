<?php
require_once 'models/AdminModel.php';

class AuthController {
    private $adminModel;

    public function __construct($db) {
        $this->adminModel = new AdminModel($db);
    }

    public function formLogin() {
        include 'views/auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $admin = $this->adminModel->getByUsername($username);

            // Mendukung password_hash (standar baru) maupun md5 (jika ada data lama)
            $isPasswordValid = false;
            if ($admin) {
                // Cek menggunakan password_verify jika di-hash, atau fallback ke md5/plaintext jika diperlukan
                if (password_verify($password, $admin['password']) || $admin['password'] === md5($password) || $admin['password'] === $password) {
                    $isPasswordValid = true;
                }
            }

            if ($admin && $isPasswordValid) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['login'] = true;
                $_SESSION['nama'] = $admin['nama_lengkap'];
                header("Location: index.php?controller=dashboard&action=index");
                exit;
            } else {
                header("Location: index.php?controller=auth&action=formLogin&error=1");
                exit;
            }
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: index.php?controller=auth&action=formLogin");
        exit;
    }
}
?>