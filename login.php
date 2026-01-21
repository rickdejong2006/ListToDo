<?php
require 'db.php';

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
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Inloggen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100 pagina-login">
    <div class="card schaduw-kaart" style="width: 350px;">
        <div class="card-body inhoud-kaart">
            <h3 class="text-center mb-4 titel-login">Inloggen</h3>

            <form method="post" class="formulier-login">
                <div class="mb-3">
                    <input
                        type="email"
                        name="email"
                        class="form-control invoer-email"
                        placeholder="E-mail"
                        required
                    >
                </div>

                <div class="mb-3">
                    <input
                        type="password"
                        name="wachtwoord"
                        class="form-control invoer-wachtwoord"
                        placeholder="Wachtwoord"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary w-100 knop-inloggen">
                    Inloggen
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
