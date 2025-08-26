<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üdvözlő oldal</title>
</head>
<body>
    <h1><?php echo "Üdvözöllek, " . htmlspecialchars($_SESSION['username']) . "!"; ?></h1>
    <a href="logout.php">Kijelentkezés</a>
</body>
</html>