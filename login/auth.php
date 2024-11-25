<?php
require_once '../db.php';

// セッション開始
session_start();
session_regenerate_id(true);

//POSTデータ取得
$posts = $_POST;

// name=email のデータ
$email = $posts['email'];
// name=password のデータ
$password = $posts['password'];

//Email検索(SQL)
$sql = "SELECT * FROM users WHERE email = ?;";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);

// データ変換
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$is_scussess = false;
if ($user) {
    $hash = $user['password'];
    //パスワードハッシュ検証
    $is_scussess = password_verify($password, $hash);
}

if ($is_scussess) {
    // セッションにユーザを登録
    $_SESSION['user'] = $user;

    //ログイン成功の場合、user/ にリダイレクト
    header('Location: ../user/');
} else {
    //ログイン失敗の場合、login/input.php にリダイレクト
    header('Location: input.php');
}