<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
    $stmt->execute([$username, $password]);

    echo "Pendaftaran admin berhasil! Silakan login.";
    echo '<a href="login_admin.php"><button>Login Admin</button></a>';  
}
?>

<form method="POST" action="registrasi_admin.php">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Daftar Admin Baru</button>
</form>
