<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: pages/login.php');
    exit;
}
require_once 'dbconnect.php';

$stmt = $pdo->query("SELECT * FROM gas_measures_g7a ORDER BY created_at DESC LIMIT 1");
$last = $stmt->fetch();

$stmt2 = $pdo->query("SELECT * FROM gas_measures_g7a ORDER BY created_at DESC LIMIT 20");
$history = $stmt2->fetchAll();

function getStatus($value) {
    if ($value <= 200) return "SAFE";
    if ($value <= 500) return "WARNING";
    return "DANGER";
}
function getColor($status) {
    if ($status === "SAFE") return "green";
    if ($status === "WARNING") return "orange";
    return "red";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gas Dashboard</title>
    <meta http-equiv="refresh" content="5">
</head>
<body>
    <h1>Gas Rover Dashboard</h1>
    <p>Bienvenue, <?= $_SESSION['username'] ?> | <a href="pages/logout.php">Déconnexion</a></p>

    <h2>Dernière mesure</h2>
    <?php if ($last): ?>
        <?php $status = getStatus($last['gas_value']); ?>
        <p><strong>Capteur :</strong> <?= $last['sensor_name'] ?></p>
        <p><strong>Valeur gaz :</strong> <?= $last['gas_value'] ?></p>
        <p><strong>Status :</strong> <span style="color:<?= getColor($status) ?>; font-weight:bold"><?= $status ?></span></p>
        <p><strong>Niveau danger :</strong> <?= $last['danger_level'] ?></p>
        <p><strong>Date :</strong> <?= $last['created_at'] ?></p>
    <?php else: ?>
        <p>Aucune donnée disponible.</p>
    <?php endif; ?>

    <h2>Historique (20 dernières mesures)</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>Date</th>
            <th>Capteur</th>
            <th>Valeur</th>
            <th>Status</th>
            <th>Danger</th>
        </tr>
        <?php foreach ($history as $row): ?>
        <tr>
            <td><?= $row['created_at'] ?></td>
            <td><?= $row['sensor_name'] ?></td>
            <td><?= $row['gas_value'] ?></td>
            <td style="color:<?= getColor(getStatus($row['gas_value'])) ?>"><?= getStatus($row['gas_value']) ?></td>
            <td><?= $row['danger_level'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>