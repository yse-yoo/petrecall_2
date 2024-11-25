<?php
require_once "../db.php";

session_start();
session_regenerate_id(true);

if (empty($_SESSION['user'])) {
    header('Location: ../login/');
    exit;
} else {
    $user = $_SESSION['user'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posts = $_POST;
    $posts['user_id'] = $user['id'];

    // データベースに保存
    $sql = "INSERT INTO pets (name, user_id, animal_id, image_name, description) 
                     VALUES (:name, :user_id, :animal_id, :image_name, :description);";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($posts);
    header('Location: complete.php');
}
