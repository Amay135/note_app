<?php
session_start();
require 'config/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$pesan = "";
$pesan_type = "";

if (isset($_POST['daftar'])) {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Cek email sudah terdaftar
    $cek = $koleksi_users->findOne(['email' => $email]);

    if ($cek) {
        $pesan = "Email sudah terdaftar! Gunakan email lain.";
        $pesan_type = "error";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $koleksi_users->insertOne([
            'nama' => $nama,
            'email' => $email,
            'password' => $hash,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        $pesan = "Pendaftaran berhasil! Silakan login.";
        $pesan_type = "success";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Notes Cihuy</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="logo">
                <h1>Cihuy<span>Notes</span></h1>
                <p>Buat akun baru</p>
            </div>

            <?php if ($pesan != ""): ?>
                <div class="alert alert-<?php echo $pesan_type; ?>"><?php echo $pesan; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Nama kamu" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="nama@email.com" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter" minlength="6" required>
                </div>
                <button type="submit" name="daftar" class="btn btn-primary">Daftar</button>
            </form>

            <div class="auth-footer">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>
        </div>
    </div>
</body>

</html>