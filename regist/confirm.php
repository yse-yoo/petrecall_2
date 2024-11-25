<?php
require_once '../db.php';

// POSTリクエストでなければ何も表示しない
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

// セッション開始
session_start();
session_regenerate_id(true);

// セッションにPOSTデータを登録
$_SESSION['regist'] = $_POST;

// POSTデータ受信（サニタイズ）
$post = sanitize($_POST);

// バリデーション（データチェック）
if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}
$errors = validate($post, $pdo);

if ($errors) {
    $_SESSION['errors'] = $errors;
    header('Location: input.php');
    exit;
}

/**
 * サニタイズ
 */
function sanitize($array)
{
    if (!is_array($array)) return [];
    foreach ($array as $key => $value) {
        $array[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    return $array;
}

function validate($posts, $pdo)
{
    $errors = [];

    // Email重複チェック
    $sql = "SELECT * FROM users WHERE email = :email;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $posts['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $errors['email'] = "Emailは既に登録されています";
    }

    // Validation
    if (empty($posts['name'])) {
        $errors['name'] = '名前を入力してください';
    }
    if (empty($posts['email'])) {
        $errors['email'] = 'メールアドレスを入力してください';
    }

    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]{6,12}$/';
    if (empty($posts['password'])) {
        $errors['password'] = 'パスワードを入力してください';
    } elseif (!preg_match($pattern, $posts['password'])) {
        // $errors['password'] = 'パスワードは6文字以上12文字以内の半角英数で入力してください。（大文字、文字含む）';
    }
    return $errors;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ペット検索システム - 会員登録確認</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- ヘッダー -->
    <?php include "../components/header.php"; ?>

    <!-- メインコンテンツ -->
    <main class="flex-grow flex items-center justify-center px-4">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
            <h2 class="text-3xl font-semibold text-center text-gray-800 mb-6">会員登録確認</h2>
            <form action="add.php" method="post">
                <!-- 名前 -->
                <div class="mb-6">
                    <label for="name" class="block text-gray-700 font-medium mb-2">氏名</label>
                    <p class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md"><?= htmlspecialchars($post["name"]); ?></p>
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                    <p class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md"><?= htmlspecialchars($post["email"]); ?></p>
                </div>

                <!-- 住所 -->
                <div class="mb-6">
                    <label for="address" class="block text-gray-700 font-medium mb-2">住所</label>
                    <p class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md"><?= htmlspecialchars($post["address"]); ?></p>
                </div>

                <!-- 電話番号 -->
                <div class="mb-6">
                    <label for="phone" class="block text-gray-700 font-medium mb-2">電話番号</label>
                    <p class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md"><?= htmlspecialchars($post["phone"]); ?></p>
                </div>

                <!-- ボタン -->
                <div class="flex justify-between mt-8">
                    <button type="submit"
                        class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                        登録
                    </button>
                    <a href="./input.php"
                        class="w-1/2 ml-4 text-center px-4 py-2 border border-blue-500 text-blue-500 rounded-md hover:bg-blue-100 focus:outline-none focus:ring focus:ring-blue-300">
                        戻る
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>