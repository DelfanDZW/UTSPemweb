<?php
require 'db.php';

$registrationMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
    $stmt->execute([$username, $password]);

    $registrationMessage = "Pendaftaran pengguna berhasil! Silakan login.";
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Form Registrasi Admin</title>
    <link rel="stylesheet" type="text/css" href="stylereg.css">
</head>
<body>

    <form action="registrasi.php" method="POST">
        <h2>Form Registrasi</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Daftar Pengguna Baru</button>
    </form>

    <?php if (!empty($registrationMessage)) : ?>
        <p><?php echo $registrationMessage; ?></p>
        <a href="login.php"><button>Login</button></a>
    <?php endif; ?>

</body>
</html>