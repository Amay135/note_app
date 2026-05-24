<?php
session_start();
require 'config/database.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$pesan_error = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Cari user berdasarkan email
    $user = $koleksi_users->findOne(['email' => $email]);

    if ($user && password_verify($password, $user['password'])) {
        // Set session
        $_SESSION['user_id'] = (string) $user['_id'];
        $_SESSION['user_nama'] = $user['nama'];
        $_SESSION['user_email'] = $user['email'];

        header("Location: dashboard.php");
        exit;
    } else {
        $pesan_error = "Email atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Notes Cihuy</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="logo">
                <h1>Cihuy<span>Notes</span></h1>
                <p>Masuk ke akun kamu</p>
            </div>

            <?php if ($pesan_error != ""): ?>
                <div class="alert alert-error"><?php echo $pesan_error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="nama@email.com" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary">Masuk</button>
            </form>

            <div class="auth-footer">
                Belum punya akun? <a href="register.php">Daftar sekarang</a>
            </div>
        </div>
    </div>
</body>

</html>