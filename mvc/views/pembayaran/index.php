<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pembayaran - Asrama Putri</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<!-- Navbar / Navigasi Atas -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold me-4" href="index.php?controller=dashboard&action=index">Asrama Putri</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=dashboard&action=index">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=mahasiswa&action=index">Mahasiswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=kamar&action=index">Kamar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=penghuni&action=index">Penghuni</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="index.php?controller=pembayaran&action=index">Pembayaran</a>
                </li>
                <!-- Menu Admin yang ditambahkan -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=admin&action=index">Admin</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-warning fw-semibold" href="index.php?controller=auth&action=logout" onclick="return confirm('Yakin ingin keluar dari sistem?')">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Konten Utama -->
<div class="container py-2">
    <div class="card shadow-sm border-0 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h3 class="fw-bold text-primary mb-0">Data Pembayaran Asrama</h3>
            <a href="index.php?controller=pembayaran&action=create" class="btn btn-primary px-3">+ Tambah Pembayaran</a>
        </div>

        <?php $searchPlaceholder = 'Cari nama, kamar, bulan, tanggal, atau status...'; include 'views/layouts/search.php'; ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 5%;">No</th>
                        <th scope="col">Nama Penghuni</th>
                        <th scope="col">Bulan Bayar</th>
                        <th scope="col">Jumlah Bayar</th>
                        <th scope="col">Tanggal Bayar</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-center" style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dataPembayaran)): ?>
                        <?php $no = $offset + 1; foreach ($dataPembayaran as $bayar): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="fw-semibold">
                                    <?= htmlspecialchars($bayar['nama_mahasiswa'] ?? $bayar['nama'] ?? $bayar['nama_penghuni'] ?? '-'); ?>
                                </td>
                                <td><?= htmlspecialchars($bayar['bulan_bayar'] ?? $bayar['bulan'] ?? '-'); ?></td>
                                <td>Rp <?= number_format($bayar['jumlah_bayar'] ?? $bayar['jumlah'] ?? 0, 0, ',', '.'); ?></td>
                                <td><?= htmlspecialchars($bayar['tanggal_bayar'] ?? $bayar['tanggal'] ?? '-'); ?></td>
                                <td>
                                    <?php 
                                        $status = $bayar['status'] ?? $bayar['status_pembayaran'] ?? 'Lunas';
                                        if ($status == 'Lunas' || $status == 'Berhasil'): 
                                    ?>
                                        <span class="badge bg-success">Lunas</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><?= htmlspecialchars($status); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="index.php?controller=pembayaran&action=detail&id=<?= urlencode($bayar['id_pembayaran'] ?? $bayar['id'] ?? ''); ?>" class="btn btn-info btn-sm text-white px-2 py-1 me-1">Lihat</a>
                                    <a href="index.php?controller=pembayaran&action=edit&id=<?= $bayar['id_pembayaran'] ?? $bayar['id'] ?? ''; ?>" class="btn btn-warning btn-sm text-white px-2 py-1 me-1">Ubah</a>
                                    <a href="index.php?controller=pembayaran&action=delete&id=<?= $bayar['id_pembayaran'] ?? $bayar['id'] ?? ''; ?>" class="btn btn-danger btn-sm px-2 py-1" onclick="return confirm('Yakin ingin menghapus data pembayaran ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data pembayaran ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php include 'views/layouts/pagination.php'; ?>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle CDN -->
<?php include 'views/layouts/footer-content.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>