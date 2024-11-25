<?php
// TODO: env.php
$username = "root";         // MySQLのユーザー名
$password = "";             // MySQLのパスワード
$db_name = "pet_db";
$host = "localhost";

// DB接続設定（MySQL）
$dsn = "mysql:host={$host};dbname={$db_name};charset=utf8";  // MySQLのホスト、データベース名、文字コード

try {
    // PDOを使ってMySQLに接続
    $pdo = new PDO($dsn, $username, $password);
    // エラーモードを例外に設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '接続失敗: ' . $e->getMessage();
    exit;
}
?>
