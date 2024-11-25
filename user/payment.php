<?php
require_once '../db.php';
session_start();
session_regenerate_id(true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ./');
    exit;
}

// 入力データの取得
$reword_id = $_POST['reward_id'];

try {
    $sql = "UPDATE rewards SET is_payment = true WHERE id = :reward_id;";
    $stmt_update = $pdo->prepare($sql);
    $stmt_update->execute([':reward_id' => $reword_id]);

    // 正常終了後のリダイレクト
    header("Location: ./");
    exit;
} catch (PDOException $e) {
    // ロールバック
    echo "<p>データベースエラー: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo '<a href="./">戻る</a>';
    exit;
}
