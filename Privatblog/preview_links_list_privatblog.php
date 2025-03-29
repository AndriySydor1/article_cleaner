<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "tutsem09_privat";
$password = "xbyfntktrjv";
$dbname = "tutsem09_privat";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");
if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

$sql = "SELECT id, `fulltext` FROM stasz_content WHERE `fulltext` LIKE '%http%';";
$result = $conn->query($sql);

function extractLinks($text) {
    $links = [];

    // 1. Звичайні <a href="...">...</a>
    preg_match_all('/<a[^>]+href=["\']?(https?:\/\/[^"\'>\s]+)["\']?[^>]*>.*?<\/a>/i', $text, $matches);
    if (!empty($matches[1])) {
        $links = array_merge($links, $matches[1]);
    }

    // 2. [ПОЧАТОК_ПОСИЛАННЯ ... КІНЕЦЬ_ПОСИЛАННЯ]
    preg_match_all('/\[ПОЧАТОК_ПОСИЛАННЯ.*?(https?:\/\/[^\s\]]+)/i', $text, $matches);
    if (!empty($matches[1])) {
        $links = array_merge($links, $matches[1]);
    }

    // 3. "Голі" посилання
    preg_match_all('/(?<!["\'=])\b(https?:\/\/|www\.|[a-z0-9.-]+\.(com|ua|org|net|gov|biz|info|co))([\/a-zA-Z0-9?&=._%#-]*)\b/i', $text, $matches);
    if (!empty($matches[0])) {
        $links = array_merge($links, $matches[0]);
    }

    return array_unique($links);
}

if ($result->num_rows > 0) {
    echo "<b>Посилання, які будуть перетворені на текст:</b><br><br>";
    while ($row = $result->fetch_assoc()) {
        $links = extractLinks($row["fulltext"]);
        foreach ($links as $link) {
            echo $row['id'] . " " . htmlspecialchars($link) . "<br>";
        }
    }
} else {
    echo "Зовнішні посилання не знайдено.";
}

$conn->close();
?>
