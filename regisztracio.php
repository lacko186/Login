<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    
    if (!empty($username) && !empty($email) && !empty($password) && !empty($password2)) {
        if ($password !== $password2) {
            $error = "A jelszavak nem egyeznek meg.";
        } else {
            $conn = getConnection();
            
            $stmt = $conn->prepare("SELECT username FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = "A felhasználónév vagy email már létezik!";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $password_hash);
                
                if ($stmt->execute()) {
                    $success = "Sikeres regisztráció! Most már bejelentkezhetsz.";
                } else {
                    $error = "Hiba a regisztráció során: " . $conn->error;
                }
            }
            
            $stmt->close();
            $conn->close();
        }
    } else {
        $error = "Minden mezőt ki kell tölteni!";
    }
}
?>

<!DOCTYPE html>
<html lang='hu'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' type='text/css' href='style.css'>
    <title>Regisztráció</title>
</head>
<body>
    <form method="POST" action="regisztracio.php">
        <h1>Regisztráció</h1><hr>
        <?php if ($error): ?>
            <div style="color: red; margin-bottom: 10px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div style="color: green; margin-bottom: 10px;"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <input type="text" name="username" placeholder='Felhasználónév' required><br>
        <input type="email" name="email" placeholder='Email' required><br>
        <input type="password" name="password" placeholder="Jelszó" required><br>
        <input type="password" name="password2" placeholder="Jelszó újra" required><br><br>
        <a href="index.php">Már van fiókod? Jelentkezz be!</a><br><br>
        <button type="submit">Regisztráció</button>
    </form>
</body>
</html>