<?php
// ======== replace_links_privatblog.php ========
// Очищення статей на privatblog.com.ua від зовнішніх посилань

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Параметри підключення до бази
$servername = "localhost";
$username = "tutsem09_lvv";
$password = "66BrytPAzP";
$dbname = "tutsem09_lvv";
// Підключення до бази
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// Отримати всі статті, які можуть містити зовнішні посилання
$sql = "SELECT id, `fulltext`, `introtext` FROM kievmy_content WHERE `fulltext` LIKE '%http%' OR `introtext` LIKE '%http%'";
$result = $conn->query($sql);

// 🧼 Функція для видалення зовнішніх посилань
function removeExternalLinks($text) {
    if (empty($text)) return $text;

    // 1. Декодування HTML-сутностей
    $text = htmlspecialchars_decode($text);

    // 2. Видалення вставок виду [ПОЧАТОК_ПОСИЛАННЯ ... КІНЕЦЬ_ПОСИЛАННЯ]
    $text = preg_replace('/\[ПОЧАТОК_ПОСИЛАННЯ.*?КІНЕЦЬ_ПОСИЛАННЯ\]/isu', '', $text);

    // 3. Видалення HTML-посилань <a href="...">...</a>
    $text = preg_replace('/<a[^>]+href=["\']?(https?:\/\/[^"\'>\s]+)["\']?[^>]*>.*?<\/a>/isu', '', $text);

    // 4. Видалення "голих" посилань (https://, www., просто домени), навіть усередині HTML-тегів
    $text = preg_replace_callback('/(?<!["\'=])\b((https?:\/\/|www\.|[a-z0-9.-]+\.(com|ua|org|net|gov|biz|info|co))([\/a-zA-Z0-9?&=._%#-]*)?)\b/isu', function ($matches) {
        return '';
    }, $text);

    return $text;
}

$updated = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $cleaned_fulltext = removeExternalLinks($row["fulltext"]);
        $cleaned_introtext = removeExternalLinks($row["introtext"]);

        if ($cleaned_fulltext !== $row["fulltext"] || $cleaned_introtext !== $row["introtext"]) {
            $stmt = $conn->prepare("UPDATE kievmy_content SET `fulltext` = ?, `introtext` = ? WHERE id = ?");
            $stmt->bind_param("ssi", $cleaned_fulltext, $cleaned_introtext, $id);
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
