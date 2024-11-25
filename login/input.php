<?php
// TODO: エラーメッセージ
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ペット検索システム - ログイン</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- ヘッダー -->
    <?php include "../components/header.php" ?>

    <!-- メインコンテンツ -->
    <main class="flex-grow flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-semibold text-center mb-6">ログイン</h2>
            <form action="auth.php" method="post">
                <!-- Email入力 -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                    <input
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                        type="text" name="email" id="email" value="<?= @$member['email'] ?>">
                    <?php if (!empty($errors["email"])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors["email"] ?></p>
                    <?php endif; ?>
                </div>

                <!-- パスワード入力 -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium mb-1">パスワード</label>
                    <input
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                        type="password" name="password" id="password" value="<?= @$member['password'] ?>">
                    <?php if (!empty($errors["password"])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors["password"] ?></p>
                    <?php endif; ?>
                </div>

                <!-- ログインボタン -->
                <div class="mt-6">
                    <button type="submit"
                        class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                        ログイン
                    </button>
                </div>

                <!-- 新規登録リンク -->
                <div class="mt-4">
                    <a href="../regist/"
                        class="w-full block px-4 py-2 border border-blue-500 text-blue-500 text-center rounded-md hover:bg-blue-100 focus:outline-none focus:ring focus:ring-blue-300">
                        新規会員登録
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>