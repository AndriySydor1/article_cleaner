<?php
// ======== preview_links_list_privatblog.php ========
// Показ усіх зовнішніх посилань зі статей privatblog.com.ua

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Параметри підключення до бази
$servername = "localhost";
$username = "tutsem09_privat";
$password = "xbyfntktrjv";
$dbname = "tutsem09_privat";
$table = "stasz_content";

// Підключення
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// Отримання статей, які потенційно містять зовнішні посилання
$sql = "SELECT `id`, `introtext`, `fulltext` FROM `$table`
        WHERE `introtext` LIKE '%http%' OR `introtext` LIKE '%www.%'
           OR `fulltext` LIKE '%http%' OR `fulltext` LIKE '%www.%'";
$result = $conn->query($sql);

// Функція для витягування зовнішніх посилань
function extractLinks($text) {
    $links = [];

    // 1. HTML-посилання
    preg_match_all('/<a[^>]+href=["\']?(https?:\/\/[^"\'>\s]+)["\']?[^>]*>.*?<\/a>/i', $text, $matches1);
    if (!empty($matches1[1])) {
        $links = array_merge($links, $matches1[1]);
    }

    // 2. Вставки [ПОЧАТОК_ПОСИЛАННЯ ...]
    preg_match_all('/\[ПОЧАТОК_ПОСИЛАННЯ.*?(https?:\/\/[^\s\]]+)/i', $text, $matches2);
    if (!empty($matches2[1])) {
        $links = array_merge($links, $matches2[1]);
    }

    // 3. Голі посилання
    preg_match_all('/(?<!["\'=])\b((https?:\/\/|www\.|[a-z0-9.-]+\.(com|ua|org|net|gov|biz|info|co))([\/a-zA-Z0-9?&=._%#-]*)?)\b/i', $text, $matches3);
    if (!empty($matches3[0])) {
        $links = array_merge($links, $matches3[0]);
    }

    return array_unique($links);
}

// Обробка та вивід результатів
$found = false;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $combined = $row["introtext"] . ' ' . $row["fulltext"];
        $links = extractLinks($combined);

        if (!empty($links)) {
            if (!$found) {
                echo "<b>Статті з зовнішніми посиланнями:</b><br><br>";
                $found = true;
            }

            foreach ($links as $link) {
                echo "<b>ID:</b> {$row['id']} &nbsp;&nbsp;&nbsp; <b>Link:</b> " . htmlspecialchars($link) . "<br>";
            }
        }
    }
}

if (!$found) {
    echo "Зовнішні посилання не знайдено.";
}

$conn->close();
?>
