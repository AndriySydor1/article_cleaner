<?php
$servername = "localhost";
$username = "tutsem09_mcty";
$password = "apAcE0nihO";
$dbname = "tutsem09_mcty"; 
// Підключення до бази
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// Отримати всі статті
$sql = "SELECT id, `fulltext` FROM kievmy_content";
$result = $conn->query($sql);

// Функція для очищення
function cleanExternalLinks($text) {
    $text = preg_replace('/\[ПОЧАТОК_ПОСИЛАННЯ.*?https?:\/\/([^\s\]]+).*?КІНЕЦЬ_ПОСИЛАННЯ\]/i', 'https://$1', $text);
    $text = preg_replace_callback('/<a[^>]+href=["\']?(https?:\/\/[^"\'>\s]+)["\']?[^>]*>(.*?)<\/a>/i', function ($matches) {
        return $matches[1] . ' ' . strip_tags($matches[2]);
    }, $text);
    $text = preg_replace('/(?<!["\'=])\b(https?:\/\/|www\.|[a-z0-9.-]+\.(com|ua|org|net|gov|biz|info|co))([\/a-zA-Z0-9?&=._%#-]*)\b/i', '$0', $text);
    return $text;
}

// Оновлення статей
$updated = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cleaned = cleanExternalLinks($row["fulltext"]);
        if ($cleaned !== $row["fulltext"]) {
            $stmt = $conn->prepare("UPDATE kievmy_content SET `fulltext` = ? WHERE id = ?");
            $stmt->bind_param("si", $cleaned, $row["id"]);
            $stmt->execute();
            $updated++;
        }
    }
    echo "Оновлено $updated статей.";
} else {
    echo "Немає статей.";
}

$conn->close();
?>
