<form action="search.php" method="get">
    <input type="text" name="keyword" placeholder="ペットの名前や種類で検索">
    <select name="category">
        <option value="">すべて</option>
        <option value="犬">犬</option>
        <option value="猫">猫</option>
        <option value="鳥">鳥</option>
        <option value="その他">その他</option>
        <option value="迷子">迷子</option>
    </select>
    <button type="submit">検索</button>
</form>

<?php
if (isset($_GET['keyword']) || isset($_GET['category'])) {
    $keyword = $_GET['keyword'];
    $category = $_GET['category'];
    $query = "SELECT * FROM pets WHERE name LIKE ? OR breed LIKE ?";

    if ($category) {
        $query .= " AND category = ?";
        $stmt = $conn->prepare($query);
        $likeKeyword = '%' . $keyword . '%';
        $stmt->bind_param("sss", $likeKeyword, $likeKeyword, $category);
    } else {
        $stmt = $conn->prepare($query);
        $likeKeyword = '%' . $keyword . '%';
        $stmt->bind_param("ss", $likeKeyword, $likeKeyword);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<div class='pet-item'>
            <img src='{$row['image_path']}' alt='{$row['name']}' />
            <h3>{$row['name']} ({$row['category']})</h3>
            <p>{$row['breed']}</p>
            <p>{$row['description']}</p>
        </div>";
    }
    $stmt->close();
}
?>
