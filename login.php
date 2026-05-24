<?php
session_start();
require 'vendor/autoload.php';
require 'config/database.php';
 
// toleransi JWT Google
\Firebase\JWT\JWT::$leeway = 300;
 
// redirect kalau sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
 
// GOOGLE CLIENT ID
$client_id = "725037062358-jcnaol52lhit7as24atkivh3nij30j2a.apps.googleusercontent.com";
 
$pesan_error = "";
 
if (isset($_POST['credential'])) {
    $client_google = new Google_Client(['client_id' => $client_id]);
    $payload = $client_google->verifyIdToken($_POST['credential']);
 
    if ($payload) {
        $email = $payload['email'];
        $nama = $payload['name'];
 
        // cek user
        $user = $koleksi_users->findOne(['email' => $email]);
 
        // auto register kalau belum ada
        if (!$user) {
            $koleksi_users->insertOne([
                'nama' => $nama,
                'email' => $email,
                'auth_type' => 'google'
            ]);
 
            $user = $koleksi_users->findOne(['email' => $email]);
        }
 
        // set session
        $_SESSION['user_id'] = (string) $user['_id'];
        $_SESSION['user_nama'] = $user['nama'];
        $_SESSION['user_email'] = $user['email'];
 
        header("Location: dashboard.php");
        exit;
    } else {
        $pesan_error = "Login Google gagal!";
    }
}
 
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
 
    $user = $koleksi_users->findOne(['email' => $email]);
 
    if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
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
    <script src="https://accounts.google.com/gsi/client" async defer></script>
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
                    <input type="email" name="email" placeholder="nama@gmail.com" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
                <div id="g_id_onload" data-client_id="<?php echo $client_id; ?>"
                    data-login_uri="http://localhost/noteapp/login.php" data-auto_prompt="false">
                </div>
                <button type="submit" name="login" class="btn btn-primary">Masuk</button>
                <div class="divider" style="text-align:center; margin:15px 0;">Atau Masuk dengan Google</div>
                <div class="google-btn-wrapper">
                    <div class="g_id_signin" data-type="standard" data-size="large" data-theme="filled_black"
                        data-shape="rectangular" data-width="326">
                    </div>
                </div>
            </form>
 
            <div class="auth-footer">
                Belum punya akun? <a href="register.php">Daftar sekarang</a>
            </div>
        </div>
    </div>
</body>
 
</html>
