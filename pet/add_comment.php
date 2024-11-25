<?php
require_once '../db.php';

session_start();
session_regenerate_id(true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ./');
    exit;
}

// ログインユーザ情報
$user = (isset($_SESSION['user'])) ? $_SESSION['user'] : [];
if (!$user) {
    header('Location: ../login/');
    exit;
}

// サニタイズ処理
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

try {
    // 必須データの取得と検証
    $posts['pet_id'] = isset($_POST['pet_id']) ? (int)$_POST['pet_id'] : 0;
    $posts['user_id'] = isset($user['id']) ? $user['id'] : 0;
    $posts['comment'] = isset($_POST['comment']) ? sanitize($_POST['comment']) : '';
    $posts['image_name'] = "";

    // 画像アップロード処理
    $image_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = basename($_FILES['image']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_ext, $allowed_extensions)) {
            throw new Exception('アップロードされたファイルは画像形式ではありません。');
        }

        // ファイル名を一意にする
        $unique_name = uniqid('comment_', true) . '.' . $file_ext;
        $upload_path = "../uploads/" . $unique_name;

        if (!move_uploaded_file($file_tmp, $upload_path)) {
            throw new Exception('画像のアップロードに失敗しました。');
        }

        $posts['image_name'] = $unique_name;
    }

    // データベースにコメントを登録
    $sql = "INSERT INTO comments (pet_id, user_id, image_name, comment, created_at, updated_at) VALUES (:pet_id, :user_id, :image_name, :comment, NOW(), NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($posts);

    // 正常終了後のリダイレクト
    header("Location: detail.php?pet_id={$posts['pet_id']}");
    exit;
} catch (Exception $e) {
    // TODO: エラーメッセージ表示
    var_dump($e);
    // header("Location: detail.php/?pet_id={$pet_id}");
    exit;
}
?>