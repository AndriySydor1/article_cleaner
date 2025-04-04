<?php
// ======== replace_links_donetsk.php ========
// Повне видалення зовнішніх посилань із тексту статей

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Параметри підключення до бази donetsk.mycityua.com
$servername = "localhost";
$username = "tutsem09_dntsk";
$password = "uBOPspAlTT";
$dbname = "tutsem09_dntsk";

// Підключення до бази
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");
if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// Отримання статей з потенційними зовнішніми посиланнями
$sql = "SELECT id, `fulltext` FROM md_content WHERE `fulltext` LIKE '%http%'";
$result = $conn->query($sql);

// Функція для очищення зовнішніх посилань
function removeExternalLinks($text) {
    // Видалити вставки виду [ПОЧАТОК_ПОСИЛАННЯ ... КІНЕЦЬ_ПОСИЛАННЯ]
    $text = preg_replace('/\[ПОЧАТОК_ПОСИЛАННЯ.*?КІНЕЦЬ_ПОСИЛАННЯ\]/isu', '', $text);

    // Видалити HTML-посилання <a href="...">...</a>
    $text = preg_replace('/<a[^>]+href=["\']?(https?:\/\/[^"\'>\s]+)["\']?[^>]*>.*?<\/a>/isu', '', $text);

    // Видалити "голі" посилання (включно з www, .ua, .com тощо)
    $text = preg_replace('/(?<!["\'=])\b(https?:\/\/|www\.|[a-z0-9.-]+\.(com|ua|org|net|gov|biz|info|co))([\/a-zA-Z0-9?&=._%#-]*)\b/isu', '', $text);

    return $text;
}

// Обробка та оновлення
$updated = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cleaned = removeExternalLinks($row["fulltext"]);
        if ($cleaned !== $row["fulltext"]) {
            $stmt = $conn->prepare("UPDATE md_content SET `fulltext` = ? WHERE id = ?");
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
