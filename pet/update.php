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

    // is_resolved を数値に変換
    $posts['is_resolved'] = isset($posts['is_resolved']) ? 1 : 0;

    // ペット情報の取得
    $sql = "SELECT * FROM pets WHERE id = :pet_id;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['pet_id' => $posts['pet_id']]);
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pet) {
        echo "<p>ペット情報が見つかりません。</p>";
        exit;
    }

    // 既存の画像名を設定
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
                is_resolved = :is_resolved,
                updated_at = NOW()
            WHERE id = :pet_id AND user_id = :user_id;";

    // パラメータのバインド
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $posts['name'],
        ':animal_id' => $posts['animal_id'],
        ':description' => $posts['description'],
        ':image_name' => $posts['image_name'],
        ':is_resolved' => $posts['is_resolved'],
        ':pet_id' => $posts['pet_id'],
        ':user_id' => $_SESSION['user']['id']
    ]);

    // 編集後のリダイレクト
    header('Location: ../user/');
    exit;
}
