<?php
// KONEKSI DATABASE MONGODB
require_once __DIR__ . '/../vendor/autoload.php';

// Kunci koneksi ke MongoDB Atlas (cluster)
$kunci_gudang = "mongodb+srv://regari:kalitengah@cluster0.wcurq0d.mongodb.net/?appName=Cluster0";

try {
    // Buka koneksi ke MongoDB
    $client = new MongoDB\Client($kunci_gudang);

    // Pilih database: notes_app
    $database = $client->notes_app;

    // Pilih collection (tabel)
    $koleksi_users = $database->users;   // Collection untuk data user
    $koleksi_notes = $database->notes;   // Collection untuk data catatan
} catch (Exception $e) {
    die("Gagal konek ke database: " . $e->getMessage());
}
?>