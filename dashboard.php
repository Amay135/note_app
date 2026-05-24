<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'config/database.php';

$user_id = $_SESSION['user_id'];
$user_nama = $_SESSION['user_nama'];

// Filter kategori
$filter_kategori = $_GET['kategori'] ?? '';
$search_query = $_GET['q'] ?? '';

// Build filter
$filter = ['user_id' => $user_id];

if ($filter_kategori != '') {
    $filter['kategori'] = $filter_kategori;
}

if ($search_query != '') {
    $filter['$or'] = [
        ['judul' => new MongoDB\BSON\Regex($search_query, 'i')],
        ['isi' => new MongoDB\BSON\Regex($search_query, 'i')]
    ];
}

// Ambil catatan: pinned dulu, lalu terbaru
$semua_notes = $koleksi_notes->find($filter, [
    'sort' => ['pinned' => -1, 'created_at' => -1]
]);

$notes_array = iterator_to_array($semua_notes);

// Pisahkan pinned dan biasa
$pinned_notes = [];
$other_notes = [];
foreach ($notes_array as $note) {
    if (isset($note['pinned']) && $note['pinned'] == true) {
        $pinned_notes[] = $note;
    } else {
        $other_notes[] = $note;
    }
}

// Ambil kategori unik
$kategori_list = $koleksi_notes->distinct('kategori', ['user_id' => $user_id]);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Notes Cihuy</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="app-container">
        <!-- Navbar -->
        <nav class="navbar">
            <a href="dashboard.php" class="brand">Cihuy<span>Notes</span></a>
            <div class="nav-right">
                <div class="user-info">
                    <div class="user-avatar"><?php echo strtoupper(substr($user_nama, 0, 1)); ?></div>
                    <span><?php echo htmlspecialchars($user_nama); ?></span>
                </div>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </nav>

        <!-- Main -->
        <div class="main-content">
            <div class="dashboard-header">
                <h2>Catatan Harian Anda</h2>
                <a href="tambah_note.php" class="btn btn-primary" style="width:auto; padding:12px 28px;">
                    <i class="bi bi-plus-lg icon-btn"></i> Tambah Catatan
                </a>
            </div>

            <?php if (count($notes_array) == 0): ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-pencil-square"></i></div>
                    <h3>Belum ada catatan</h3>
                    <p>Mulai tulis catatan pertamamu sekarang!</p>
                    <a href="tambah_note.php" class="btn btn-primary" style="width:auto; padding:14px 32px;">
                        <i class="bi bi-plus-lg icon-btn"></i> Buat Catatan Baru
                    </a>
                </div>
            <?php else: ?>

                <?php if (count($pinned_notes) > 0): ?>
                    <div class="section-label"><i class="bi bi-pin-angle-fill icon-section"></i> Disematkan</div>
                    <div class="notes-grid" style="margin-bottom: 32px;">
                        <?php foreach ($pinned_notes as $note): ?>
                            <?php include 'partials/note_card.php'; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (count($other_notes) > 0): ?>
                    <?php if (count($pinned_notes) > 0): ?>
                        <div class="section-label"><i class="bi bi-collection icon-section"></i> Lainnya</div>
                    <?php endif; ?>
                    <div class="notes-grid">
                        <?php foreach ($other_notes as $note): ?>
                            <?php include 'partials/note_card.php'; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
</body>

</html>