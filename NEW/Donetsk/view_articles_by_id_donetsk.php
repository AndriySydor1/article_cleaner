<?php
// view_articles_by_id_donetsk.php — перевірка статей за ID з повним HTML

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "tutsem09_dntsk";
$password = "uBOPspAlTT";
$dbname = "tutsem09_dntsk";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// 🔽 Вкажіть ID статей для перегляду
$article_ids = [1021, 1034, 1475, 1640, 1646];
$id_list = implode(',', array_map('intval', $article_ids));

// SQL-запит
$sql = "SELECT id, `fulltext` FROM md_content WHERE id IN ($id_list)";
$result = $conn->query($sql);

// Перевірка на помилку SQL-запиту
if (!$result) {
    die("SQL помилка: " . $conn->error);
}

// Вивід результату
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<h2>Стаття ID: " . $row['id'] . "</h2>";
        echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:20px; white-space: pre-wrap;'>";
        echo htmlspecialchars($row['fulltext']);
        echo "</div>";
    }
} else {
    echo "<b>Статті не знайдено. Перевір IDs або наявність в базі.</b><br>";
    echo "SQL: $sql";
}

$conn->close();
?>
