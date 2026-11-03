<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Penghuni - Asrama Putri</title>
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
                    <a class="nav-link active" href="index.php?controller=penghuni&action=index">Penghuni</a>
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

<!-- Konten Form Ubah Penghuni -->
<div class="container py-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4">
                <div class="mb-4 border-bottom pb-3">
                    <h3 class="fw-bold text-primary mb-0">Ubah Data Penghuni Asrama</h3>
                </div>

                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-warning" role="alert"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <form action="index.php?controller=penghuni&action=update" method="POST">
                    <!-- Hidden ID Penghuni -->
                    <input type="hidden" name="id_penghuni" value="<?= $penghuni['id_penghuni'] ?? $penghuni['id'] ?? ''; ?>">

                    <!-- Pilihan Mahasiswa -->
                    <div class="mb-3">
                        <label for="id_mahasiswa" class="form-label fw-semibold">Mahasiswa</label>
                        <select class="form-select" id="id_mahasiswa" name="id_mahasiswa" required>
                            <option value="" disabled>-- Pilih Mahasiswa --</option>
                            <?php if (!empty($dataMahasiswa)): ?>
                                <?php foreach ($dataMahasiswa as $mhs): ?>
                                    <?php 
                                        $mhsId = $mhs['id_mahasiswa'] ?? $mhs['id'] ?? '';
                                        $selectedMhsId = $formData['id_mahasiswa'] ?? $penghuni['id_mahasiswa'] ?? '';
                                        $selected = ($selectedMhsId == $mhsId) ? 'selected' : '';
                                    ?>
                                    <option value="<?= $mhsId; ?>" <?= $selected; ?>>
                                        <?= htmlspecialchars($mhs['nama'] ?? $mhs['nama_mahasiswa'] ?? ''); ?> (NIM: <?= htmlspecialchars($mhs['nim'] ?? ''); ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Pilihan Kamar -->
                    <div class="mb-3">
                        <label for="id_kamar" class="form-label fw-semibold">Nomor Kamar</label>
                        <select class="form-select" id="id_kamar" name="id_kamar" required>
                            <option value="" disabled>-- Pilih Nomor Kamar --</option>
                            <?php if (!empty($dataKamar)): ?>
                                <?php foreach ($dataKamar as $kmr): ?>
                                    <?php 
                                        $kmrId = $kmr['id_kamar'] ?? $kmr['id'] ?? '';
                                        $selectedRoomId = $formData['id_kamar'] ?? $penghuni['id_kamar'] ?? '';
                                        $selectedKmr = ($selectedRoomId == $kmrId) ? 'selected' : '';
                                        $roomFull = ($kmr['status'] ?? '') === 'Penuh' && !$selectedKmr;
                                    ?>
                                    <option value="<?= $kmrId; ?>" <?= $selectedKmr; ?> <?= $roomFull ? 'disabled' : ''; ?>>
                                        Kamar <?= htmlspecialchars($kmr['nomor_kamar'] ?? ''); ?> (Kapasitas: <?= htmlspecialchars($kmr['kapasitas'] ?? ''); ?> Orang<?= $roomFull ? ' - Penuh' : ''; ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Tanggal Masuk -->
                    <div class="mb-3">
                        <label for="tanggal_masuk" class="form-label fw-semibold">Tanggal Masuk</label>
                        <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?= htmlspecialchars($formData['tanggal_masuk'] ?? $penghuni['tanggal_masuk'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <!-- Status Penghuni -->
                    <div class="mb-3">
                        <label for="status_penghuni" class="form-label fw-semibold">Status Penghuni</label>
                        <select class="form-select" id="status_penghuni" name="status_penghuni" required>
                            <option value="Aktif" <?= (($formData['status_penghuni'] ?? $penghuni['status_penghuni'] ?? '') === 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                            <option value="Nonaktif" <?= (($formData['status_penghuni'] ?? $penghuni['status_penghuni'] ?? '') === 'Nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="index.php?controller=penghuni&action=index" class="btn btn-secondary px-4">Kembali</a>
                        <button type="submit" class="btn btn-primary px-4">Perbarui Penghuni</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle CDN -->
<?php include 'views/layouts/footer-content.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>