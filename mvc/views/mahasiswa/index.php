<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Master Mahasiswa - Asrama Putri</title>
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
            <!-- Menu di sebelah kiri -->
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
                <!-- Menu Admin yang ditambahkan -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=admin&action=index">Admin</a>
                </li>
            </ul>
            <!-- Logout di sebelah kanan -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-warning fw-semibold" href="index.php?controller=auth&action=logout" onclick="return confirm('Yakin ingin keluar dari sistem?')">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Konten Utama Data Mahasiswa -->
<div class="container py-2">
    <div class="card shadow-sm border-0 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-primary mb-0">Data Master Mahasiswa</h3>
            <a href="index.php?controller=mahasiswa&action=create" class="btn btn-primary">+ Tambah Mahasiswa</a>
        </div>

        <?php $searchPlaceholder = 'Cari NIM, nama, prodi, atau nomor HP...'; include 'views/layouts/search.php'; ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Prodi</th>
                        <th>No HP</th>
                        <th>Email</th>
                        <th class="text-center" style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dataMahasiswa)): $no = $offset + 1; foreach ($dataMahasiswa as $row): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nim'] ?? ''); ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($row['nama'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($row['prodi'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($row['no_hp'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($row['email'] ?? '-'); ?></td>
                        <td class="text-center">
                            <a href="index.php?controller=mahasiswa&action=detail&id=<?= $row['id_mahasiswa']; ?>" class="btn btn-sm btn-info text-white">Lihat</a>
                            <a href="index.php?controller=mahasiswa&action=edit&id=<?= $row['id_mahasiswa']; ?>" class="btn btn-sm btn-warning text-white">Ubah</a>
                            <a href="index.php?controller=mahasiswa&action=delete&id=<?= $row['id_mahasiswa']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data mahasiswa ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data mahasiswa terdaftar.</td>
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