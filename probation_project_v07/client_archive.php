<?php
$host     = 'localhost';
$username = 'root';
$password = '';
$dbname   = 'probasyon';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $clientId = intval($_GET['id']);

    $sql = "UPDATE clients SET archived_status = 'archived' WHERE id = $clientId";

    if ($conn->query($sql) === TRUE) {
        header("Location: clients_list.php?message=archived");
        exit();
    } else {
        echo "Error archiving client: " . $conn->error;
    }
} else {
    echo "No client ID specified.";
}

$conn->close();

