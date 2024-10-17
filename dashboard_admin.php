<?php
require 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit();
}

if (isset($_GET['delete'])) {
    $character_id = $_GET['delete'];
    $stmt = $pdo->prepare("SELECT image FROM characters WHERE id = ?");
    $stmt->execute([$character_id]);
    $character = $stmt->fetch();
    
    if ($character) {
        $image_path = 'img/' . $character['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        $stmt = $pdo->prepare("DELETE FROM characters WHERE id = ?");
        $stmt->execute([$character_id]);
        
        echo "Karakter berhasil dihapus!";
    } else {
        echo "Karakter tidak ditemukan.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $tags = implode(',', $_POST['tags']);
    $description = $_POST['description'];
    
    $image = $_FILES['image']['name'];
    $target_dir = "img/";
    $target_file = $target_dir . basename($image);
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $stmt = $pdo->prepare("INSERT INTO characters (name, tags, description, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $tags, $description, $image]);
        echo "Karakter berhasil ditambahkan!";
    } else {
        echo "Error mengunggah gambar.";
    }
}

$stmt = $pdo->query("SELECT * FROM characters");
$characters = $stmt->fetchAll();
?>

<html>
    <link rel="stylesheet" href="styleadmin.css">
    <h2>Tambah Karakter Baru</h2>
    <form method="POST" action="dashboard_admin.php" enctype="multipart/form-data">
        <label>Nama Karakter:</label>
        <input type="text" name="name" required><br>
    
        <label>Pilih Tag:</label><br>
        <input type="checkbox" name="tags[]" value="Melee"> Melee<br>
        <input type="checkbox" name="tags[]" value="Defense"> Defense<br>
        <input type="checkbox" name="tags[]" value="Ranged"> Ranged<br>
        <input type="checkbox" name="tags[]" value="Caster"> Caster<br>
        <input type="checkbox" name="tags[]" value="Medic"> Medic<br>
        <input type="checkbox" name="tags[]" value="Guard"> Guard<br>
        <input type="checkbox" name="tags[]" value="Vanguard"> Vanguard<br>
        <input type="checkbox" name="tags[]" value="Sniper"> Sniper<br>
        <input type="checkbox" name="tags[]" value="Supporter"> Supporter<br>
    
        <label>Deskripsi Karakter:</label><br>
        <textarea name="description" rows="5" cols="30" required></textarea><br>
    
        <label>Unggah Gambar Karakter:</label>
        <input type="file" name="image" accept="image/*" required><br>
    
        <button type="submit">Tambah Karakter</button>
    </form>
    <a href="logout.php" id="logout-button">Logout</a>
</html>

<h2>Daftar Karakter yang Sudah Ada</h2>
<table border="1">
    <tr>
        <th>Nama</th>
        <th>Tag</th>
        <th>Deskripsi</th>
        <th>Gambar</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($characters as $character): ?>
    <tr>
        <td><?php echo htmlspecialchars($character['name']); ?></td>
        <td><?php echo htmlspecialchars($character['tags']); ?></td>
        <td><?php echo htmlspecialchars($character['description']); ?></td>
        <td><img src="img/<?php echo htmlspecialchars($character['image']); ?>" width="100" height="100"></td>
        <td>
            <a href="dashboard_admin.php?delete=<?php echo $character['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus karakter ini?')">Hapus</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>