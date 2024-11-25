<?php
require_once "../models/Animal.php";
require_once "../db.php";

session_start();
session_regenerate_id(true);

if (empty($_SESSION['user'])) {
    header('Location: ../login/');
    exit;
} else {
    $user = $_SESSION['user'];
}

if (empty($_GET['pet_id'])) {
    header('Location: ../user/');
    exit;
}

$pet_id = $_GET['pet_id'];

// ペット情報の取得
$sql_pet = "SELECT * FROM pets WHERE id = :pet_id AND user_id = :user_id;";
$stmt_pet = $pdo->prepare($sql_pet);
$stmt_pet->execute(['pet_id' => $pet_id, 'user_id' => $user['id']]);
$pet = $stmt_pet->fetch(PDO::FETCH_ASSOC);

if (!$pet) {
    header('Location: ./');
    exit;
}

$animal = new Animal();
$animals = $animal->getList();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ペット検索システム</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <!-- ヘッダー -->
    <?php include "../components/header.php"; ?>

    <!-- コンテンツ -->
    <main class="max-w-3xl mx-auto bg-white my-2 p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">ペット情報の編集</h2>
        <form action="update.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="pet_id" value="<?= htmlspecialchars($pet['id']); ?>">

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">解決済み</label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_resolved" value="1" <?= @$pet['is_resolved'] ? 'checked' : '' ?> class="form-checkbox">
                    <span class="ml-2 text-gray-700">解決済みにする</span>
                </label>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">ペットの名前</label>
                <input type="text" name="name" value="<?= htmlspecialchars($pet['name']); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">ペットの種類</label>
                <select name="animal_id" id="animal_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <?php foreach ($animals as $value): ?>
                        <option value="<?= $value['id'] ?>" <?= $value['id'] == $pet['animal_id'] ? 'selected' : '' ?>><?= htmlspecialchars($value['name']); ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">ペットの画像</label>
                <input type="file" name="image" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300" accept="image/*">
                <p class="text-sm text-gray-600 mt-1">現在の画像: <?= htmlspecialchars($pet['image_name']); ?></p>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">説明</label>
                <textarea name="description" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300" rows="4" required><?= htmlspecialchars($pet['description']); ?></textarea>
            </div>

            <!-- ボタン -->
            <div class="flex justify-between mt-8">
                <a href="../" class="w-48 text-center bg-gray-300 text-gray-800 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    戻る
                </a>
                <button type="submit" class="w-48 bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    更新
                </button>
            </div>
        </form>
    </main>
</body>

</html>
