<?php
$lastMeasure = [
    "sensor_name" => "MQ-135",
    "value_raw" => 420,
    "status" => "WARNING",
    "zone" => "Reactor Corridor",
    "created_at" => "2026-06-09 14:30:00"
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gas Sensor Dashboard</title>
</head>
<body>

<h1>MQ-135 Gas Sensor Dashboard</h1>

<p><strong>Sensor:</strong> <?= $lastMeasure["sensor_name"] ?></p>
<p><strong>Gas value:</strong> <?= $lastMeasure["value_raw"] ?></p>
<p><strong>Status:</strong> <?= $lastMeasure["status"] ?></p>
<p><strong>Zone:</strong> <?= $lastMeasure["zone"] ?></p>
<p><strong>Date:</strong> <?= $lastMeasure["created_at"] ?></p>

</body>
</html>
