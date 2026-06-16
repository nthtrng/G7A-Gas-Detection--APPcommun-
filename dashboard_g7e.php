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

$stmt = $pdo->query("SELECT * FROM G7E_audiofiles ORDER BY uploadedAt DESC LIMIT 1");
$last = $stmt->fetch();

$stmt2 = $pdo->query("SELECT * FROM G7E_audiofiles ORDER BY uploadedAt DESC LIMIT 20");
$history = $stmt2->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>G7E - Audio Sensor</title>
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
    .page { max-width: 900px; margin: 0 auto; padding: 2rem 1.5rem; flex: 1; width: 100%; }
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
    .page-title { font-size: 22px; font-weight: 500; }
    .page-sub { font-size: 13px; color: #888; margin-top: 2px; }
    .btn { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; padding: 7px 14px; border-radius: 8px; border: none; cursor: pointer; text-decoration: none; }
    .btn-primary { background: #1a1a2e; color: #fff; }
    .btn-secondary { background: #fff; color: #1a1a1a; border: 0.5px solid rgba(0,0,0,0.15); }
    .metrics { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 1.5rem; }
    .metric { background: #fff; border: 0.5px solid rgba(0,0,0,0.1); border-radius: 10px; padding: 1rem; }
    .metric-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
    .metric-label { font-size: 12px; color: #888; }
    .metric-icon { font-size: 16px; color: #aaa; }
    .metric-value { font-size: 20px; font-weight: 500; }
    .card { background: #fff; border: 0.5px solid rgba(0,0,0,0.1); border-radius: 12px; padding: 1.25rem; margin-bottom: 1rem; }
    .card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
    .card-title { font-size: 12px; font-weight: 500; color: #888; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 500; padding: 3px 9px; border-radius: 6px; }
    .badge-info { background: #e6f1fb; color: #0c447c; }
    .row { display: flex; align-items: center; justify-content: space-between; padding: 9px 0; border-bottom: 0.5px solid rgba(0,0,0,0.07); font-size: 13px; }
    .row:last-child { border-bottom: none; }
    .row-label { color: #888; display: flex; align-items: center; gap: 7px; }
    .row-value { font-weight: 500; color: #1a1a1a; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    th { text-align: left; font-size: 11px; font-weight: 500; color: #aaa; text-transform: uppercase; letter-spacing: 0.06em; padding: 0 0 10px; border-bottom: 0.5px solid rgba(0,0,0,0.08); }
    td { padding: 10px 0; border-bottom: 0.5px solid rgba(0,0,0,0.06); vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
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
    <a href="dashboard.php">Overview</a>
    <a href="gas_dashboard.php">G7A</a>
    <a href="dashboard_g7b.php">G7B</a>
    <a href="dashboard_g7c.php">G7C</a>
    <a href="dashboard_g7e.php" class="active">G7E</a>
  </div>
  <div class="nav-status">
    <span class="dot"></span>
    <?= h($_SESSION['username']) ?> &nbsp;|&nbsp;
    <a href="pages/logout.php" style="color:#aaa;">Log out</a>
  </div>
</nav>

<div class="page">

  <div class="page-header">
    <div>
      <p class="page-title">G7E - Audio Sensor</p>
      <p class="page-sub">Last update: <?= $last ? date('d F Y \a\t H:i:s', strtotime($last['uploadedAt'])) : 'No data' ?></p>
    </div>
    <div style="display:flex; gap:8px;">
      <a href="dashboard.php" class="btn btn-secondary"><i class="ti ti-arrow-left" style="font-size:14px"></i> Overview</a>
      <a href="dashboard_g7e.php" class="btn btn-primary"><i class="ti ti-refresh" style="font-size:14px"></i> Refresh</a>
    </div>
  </div>

  <?php if ($last): ?>

  <div class="metrics">
    <div class="metric">
      <div class="metric-top"><span class="metric-label">Last file</span><i class="ti ti-file metric-icon"></i></div>
      <div class="metric-value" style="font-size:13px;margin-top:4px;"><?= h($last['filename']) ?></div>
    </div>
    <div class="metric">
      <div class="metric-top"><span class="metric-label">Duration</span><i class="ti ti-clock metric-icon"></i></div>
      <div class="metric-value"><?= $last['duration'] ?> <span style="font-size:13px;color:#aaa;">s</span></div>
    </div>
    <div class="metric">
      <div class="metric-top"><span class="metric-label">File size</span><i class="ti ti-database metric-icon"></i></div>
      <div class="metric-value"><?= round($last['fileSize'] / 1024) ?> <span style="font-size:13px;color:#aaa;">KB</span></div>
    </div>
  </div>

  <div class="card">
    <div class="card-head"><span class="card-title">Latest file</span><span class="badge badge-info">Live</span></div>
    <div class="row">
      <span class="row-label"><i class="ti ti-file" style="font-size:14px"></i> Filename</span>
      <span class="row-value" style="font-size:12px;"><?= h($last['filename']) ?></span>
    </div>
    <div class="row">
      <span class="row-label"><i class="ti ti-clock" style="font-size:14px"></i> Duration</span>
      <span class="row-value"><?= $last['duration'] ?>s</span>
    </div>
    <div class="row">
      <span class="row-label"><i class="ti ti-database" style="font-size:14px"></i> File size</span>
      <span class="row-value"><?= round($last['fileSize'] / 1024) ?> KB</span>
    </div>
    <div class="row">
      <span class="row-label"><i class="ti ti-clock" style="font-size:14px"></i> Uploaded at</span>
      <span class="row-value" style="font-size:12px;"><?= date('d F Y \a\t H:i:s', strtotime($last['uploadedAt'])) ?></span>
    </div>
  </div>

  <div class="card">
    <div class="card-head"><span class="card-title">Audio files history</span></div>
    <table>
      <thead>
        <tr><th>Uploaded at</th><th>Filename</th><th>Duration</th><th>Size</th></tr>
      </thead>
      <tbody>
        <?php foreach ($history as $row): ?>
        <tr>
          <td style="color:#888;"><?= date('d F Y \a\t H:i:s', strtotime($row['uploadedAt'])) ?></td>
          <td style="font-size:12px;"><?= h($row['filename']) ?></td>
          <td><?= $row['duration'] ?>s</td>
          <td><?= round($row['fileSize'] / 1024) ?> KB</td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php else: ?>
    <div class="card"><p style="color:#888;text-align:center;">No data available yet.</p></div>
  <?php endif; ?>

</div>

<footer>Rover Monitoring System &nbsp;·&nbsp; <span style="font-size:10px; color:#bbb;">ISEP · 2026</span></footer>
</body>
</html>