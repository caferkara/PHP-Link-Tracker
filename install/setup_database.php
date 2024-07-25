<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Database</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .message {
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            width: 80%;
            max-width: 400px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<?php
// Include configuration file
$config = include(__DIR__ . '/../config/config.php');

// Establish database connection
$mysqli = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
if ($mysqli->connect_error) {
    echo '<div class="message error">Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error . '</div>';
    exit();
}

// SQL query to create the links table with utf8_general_ci collation
$sql = "CREATE TABLE IF NOT EXISTS links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    clicks INT DEFAULT 0
) CHARACTER SET utf8 COLLATE utf8_general_ci";

if ($mysqli->query($sql) === TRUE) {
    echo '<div class="message success">Table "links" created successfully. For your security, please delete the <b>"install"</b> folder.</div>';
} else {
    echo '<div class="message error">Error creating table: ' . $mysqli->error . '</div>';
}

$mysqli->close();
?>

</body>
</html>