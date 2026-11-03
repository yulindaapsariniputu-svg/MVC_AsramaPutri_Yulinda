<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Asrama Putri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <main class="flex-grow-1 d-flex align-items-center justify-content-center w-100 py-4">
        <div class="card shadow p-4 rounded-4" style="width: 380px;">
            <h3 class="text-center mb-3 text-primary fw-bold">Login Asrama</h3>
            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger py-2 text-center">Username/password salah!</div>
            <?php endif; ?>
            <form action="index.php?controller=auth&action=login" method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill">Masuk</button>
            </form>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>