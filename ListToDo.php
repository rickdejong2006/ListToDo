<?php
require 'db.php';

if (!isset($_SESSION['ingelogd'])) {
    header('Location: login.php');
    exit;
}

$gebruikerId = $_SESSION['gebruiker_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['nieuw_doel'])) {
        $stmt = $pdo->prepare(
            'INSERT INTO doelen (gebruiker_id, tekst) VALUES (?, ?)'
        );
        $stmt->execute([$gebruikerId, $_POST['nieuw_doel']]);
    }

    if (isset($_POST['verwijder'])) {
        $stmt = $pdo->prepare(
            'DELETE FROM doelen WHERE id = ? AND gebruiker_id = ?'
        );
        $stmt->execute([$_POST['verwijder'], $gebruikerId]);
    }

    if (isset($_POST['opslaan'])) {
        $stmt = $pdo->prepare(
            'UPDATE doelen SET tekst = ? WHERE id = ? AND gebruiker_id = ?'
        );
        $stmt->execute([
            $_POST['aangepaste_tekst'],
            $_POST['opslaan'],
            $gebruikerId
        ]);
    }

    // Doel voltooien
    if (isset($_POST['voltooi'])) {
        $stmt = $pdo->prepare(
            'UPDATE doelen SET voltooid = 1 WHERE id = ? AND gebruiker_id = ?'
        );
        $stmt->execute([$_POST['voltooi'], $gebruikerId]);
    }

    // Doel ongedaan maken
    if (isset($_POST['onvoltooi'])) {
        $stmt = $pdo->prepare(
            'UPDATE doelen SET voltooid = 0 WHERE id = ? AND gebruiker_id = ?'
        );
        $stmt->execute([$_POST['onvoltooi'], $gebruikerId]);
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
    'SELECT id, tekst, voltooid FROM doelen WHERE gebruiker_id = ?'
);
$stmt->execute([$gebruikerId]);
$doelen = $stmt->fetchAll(PDO::FETCH_ASSOC);

$bewerkId = $_GET['bewerk'] ?? null;
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>ToDo lijst</title>
    <link href="bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body">

            <div class="d-flex justify-content-between mb-3">
                <h3>Mijn taken</h3>

                <form method="post">
                    <button name="uitloggen" class="btn btn-outline-danger btn-sm">
                        Uitloggen
                    </button>
                </form>
            </div>

            <form method="post" class="d-flex gap-2 mb-4">
                <input
                    type="text"
                    name="nieuw_doel"
                    class="form-control"
                    placeholder="Nieuwe taak"
                    required
                >

                <button class="btn btn-primary">
                    Toevoegen
                </button>
            </form>

            <ul class="list-group">

                <?php foreach ($doelen as $doel): ?>

                    <li class="list-group-item d-flex justify-content-between align-items-center">

                        <?php if ($bewerkId == $doel['id']): ?>

                            <form method="post" class="d-flex gap-2 w-100">
                                <input
                                    type="text"
                                    name="aangepaste_tekst"
                                    class="form-control"
                                    value="<?= htmlspecialchars($doel['tekst']) ?>"
                                    required
                                >

                                <button
                                    name="opslaan"
                                    value="<?= $doel['id'] ?>"
                                    class="btn btn-success btn-sm"
                                >
                                    Opslaan
                                </button>
                            </form>

                        <?php else: ?>

                            <span style="<?= $doel['voltooid'] ? 'text-decoration: line-through;' : '' ?>">
                                <?= htmlspecialchars($doel['tekst']) ?>
                            </span>

                            <div class="d-flex gap-2">

                                <form method="post">
                                    <?php if ($doel['voltooid']): ?>

                                        <button
                                            name="onvoltooi"
                                            value="<?= $doel['id'] ?>"
                                            class="btn btn-sm btn-secondary"
                                            style="width: 85px;"
                                        >
                                            Ongedaan
                                        </button>

                                    <?php else: ?>

                                        <button
                                            name="voltooi"
                                            value="<?= $doel['id'] ?>"
                                            class="btn btn-sm btn-success"
                                            style="width: 95px;"
                                        >
                                            Voltooid
                                        </button>

                                    <?php endif; ?>
                                </form>

                                <a
                                    href="ListToDo.php?bewerk=<?= $doel['id'] ?>"
                                    class="btn btn-sm btn-warning"
                                    style="width: 95px;"
                                >
                                    Bewerken
                                </a>

                                <form method="post">
                                    <button
                                        name="verwijder"
                                        value="<?= $doel['id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        style="width: 95px;"
                                    >
                                        verwijderen
                                    </button>
                                </form>

                            </div>

                        <?php endif; ?>

                    </li>

                <?php endforeach; ?>

            </ul>

        </div>
    </div>
</div>

</body>
</html>