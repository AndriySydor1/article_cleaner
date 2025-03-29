<?php
// Параметри підключення
$host = "localhost";
$user = "tutsem09_lvv";
$pass = "66BrytPAzP";
$dbname = "tutsem09_lvv";

// Підключення до бази
$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// Витягуємо всі статті з fulltext
$sql = "SELECT id, `fulltext` FROM kievmy_content WHERE `fulltext` LIKE '%http%'";
$result = $conn->query($sql);

// Патерни
$patterns = [
    // <a href="http://...">текст</a> → http://... текст
    '/<a[^>]+href=[\'"]?(http[^\'" >]+)[\'"]?[^>]*>(.*?)<\/a>/is',
    // голі посилання (починаються з http або https, не в <a>)
    '/(?<!["\'=])(https?:\/\/[^\s<>"\'\[\]]+)/i',
];

// Кількість оновлень
$count = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $text = $row['fulltext'];
        $original = $text;
        $replaced_links = [];

        // Заміна <a href="...">анкор</a> на "https://... анкор"
        $text = preg_replace_callback($patterns[0], function ($matches) use (&$replaced_links) {
            $url = $matches[1];
            $anchor = strip_tags($matches[2]);
            $replaced_links[] = "$url $anchor";
            return "$url $anchor";
        }, $text);

        // Заміна голих посилань (не в <a>)
        $text = preg_replace_callback($patterns[1], function ($matches) use (&$replaced_links) {
            $url = $matches[1];
            $replaced_links[] = $url;
            return $url;
        }, $text);

        // Якщо були зміни — оновлюємо
        if ($text !== $original) {
            $safe_text = $conn->real_escape_string($text);
            $update_sql = "UPDATE kievmy_content SET `fulltext` = \"$safe_text\" WHERE id = $id";
            $conn->query($update_sql);
            $count++;

            // Вивід результату
            echo "$id\n";
            foreach (array_unique($replaced_links) as $link) {
                echo "    $link\n";
            }
        }
    }

    echo "\n✅ Готово. Оновлено статей: $count\n";
} else {
    echo "Не знайдено статей з зовнішніми посиланнями.\n";
}

$conn->close();
?>