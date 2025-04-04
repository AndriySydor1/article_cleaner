<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Параметри підключення
$servername = "localhost";
$username = "tutsem09_dnpr";
$password = "gdXqaStA";
$dbname = "tutsem09_dnpr";

// Підключення
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// 🔽 Вкажи тут список ID статей, які хочеш переглянути
$article_ids = [2236, 2241, 2543, 2555, 2709];
$id_list = implode(',', array_map('intval', $article_ids));

// Оновлена назва таблиці
$sql = "SELECT id, `fulltext` FROM kievmy_content WHERE id IN ($id_list)";
$result = $conn->query($sql);

// Вивід
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<h2>Стаття ID: " . $row["id"] . "</h2>";
        echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:20px;'>";
        echo htmlspecialchars($row["fulltext"]);
        echo "</div>";
    }
} else {
    echo "Статті не знайдено.";
}

$conn->close();
?>
