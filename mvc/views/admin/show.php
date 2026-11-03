<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Admin - Asrama Putri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
<?php include 'views/layouts/navigation.php'; ?>
<main class="container flex-grow-1 mb-5">
    <div class="card shadow-sm border-0 p-4 mx-auto" style="max-width: 700px;">
        <h1 class="h3 fw-bold text-primary mb-4">Detail Administrator</h1>
        <?php if (!empty($data)): ?>
            <table class="table table-bordered align-middle">
                <tr><th>ID Admin</th><td><?= htmlspecialchars($data['id_admin'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                <tr><th>Username</th><td><?= htmlspecialchars($data['username'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                <tr><th>Nama Lengkap</th><td><?= htmlspecialchars($data['nama_lengkap'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                <tr><th>Email</th><td><?= htmlspecialchars($data['email'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td></tr>
            </table>
            <div class="d-flex justify-content-between">
                <a href="index.php?controller=admin&action=index" class="btn btn-secondary">Kembali</a>
                <a href="index.php?controller=admin&action=edit&id=<?= urlencode($data['id_admin']); ?>" class="btn btn-warning text-white">Ubah</a>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">Data administrator tidak ditemukan.</div>
            <a href="index.php?controller=admin&action=index" class="btn btn-secondary align-self-start">Kembali</a>
        <?php endif; ?>
    </div>
</main>
<?php include 'views/layouts/footer-content.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
