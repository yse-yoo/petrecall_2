<?php
require_once "../models/Animal.php";

session_start();
session_regenerate_id(true);

if (empty($_SESSION['user'])) {
    header('Location: ../login/');
    exit;
} else {
    $user = $_SESSION['user'];
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
    <?php include "../components/header.php" ?>

    <!-- コンテンツ -->
    <main class="max-w-3xl mx-auto bg-white my-2 p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">ペットの登録</h2>
        <form action="confirm.php" method="post" enctype="multipart/form-data">
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">ペットの名前</label>
                <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">ペットの種類</label>
                <select name="animal_id" id="animal_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <?php foreach ($animals as $value): ?>
                        <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">ペットの画像</label>
                <input type="file" name="image" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300" accept="image/*" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">説明</label>
                <textarea name="description" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300" rows="4" required></textarea>
            </div>

            <!-- ボタン -->
            <div class="flex justify-between mt-8">
                <a href="../" class="w-48 text-center bg-gray-300 text-gray-800 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    戻る
                </a>
                <button type="submit" class="w-48 bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    登録
                </button>
            </div>
        </form>
    </main>
</body>

</html>