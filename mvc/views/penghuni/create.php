<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penghuni - Asrama Putri</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<?php include 'views/layouts/navigation.php'; ?>

<!-- Konten Form Tambah Penghuni -->
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4">
                <div class="mb-4 border-bottom pb-3">
                    <h3 class="fw-bold text-primary mb-0">Tambah Data Penghuni Asrama</h3>
                </div>

                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-warning" role="alert"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <form action="index.php?controller=penghuni&action=store" method="POST">
                    <!-- Pilihan Mahasiswa (Terhubung dengan tabel mahasiswa) -->
                    <div class="mb-3">
                        <label for="id_mahasiswa" class="form-label fw-semibold">Pilih Mahasiswa</label>
                        <select class="form-select" id="id_mahasiswa" name="id_mahasiswa" required>
                            <option value="" disabled <?= empty($formData['id_mahasiswa']) ? 'selected' : ''; ?>>-- Pilih Mahasiswa --</option>
                            <?php if (!empty($dataMahasiswa)): ?>
                                <?php foreach ($dataMahasiswa as $mhs): ?>
                                    <?php $mhsId = $mhs['id_mahasiswa'] ?? $mhs['id'] ?? ''; ?>
                                    <option value="<?= $mhsId; ?>" <?= (($formData['id_mahasiswa'] ?? '') == $mhsId) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($mhs['nama'] ?? $mhs['nama_mahasiswa'] ?? ''); ?> (NIM: <?= htmlspecialchars($mhs['nim'] ?? ''); ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="form-text text-muted">Data mahasiswa diambil dari database mahasiswa yang terdaftar.</div>
                    </div>

                    <!-- Pilihan Kamar (Terhubung dengan tabel kamar) -->
                    <div class="mb-3">
                        <label for="id_kamar" class="form-label fw-semibold">Nomor Kamar</label>
                        <select class="form-select" id="id_kamar" name="id_kamar" required>
                            <option value="" disabled <?= empty($formData['id_kamar']) ? 'selected' : ''; ?>>-- Pilih Nomor Kamar --</option>
                            <?php if (!empty($dataKamar)): ?>
                                <?php foreach ($dataKamar as $kmr): ?>
                                    <?php $roomId = $kmr['id_kamar'] ?? $kmr['id'] ?? ''; ?>
                                    <?php $roomFull = ($kmr['status'] ?? '') === 'Penuh'; ?>
                                    <option value="<?= $roomId; ?>" <?= (($formData['id_kamar'] ?? '') == $roomId) ? 'selected' : ''; ?> <?= $roomFull ? 'disabled' : ''; ?>>
                                        Kamar <?= htmlspecialchars($kmr['nomor_kamar'] ?? ''); ?> (Kapasitas: <?= htmlspecialchars($kmr['kapasitas'] ?? ''); ?> Orang<?= $roomFull ? ' - Penuh' : ''; ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="form-text text-muted">Pilih nomor kamar asrama yang tersedia.</div>
                    </div>

                    <!-- Tanggal Masuk -->
                    <div class="mb-3">
                        <label for="tanggal_masuk" class="form-label fw-semibold">Tanggal Masuk</label>
                        <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?= htmlspecialchars($formData['tanggal_masuk'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <!-- Status Penghuni -->
                    <div class="mb-3">
                        <label for="status_penghuni" class="form-label fw-semibold">Status Penghuni</label>
                        <select class="form-select" id="status_penghuni" name="status_penghuni" required>
                            <option value="Aktif" <?= (($formData['status_penghuni'] ?? 'Aktif') === 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                            <option value="Nonaktif" <?= (($formData['status_penghuni'] ?? '') === 'Nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="index.php?controller=penghuni&action=index" class="btn btn-secondary px-4">Kembali</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Penghuni</button>
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