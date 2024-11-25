<?php
require_once "../models/Animal.php";
require '../db.php';

session_start();
session_regenerate_id(true);

$user = (isset($_SESSION['user'])) ? $_SESSION['user'] : [];

if (isset($_GET['animal_id'])) {
    $animal_id = $_GET['animal_id'];
    // TODO: SQL injection
    $sql = "SELECT * FROM pets WHERE animal_id = {$animal_id};";

    $animal = new Animal();
    $animal_data = $animal->fetch($animal_id);
} else {
    // ペット情報の取得
    $sql = "SELECT * FROM pets";
}
$stmt = $pdo->query($sql);
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    <main class="container mx-auto p-8">
        <?php if (isset($animal_data)): ?>
            <h1 class="text-3xl font-bold mb-8 text-center">登録された<?= $animal_data['name'] ?>の一覧</h1>
        <?php else: ?>
            <h1 class="text-3xl font-bold mb-8 text-center">登録されたペットの一覧</h1>
        <?php endif ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($pets as $pet): ?>
                <div class="bg-white p-4 rounded-lg shadow-lg">
                    <img src="../uploads/<?php echo htmlspecialchars($pet['image_name']); ?>" alt="画像" class="mt-2 w-full h-auto rounded-md">
                    <p class="mt-2 text-gray-600">ペット名：<?php echo htmlspecialchars($pet['name']); ?></p>
                    <!-- <p class="mt-2 text-gray-600"><?php echo htmlspecialchars($pet['user_name']); ?>さんの犬</p> 
                    <p class="mt-2 text-gray-600">Email: <?php echo htmlspecialchars($pet['email']); ?></p>
                    <p class="mt-2 text-gray-600">住所: <?php echo htmlspecialchars($pet['address']); ?></p>
                    <p class="mt-2 text-gray-600">電話番号: <?php echo htmlspecialchars($pet['phone']); ?></p>-->
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>

</html>