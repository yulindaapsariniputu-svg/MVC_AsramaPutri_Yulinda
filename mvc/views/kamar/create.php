<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kamar - Asrama Putri</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<?php include 'views/layouts/navigation.php'; ?>

<!-- Konten Form Tambah Kamar -->
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4">
                <div class="mb-4 border-bottom pb-3">
                    <h3 class="fw-bold text-primary mb-0">Tambah Data Kamar Asrama</h3>
                </div>

                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-warning" role="alert"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <form action="index.php?controller=kamar&action=store" method="POST">
                    <!-- Nomor Kamar -->
                    <div class="mb-3">
                        <label for="nomor_kamar" class="form-label fw-semibold">Nomor Kamar</label>
                        <input type="text" class="form-control" id="nomor_kamar" name="nomor_kamar"
                               value="<?= htmlspecialchars($formData['nomor_kamar'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                               placeholder="Contoh: 101" inputmode="numeric" pattern="[0-9]+" maxlength="10"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                        <div class="form-text text-muted">Masukkan nomor kamar menggunakan angka saja.</div>
                    </div>

                    <!-- Kapasitas (Dropdown) -->
                    <div class="mb-3">
                        <label for="kapasitas" class="form-label fw-semibold">Kapasitas (Jumlah Orang)</label>
                        <select class="form-select" id="kapasitas" name="kapasitas" required>
                            <option value="" disabled selected>-- Pilih Kapasitas --</option>
                            <?php foreach ([1, 2, 3, 4] as $capacity): ?>
                                <option value="<?= $capacity; ?>" <?= (($formData['kapasitas'] ?? '') == $capacity) ? 'selected' : ''; ?>><?= $capacity; ?> Orang</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Harga / Bulan (Fixed Rp 100.000) -->
                    <div class="mb-3">
                        <label for="harga_per_bulan" class="form-label fw-semibold">Harga / Bulan (Rp)</label>
                        <input type="number" class="form-control bg-light" id="harga_per_bulan" name="harga_per_bulan" value="100000" readonly required>
                        <div class="form-text text-muted">Harga sewa kamar ditetapkan Rp 100.000 / bulan.</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-text text-muted">Status kamar akan diperbarui otomatis berdasarkan jumlah penghuni aktif dan kapasitas kamar.</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="index.php?controller=kamar&action=index" class="btn btn-secondary px-4">Kembali</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Kamar</button>
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