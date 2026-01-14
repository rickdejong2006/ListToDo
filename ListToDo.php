<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['ingelogd'])) {
    header('Location: login.php');
    exit;
}

$pdo = new PDO(
    'mysql:host=localhost;dbname=taken_app;charset=utf8',
    'root',
    '',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

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

echo '<!DOCTYPE html>';
echo '<html>';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<title>ListToDo</title>';
echo '<style>
    body
    .container {
        width: 400px;
        margin: 60px auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
    }
    h1 {
        text-align: center;
    }
    form {
        margin-bottom: 15px;
    }
    input[type="text"] {
        width: 70%;
        padding: 6px;
    }
    button {
        padding: 6px 10px;
        cursor: pointer;
    }
    ul {
        list-style: none;
        padding: 0;
    }
    li {
        padding: 8px;
        margin-bottom: 6px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 4px;
    }
</style>';
echo '</head>';
echo '<body>';

echo '<div class="container">';

echo '<form method="post" style="text-align:right">';
echo '<button name="uitloggen">Uitloggen</button>';
echo '</form>';

echo '<h1>Mijn ToDo lijst</h1>';

echo '<form method="post">';
echo '<input type="text" name="nieuw_doel" required>' ;
echo '<button type="submit">Toevoegen</button>';
echo '</form>';

echo '<ul>';
foreach ($doelen as $doel) {
    echo '<li>';
    echo htmlspecialchars($doel['tekst']);
    echo '<form method="post">';
    echo '<button name="verwijder" value="'.$doel['id'].'">✕</button>';
    echo '</form>';
    echo '</li>';
}
echo '</ul>';

echo '</div>';

echo '</body>';
echo '</html>';
