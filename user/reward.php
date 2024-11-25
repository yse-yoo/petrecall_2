<?php
require_once '../db.php';
session_start();
session_regenerate_id(true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ./');
    exit;
}

// 入力データの取得
$user_id = $_POST['user_id'] ?? null;
$pet_id = $_POST['pet'] ?? null;
$amount = $_POST['amount'] ?? null;

// 入力データのバリデーション
if (empty($user_id) || empty($pet_id) || !is_numeric($amount) || $amount < 0) {
    echo "<p>入力データが不正です。</p>";
    echo '<a href="./">戻る</a>';
    exit;
}

try {
    // `user_id`と`pet_id`の組み合わせがすでに存在するか確認
    $sql_check = "
        SELECT id FROM rewards WHERE user_id = :user_id AND pet_id = :pet_id;
    ";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':user_id' => $user_id, ':pet_id' => $pet_id]);
    $existing_reward = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($existing_reward) {
        // 存在する場合は`UPDATE`
        $sql_update = "
            UPDATE rewards
            SET amount = :amount, updated_at = NOW()
            WHERE user_id = :user_id AND pet_id = :pet_id;
        ";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            ':amount' => $amount,
            ':user_id' => $user_id,
            ':pet_id' => $pet_id
        ]);
    } else {
        // 存在しない場合は`INSERT`
        $sql_insert = "
            INSERT INTO rewards (user_id, pet_id, amount, created_at, updated_at)
            VALUES (:user_id, :pet_id, :amount, NOW(), NOW());
        ";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([
            ':user_id' => $user_id,
            ':pet_id' => $pet_id,
            ':amount' => $amount
        ]);
    }

    // 正常終了後のリダイレクト
    header("Location: detail.php?pet_id={$pet_id}&user_id={$user_id}");
    exit;
} catch (PDOException $e) {
    // ロールバック
    echo "<p>データベースエラー: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo '<a href="./">戻る</a>';
    exit;
}
