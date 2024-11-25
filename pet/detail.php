<?php
require_once '../db.php';
require_once '../models/Animal.php';

session_start();
session_regenerate_id(true);

if (empty($_GET['pet_id'])) {
    header('Location: ./');
    exit;
}


$user = (isset($_SESSION['user'])) ? $_SESSION['user'] : [];

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

// 飼い主
$sql = "SELECT * FROM users WHERE id = :user_id;";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $pet['user_id']]);
$pet_user = $stmt->fetch(PDO::FETCH_ASSOC);

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
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">ペット情報</h2>

        <!-- ペット情報 -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="w-full md:w-1/2">
                    <img src="../uploads/<?= htmlspecialchars($pet['image_name']) ?>" alt="ペット画像" class="w-full h-auto rounded-md">
                </div>
                <div class="w-full md:w-1/2 space-y-4">
                    <div class="my-2">
                        <span class="px-4 py-2 bg-red-500 text-white rounded-md">解決済み</span>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-700"><?= htmlspecialchars($pet['name']) ?></h3>
                    <p class="text-gray-600">種類: <?= htmlspecialchars($animal_data['name'] ?? '不明') ?></p>
                    <p class="text-gray-600">説明: <?= nl2br(htmlspecialchars($pet['description'] ?? '説明がありません。')) ?></p>
                    <p class="text-gray-600">ユーザ名: <?= htmlspecialchars($pet_user['name']) ?></p>
                    <p class="text-gray-600">Email: <?= htmlspecialchars($pet_user['email']) ?></p>
                    <p class="text-gray-600">登録日: <?= htmlspecialchars($pet['created_at']) ?></p>
                </div>
            </div>
        </div>

        <!-- コメント入力欄 -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
            <h3 class="text-2xl font-semibold text-gray-700 mb-4">発見情報を報告</h3>
            <form action="add_comment.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="pet_id" value="<?= htmlspecialchars($pet_id) ?>">
                <div class="mb-4">
                    <label for="comment" class="block text-gray-700 font-medium mb-2">コメント</label>
                    <textarea id="comment" name="comment" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300" required></textarea>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">報告</button>
            </form>
        </div>

    </main>
</body>

</html>