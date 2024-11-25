<?php
require_once "../db.php";

session_start();
session_regenerate_id(true);

if (empty($_SESSION['user'])) {
    header('Location: ../login/');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POSTデータの取得
    $posts = $_POST;

    $sql = "SELECT * FROM pets WHERE id = :pet_id;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['pet_id' => $posts['pet_id']]);
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);

    $posts['image_name'] = $pet['image_name'];

    // 画像がアップロードされている場合
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = uniqid('pet_', true) . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($image_tmp, "../uploads/{$image_name}");
        $posts['image_name'] = $image_name;
    }

    // `UPDATE`クエリの作成
    $sql = "UPDATE pets
            SET name = :name,
                animal_id = :animal_id,
                description = :description,
                image_name = :image_name,
                updated_at = NOW()
            WHERE id = :pet_id;";

    // パラメータのバインド
    $stmt = $pdo->prepare($sql);
    $stmt->execute($posts);

    // 編集後のリダイレクト
    header('Location: ../user/');
    exit;
}
