<?php
// セッション開始
session_start();
session_regenerate_id(true);

//セッションが空でなければ、前回のデータ取得
if (!empty($_SESSION['regist'])) {
    $regist = $_SESSION['regist'];
}
if (!empty($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ペット検索システム - 会員登録</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- ヘッダー -->
    <?php include "../components/header.php" ?>

    <!-- メインコンテンツ -->
    <main class="flex-grow flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-center text-2xl font-semibold mb-6">会員登録</h2>
            <!-- フォーム -->
            <form action="confirm.php" method="post">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1" for="name">名前</label>
                    <input
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                        type="text" name="name" value="<?= @$regist['name'] ?>" id="name">
                    <?php if (!empty($errors['name'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['name'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1" for="email">Email</label>
                    <input
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                        type="email" name="email" value="<?= @$regist['email'] ?>" id="email">
                    <?php if (!empty($errors['email'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['email'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1" for="password">パスワード</label>
                    <input
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                        type="password" name="password" id="password">
                    <?php if (!empty($errors['password'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1" for="address">住所</label>
                    <input
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                        type="text" name="address" value="<?= @$regist['address'] ?>" id="address">
                    <?php if (!empty($errors['address'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['address'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1" for="phone">電話番号</label>
                    <input
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                        type="text" name="phone" value="<?= @$regist['phone'] ?>" id="phone">
                    <?php if (!empty($errors['phone'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['phone'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="submit"
                        class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                        次へ
                    </button>
                    <a href="../login/"
                        class="w-1/2 ml-4 text-center px-4 py-2 border border-blue-500 text-blue-500 rounded-md hover:bg-blue-100 focus:outline-none focus:ring focus:ring-blue-300">
                        ログイン
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>