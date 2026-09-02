```php
<?php
require 'db.php';

$foutmelding = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    // Controleer of het wachtwoord minimaal 3 tekens heeft
    if (strlen($wachtwoord) < 3) {
        $foutmelding = 'Je wachtwoord moet minimaal 3 tekens bevatten.';
    }
    // Controleer of het wachtwoord minimaal 1 hoofdletter heeft
    elseif (!preg_match('/[A-Z]/', $wachtwoord)) {
        $foutmelding = 'Je wachtwoord moet minimaal 1 hoofdletter bevatten.';
    }
    else {
        // Zoek de gebruiker op
        $stmt = $pdo->prepare('SELECT * FROM gebruikers WHERE email = ?');
        $stmt->execute([$email]);
        $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

        // Gebruiker bestaat en wachtwoord klopt
        if ($gebruiker && password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
            $_SESSION['ingelogd'] = true;
            $_SESSION['gebruiker_id'] = $gebruiker['id'];

            header('Location: ListToDo.php');
            exit;
        }

        // Gebruiker bestaat nog niet, dus account aanmaken
        if (!$gebruiker) {
            $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                'INSERT INTO gebruikers (email, wachtwoord) VALUES (?, ?)'
            );

            $stmt->execute([$email, $hash]);

            $_SESSION['ingelogd'] = true;
            $_SESSION['gebruiker_id'] = $pdo->lastInsertId();

            header('Location: ListToDo.php');
            exit;
        }

        // Gebruiker bestaat wel, maar wachtwoord klopt niet
        $foutmelding = 'Het e-mailadres of wachtwoord is niet correct.';
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inloggen</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100 pagina-login">

    <div class="card schaduw-kaart" style="width: 350px;">

        <div class="card-body inhoud-kaart">

            <h3 class="text-center mb-4 titel-login">
                Inloggen
            </h3>

            <?php if ($foutmelding): ?>

                <div class="alert alert-danger" role="alert">
                    ❌ <?= htmlspecialchars($foutmelding) ?>
                </div>

            <?php endif; ?>

            <form method="post" class="formulier-login">

                <div class="mb-3">

                    <input
                        type="email"
                        name="email"
                        class="form-control invoer-email"
                        placeholder="E-mail"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required
                    >

                </div>

                <div class="mb-3">

                    <input
                        type="password"
                        name="wachtwoord"
                        class="form-control invoer-wachtwoord"
                        placeholder="Wachtwoord"
                        minlength="3"
                        pattern="(?=.*[A-Z]).{3,}"
                        title="Je wachtwoord moet minimaal 3 tekens en 1 hoofdletter bevatten."
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="btn btn-primary w-100 knop-inloggen"
                >
                    Inloggen
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>
```
