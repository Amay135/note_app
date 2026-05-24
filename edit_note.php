<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'config/database.php';

$pesan = "";

// Ambil ID dari URL
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$note_id = $_GET['id'];
$barcode = new MongoDB\BSON\ObjectId($note_id);

// Ambil data catatan lama
$note = $koleksi_notes->findOne([
    '_id' => $barcode,
    'user_id' => $_SESSION['user_id']
]);

if (!$note) {
    header("Location: dashboard.php");
    exit;
}

// Proses update
if (isset($_POST['update'])) {
    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);
    $kategori = trim($_POST['kategori']);
    $reminder = $_POST['reminder'] ?? '';

    if ($judul == '' || $isi == '') {
        $pesan = "Judul dan isi catatan wajib diisi!";
    } else {
        $data_baru = [
            'judul' => $judul,
            'isi' => $isi,
            'kategori' => $kategori,
            'reminder' => $reminder
        ];

        $koleksi_notes->updateOne(
            ['_id' => $barcode],
            ['$set' => $data_baru]
        );

        header("Location: dashboard.php");
        exit;
    }
}

$kategori_aktif = $note['kategori'] ?? '';
$reminder_aktif = $note['reminder'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Catatan - Notes Cihuy</title>
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
                <h2>Edit Catatan</h2>

                <?php if ($pesan != ""): ?>
                    <div class="alert alert-error"><?php echo $pesan; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="judul" value="<?php echo htmlspecialchars($note['judul']); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Isi Catatan</label>
                        <textarea name="isi" rows="6" required><?php echo htmlspecialchars($note['isi']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="kategori">
                            <option value="" <?php echo ($kategori_aktif == '') ? 'selected' : ''; ?>>Tanpa Kategori
                            </option>
                            <option value="Kuliah" <?php echo ($kategori_aktif == 'Kuliah') ? 'selected' : ''; ?>>Kuliah
                            </option>
                            <option value="Kerja" <?php echo ($kategori_aktif == 'Kerja') ? 'selected' : ''; ?>>Kerja
                            </option>
                            <option value="Keuangan" <?php echo ($kategori_aktif == 'Keuangan') ? 'selected' : ''; ?>>
                                Keuangan</option>
                            <option value="Penting" <?php echo ($kategori_aktif == 'Penting') ? 'selected' : ''; ?>>
                                Penting</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Deadline / Pengingat</label>
                        <input type="date" name="reminder" value="<?php echo htmlspecialchars($reminder_aktif); ?>">
                    </div>
            </div>
            <div class="form-actions">
                <a href="dashboard.php" class="btn btn-secondary">Batal</a>
                <button type="submit" name="update" class="btn btn-primary">Update Catatan</button>
            </div>
            </form>
        </div>
    </div>
    </div>
</body>

</html>