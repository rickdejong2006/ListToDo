<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

$pdo = new PDO(
    'mysql:host=localhost;dbname=taken_app;charset=utf8',
    'root',
    '',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM gebruikers WHERE email = ?');
    $stmt->execute([$email]);
    $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($gebruiker && password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
        $_SESSION['ingelogd'] = true;
        $_SESSION['gebruiker_id'] = $gebruiker['id'];
        header('Location: ListToDo.php');
        exit;
    }

    if (!$gebruiker) {
        $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO gebruikers (email, wachtwoord) VALUES (?, ?)');
        $stmt->execute([$email, $hash]);
        $_SESSION['ingelogd'] = true;
        $_SESSION['gebruiker_id'] = $pdo->lastInsertId();
        header('Location: ListToDo.php');
        exit;
    }
}

echo '<!DOCTYPE html>';
echo '<html>';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<title>Login</title>';
echo '<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f4f4;
    }
    .container {
        width: 320px;
        margin: 100px auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }
    input {
        width: 90%;
        padding: 8px;
        margin-bottom: 10px;
    }
    button {
        padding: 8px 14px;
        cursor: pointer;
    }
</style>';
echo '</head>';
echo '<body>';

echo '<div class="container">';
echo '<h1>Login</h1>';

echo '<form method="post">';
echo '<input type="email" name="email" placeholder="Email" required>';
echo '<input type="password" name="wachtwoord" placeholder="Wachtwoord" required>';
echo '<button type="submit">Inloggen</button>';
echo '</form>';

echo '</div>';

echo '</body>';
echo '</html>';
