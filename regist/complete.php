<?php 
session_start();
session_regenerate_id(true);

//セッションが空でなければ、前回のデータ取得
if (isset($_SESSION['regist'])) {
    unset($_SESSION['regist']);
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ペット検索システム</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <!-- ヘッダー -->
    <?php include "../components/header.php" ?>

    <main class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center mb-4">会員登録完了</h2>
        <p class="text-center text-gray-700 mb-6">
            会員登録が完了しました。
        </p>
        <div class="flex justify-between">
            <a href="../"
                class="w-1/2 text-center px-4 py-2 mr-2 border border-blue-500 text-blue-500 rounded hover:bg-blue-100 focus:outline-none focus:ring focus:ring-blue-300">
                トップページ
            </a>
            <a href="../login/"
                class="w-1/2 text-center px-4 py-2 ml-2 border border-blue-500 text-blue-500 rounded hover:bg-blue-100 focus:outline-none focus:ring focus:ring-blue-300">
                ログイン
            </a>
        </div>
    </main>
</body>

</html>