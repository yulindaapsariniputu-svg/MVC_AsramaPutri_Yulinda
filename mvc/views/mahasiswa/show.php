<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mahasiswa - Asrama Putri</title>
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
                    <a class="nav-link active" href="index.php?controller=mahasiswa&action=index">Mahasiswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=kamar&action=index">Kamar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=penghuni&action=index">Penghuni</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=pembayaran&action=index">Pembayaran</a>
                </li>
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

<!-- Konten Detail Mahasiswa -->
<div class="container py-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4">
                <div class="mb-4 border-bottom pb-3">
                    <h3 class="fw-bold text-primary mb-0">Detail Data Mahasiswa</h3>
                </div>

                <?php if (!empty($mhs)): ?>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-4">
                        <tr>
                            <th style="width: 30%;" class="text-muted">NIM</th>
                            <td style="width: 2%;">:</td>
                            <td class="fw-semibold"><?= htmlspecialchars($mhs['nim'] ?? ''); ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Nama Mahasiswa</th>
                            <td>:</td>
                            <td class="fw-semibold text-dark"><?= htmlspecialchars($mhs['nama'] ?? ''); ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Program Studi</th>
                            <td>:</td>
                            <td><?= htmlspecialchars($mhs['prodi'] ?? ''); ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">No HP</th>
                            <td>:</td>
                            <td><?= htmlspecialchars($mhs['no_hp'] ?? ''); ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Email</th>
                            <td>:</td>
                            <td><?= htmlspecialchars($mhs['email'] ?? '-'); ?></td>
                        </tr>
                    </table>
                </div>

                <!-- Tombol Kembali dan Ubah Data di Bawah -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="index.php?controller=mahasiswa&action=index" class="btn btn-secondary px-4">Kembali</a>
                    <a href="index.php?controller=mahasiswa&action=edit&id=<?= $mhs['id_mahasiswa']; ?>" class="btn btn-warning text-white px-4">Ubah Data</a>
                </div>
                <?php else: ?>
                <div class="alert alert-danger text-center mb-0">Data mahasiswa tidak ditemukan.</div>
                <div class="mt-3 text-center">
                    <a href="index.php?controller=mahasiswa&action=index" class="btn btn-secondary px-4">Kembali</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle CDN -->
<?php include 'views/layouts/footer-content.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>