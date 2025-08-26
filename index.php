<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (!empty($username) && !empty($password)) {
        $conn = getConnection();
        
        $stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                header("Location: welcome.php");
                exit();
            } else {
                $error = "Hibás felhasználónév vagy jelszó";
            }
        } else {
            $error = "Hibás felhasználónév vagy jelszó";
        }
        
        $stmt->close();
        $conn->close();
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
    <title>Bejelentkezés</title>
</head>
<body>
    <form method="POST" action="index.php">
        <h1>Bejelentkezés</h1><hr>
        <?php if ($error): ?>
            <div style="color: red; margin-bottom: 10px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <input type="text" name="username" placeholder='Felhasználónév' required><br>
        <input type="password" name="password" placeholder="Jelszó" required><br><br>
        <a href="regisztracio.php">Nincs még fiókod? Regisztrálj!</a><br><br>
        <button type="submit">Bejelentkezés</button>
    </form>
</body>
</html>
