<?php
require_once "dbconnect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gas Rover Monitoring</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <main class="container">
        <h1>Gas Rover Monitoring System</h1>

        <p>
            This website displays gas sensor observations collected by our rover.
            The goal is to monitor dangerous gases, humidity, and air quality indicators.
        </p>

        <div class="status success">
            ✔ DATABASE CONNECTED
        </div>

        <nav>
            <a href="pages/dashboard.php">Dashboard</a>
            <a href="pages/sensors.php">Sensors</a>
            <a href="pages/observations.php">Observations</a>
        </nav>
    </main>

</body>
</html>