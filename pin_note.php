<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'config/database.php';

if (isset($_GET['id'])) {
    $note_id = $_GET['id'];
    $barcode = new MongoDB\BSON\ObjectId($note_id);

    // Ambil note saat ini
    $note = $koleksi_notes->findOne([
        '_id' => $barcode,
        'user_id' => $_SESSION['user_id']
    ]);

    if ($note) {
        // Toggle pin status
        $status_baru = !(isset($note['pinned']) && $note['pinned'] == true);

        $koleksi_notes->updateOne(
            ['_id' => $barcode],
            ['$set' => ['pinned' => $status_baru]]
        );
    }
}

header("Location: dashboard.php");
exit;
?>