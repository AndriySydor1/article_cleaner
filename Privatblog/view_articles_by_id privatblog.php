<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Налаштування бази
$servername = "localhost";
$username = "tutsem09_privat";
$password = "xbyfntktrjv";
$dbname = "tutsem09_privat";

// Встановлення з'єднання
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// 🔽 Сюди впиши ID потрібних статей
$article_ids = [162, 263, 417, 457, 1920];

// Створення рядка з ID для SQL
$id_list = implode(',', array_map('intval', $article_ids));

$sql = "SELECT id, `fulltext` FROM stasz_content WHERE id IN ($id_list)";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
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