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
                <a href="detail.php?pet_id=<?= $pet['id'] ?>">
                    <div class="bg-white p-4 rounded-lg shadow-lg">
                        <img src="../uploads/<?= $pet['image_name'] ?>" alt="画像" class="mt-2 w-full h-auto rounded-md">
                        <p class="mt-2 text-gray-600">ペット名：<?php echo htmlspecialchars($pet['name']); ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </main>
</body>

</html>