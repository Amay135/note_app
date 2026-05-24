<?php
// KONEKSI DATABASE MONGODB
require_once __DIR__ . '/../vendor/autoload.php';

// Kunci koneksi ke MongoDB Atlas (cluster)
$kunci_gudang = "mongodb+srv://amarmaruf:amarmaruf135@new.5cbkswv.mongodb.net/?appName=new";

try {
    // 🔥 GANTI NAMA VARIABLE (INI KUNCI FIX)
    $mongo_client = new MongoDB\Client($kunci_gudang);

    // Pilih database
    $database = $mongo_client->notes_app;

    // Collection
    $koleksi_users = $database->users;
    $koleksi_notes = $database->notes;

} catch (Exception $e) {
    die("Gagal konek ke database: " . $e->getMessage());
}
?>