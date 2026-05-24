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

    // Hapus hanya catatan milik user yang sedang login
    $koleksi_notes->deleteOne([
        '_id' => $barcode,
        'user_id' => $_SESSION['user_id']
    ]);
}

header("Location: dashboard.php");
exit;
?>