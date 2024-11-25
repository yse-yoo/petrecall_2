<?php 
require_once '../db.php';

// POSTリクエストでなければ何も表示しない
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

// セッション開始
session_start();
// セッションハイジャック対策
session_regenerate_id(true);

// セッションデータ取得
$regist = $_SESSION['regist'];

// パスワードのハッシュ化
$regist['password'] = password_hash($regist['password'], PASSWORD_DEFAULT);

// users テーブルにレコードを挿入するSQL
$sql = "INSERT INTO users (name, email, password, address, phone)
        VALUES (:name, :email, :password, :address, :phone);
        ";

// データベースに登録
$stmt = $pdo->prepare($sql);

// 成功の場合は、完了画面にリダイレクト
try {
    $stmt->execute($regist);
} catch (\Throwable $th) {
    // 予期せぬエラーの場合は、入力画面にリダイレクト
    header('Location: input.php');
    exit;
}

header('Location: complete.php');
?>