<?php
// ======== view_articles_by_id_sport.php ========
// Перегляд статей по ID з бази tutsem09_sprt

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Параметри підключення до бази

$servername = "localhost";
$username = "tutsem09_sprt";
$password = "nkTECcwo";
$dbname = "tutsem09_sprt";

// Підключення до бази
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// 🔽 Вкажи ID статей, які хочеш переглянути:
$article_ids = [1474];  // ← сюди впиши потрібні ID

// Формуємо SQL-запит
$id_list = implode(',', array_map('intval', $article_ids));
$sql = "SELECT id, `title`, `introtext`, `fulltext` FROM `jos_content` WHERE id IN ($id_list)";
$result = $conn->query($sql);

// Вивід результату
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<h2>Стаття ID: " . $row["id"] . " — " . htmlspecialchars($row["title"]) . "</h2>";
        echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:30px; background:#f9f9f9;'>";
        echo "<h4>Introtext:</h4>";
        echo "<div style='margin-bottom:15px;'>" . htmlspecialchars($row["introtext"]) . "</div>";
        echo "<h4>Fulltext:</h4>";
        echo "<div>" . htmlspecialchars($row["fulltext"]) . "</div>";
        echo "</div>";
    }
} else {
    echo "Статті не знайдено.";
}

$conn->close();
?>