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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POSTデータを取得
    $posts = $_POST;

    $animal = new Animal();
    $animalData = $animal->fetch($posts['animal_id']);

    // 画像のアップロード処理
    if (isset($_FILES['image'])) {
        $imageName = $_FILES['image']['name'];
        $imageTmpName = $_FILES['image']['tmp_name'];

        // UUID風の画像名を生成
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION); // 画像の拡張子を取得
        $uniqueImageName = uniqid('pet_', true) . '.' . $imageExtension; // UUID風の名前を生成

        $imagePath = '../uploads/' . $uniqueImageName;
        $posts['image_name'] = $uniqueImageName;

        if (!move_uploaded_file($imageTmpName, $imagePath)) {
            echo "画像のアップロードに失敗しました。";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ペットの登録確認画面</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <!-- ヘッダー -->
    <?php include "../components/header.php" ?>

    <!-- メインコンテンツ -->
    <main class="max-w-xl mx-auto bg-white my-2 p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">ペットの登録確認</h2>
        <form action="add.php" method="post">
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">ペットの名前</label>
                <p class="px-4 py-2 bg-gray-100 border rounded-md"><?= htmlspecialchars($posts['name']) ?></p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">ペットの種類</label>
                <p class="px-4 py-2 bg-gray-100 border rounded-md"><?= htmlspecialchars($animalData['name']) ?></p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">ペットの画像</label>
                <img src="<?= htmlspecialchars($imagePath) ?>" alt="ペット画像" class="w-full rounded-md">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">説明</label>
                <p class="px-4 py-2 bg-gray-100 border rounded-md whitespace-pre-wrap"><?= nl2br(htmlspecialchars($posts['description'])) ?></p>
            </div>

            <div class="flex justify-between mt-8">
                <a href="regist.php" class="w-1/2 mr-2 bg-gray-300 text-gray-800 text-center py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    戻る
                </a>
                <button type="submit" class="w-1/2 ml-2 bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    登録
                </button>
            </div>

            <input type="hidden" name="name" value="<?= htmlspecialchars($posts['name']); ?>">
            <input type="hidden" name="animal_id" value="<?= htmlspecialchars($posts['animal_id']); ?>">
            <input type="hidden" name="description" value="<?= htmlspecialchars($posts['description']); ?>">
            <input type="hidden" name="image_name" value="<?= htmlspecialchars($posts['image_name']); ?>">
        </form>
    </main>
</body>

</html>