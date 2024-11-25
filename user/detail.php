<?php
require_once "../models/Animal.php";
require_once '../db.php';

session_start();
session_regenerate_id(true);

if (empty($_SESSION['user'])) {
    header('Location: ../login/');
    exit;
}

$user = $_SESSION['user'];

if (empty($_GET['pet_id']) || empty($_GET['user_id'])) {
    header('Location: ../login/');
    exit;
}

$pet_id = $_GET['pet_id'];
$user_id = $_GET['user_id'];

// ペット情報取得
$sql_pet = "SELECT * FROM pets WHERE pets.id = :pet_id;";
$stmt_pet = $pdo->prepare($sql_pet);
$stmt_pet->execute(['pet_id' => $pet_id]);
$pet = $stmt_pet->fetch(PDO::FETCH_ASSOC);

if (!$pet) {
    header('Location: ./');
    exit;
}

// 発見者情報取得
$sql_user = "SELECT * FROM users WHERE id = :user_id;";
$stmt_user = $pdo->prepare($sql_user);
$stmt_user->execute(['user_id' => $user_id]);
$report_user = $stmt_user->fetch(PDO::FETCH_ASSOC);

if (!$report_user) {
    header('Location: ./');
    exit;
}

// 報酬
$sql_user = "SELECT * FROM rewards WHERE pet_id = :pet_id AND user_id = :user_id;";
$stmt_user = $pdo->prepare($sql_user);
$stmt_user->execute(['pet_id' => $pet['id'], 'user_id' => $user_id]);
$reward = $stmt_user->fetch(PDO::FETCH_ASSOC);

// 発見者のコメント取得
$sql_comments = "
    SELECT comments.comment AS comment_text, comments.image_name AS comment_image, comments.created_at AS comment_created_at
    FROM comments
    WHERE comments.pet_id = :pet_id AND comments.user_id = :user_id
    ORDER BY comments.created_at DESC;
";
$stmt_comments = $pdo->prepare($sql_comments);
$stmt_comments->execute(['pet_id' => $pet_id, 'user_id' => $user_id]);
$comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ペット検索システム - ペット詳細</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- ヘッダー -->
    <?php include "../components/header.php"; ?>

    <main class="container mx-auto p-8">
        <h3 class="text-3xl font-bold mb-8 text-center">ペット情報</h3>

        <!-- ペット情報 -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="w-full md:w-1/2">
                    <img src="../uploads/<?= htmlspecialchars($pet['image_name']); ?>" alt="ペット画像" class="w-full h-auto rounded-md">
                </div>
                <div class="w-full md:w-1/2 space-y-4">
                    <h2 class="text-2xl font-semibold text-gray-700"><?= htmlspecialchars($pet['name']); ?></h2>
                    <p class="text-gray-600">説明: <?= nl2br(htmlspecialchars($pet['description'])); ?></p>
                    <p class="text-gray-600">登録日: <?= htmlspecialchars($pet['created_at']); ?></p>
                </div>
            </div>
        </div>

        <!-- 発見者情報 -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
            <h3 class="text-3xl font-bold mb-4">発見者情報</h3>
            <p class="text-gray-600 p-2">ユーザ名: <?= htmlspecialchars($report_user['name']); ?></p>
            <p class="text-gray-600 p-2">Email: <?= htmlspecialchars($report_user['email']); ?></p>
            <p class="text-gray-600 p-2">電話: <?= htmlspecialchars($report_user['phone']); ?></p>

            <div class="mt-4">
                <?php if ($reward['is_payment']): ?>
                    <span class="px-4 py-2 bg-red-500 text-white rounded-md">支払い済み</span>
                    <?= number_format($reward['amount']) ?>円
                <?php else: ?>
                    <div class="my-2">
                        <form action="reward.php" method="post">
                            <input class="px-2 py-1 border border-gray-400 rounded text-right" type="number" name="amount" value="<?= @$reward['amount'] ?>" min="0" max="100000">
                            円
                            <button class="px-4 py-2 bg-blue-500 text-white rounded-md">報酬を更新</button>
                            <input type="hidden" name="user_id" value="<?= $report_user['id'] ?>">
                            <input type="hidden" name="pet" value="<?= $pet['id'] ?>">
                        </form>
                    </div>

                    <div class="my-2">
                        <form action="payment.php" method="post" onsubmit="return confirm('<?= number_format($reward['amount']) ?>円で支払い済みにしますか？')">
                            <button class="px-4 py-2 bg-red-500 text-white rounded-md">支払い済みにする</button>
                            <input type="hidden" name="reward_id" value="<?= $reward['id'] ?>">
                        </form>
                    </div>
                <?php endif ?>
            </div>
        </div>

        <!-- 発見者のコメント一覧 -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-3xl font-bold mb-4">コメント一覧</h3>
            <?php if (count($comments) > 0): ?>
                <ul class="space-y-6">
                    <?php foreach ($comments as $comment): ?>
                        <li class="flex gap-4">
                            <?php if ($comment['comment_image']): ?>
                                <div class="w-16 h-16 flex-shrink-0">
                                    <img src="../uploads/<?= htmlspecialchars($comment['comment_image']); ?>" alt="コメント画像" class="w-full h-full object-cover rounded-md">
                                </div>
                            <?php endif; ?>
                            <div>
                                <p class="text-gray-700"><?= nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                                <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($comment['comment_created_at']); ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-600">コメントがまだありません。</p>
            <?php endif; ?>
        </div>

        <!-- 戻るボタン -->
        <div class="flex justify-start mt-4">
            <a href="./" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400">戻る</a>
        </div>
    </main>
</body>

</html>