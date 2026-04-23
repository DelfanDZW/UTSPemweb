<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Membuat database jika belum ada
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `tag_based_search`");
    echo "Database 'tag_based_search' berhasil dibuat atau sudah ada.\n";

    // Menggunakan database tersebut
    $pdo->exec("USE `tag_based_search`");

    // Membuat tabel users
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(50) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `role` ENUM('user', 'admin') DEFAULT 'user'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "Tabel 'users' berhasil dibuat.\n";

    // Membuat tabel characters
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `characters` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `tags` VARCHAR(255) NOT NULL,
            `description` TEXT NOT NULL,
            `image` VARCHAR(255) DEFAULT 'default.png'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "Tabel 'characters' berhasil dibuat.\n";

    echo "Selesai! Database siap digunakan.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
