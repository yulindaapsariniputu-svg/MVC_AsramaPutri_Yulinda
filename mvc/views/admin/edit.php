<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Administrator - Asrama Putri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<?php include 'views/layouts/navigation.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-4">
                <h3 class="fw-bold text-primary mb-3">Ubah Data Admin</h3>
                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-warning" role="alert"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>
                
                <form action="index.php?controller=admin&action=update" method="POST">
                    <!-- Menyimpan ID lama sebagai acuan WHERE di database -->
                    <input type="hidden" name="id_lama" value="<?= htmlspecialchars($admin['id_admin'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ID Admin</label>
                        <input type="text" name="id_admin" class="form-control" value="<?= htmlspecialchars($formData['id_admin'] ?? $admin['id_admin'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" inputmode="numeric" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($formData['nama_lengkap'] ?? $admin['nama_lengkap'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($formData['username'] ?? $admin['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($formData['email'] ?? $admin['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="index.php?controller=admin&action=index" class="btn btn-secondary px-3">Kembali</a>
                        <button type="submit" class="btn btn-primary px-4">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer-content.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>