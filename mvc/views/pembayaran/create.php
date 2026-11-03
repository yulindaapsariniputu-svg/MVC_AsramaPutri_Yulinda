<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pembayaran - Asrama Putri</title>
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

<!-- Konten Form Tambah Pembayaran -->
<div class="container py-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4">
                <div class="mb-4 border-bottom pb-3">
                    <h3 class="fw-bold text-primary mb-0">Tambah Data Pembayaran Asrama</h3>
                </div>

                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-warning" role="alert"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <form action="index.php?controller=pembayaran&action=store" method="POST">
                    <!-- Pilihan Penghuni -->
                    <div class="mb-3">
                        <label for="id_penghuni" class="form-label fw-semibold">Pilih Penghuni</label>
                        <select class="form-select" id="id_penghuni" name="id_penghuni" required>
                            <option value="" disabled <?= empty($formData['id_penghuni']) ? 'selected' : ''; ?>>-- Pilih Penghuni --</option>
                            <?php if (!empty($dataPenghuni)): ?>
                                <?php foreach ($dataPenghuni as $penghuni): ?>
                                    <?php 
                                        $namaMhs = $penghuni['nama_mahasiswa'] ?? $penghuni['nama'] ?? $penghuni['nama_mhs'] ?? 'Tanpa Nama';
                                        $noKamar = $penghuni['nomor_kamar'] ?? '-';
                                        $idPenghuni = $penghuni['id_penghuni'] ?? $penghuni['id'] ?? '';
                                        $bulanTerbayar = array_filter(explode(',', $penghuni['bulan_terbayar'] ?? ''));
                                    ?>
                                    <option value="<?= htmlspecialchars((string)$idPenghuni, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-paid-months="<?= htmlspecialchars(json_encode(array_values($bulanTerbayar)), ENT_QUOTES, 'UTF-8'); ?>"
                                            <?= (($formData['id_penghuni'] ?? '') == $idPenghuni) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($namaMhs); ?> (Kamar: <?= htmlspecialchars($noKamar); ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="form-text text-muted">Hanya mahasiswa yang sudah terdaftar sebagai penghuni kamar yang muncul di sini.</div>
                    </div>

                    <!-- Bulan Bayar (Format Bulan & Tahun) -->
                    <div class="mb-3">
                        <label for="bulan_bayar" class="form-label fw-semibold">Bulan Bayar (Periode Tagihan)</label>
                        <input type="month" class="form-control" id="bulan_bayar" name="bulan_bayar" value="<?= htmlspecialchars($formData['bulan_bayar'] ?? date('Y-m'), ENT_QUOTES, 'UTF-8'); ?>" required>
                        <div class="form-text text-muted">Pilih bulan dan tahun yang ingin dibayarkan oleh penghuni.</div>
                    </div>

                    <!-- Jumlah Bayar -->
                    <div class="mb-3">
                        <label for="jumlah_bayar" class="form-label fw-semibold">Jumlah Bayar (Rp)</label>
                        <input type="number" class="form-control" id="jumlah_bayar" name="jumlah_bayar" value="<?= htmlspecialchars($formData['jumlah_bayar'] ?? '100000', ENT_QUOTES, 'UTF-8'); ?>" required>
                        <div class="form-text text-muted">Tarif standar sewa kamar asrama adalah Rp 100.000 / bulan.</div>
                    </div>

                    <!-- Tanggal Transaksi Bayar -->
                    <div class="mb-3">
                        <label for="tanggal_bayar" class="form-label fw-semibold">Tanggal Transaksi Bayar</label>
                        <input type="date" class="form-control" id="tanggal_bayar" name="tanggal_bayar" value="<?= htmlspecialchars($formData['tanggal_bayar'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <!-- Status Bayar -->
                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold">Status Pembayaran</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Lunas" <?= (($formData['status'] ?? 'Lunas') === 'Lunas') ? 'selected' : ''; ?>>Lunas</option>
                            <option value="Belum Lunas" <?= (($formData['status'] ?? '') === 'Belum Lunas') ? 'selected' : ''; ?>>Belum Lunas</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="index.php?controller=pembayaran&action=index" class="btn btn-secondary px-4">Kembali</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Pembayaran</button>
                    </div>
                </form>
                <script>
                    const paymentMonth = document.getElementById('bulan_bayar');
                    const residentSelect = document.getElementById('id_penghuni');

                    function filterPaidResidents() {
                        Array.from(residentSelect.options).forEach((option) => {
                            if (!option.value) {
                                return;
                            }

                            const paidMonths = JSON.parse(option.dataset.paidMonths || '[]');
                            const alreadyPaid = paidMonths.includes(paymentMonth.value);
                            option.hidden = alreadyPaid;
                            option.disabled = alreadyPaid;

                            if (alreadyPaid && option.selected) {
                                residentSelect.value = '';
                            }
                        });
                    }

                    paymentMonth.addEventListener('change', filterPaidResidents);
                    filterPaidResidents();
                </script>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle CDN -->
<?php include 'views/layouts/footer-content.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>