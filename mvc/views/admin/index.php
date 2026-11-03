<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Administrator - Asrama Putri</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<!-- Navbar / Navigasi Atas (Opsional, agar sama persis dengan halaman lain) -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold me-4" href="index.php?controller=dashboard&action=index">Asrama Putri</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php?controller=dashboard&action=index">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?controller=mahasiswa&action=index">Mahasiswa</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?controller=kamar&action=index">Kamar</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?controller=penghuni&action=index">Penghuni</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?controller=pembayaran&action=index">Pembayaran</a></li>
                <li class="nav-item"><a class="nav-link active" href="index.php?controller=admin&action=index">Admin</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-warning fw-semibold" href="index.php?controller=auth&action=logout" onclick="return confirm('Yakin ingin keluar dari sistem?')">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Konten Utama Admin -->
<div class="container py-2">
    <div class="card shadow-sm border-0 p-4">
        <!-- Bagian Judul dan Tombol Tambah -->
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <div>
                <h3 class="fw-bold text-primary mb-1">Data Administrator Asrama</h3>
                <p class="text-muted small mb-0">Daftar akun administrator yang memiliki hak akses penuh ke dalam sistem.</p>
            </div>
            <a href="index.php?controller=admin&action=create" class="btn btn-primary px-3 shadow-sm">+ Tambah Admin</a>
        </div>

        <?php $searchPlaceholder = 'Cari ID, nama, username, atau email...'; include 'views/layouts/search.php'; ?>

        <!-- Tabel Data dengan Warna Header dan Garis yang Rapi -->
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered align-middle" style="min-width: 1050px;">
                <thead class="table-primary text-center">
                    <tr>
                        <th scope="col" style="width: 55px;">No</th>
                        <th scope="col" style="width: 110px;">ID Admin</th>
                        <th scope="col" style="width: 220px;">Nama Lengkap</th>
                        <th scope="col" style="width: 150px;">Username</th>
                        <th scope="col" style="width: 130px;">Password</th>
                        <th scope="col" style="width: 220px;">Email</th>
                        <th scope="col" style="width: 210px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dataAdmin)): ?>
                        <?php $no = $offset + 1; foreach ($dataAdmin as $admin): ?>
                            <tr>
                                <td class="text-center fw-semibold"><?= $no++; ?></td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($admin['id_admin'] ?? $admin['id'] ?? '-'); ?>
                                    </span>
                                </td>
                                <td class="fw-bold text-dark"><?= htmlspecialchars($admin['nama_lengkap'] ?? $admin['nama'] ?? '-'); ?></td>
                                <td><span class="font-monospace text-primary"><?= htmlspecialchars($admin['username'] ?? '-'); ?></span></td>
                                <td class="text-center"><span class="text-muted font-monospace">••••••••</span></td>
                                <td>
                                    <?php if (!empty($admin['email']) && $admin['email'] !== '-'): ?>
                                        <a href="mailto:<?= htmlspecialchars($admin['email']); ?>" class="text-decoration-none">
                                            <?= htmlspecialchars($admin['email']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center text-nowrap">
                                    <div class="d-inline-flex flex-nowrap justify-content-center gap-1">
                                        <a href="index.php?controller=admin&action=detail&id=<?= urlencode($admin['id_admin'] ?? $admin['id'] ?? ''); ?>" class="btn btn-info btn-sm text-white">Lihat</a>
                                        <a href="index.php?controller=admin&action=edit&id=<?= urlencode($admin['id_admin'] ?? $admin['id'] ?? ''); ?>" class="btn btn-warning btn-sm text-white">Ubah</a>
                                        <a href="index.php?controller=admin&action=delete&id=<?= urlencode($admin['id_admin'] ?? $admin['id'] ?? ''); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data admin ini?')">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data administrator ditemukan.</td>
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