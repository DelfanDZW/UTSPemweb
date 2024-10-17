<?php
require 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit();
}

// Mendapatkan data karakter berdasarkan ID
if (isset($_GET['id'])) {
    $character_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM characters WHERE id = ?");
    $stmt->execute([$character_id]);
    $character = $stmt->fetch();

    if (!$character) {
        echo "Karakter tidak ditemukan!";
        exit();
    }
}

// Proses update data karakter
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $tags = implode(',', $_POST['tags']);
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $target_dir = "img/";
    $target_file = $target_dir . basename($image);

    // Periksa apakah gambar baru diunggah atau tidak
    if (!empty($image)) {
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $stmt = $pdo->prepare("UPDATE characters SET name = ?, tags = ?, description = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $tags, $description, $image, $character_id]);
    } else {
        // Jika gambar tidak diubah, hanya update field lainnya
        $stmt = $pdo->prepare("UPDATE characters SET name = ?, tags = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $tags, $description, $character_id]);
    }
    
    echo "Karakter berhasil diperbarui!";
    header('Location: dashboard_admin.php');
    exit();
}
?>

<html>
    <link rel="stylesheet" href="styleadmin.css">
    <h2>Edit Karakter</h2>
    <form method="POST" action="edit_character.php?id=<?php echo $character_id; ?>" enctype="multipart/form-data">
        <label>Nama Karakter:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($character['name']); ?>" required><br>
    
        <label>Pilih Tag:</label><br>
        <?php 
        $existing_tags = explode(',', $character['tags']);
        $tags = ["Melee", "Defense", "Ranged", "Caster", "Medic", "Guard", "Vanguard", "Sniper", "Supporter"];
        foreach ($tags as $tag): ?>
            <input type="checkbox" name="tags[]" value="<?php echo $tag; ?>" <?php echo in_array($tag, $existing_tags) ? 'checked' : ''; ?>> <?php echo $tag; ?><br>
        <?php endforeach; ?>
    
        <label>Deskripsi Karakter:</label><br>
        <textarea name="description" rows="5" cols="30" required><?php echo htmlspecialchars($character['description']); ?></textarea><br>
    
        <label>Unggah Gambar Karakter (Opsional):</label>
        <input type="file" name="image" accept="image/*"><br>
        <img src="img/<?php echo htmlspecialchars($character['image']); ?>" width="100" height="100"><br>
    
        <button type="submit">Simpan Perubahan</button>
    </form>
    <a href="dashboard_admin.php">Kembali</a>
</html>
