<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembayaran - Asrama Putri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
<?php include 'views/layouts/navigation.php'; ?>
<main class="container flex-grow-1 mb-5">
    <div class="card shadow-sm border-0 p-4 mx-auto" style="max-width: 700px;">
        <h1 class="h3 fw-bold text-primary mb-4">Detail Pembayaran</h1>
        <?php if (!empty($data)): ?>
            <table class="table table-bordered align-middle">
                <tr><th>Nama Mahasiswa</th><td><?= htmlspecialchars($data['nama_mahasiswa'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                <tr><th>NIM</th><td><?= htmlspecialchars($data['nim'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                <tr><th>Nomor Kamar</th><td><?= htmlspecialchars($data['nomor_kamar'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                <tr><th>Bulan Bayar</th><td><?= htmlspecialchars($data['bulan_bayar'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                <tr><th>Jumlah Bayar</th><td>Rp <?= number_format($data['jumlah_bayar'] ?? 0, 0, ',', '.'); ?></td></tr>
                <tr><th>Tanggal Bayar</th><td><?= htmlspecialchars($data['tanggal_bayar'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                <tr><th>Status Bayar</th><td><?= htmlspecialchars($data['status_bayar'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td></tr>
            </table>
            <div class="d-flex justify-content-between">
                <a href="index.php?controller=pembayaran&action=index" class="btn btn-secondary">Kembali</a>
                <a href="index.php?controller=pembayaran&action=edit&id=<?= urlencode($data['id_pembayaran']); ?>" class="btn btn-warning text-white">Ubah</a>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">Data pembayaran tidak ditemukan.</div>
            <a href="index.php?controller=pembayaran&action=index" class="btn btn-secondary align-self-start">Kembali</a>
        <?php endif; ?>
    </div>
</main>
<?php include 'views/layouts/footer-content.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
