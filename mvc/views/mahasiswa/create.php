<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa Baru - Asrama Putri</title>
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

<!-- Konten Form Tambah Mahasiswa -->
<div class="container py-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4">
                <div class="mb-4 border-bottom pb-3">
                    <h3 class="fw-bold text-primary mb-0">Tambah Mahasiswa Baru</h3>
                </div>

                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-warning" role="alert"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <form action="index.php?controller=mahasiswa&action=store" method="POST" onsubmit="return validateForm()">
                    <!-- NIM: Hanya Angka (Dibatasi secara ketat via JS & inputmode) -->
                    <div class="mb-3">
                        <label for="nim" class="form-label fw-semibold">NIM (Nomor Induk Mahasiswa)</label>
                        <input type="text" class="form-control" id="nim" name="nim" value="<?= htmlspecialchars($formData['nim'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Contoh: 220101001" inputmode="numeric" onkeypress="return hanyaAngka(event)" required>
                        <div class="form-text text-muted">NIM wajib berupa angka (tidak boleh ada huruf).</div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($formData['nama'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Masukkan nama lengkap mahasiswa" required>
                    </div>

                    <!-- Program Studi: Dropdown Prodi UHN I Gusti Bagus Sugriwa Denpasar -->
                    <div class="mb-3">
                        <label for="prodi" class="form-label fw-semibold">Program Studi / Prodi (UHN Sugriwa Denpasar)</label>
                        <select class="form-select" id="prodi" name="prodi" data-selected-value="<?= htmlspecialchars($formData['prodi'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            <option value="" disabled selected>-- Pilih Program Studi --</option>
                            <optgroup label="Fakultas Sains dan Teknologi">
                                <option value="Informatika">S1 - Informatika</option>
                                <option value="Sains Informasi">S1 - Sains Informasi</option>
                                <option value="Desain Komunikasi Visual">S1 - Desain Komunikasi Visual</option>
                            </optgroup>
                            <optgroup label="Fakultas Dharma Duta">
                                <option value="Hukum Hindu">S1 - Hukum Hindu</option>
                                <option value="Ilmu Komunikasi Hindu">S1 - Ilmu Komunikasi Hindu</option>
                                <option value="Penerangan Agama Hindu">S1 - Penerangan Agama Hindu</option>
                                <option value="Industri Perjalanan">S1 - Industri Perjalanan</option>
                                <option value="Kewirausahaan">S1 - Kewirausahaan</option>
                            </optgroup>
                            <optgroup label="Fakultas Dharma Acarya">
                                <option value="Pendidikan Agama Hindu">S1 - Pendidikan Agama Hindu</option>
                                <option value="Pendidikan Guru Sekolah Dasar Hindu">S1 - Pendidikan Guru Sekolah Dasar (PGSD) Hindu</option>
                                <option value="Pendidikan Anak Usia Dini Hindu">S1 - Pendidikan Guru PAUD Hindu</option>
                                <option value="Pendidikan Bahasa Inggris">S1 - Pendidikan Bahasa Inggris</option>
                                <option value="Sastra Agama dan Pendidikan Bahasa Bali">S1 - Sastra Agama & Pendidikan Bahasa Bali</option>
                            </optgroup>
                            <optgroup label="Fakultas Brahma Widya">
                                <option value="Filsafat Hindu">S1 - Filsafat Hindu</option>
                                <option value="Teologi Hindu">S1 - Teologi Hindu</option>
                                <option value="Yoga Kesehatan">S1 - Yoga Kesehatan</option>
                            </optgroup>
                            <optgroup label="Program Diploma">
                                <option value="D-III Kepanditaan">D-III Kepanditaan</option>
                            </optgroup>
                        </select>
                    </div>

                    <!-- Nomor HP: Hanya Angka (Dibatasi secara ketat via JS & inputmode) -->
                    <div class="mb-3">
                        <label for="no_hp" class="form-label fw-semibold">Nomor HP / WhatsApp</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= htmlspecialchars($formData['no_hp'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Contoh: 081234567890" inputmode="numeric" onkeypress="return hanyaAngka(event)" required>
                        <div class="form-text text-muted">Nomor HP wajib berupa angka (tidak boleh ada huruf).</div>
                    </div>

                    <!-- Email: Wajib berakhiran @gmail.com -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($formData['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="contoh@gmail.com" required>
                        <div class="form-text text-muted">Email wajib menggunakan domain @gmail.com</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="index.php?controller=mahasiswa&action=index" class="btn btn-secondary px-4">Kembali</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Mahasiswa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script Validasi JavaScript -->
<script>
var prodiSelect = document.getElementById("prodi");
if (prodiSelect.dataset.selectedValue) {
    prodiSelect.value = prodiSelect.dataset.selectedValue;
}

// Fungsi agar input hanya bisa menerima angka saat diketik
function hanyaAngka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

// Validasi tambahan saat form disubmit
function validateForm() {
    var nim = document.getElementById("nim").value.trim();
    var no_hp = document.getElementById("no_hp").value.trim();
    var email = document.getElementById("email").value.trim();

    // Validasi NIM harus angka
    if (isNaN(nim)) {
        alert("NIM harus berupa angka saja!");
        document.getElementById("nim").focus();
        return false;
    }

    // Validasi No HP harus angka
    if (isNaN(no_hp)) {
        alert("Nomor HP harus berupa angka saja!");
        document.getElementById("no_hp").focus();
        return false;
    }

    // Validasi Email harus @gmail.com
    if (!email.endsWith("@gmail.com")) {
        alert("Email harus menggunakan domain @gmail.com! (Contoh: nama@gmail.com)");
        document.getElementById("email").focus();
        return false;
    }

    return true;
}
</script>

<!-- Bootstrap 5 JS Bundle CDN -->
<?php include 'views/layouts/footer-content.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>