<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'config/database.php';

$pesan = "";

if (isset($_POST['simpan'])) {
    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);
    $kategori = trim($_POST['kategori']);
    $reminder = $_POST['reminder'] ?? '';

    if ($judul == '' || $isi == '') {
        $pesan = "Judul dan isi catatan wajib diisi!";
    } else {
        $data_note = [
            'user_id' => $_SESSION['user_id'],
            'judul' => $judul,
            'isi' => $isi,
            'kategori' => $kategori,
            'pinned' => false,
            'reminder' => $reminder,
            'created_at' => date("Y-m-d H:i:s")
        ];

        $hasil = $koleksi_notes->insertOne($data_note);

        if ($hasil->getInsertedCount() > 0) {
            header("Location: dashboard.php");
            exit;
        } else {
            $pesan = "Gagal menyimpan catatan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Catatan - Notes Cihuy</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="app-container">
        <nav class="navbar">
            <a href="dashboard.php" class="brand">Cihuy<span>Notes</span></a>
            <div class="nav-right">
                <a href="dashboard.php" class="btn-logout">← Kembali</a>
            </div>
        </nav>

        <div class="form-wrapper">
            <div class="form-card">
                <h2>Catatan Baru</h2>

                <?php if ($pesan != ""): ?>
                    <div class="alert alert-error"><?php echo $pesan; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="judul" placeholder="Judul catatan..." required>
                    </div>

                    <div class="form-group">
                        <label>Isi Catatan</label>
                        <textarea name="isi" placeholder="Tulis catatan kamu di sini..." rows="6" required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori">
                                <option value="">Tanpa Kategori</option>
                                <option value="Kuliah">Kuliah</option>
                                <option value="Kerja">Kerja</option>
                                <option value="Keuangan">Keuangan</option>
                                <option value="Penting">Penting</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Deadline / Pengingat</label>
                            <input type="date" name="reminder">
                        </div>
                    </div>
                    <div class="form-actions">
                        <a href="dashboard.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" name="simpan" class="btn btn-primary">Simpan Catatan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>