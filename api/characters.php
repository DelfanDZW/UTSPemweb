<?php
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM characters WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $character = $stmt->fetch();
        if ($character) {
            echo json_encode($character);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Character not found"]);
        }
    } else {
        $tags = $_GET['tags'] ?? '';
        if ($tags) {
            $tagArray = explode(',', $tags);
            $query = "SELECT * FROM characters WHERE ";
            foreach ($tagArray as $tag) {
                $query .= "tags LIKE '%$tag%' OR ";
            }
            $query = rtrim($query, ' OR '); 
            $stmt = $pdo->query($query);
            echo json_encode($stmt->fetchAll());
        } else {
            $stmt = $pdo->query("SELECT * FROM characters");
            echo json_encode($stmt->fetchAll());
        }
    }
} elseif ($method === 'POST') {
    // Check if it's an update (form data with id) or create
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? '';
    $tags = $_POST['tags'] ?? ''; 
    $description = $_POST['description'] ?? '';
    
    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = $_FILES['image']['name'];
        $target_dir = "../public/img/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($imageName);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    if ($id) {
        // Update
        if ($imageName) {
            $stmt = $pdo->prepare("UPDATE characters SET name=?, tags=?, description=?, image=? WHERE id=?");
            $stmt->execute([$name, $tags, $description, $imageName, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE characters SET name=?, tags=?, description=? WHERE id=?");
            $stmt->execute([$name, $tags, $description, $id]);
        }
        echo json_encode(["success" => true, "message" => "Karakter berhasil diperbarui"]);
    } else {
        // Create
        if (!$imageName) $imageName = 'default.png';
        $stmt = $pdo->prepare("INSERT INTO characters (name, tags, description, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $tags, $description, $imageName]);
        echo json_encode(["success" => true, "message" => "Karakter berhasil ditambahkan"]);
    }
} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $_GET['id'] ?? ($data['id'] ?? null);
    
    if ($id) {
        $stmt = $pdo->prepare("SELECT image FROM characters WHERE id = ?");
        $stmt->execute([$id]);
        $character = $stmt->fetch();
        if ($character) {
            $image_path = '../public/img/' . $character['image'];
            if (file_exists($image_path) && $character['image'] !== 'default.png') {
                @unlink($image_path);
            }
            $stmt = $pdo->prepare("DELETE FROM characters WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(["success" => true]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Character not found"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "ID not provided"]);
    }
}
