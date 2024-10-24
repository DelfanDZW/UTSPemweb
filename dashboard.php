<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Silakan login dulu.";
    header('Location: index.php');
    exit();
}


$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user) {
    echo "<h1>Recruitment Calculator</h1>";
    echo "<h1>Pilih Tag</h1>";
    echo '<a href="logout.php" class="logout-button">Logout</a>';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selected_tags = $_POST['tags'];
        $selected_tags_str = implode(',', $selected_tags);
        
        $query = "SELECT * FROM characters WHERE ";
        foreach ($selected_tags as $tag) {
            $query .= "tags LIKE '%$tag%' OR ";
        }
        $query = rtrim($query, ' OR '); 
        
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $characters = $stmt->fetchAll();
        
        if ($characters) {
            echo "<h2>Karakter ditemukan:</h2>";
            foreach ($characters as $character) {
                echo "<div>";
                echo "<h3>" . htmlspecialchars($character['name']) . "</h3>";
                
                // Menambahkan link ke halaman detail karakter
                echo '<a href="character_detail.php?id=' . htmlspecialchars($character['id']) . '">';
                echo '<img src="img/' . htmlspecialchars($character['image']) . '" alt="Character Image" width="150" height="150">';
                echo '</a>';
                
                echo "<p>" . htmlspecialchars($character['tags']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "Tidak ada karakter yang cocok dengan tag yang dipilih.";
        }
    }
}
?>

<form method="POST" action="dashboard.php">
<link rel="stylesheet" href="style.css">
    <div class="tag-container">
        <label class="tag-checkbox">
            <input type="checkbox" name="tags[]" value="Melee">
            <span>Melee</span>
        </label>
        <label class="tag-checkbox">
            <input type="checkbox" name="tags[]" value="Defense">
            <span>Defense</span>
        </label>
        <label class="tag-checkbox">
            <input type="checkbox" name="tags[]" value="Ranged">
            <span>Ranged</span>
        </label>
        <label class="tag-checkbox">
            <input type="checkbox" name="tags[]" value="Caster">
            <span>Caster</span>
        </label>
        <label class="tag-checkbox">
            <input type="checkbox" name="tags[]" value="Medic">
            <span>Medic</span>
        </label>
        <label class="tag-checkbox">
            <input type="checkbox" name="tags[]" value="Guard">
            <span>Guard</span>
        </label>
        <label class="tag-checkbox">
            <input type="checkbox" name="tags[]" value="Vanguard">
            <span>Vanguard</span>
        </label>
        <label class="tag-checkbox">
            <input type="checkbox" name="tags[]" value="Sniper">
            <span>Sniper</span>
        </label>
        <label class="tag-checkbox">
            <input type="checkbox" name="tags[]" value="Supporter">
            <span>Supporter</span>
        </label>
    </div>
    
    <button type="submit">Cari Karakter</button>
</form>
