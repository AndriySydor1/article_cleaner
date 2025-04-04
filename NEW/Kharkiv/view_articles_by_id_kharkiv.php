<?php
// ======== view_articles_by_id_kharkiv.php ========
// Перегляд статей за ID для kharkov.mycityua.com

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Параметри підключення до бази
$servername = "localhost";
$username = "tutsem09_khrv";
$password = "GXNC7RkJ4i";
$dbname = "tutsem09_khrv";

// Підключення до бази
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// 🔽 Вкажіть ID статей, які хочете переглянути:
$article_ids = [2298, 2518];  // ← сюди вставте потрібні ID

// Формуємо SQL-запит
$id_list = implode(',', array_map('intval', $article_ids));
$sql = "SELECT id, `fulltext` FROM kievmy_content WHERE id IN ($id_list)";
$result = $conn->query($sql);

// Вивід результату
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<h2>Стаття ID: " . $row["id"] . "</h2>";
        echo "<div style='border:1px solid #aaa; padding:10px; margin-bottom:20px;'>";
        echo htmlspecialchars($row["fulltext"]);
        echo "</div>";
    }
} else {
    echo "Статті не знайдено.";
}

$conn->close();
?>
