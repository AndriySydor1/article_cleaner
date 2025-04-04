<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "tutsem09_dnpr";
$password = "gdXqaStA";
$dbname = "tutsem09_dnpr";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");
if ($conn->connect_error) {
    die("\u041f\u043e\u043c\u0438\u043b\u043a\u0430 \u043f\u0456\u0434\u043a\u043b\u044e\u0447\u0435\u043d\u043d\u044f: " . $conn->connect_error);
}

$sql = "SELECT id, `fulltext` FROM kievmy_content WHERE `fulltext` LIKE '%http%'";
$result = $conn->query($sql);

function removeExternalLinks($text) {
    // Видалення конструкцій [ПОЧАТОК_ПОСИЛАННЯ...КІНЕЦЬ_ПОСИЛАННЯ]
    $text = preg_replace('/\[ПОЧАТОК_ПОСИЛАННЯ.*?КІНЕЦЬ_ПОСИЛАННЯ\]/i', '', $text);

    // Видалення HTML-посилань <a href="...">...</a>
    $text = preg_replace('/<a[^>]+href=["\']?(https?:\/\/[^"\'>\s]+)["\']?[^>]*>.*?<\/a>/i', '', $text);

    // Видалення "голих" посилань
    $text = preg_replace('/(?<!["\'=])\b(https?:\/\/|www\.|[a-z0-9.-]+\.(com|ua|org|net|gov|biz|info|co))([\/a-zA-Z0-9?&=._%#-]*)\b/i', '', $text);

    return $text;
}

$updated = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cleaned = removeExternalLinks($row["fulltext"]);
        if ($cleaned !== $row["fulltext"]) {
            $stmt = $conn->prepare("UPDATE kievmy_content SET `fulltext` = ? WHERE id = ?");
            $stmt->bind_param("si", $cleaned, $row["id"]);
            $stmt->execute();
            $updated++;
        }
    }
    echo "\u041e\u043d\u043e\u0432\u043b\u0435\u043d\u043e $updated \u0441\u0442\u0430\u0442\u0435\u0439.";
} else {
    echo "\u041d\u0435\u043c\u0430\u0454 \u0441\u0442\u0430\u0442\u0435\u0439 \u0437 \u0437\u043e\u0432\u043d\u0456\u0448\u043d\u0456\u043c\u0438 \u043f\u043e\u0441\u0438\u043b\u0430\u043d\u043d\u044f\u043c\u0438.";
}

$conn->close();
?>
