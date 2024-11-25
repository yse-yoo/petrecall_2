<?php
require_once '../db.php';
require_once '../models/Animal.php';

if (empty($_GET['pet_id'])) {
    header('Location: ./');
    exit;
}

$pet_id = $_GET['pet_id'];

// ペット情報取得（SQLインジェクション対策）
$sql = "SELECT * FROM pets WHERE id = :pet_id;";
$stmt = $pdo->prepare($sql);
$stmt->execute(['pet_id' => $pet_id]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pet) {
    header('Location: ./');
    exit;
}

// コメントリスト取得
$sql = "SELECT * FROM comments WHERE pet_id = :pet_id ORDER BY created_at DESC;";
$stmt = $pdo->prepare($sql);
$stmt->execute(['pet_id' => $pet_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ペット種類
$animal = new Animal();
$animal_data = $animal->fetch($pet['animal_id']);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ペット検索システム - ペット情報とコメント</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- ヘッダー -->
    <?php include "../components/header.php"; ?>

    <!-- メインコンテンツ -->
    <main class="flex-grow container mx-auto p-8">
        <h2 class="text-3xl font-bold text-left text-gray-800 mb-6">ペット情報</h2>

        <!-- ペット情報 -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="w-full md:w-1/2">
                    <img src="../uploads/<?= htmlspecialchars($pet['image_name']) ?>" alt="ペット画像" class="w-full h-auto rounded-md">
                </div>
                <div class="w-full md:w-1/2 space-y-4">
                    <h3 class="text-2xl font-semibold text-gray-700"><?= htmlspecialchars($pet['name']) ?></h3>
                    <p class="text-gray-600">種類: <?= htmlspecialchars($animal_data['name'] ?? '不明') ?></p>
                    <p class="text-gray-600">説明: <?= nl2br(htmlspecialchars($pet['description'] ?? '説明がありません。')) ?></p>
                    <p class="text-gray-600">登録日: <?= htmlspecialchars($pet['created_at']) ?></p>
                </div>
            </div>
        </div>

        <!-- コメントリスト -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-2xl font-semibold text-gray-700 mb-4">コメント一覧</h3>
            <?php if (count($comments) > 0): ?>
                <ul class="space-y-6">
                    <?php foreach ($comments as $comment): ?>
                        <li class="flex gap-4">
                            <div class="w-16 h-16 flex-shrink-0">
                                <img src="../uploads/<?= htmlspecialchars($comment['image_name']) ?>" alt="コメント画像" class="w-full h-full object-cover rounded-md">
                            </div>
                            <div>
                                <p class="text-gray-700"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                                <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($comment['created_at']) ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-600">コメントがまだありません。</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>
