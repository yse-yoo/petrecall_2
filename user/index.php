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

// ペット情報とコメントをJOINして取得
$sql = "
    SELECT 
        pets.id AS pet_id, 
        pets.name AS pet_name, 
        pets.image_name AS pet_image, 
        comments.comment AS comment_text, 
        comments.image_name AS comment_image, 
        comments.created_at AS comment_created_at, 
        comments.user_id AS comment_user_id
    FROM pets
    LEFT JOIN comments ON pets.id = comments.pet_id
    WHERE pets.user_id = :user_id
    ORDER BY pets.id, comments.created_at DESC;
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user['id']]);
$pet_comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ペットごとにコメントをまとめる
$pets = [];
foreach ($pet_comments as $row) {
    $pet_id = $row['pet_id'];
    if (!isset($pets[$pet_id])) {
        $pets[$pet_id] = [
            'name' => $row['pet_name'],
            'image_name' => $row['pet_image'],
            'comments' => []
        ];
    }
    if ($row['comment_text']) {
        $pets[$pet_id]['comments'][] = [
            'text' => $row['comment_text'],
            'image' => $row['comment_image'],
            'created_at' => $row['comment_created_at'],
            'user_id' => $row['comment_user_id']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ペット検索システム - マイページ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <!-- ヘッダー -->
    <?php include "../components/header.php"; ?>

    <main class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-8 text-center">マイページ</h1>
        <h2 class="text-xl font-bold mb-8">ペット一覧</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($pets as $pet_id => $pet): ?>
                <div class="bg-white p-4 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold mt-2 text-gray-600 text-center"><?= htmlspecialchars($pet['name']); ?></h3>

                    <!-- ペット情報 -->
                    <img src="../uploads/<?= htmlspecialchars($pet['image_name']); ?>" alt="画像" class="mt-2 w-full h-auto rounded-md">

                    <!-- コメントリスト -->
                    <div class="bg-white p-4 mt-4">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4">コメント一覧</h3>
                        <?php if (!empty($pet['comments'])): ?>
                            <ul class="space-y-4">
                                <?php foreach ($pet['comments'] as $comment): ?>
                                    <li class="flex gap-4">
                                        <?php if ($comment['image']): ?>
                                            <div class="w-16 h-16 flex-shrink-0">
                                                <img src="../uploads/<?= htmlspecialchars($comment['image']) ?>" alt="コメント画像" class="w-full h-full object-cover rounded-md">
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <p class="text-gray-700 py-2"><?= nl2br(htmlspecialchars($comment['text'])) ?></p>
                                            <?php if ($comment['user_id']): ?>
                                                <p class="text-gray-700">
                                                    <a href="detail.php?pet_id=<?= $pet_id ?>&user_id=<?= htmlspecialchars($comment['user_id']) ?>" class="px-2 py-1 text-xs text-white bg-teal-500 rounded">報告を見る</a>
                                                </p>
                                            <?php endif; ?>
                                            <p class="py-2 text-sm text-gray-500 mt-1"><?= htmlspecialchars($comment['created_at']) ?></p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-gray-600">コメントがまだありません。</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>

</html>