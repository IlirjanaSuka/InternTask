<!DOCTYPE html>
<html lang="en">

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

    $expiration_time = null;
    if ($expiration) {
        $expiration_time = date('Y-m-d H:i:s', strtotime("+$expiration minutes"));
    }

 
    $stmt = $conn->prepare("INSERT INTO short_links (original_url, short_link, expiration_time) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $url, $short_link, $expiration_time);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$query = "SELECT * FROM short_links WHERE expiration_time IS NULL OR expiration_time > NOW() ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <img src="AnchorzUp Logo_No Tagline (1).svg" width="200px" height="100px" alt="Logo">
            <h2>My shortened URLs</h2>

            <div id="shortened-urls">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='url-item'>";
                    echo "<a href='{$row['original_url']}' target='_blank'>https://short.link/{$row['short_link']}</a>";
                    echo " <a href='delete.php?id={$row['id']}' class='delete' style='color: gray; width: 20px; font-size: 22px;'>&#x1F5D1;</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>No URLs have been shortened yet.</p>";
            }
            ?>
            </div>
        </div>
        
        <div class="main-content">
            <h1 >URL Shortener</h1>
            <form action="index.php" method="POST" class="form-inline">
                <div class="input-container">
                    <div class="input-field">
                        <input class="input" type="url" name="url" placeholder="Paste the URL to be shortened" required>
                    </div>
                    <div class="expiration-container">
                        <div class="custom-dropdown">
                            <button type="button" class="dropdown-button">
                                Add expiration date
                            </button>
                            <div class="dropdown-options">
                                <div class="option" data-value="1">1 minute</div>
                                <div class="option" data-value="5">5 minutes</div>
                                <div class="option" data-value="30">30 minutes</div>
                                <div class="option" data-value="60">1 hour</div>
                                <div class="option" data-value="300">5 hours</div>
                            </div>
                            <input type="hidden" name="expiration" id="expiration">
                        </div>
                    </div>
                </div>
                <button type="submit" style="font-weight: bold;">Shorten URL</button>
            </form>
        </div>
    </div>
</body>

<script>
   document.addEventListener('DOMContentLoaded', function () {
    const dropdown = document.querySelector('.custom-dropdown');
    const button = document.querySelector('.dropdown-button');
    const options = document.querySelectorAll('.option');
    const hiddenInput = document.getElementById('expiration');
    button.addEventListener('click', function () {
        dropdown.classList.toggle('open');
    });
    options.forEach(option => {
        option.addEventListener('click', function () {
            hiddenInput.value = this.getAttribute('data-value');
            dropdown.classList.remove('open');
            button.textContent = `Expires in ${this.textContent}`;
        });
    });
    document.addEventListener('click', function (e) {
        if (!dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
        }
    });
});
</script>
</html>
