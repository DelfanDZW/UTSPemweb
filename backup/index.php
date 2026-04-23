<?php
require 'db.php';
session_start();

// Jika sudah login, arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard_admin.php');
    exit();
}

// Tampilkan pesan error jika pengguna diarahkan dari dashboard
if (isset($_SESSION['error_message'])) {
    echo "<p style='color:red;'>" . $_SESSION['error_message'] . "</p>";
    unset($_SESSION['error_message']); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

<div class="container">
    <h2>Arknight Recruitment Calculator</h2>
    <a href="login.php"><button>Login Pengguna</button></a>
    <a href="login_admin.php"><button>Login Admin</button></a>
</div>

</body>
</html>
