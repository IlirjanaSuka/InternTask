<?php
$pdo = new PDO('mysql:host=localhost;dbname=Task', 'root', 'Lana.1234');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$query = $pdo->query("SELECT * FROM urls WHERE expires_at IS NULL OR expires_at > NOW() ORDER BY created_at DESC");
$urls = $query->fetchAll(PDO::FETCH_ASSOC);

if ($urls) {
    foreach ($urls as $row) {
        echo "<div class='url-item'>";
        echo "<a href='{$row['url']}' target='_blank'>https://shorturl.co/{$row['code']}</a>";
        echo " <a href='delete.php?id=" . htmlspecialchars($row['id']) . "' class='delete'>&#x1F5D1;</a>";
        echo "</div>";
    }
} else {
    echo "<p>No shortened URLs yet.</p>";
}
?>
