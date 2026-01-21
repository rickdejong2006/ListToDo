<?php
require 'db.php';

if (!isset($_SESSION['ingelogd'])) {
    header('Location: login.php');
    exit;
}

$gebruikerId = $_SESSION['gebruiker_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nieuw_doel'])) {
        $stmt = $pdo->prepare('INSERT INTO doelen (gebruiker_id, tekst) VALUES (?, ?)');
        $stmt->execute([$gebruikerId, $_POST['nieuw_doel']]);
    }

    if (isset($_POST['verwijder'])) {
        $stmt = $pdo->prepare(
            'DELETE doelen
             FROM doelen
             JOIN gebruikers ON doelen.gebruiker_id = gebruikers.id
             WHERE doelen.id = ? AND gebruikers.id = ?'
        );
        $stmt->execute([$_POST['verwijder'], $gebruikerId]);
    }

    if (isset($_POST['uitloggen'])) {
        session_destroy();
        header('Location: login.php');
        exit;
    }

    header('Location: ListToDo.php');
    exit;
}

$stmt = $pdo->prepare(
    'SELECT doelen.id, doelen.tekst
     FROM doelen
     JOIN gebruikers ON doelen.gebruiker_id = gebruikers.id
     WHERE gebruikers.id = ?'
);
$stmt->execute([$gebruikerId]);
$doelen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>ToDo lijst</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card schaduw-kaart">
        <div class="card-body inhoud-kaart">

            <div class="d-flex justify-content-between align-items-center mb-3 kop-balk">
                <h3 class="titel-pagina mb-0">Mijn taken</h3>
                <form method="post">
                    <button name="uitloggen" class="btn btn-outline-danger btn-sm knop-uitloggen">
                        Uitloggen
                    </button>
                </form>
            </div>

            <form method="post" class="d-flex gap-2 mb-4 formulier-toevoegen">
                <input
                    type="text"
                    name="nieuw_doel"
                    class="form-control invoer-taak"
                    placeholder="Nieuwe taak"
                    required
                >
                <button type="submit" class="btn btn-primary knop-toevoegen">
                    Toevoegen
                </button>
            </form>

            <ul class="list-group lijst-taken">
                <?php foreach ($doelen as $doel): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center taak-item">
                        <span class="taak-tekst">
                            <?= htmlspecialchars($doel['tekst']) ?>
                        </span>
                        <form method="post">
                            <button
                                name="verwijder"
                                value="<?= $doel['id'] ?>"
                                class="btn btn-sm btn-danger knop-verwijderen"
                            >
                                ✕
                            </button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>

        </div>
    </div>
</div>

</body>
</html>
