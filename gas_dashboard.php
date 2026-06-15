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
function badgeClass($status) {
    return match($status) {
        "SAFE"    => "badge-safe",
        "WARNING" => "badge-warning",
        "DANGER"  => "badge-danger",
        default   => "badge-info",
    };
}
function barClass($status) {
    return match($status) {
        "SAFE"    => "bar-safe",
        "WARNING" => "bar-warning",
        "DANGER"  => "bar-danger",
        default   => "",
    };
}
function statusIcon($status) {
    return match($status) {
        "SAFE"    => "ti-check",
        "WARNING" => "ti-alert-triangle",
        "DANGER"  => "ti-flame",
        default   => "ti-help",
    };
}
function tagClass($status) {
    return match($status) {
        "SAFE"    => "tag-safe",
        "WARNING" => "tag-warn",
        "DANGER"  => "tag-danger",
        default   => "",
    };
}
function barWidth($value, $max = 700) {
    return min(100, (int) round($value / $max * 100));
}
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$currentStatus = $last ? getStatus($last['gas_value']) : "SAFE";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rover Gas Monitor</title>
  <meta http-equiv="refresh" content="5">
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
    .metrics { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 1.5rem; }
    .metric { background: #fff; border: 0.5px solid rgba(0,0,0,0.1); border-radius: 10px; padding: 1rem; }
    .metric-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
    .metric-label { font-size: 12px; color: #888; }
    .metric-icon { font-size: 16px; color: #aaa; }
    .metric-value { font-size: 22px; font-weight: 500; }
    .metric-tag { font-size: 11px; margin-top: 4px; }
    .tag-safe { color: #3b6d11; } .tag-warn { color: #854f0b; } .tag-danger { color: #a32d2d; }
    .card { background: #fff; border: 0.5px solid rgba(0,0,0,0.1); border-radius: 12px; padding: 1.25rem; margin-bottom: 1rem; }
    .card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
    .card-title { font-size: 12px; font-weight: 500; color: #888; text-transform: uppercase; letter-spacing: 0.05em; }
    .row { display: flex; align-items: center; justify-content: space-between; padding: 9px 0; border-bottom: 0.5px solid rgba(0,0,0,0.07); font-size: 13px; }
    .row:last-child { border-bottom: none; }
    .row-label { color: #888; display: flex; align-items: center; gap: 7px; }
    .row-value { font-weight: 500; color: #1a1a1a; }
    .badge { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 500; padding: 3px 9px; border-radius: 6px; }
    .badge-safe { background: #eaf3de; color: #3b6d11; }
    .badge-warning { background: #faeeda; color: #854f0b; }
    .badge-danger { background: #fcebeb; color: #a32d2d; }
    .badge-info { background: #e6f1fb; color: #0c447c; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    th { text-align: left; font-size: 11px; font-weight: 500; color: #aaa; text-transform: uppercase; letter-spacing: 0.06em; padding: 0 0 10px; border-bottom: 0.5px solid rgba(0,0,0,0.08); }
    td { padding: 10px 0; border-bottom: 0.5px solid rgba(0,0,0,0.06); vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    .bar-wrap { width: 80px; height: 5px; background: #eee; border-radius: 99px; overflow: hidden; }
    .bar { height: 100%; border-radius: 99px; }
    .bar-safe { background: #639922; } .bar-warning { background: #ba7517; } .bar-danger { background: #e24b4a; }
    footer { text-align: center; padding: 1.5rem; font-size: 12px; color: #aaa; border-top: 0.5px solid rgba(0,0,0,0.08); background: #fff; margin-top: auto; }
  </style>
</head>
<body>

<nav class="navbar">
  <a href="index.php" class="nav-brand">
    <i class="ti ti-radioactive"></i>
    Rover Gas Monitor
  </a>
  <div class="nav-links">
    <a href="#" class="active">Dashboard</a>
  </div>
  <div class="nav-status">
    <span class="dot"></span>
    <?= h($_SESSION['username']) ?> &nbsp;|&nbsp; <a href="pages/logout.php" style="color:#aaa;">Log out</a>
  </div>
</nav>

<div class="page">

  <div class="page-header">
    <div>
      <p class="page-title">Air Quality Monitoring</p>
      <p class="page-sub">Last update: <?= $last ? date('d F Y \a\t H:i', strtotime($last['created_at'])) : 'No data' ?></p>
    </div>
    <a href="" class="btn btn-primary">
      <i class="ti ti-refresh" style="font-size:14px"></i> Refresh
    </a>
  </div>

  <?php if ($last): ?>

  <div class="metrics">
    <div class="metric">
      <div class="metric-top"><span class="metric-label">Active sensor</span><i class="ti ti-cpu metric-icon"></i></div>
      <div class="metric-value"><?= h($last['sensor_name']) ?></div>
      <div class="metric-tag tag-safe">● Connected</div>
    </div>
    <div class="metric">
      <div class="metric-top"><span class="metric-label">Raw value</span><i class="ti ti-chart-line metric-icon"></i></div>
      <div class="metric-value"><?= $last['gas_value'] ?> <span style="font-size:13px;color:#aaa;">raw</span></div>
      <div class="metric-tag <?= tagClass($currentStatus) ?>">▲ <?= $currentStatus ?></div>
    </div>
    <div class="metric">
      <div class="metric-top"><span class="metric-label">Gas type</span><i class="ti ti-wind metric-icon"></i></div>
      <div class="metric-value" style="font-size:15px;margin-top:4px;"><?= h($last['gas_type']) ?></div>
    </div>
    <div class="metric">
      <div class="metric-top"><span class="metric-label">Overall status</span><i class="ti ti-shield metric-icon"></i></div>
      <div class="metric-value" style="font-size:15px;margin-top:4px;">
        <span class="badge <?= badgeClass($currentStatus) ?>">
          <i class="ti <?= statusIcon($currentStatus) ?>"></i>
          <?= $currentStatus ?>
        </span>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-head"><span class="card-title">Current reading</span><span class="badge badge-info">Live</span></div>
    <div class="row">
      <span class="row-label"><i class="ti ti-cpu" style="font-size:14px"></i> Sensor</span>
      <span class="row-value"><?= h($last['sensor_name']) ?></span>
    </div>
    <div class="row">
      <span class="row-label"><i class="ti ti-activity" style="font-size:14px"></i> Gas value</span>
      <span class="row-value"><?= $last['gas_value'] ?> raw</span>
    </div>
    <div class="row">
      <span class="row-label"><i class="ti ti-alert-triangle" style="font-size:14px"></i> Status</span>
      <span class="badge <?= badgeClass($currentStatus) ?>">
        <i class="ti <?= statusIcon($currentStatus) ?>"></i>
        <?= $currentStatus ?>
      </span>
    </div>
    <div class="row">
      <span class="row-label"><i class="ti ti-clock" style="font-size:14px"></i> Timestamp</span>
      <span class="row-value"><?= date('d F Y \a\t H:i', strtotime($last['created_at'])) ?></span>
    </div>
  </div>

  <div class="card">
    <div class="card-head"><span class="card-title">Measurements history</span></div>
    <table>
      <thead>
        <tr><th>Timestamp</th><th>Sensor</th><th>Value</th><th>Status</th><th>Level</th></tr>
      </thead>
      <tbody>
        <?php foreach ($history as $row): ?>
        <?php $s = getStatus($row['gas_value']); ?>
        <tr>
          <td style="color:#888;"><?= date('d F Y \a\t H:i', strtotime($row['created_at'])) ?></td>
          <td><?= h($row['sensor_name']) ?></td>
          <td><?= $row['gas_value'] ?> raw</td>
          <td><span class="badge <?= badgeClass($s) ?>"><i class="ti <?= statusIcon($s) ?>"></i><?= $s ?></span></td>
          <td><div class="bar-wrap"><div class="bar <?= barClass($s) ?>" style="width:<?= barWidth($row['gas_value']) ?>%"></div></div></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php else: ?>
    <div class="card"><p style="color:#888;text-align:center;">No data available yet.</p></div>
  <?php endif; ?>

</div>

<footer>Rover Gas Monitoring System &nbsp;·&nbsp; <span style="font-size:10px; color:#bbb;">ISEP · 2026</span></footer>
</body>
</html>