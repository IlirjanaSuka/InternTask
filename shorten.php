<?php
include('db_connect.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $url = $_POST['url'];
    $expiration = $_POST['expiration']; 

    if (filter_var($url, FILTER_VALIDATE_URL) === false) {
        echo "Invalid URL.";
        exit;
    }

    $short_link = substr(md5($url . time()), 0, 6);

    if (!empty($expiration)) {
        $expiration_date = new DateTime();
        $expiration_date->add(new DateInterval("PT{$expiration}M"));
        $expiration_time = $expiration_date->format('Y-m-d H:i:s');
    } else {
        $expiration_time = NULL;
    }

    $stmt = $conn->prepare("INSERT INTO short_links (original_url, short_link, expires_at) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $url, $short_link, $expiration_time);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$query = "SELECT * FROM short_links ORDER BY created_at DESC";
$result = $conn->query($query);
?>
