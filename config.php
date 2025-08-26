<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "users";

function getConnection() {
    global $servername, $db_username, $db_password, $dbname;
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    if ($conn->connect_error) {
        die("Csatlakozási hiba: " . $conn->connect_error);
    }
    return $conn;
}
?>

<?php
