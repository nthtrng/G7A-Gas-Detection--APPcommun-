<?php
session_start();
date_default_timezone_set('Europe/Paris');
if (!isset($_SESSION['user_id'])) {
    header('Location: pages/login.php');
    exit;
}
require_once 'dbconnect.php';

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// G7A — gaz
$g7a = $pdo->query("SELECT * FROM gas_measures_g7a ORDER BY created_at DESC LIMIT 1")->fetch();

// G7B — recul
$g7b = $pdo->query("SELECT * FROM historique_capteur_g7b_recul ORDER BY date_evenement DESC LIMIT 1")->fetch();

// G7C — capteurs
$g7c = $pdo->query("SELECT * FROM mesures_capteurs_g7c ORDER BY date_enregistrement DESC LIMIT 1")->fetch();

// G7E — audio
$g7e = $pdo->query("SELECT * FROM G7E_audiofiles ORDER BY uploadedAt DESC LIMIT 1")->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rover Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, sans-serif; background: #eef4ee; color: #1a1a1a; min-height: 100vh; display: flex; flex-direction: column; }
    .navbar { background: #1a1a2e; color: #fff; padding: 0 2rem; display: flex; align-items: center; justify-content: space-between; height: 56px; }
    .nav-brand { display: flex; align-items: center; gap: 10px; font-size: 15px; font-weight: 500; color: #fff; text-decoration: none; }
    .nav-brand i { font-size: 20px; color: #e24b4a; }
    .nav-links { display: flex; gap: 24px; }
    .nav-links a { color: #aaa; font-size: 13px; text-decoration: none; }
    .nav-links a.active { color: #fff; font-weight: 500; }
    .nav-status { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #aaa; }
    .dot { width: 7px; height: 7px; border-radius: 50%; background: #639922; display: inline-block; }
    .page { max-width: 1000px; margin: 0 auto; padding: 2rem 1.5rem; flex: 1; width: 100%; }
    .page-header { margin-bottom: 1.5rem; }
    .page-title { font-size: 22px; font-weight: 500; }
    .page-sub { font-size: 13px; color: #888; margin-top: 2px; }
    .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
    .card { background: #fff; border: 0.5px solid rgba(0,0,0,0.1); border-radius: 12px; padding: 1.25rem; color: #1a1a1a; display: block; }
    .card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 0.5px solid rgba(0,0,0,0.07); }
    .card-group { font-size: 13px; font-weight: 500; color: #1a1a1a; display: flex; align-items: center; gap: 8px; }
    .card-group i { font-size: 16px; color: #aaa; }
    .badge { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 500; padding: 3px 8px; border-radius: 6px; }
    .badge-safe { background: #eaf3de; color: #3b6d11; }
    .badge-warning { background: #faeeda; color: #854f0b; }
    .badge-danger { background: #fcebeb; color: #a32d2d; }
    .badge-info { background: #e6f1fb; color: #0c447c; }
    .badge-gray { background: #f1f1f1; color: #888; }
    .stat { display: flex; justify-content: space-between; align-items: center; padding: 7px 0; border-bottom: 0.5px solid rgba(0,0,0,0.05); font-size: 13px; }
    .stat:last-child { border-bottom: none; }
    .stat-label { color: #888; }
    .stat-value { font-weight: 500; }
    .card-link { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; color: #1a1a2e; margin-top: 1rem; font-weight: 500; text-decoration: none; }
    .no-data { font-size: 13px; color: #bbb; text-align: center; padding: 1rem 0; }
    footer { text-align: center; padding: 1.5rem; font-size: 12px; color: #aaa; border-top: 0.5px solid rgba(0,0,0,0.08); background: #fff; margin-top: auto; }
  </style>
</head>
<body>

<nav class="navbar">
  <a href="index.php" class="nav-brand">
    <i class="ti ti-radioactive"></i>
    Rover Monitor
  </a>
  <div class="nav-links">
    <a href="dashboard.php" class="active">Overview</a>
    <a href="gas_dashboard.php">G7A</a>
    <a href="dashboard_g7b.php">G7B</a>
    <a href="dashboard_g7c.php">G7C</a>
    <a href="dashboard_g7e.php">G7E</a>
  </div>
  <div class="nav-status">
    <span class="dot"></span>
    <?= h($_SESSION['username']) ?> — <?= h($_SESSION['groupe'] ?? '') ?> &nbsp;|&nbsp;
    <a href="pages/logout.php" style="color:#aaa;">Log out</a>
  </div>
</nav>

<div class="page">

  <div class="page-header">
    <p class="page-title">Rover Overview</p>
    <p class="page-sub">All sensors — last readings</p>
  </div>

  <div class="grid">

    <!-- G7A -->
    <div class="card">
      <div class="card-head">
        <span class="card-group"><i class="ti ti-flame"></i> G7A — Gas sensor</span>
        <?php if ($g7a):
          $s = $g7a['gas_value'] <= 200 ? 'SAFE' : ($g7a['gas_value'] <= 500 ? 'WARNING' : 'DANGER');
          $cls = $s === 'SAFE' ? 'badge-safe' : ($s === 'WARNING' ? 'badge-warning' : 'badge-danger');
        ?>
          <span class="badge <?= $cls ?>"><?= $s ?></span>
        <?php else: ?>
          <span class="badge badge-gray">No data</span>
        <?php endif; ?>
      </div>
      <?php if ($g7a): ?>
        <div class="stat"><span class="stat-label">Gas value</span><span class="stat-value"><?= $g7a['gas_value'] ?> raw</span></div>
        <div class="stat"><span class="stat-label">Sensor</span><span class="stat-value"><?= h($g7a['sensor_name']) ?></span></div>
        <div class="stat"><span class="stat-label">Last update</span><span class="stat-value" style="font-size:12px;"><?= date('d M Y \a\t H:i', strtotime($g7a['created_at'])) ?></span></div>
      <?php else: ?>
        <p class="no-data">No data available</p>
      <?php endif; ?>
      <a href="gas_dashboard.php" class="card-link"><i class="ti ti-arrow-right" style="font-size:13px"></i> View details</a>
    </div>

    <!-- G7B -->
    <div class="card">
      <div class="card-head">
        <span class="card-group"><i class="ti ti-arrow-autofit-left"></i> G7B — Distance sensor</span>
        <?php if ($g7b): ?>
          <span class="badge badge-info"><?= h($g7b['statut']) ?></span>
        <?php else: ?>
          <span class="badge badge-gray">No data</span>
        <?php endif; ?>
      </div>
      <?php if ($g7b): ?>
        <div class="stat"><span class="stat-label">Distance</span><span class="stat-value"><?= h($g7b['distance_cm']) ?> cm</span></div>
        <div class="stat"><span class="stat-label">Raw value</span><span class="stat-value"><?= h($g7b['valeur_brute']) ?></span></div>
        <div class="stat"><span class="stat-label">Last update</span><span class="stat-value" style="font-size:12px;"><?= date('d M Y \a\t H:i', strtotime($g7b['date_evenement'])) ?></span></div>
      <?php else: ?>
        <p class="no-data">No data available</p>
      <?php endif; ?>
      <a href="dashboard_g7b.php" class="card-link"><i class="ti ti-arrow-right" style="font-size:13px"></i> View details</a>
    </div>

    <!-- G7C -->
    <div class="card">
      <div class="card-head">
        <span class="card-group"><i class="ti ti-map-pin"></i> G7C — GPS & environment</span>
        <?php if ($g7c): ?>
          <span class="badge badge-info">Live</span>
        <?php else: ?>
          <span class="badge badge-gray">No data</span>
        <?php endif; ?>
      </div>
      <?php if ($g7c): ?>
        <div class="stat"><span class="stat-label">Humidity</span><span class="stat-value"><?= $g7c['humidite_pourcent'] ?> %</span></div>
        <div class="stat"><span class="stat-label">Altitude</span><span class="stat-value"><?= $g7c['altitude'] ?> m</span></div>
        <div class="stat"><span class="stat-label">Distance</span><span class="stat-value"><?= $g7c['distance_cm'] ?> cm</span></div>
        <div class="stat"><span class="stat-label">Last update</span><span class="stat-value" style="font-size:12px;"><?= date('d M Y \a\t H:i', strtotime($g7c['date_enregistrement'])) ?></span></div>
      <?php else: ?>
        <p class="no-data">No data available</p>
      <?php endif; ?>
      <a href="dashboard_g7c.php" class="card-link"><i class="ti ti-arrow-right" style="font-size:13px"></i> View details</a>
    </div>

    <!-- G7E -->
    <div class="card">
      <div class="card-head">
        <span class="card-group"><i class="ti ti-microphone"></i> G7E — Audio sensor</span>
        <?php if ($g7e): ?>
          <span class="badge badge-info">Live</span>
        <?php else: ?>
          <span class="badge badge-gray">No data</span>
        <?php endif; ?>
      </div>
      <?php if ($g7e): ?>
        <div class="stat"><span class="stat-label">File</span><span class="stat-value" style="font-size:12px;"><?= h($g7e['filename']) ?></span></div>
        <div class="stat"><span class="stat-label">Duration</span><span class="stat-value"><?= $g7e['duration'] ?>s</span></div>
        <div class="stat"><span class="stat-label">Size</span><span class="stat-value"><?= round($g7e['fileSize'] / 1024) ?> KB</span></div>
        <div class="stat"><span class="stat-label">Last update</span><span class="stat-value" style="font-size:12px;"><?= date('d M Y \a\t H:i', strtotime($g7e['uploadedAt'])) ?></span></div>
      <?php else: ?>
        <p class="no-data">No data available</p>
      <?php endif; ?>
      <a href="dashboard_g7e.php" class="card-link"><i class="ti ti-arrow-right" style="font-size:13px"></i> View details</a>
    </div>

  </div>
</div>

<footer>Rover Monitoring System &nbsp;·&nbsp; <span style="font-size:10px; color:#bbb;">ISEP · 2026</span></footer>
</body>
</html>