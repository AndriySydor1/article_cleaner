<?php

$host = 'localhost';
$db   = 'tutsem09_lvv';
$user = 'tutsem09_lvv';
$pass = '66BrytPAzP';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    if (!isset($_GET['id'])) {
        die("❌ ID не передано.");
    }

    $id = (int) $_GET['id'];

    $stmt = $pdo->prepare("SELECT id, `introtext`, `fulltext` FROM kievmy_content WHERE id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch();

    if (!$article) {
        die("❌ Стаття з ID $id не знайдена.");
    }

    $original = $article['introtext'] . "\n\n" . $article['fulltext'];
    $processed = $original;

    // 1. Заміна HTML-посилань <a href="...">текст</a> на "URL текст"
    $processed = preg_replace_callback('/<a\s+href=["\'](https?:\/\/[^"\']+)["\'][^>]*>(.*?)<\/a>/is', function ($matches) {
        $url = $matches[1];
        $text = strip_tags($matches[2]);
        return "$url $text";
    }, $processed);

    // 2. Заміна посилань виду https://site.com site.com -> одне текстове посилання
    $processed = preg_replace('/https?:\/\/([^\s<]+)[\s]+\1/iu', '$1', $processed);

    // 3. Обробка "голих" посилань (без <a>) — залишає як текст
    // Нічого не змінюємо — просто демонструємо

    echo "<h3>🔍 Оригінальний фрагмент:</h3><div style='white-space:pre-wrap;border:1px solid #ccc;padding:10px;margin-bottom:20px;'>"
        . htmlspecialchars(mb_substr($original, 0, 1000)) . "...</div>";

    echo "<h3>✅ Після обробки:</h3><div style='white-space:pre-wrap;border:1px solid #ccc;padding:10px;'>"
        . htmlspecialchars(mb_substr($processed, 0, 1000)) . "...</div>";

} catch (PDOException $e) {
    echo "❌ Помилка підключення або запиту: " . $e->getMessage();
}
?>