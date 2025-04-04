<?php
// ======== replace_links_kharkiv.php ========
// Очищення зовнішніх посилань зі статей сайту kharkov.mycityua.com

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Параметри підключення до бази mycityua.com
$servername = "localhost";
$username = "tutsem09_khrv";
$password = "GXNC7RkJ4i";
$dbname = "tutsem09_khrv";

// Підключення
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");
if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// Отримати всі статті, які містять зовнішні посилання
$sql = "SELECT id, `fulltext` FROM kievmy_content WHERE `fulltext` LIKE '%http%'";
$result = $conn->query($sql);

// Функція для видалення зовнішніх посилань
function removeExternalLinks($text) {
    // 1. Видалити вставки виду [ПОЧАТОК_ПОСИЛАННЯ...КІНЕЦЬ_ПОСИЛАННЯ]
    $text = preg_replace('/\[ПОЧАТОК_ПОСИЛАННЯ.*?КІНЕЦЬ_ПОСИЛАННЯ\]/isu', '', $text);

    // 2. Видалити HTML-посилання
    $text = preg_replace('/<a[^>]+href=["\']?(https?:\/\/[^"\'>\s]+)["\']?[^>]*>.*?<\/a>/isu', '', $text);

    // 3. Видалити голі посилання
    $text = preg_replace('/(?<!["\'=])\b(https?:\/\/|www\.|[a-z0-9.-]+\.(com|ua|org|net|gov|biz|info|co))([\/a-zA-Z0-9?&=._%#-]*)\b/isu', '', $text);

    return $text;
}

// Оновлення статей
$updated = 0;
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cleaned = removeExternalLinks($row["fulltext"]);
        if ($cleaned !== $row["fulltext"]) {
            $stmt = $conn->prepare("UPDATE kievmy_content SET `fulltext` = ? WHERE id = ?");
            $stmt->bind_param("si", $cleaned, $row["id"]);
            $stmt->execute();
            $updated++;
        }
    }
    echo "<b>Оновлено $updated статей.</b>";
} else {
    echo "<b>Немає статей з зовнішніми посиланнями.</b>";
}

$conn->close();
?>
