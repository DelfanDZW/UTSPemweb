<?php
require 'db.php';

if (!isset($_GET['id'])) {
    echo "ID karakter tidak ditemukan!";
    exit();
}

$character_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM characters WHERE id = ?");
$stmt->execute([$character_id]);
$character = $stmt->fetch();

if ($character) {
    echo '
    <div class="container">
    <link rel="stylesheet" href="stylechar.css">
        <h1>' . htmlspecialchars($character['name']) . '</h1>
        <img src="img/' . htmlspecialchars($character['image']) . '" alt="Character Image" width="150" height="150">
        <p class="tags"><strong>Tags:</strong> ' . htmlspecialchars($character['tags']) . '</p>
        <p>' . htmlspecialchars($character['description']) . '</p>
        <a href="dashboard.php"><button>Kembali</button></a>
    </div>';
} else {
    echo "Karakter tidak ditemukan!";
}
?>
