<?php
require 'config/database.php'; // sesuaikan path kamu

try {
    $result = $koleksi_users->insertOne([
        "nama" => "Amar",
        "email" => "amar@gmail.com",
        "created_at" => date("Y-m-d H:i:s")
    ]);

    echo "Data berhasil masuk! ID: " . $result->getInsertedId();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}