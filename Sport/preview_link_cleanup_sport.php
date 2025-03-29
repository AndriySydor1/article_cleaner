<?php
// ⬇️ ЗАМІНИ ці значення на потрібні для нового сайту
$servername = "localhost";
$username = "<?php
// ⬇️ ЗАМІНИ ці значення на потрібні для нового сайту
$servername = "localhost";
$username = "tutsem09_sprt";
$password = "nkTECcwo";
$dbname = "tutsem09_sprt";

// Підключення до бази
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// Отримати кілька випадкових статей
$sql = "SELECT id, `fulltext` FROM jos_content ORDER BY RAND() LIMIT 5";
$result = $conn->query($sql);

// Функція для заміни зовнішніх посилань
function cleanExternalLinks($text) {
    // 1. [ПОЧАТОК_ПОСИЛАННЯ...] → https://...
    $text = preg_replace('/\[ПОЧАТОК_ПОСИЛАННЯ.*?https?:\/\/([^\s\]]+).*?КІНЕЦЬ_ПОСИЛАННЯ\]/i', 'https://$1', $text);

    // 2. <a href="...">Текст</a> → https://... Текст
    $text = preg_replace_callback('/<a[^>]+href=["\']?(https?:\/\/[^"\'>\s]+)["\']?[^>]*>(.*?)<\/a>/i', function ($matches) {
        return $matches[1] . ' ' . strip_tags($matches[2]);
    }, $text);

    // 3. "Голі" посилання → текст
    $text = preg_replace('/(?<!["\'=])\b(https?:\/\/|www\.|[a-z0-9.-]+\.(com|ua|org|net|gov|biz|info|co))([\/a-zA-Z0-9?&=._%#-]*)\b/i', '$0', $text);

    return $text;
}

// Вивести до і після
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<h3>Стаття ID: " . $row["id"] . "</h3>";
        echo "<b>До:</b><div style='border:1px solid gray; padding:10px;'>" . htmlspecialchars($row["fulltext"]) . "</div><br>";
        $cleaned = cleanExternalLinks($row["fulltext"]);
        echo "<b>Після:</b><div style='border:1px solid green; padding:10px;'>" . htmlspecialchars($cleaned) . "</div><hr><br>";
    }
} else {
    echo "Немає статей.";
}

$conn->close();
?>";
$password = "ПАРОЛЬ_НОВОЇ_БД";
$dbname = "ІМ'Я_НОВОЇ_БАЗИ";

// Підключення до бази
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// Отримати кілька випадкових статей
$sql = "SELECT id, `fulltext` FROM kievmy_content ORDER BY RAND() LIMIT 5";
$result = $conn->query($sql);

// Функція для заміни зовнішніх посилань
function cleanExternalLinks($text) {
    // 1. [ПОЧАТОК_ПОСИЛАННЯ...] → https://...
    $text = preg_replace('/\[ПОЧАТОК_ПОСИЛАННЯ.*?https?:\/\/([^\s\]]+).*?КІНЕЦЬ_ПОСИЛАННЯ\]/i', 'https://$1', $text);

    // 2. <a href="...">Текст</a> → https://... Текст
    $text = preg_replace_callback('/<a[^>]+href=["\']?(https?:\/\/[^"\'>\s]+)["\']?[^>]*>(.*?)<\/a>/i', function ($matches) {
        return $matches[1] . ' ' . strip_tags($matches[2]);
    }, $text);

    // 3. "Голі" посилання → текст
    $text = preg_replace('/(?<!["\'=])\b(https?:\/\/|www\.|[a-z0-9.-]+\.(com|ua|org|net|gov|biz|info|co))([\/a-zA-Z0-9?&=._%#-]*)\b/i', '$0', $text);

    return $text;
}

// Вивести до і після
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<h3>Стаття ID: " . $row["id"] . "</h3>";
        echo "<b>До:</b><div style='border:1px solid gray; padding:10px;'>" . htmlspecialchars($row["fulltext"]) . "</div><br>";
        $cleaned = cleanExternalLinks($row["fulltext"]);
        echo "<b>Після:</b><div style='border:1px solid green; padding:10px;'>" . htmlspecialchars($cleaned) . "</div><hr><br>";
    }
} else {
    echo "Немає статей.";
}

$conn->close();
?>