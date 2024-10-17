<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
    $stmt->execute([$username, $password]);

    echo "Pendaftaran pengguna berhasil! Silakan login.";
    echo '<a href="login.php"><button>Login Pengguna</button></a>';  
}
?>

<form method="POST" action="registrasi.php">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Daftar Pengguna Baru</button>
</form>
