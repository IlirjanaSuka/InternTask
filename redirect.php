<?php
include('db_connect.php');

$short_link = $_GET['link']; 
$query = "SELECT * FROM short_links WHERE short_link = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $short_link);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if ($row) {
    if ($row['expires_at'] !== NULL && new DateTime() > new DateTime($row['expires_at'])) {
        echo "This link has expired.";
    } else {
        header("Location: " . $row['original_url']);
        exit;
    }
} else {
    echo "Link not found.";
}

$stmt->close();
?>
