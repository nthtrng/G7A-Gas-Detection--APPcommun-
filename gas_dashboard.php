<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rover Gas Monitor</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: Arial, sans-serif;
      background: #f0f0ed;
      color: #1a1a1a;
      min-height: 100vh;
    }

    /* Navbar */
    .navbar {
      background: #1a1a2e;
      color: #fff;
      padding: 0 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 56px;
    }
    .nav-brand { display: flex; align-items: center; gap: 10px; font-size: 15px; font-weight: 500; color: #fff; text-decoration: none; }
    .nav-brand i { font-size: 20px; color: #e24b4a; }
    .nav-links { display: flex; gap: 24px; }
    .nav-links a { color: #aaa; font-size: 13px; text-decoration: none; }
    .nav-links a.active { color: #fff; font-weight: 500; }
    .nav-status { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #aaa; }
    .dot { width: 7px; height: 7px; border-radius: 50%; background: #639922; display: inline-block; }

    /* Layout */
    .page { max-width: 900px; margin: 0 auto; padding: 2rem 1.5rem; }

    .page-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.5rem;
    }
    .page-title { font-size: 18px; font-weight: 500; }
    .page-sub { font-size: 13px; color: #888; margin-top: 2px; }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 13px;
      font-weight: 500;
      padding: 7px 14px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      text-decoration: none;
    }
    .btn-primary { background: #1a1a2e; color: #fff; }

    /* Metric cards */
    .metrics {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      margin-bottom: 1.5rem;
    }
    .metric {
      background: #fff;
      border: 0.5px solid rgba(0,0,0,0.1);
      border-radius: 10px;
      padding: 1rem;
    }
    .metric-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
    .metric-label { font-size: 12px; color: #888; }
    .metric-icon { font-size: 16px; color: #aaa; }
    .metric-value { font-size: 22px; font-weight: 500; }
    .metric-tag { font-size: 11px; margin-top: 4px; }
    .tag-safe   { color: #3b6d11; }
    .tag-warn   { color: #854f0b; }
    .tag-danger { color: #a32d2d; }

    /* 2-col grid */
    .grid2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    /* Cards */
    .card {
      background: #fff;
      border: 0.5px solid rgba(0,0,0,0.1);
      border-radius: 12px;
      padding: 1.25rem;
      margin-bottom: 1rem;
    }
    .card-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1rem;
    }
    .card-title {
      font-size: 12px;
      font-weight: 500;
      color: #888;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    /* Rows */
    .row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 9px 0;
      border-bottom: 0.5px solid rgba(0,0,0,0.07);
      font-size: 13px;
    }
    .row:last-child { border-bottom: none; }
    .row-label { color: #888; display: flex; align-items: center; gap: 7px; }
    .row-value { font-weight: 500; color: #1a1a1a; }

    /* Badges */
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 12px;
      font-weight: 500;
      padding: 3px 9px;
      border-radius: 6px;
    }
    .badge-safe    { background: #eaf3de; color: #3b6d11; }
    .badge-warning { background: #faeeda; color: #854f0b; }
    .badge-danger  { background: #fcebeb; color: #a32d2d; }
    .badge-info    { background: #e6f1fb; color: #0c447c; }

    /* Zones */
    .zone-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 9px 0;
      border-bottom: 0.5px solid rgba(0,0,0,0.07);
      font-size: 13px;
    }
    .zone-item:last-child { border-bottom: none; }
    .zone-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 8px; }

    /* Table */
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    th {
      text-align: left;
      font-size: 11px;
      font-weight: 500;
      color: #aaa;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      padding: 0 0 10px;
      border-bottom: 0.5px solid rgba(0,0,0,0.08);
    }
    td {
      padding: 10px 0;
      border-bottom: 0.5px solid rgba(0,0,0,0.06);
      vertical-align: middle;
    }
    tr:last-child td { border-bottom: none; }

    /* Progress bars */
    .bar-wrap { width: 80px; height: 5px; background: #eee; border-radius: 99px; overflow: hidden; }
    .bar { height: 100%; border-radius: 99px; }
    .bar-safe    { background: #639922; }
    .bar-warning { background: #ba7517; }
    .bar-danger  { background: #e24b4a; }

    /* Footer */
    footer {
      text-align: center;
      padding: 1.5rem;
      font-size: 12px;
      color: #aaa;
      border-top: 0.5px solid rgba(0,0,0,0.08);
      background: #fff;
      margin-top: 1rem;
    }
  </style>
</head>
<body>

<?php

/* ──────────────────────────────────────────
   DONNÉES — à remplacer par des requêtes BDD
   ────────────────────────────────────────── */

$lastMeasure = [
    "sensor_name" => "MQ-135",
    "value_raw"   => 420,
    "status"      => "WARNING",
    "zone"        => "Reactor Corridor",
    "created_at"  => "2026-06-09 14:30:00",
];

$zones = [
    ["name" => "Control Room",    "sector" => "Zone A", "status" => "SAFE"],
    ["name" => "Reactor Corridor","sector" => "Zone B", "status" => "WARNING"],
    ["name" => "Engine Room",     "sector" => "Zone C", "status" => "DANGER"],
    ["name" => "Crew Quarters",   "sector" => "Zone D", "status" => "SAFE"],
];

$history = [
    ["date" => "2026-06-09 14:30", "sensor" => "MQ-135", "zone" => "Reactor Corridor", "value" => 420, "status" => "WARNING"],
    ["date" => "2026-06-09 14:00", "sensor" => "MQ-135", "zone" => "Reactor Corridor", "value" => 120, "status" => "SAFE"],
    ["date" => "2026-06-09 13:30", "sensor" => "MQ-135", "zone" => "Engine Room",       "value" => 680, "status" => "DANGER"],
    ["date" => "2026-06-09 13:00", "sensor" => "MQ-135", "zone" => "Control Room",      "value" => 85,  "status" => "SAFE"],
];

/* ──────────────────────────────────────────
   FONCTIONS UTILITAIRES
   ────────────────────────────────────────── */

function badgeClass(string $status): string {
    return match($status) {
        "SAFE"    => "badge-safe",
        "WARNING" => "badge-warning",
        "DANGER"  => "badge-danger",
        default   => "badge-info",
    };
}

function barClass(string $status): string {
    return match($status) {
        "SAFE"    => "bar-safe",
        "WARNING" => "bar-warning",
        "DANGER"  => "bar-danger",
        default   => "",
    };
}

function statusIcon(string $status): string {
    return match($status) {
        "SAFE"    => "ti-check",
        "WARNING" => "ti-alert-triangle",
        "DANGER"  => "ti-flame",
        default   => "ti-help",
    };
}

function zoneDotColor(string $status): string {
    return match($status) {
        "SAFE"    => "#639922",
        "WARNING" => "#ba7517",
        "DANGER"  => "#e24b4a",
        default   => "#aaa",
    };
}

function tagClass(string $status): string {
    return match($status) {
        "SAFE"    => "tag-safe",
        "WARNING" => "tag-warn",
        "DANGER"  => "tag-danger",
        default   => "",
    };
}

function barWidth(int $value, int $max = 700): int {
    return min(100, (int) round($value / $max * 100));
}

function h(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

?>

<!-- ── NAVBAR ── -->
<nav class="navbar">
  <a href="#" class="nav-brand">
    <i class="ti ti-radioactive"></i>
    Rover Gas Monitor
  </a>
  <div class="nav-links">
    <a href="#" class="active">Dashboard</a>
    <a href="#">Capteurs</a>
    <a href="#">Historique</a>
    <a href="#">Alertes</a>
    <a href="#">Paramètres</a>
  </div>
  <div class="nav-status">
    <span class="dot"></span> Système en ligne
  </div>
</nav>

<!-- ── PAGE ── -->
<div class="page">

  <!-- En-tête -->
  <div class="page-header">
    <div>
      <p class="page-title">Dashboard — Air Quality Monitoring</p>
      <p class="page-sub">Dernière mise à jour : <?= h($lastMeasure["created_at"]) ?></p>
    </div>
    <a href="" class="btn btn-primary">
      <i class="ti ti-refresh" style="font-size:14px"></i> Actualiser
    </a>
  </div>

  <!-- Metric cards -->
  <div class="metrics">
    <div class="metric">
      <div class="metric-top">
        <span class="metric-label">Capteur actif</span>
        <i class="ti ti-cpu metric-icon"></i>
      </div>
      <div class="metric-value"><?= h($lastMeasure["sensor_name"]) ?></div>
      <div class="metric-tag tag-safe">● Connecté</div>
    </div>

    <div class="metric">
      <div class="metric-top">
        <span class="metric-label">Valeur brute</span>
        <i class="ti ti-chart-line metric-icon"></i>
      </div>
      <div class="metric-value">
        <?= $lastMeasure["value_raw"] ?>
        <span style="font-size:13px;color:#aaa;">raw</span>
      </div>
      <div class="metric-tag <?= tagClass($lastMeasure["status"]) ?>">
        ▲ Seuil <?= h($lastMeasure["status"]) ?>
      </div>
    </div>

    <div class="metric">
      <div class="metric-top">
        <span class="metric-label">Zone surveillée</span>
        <i class="ti ti-map-pin metric-icon"></i>
      </div>
      <div class="metric-value" style="font-size:15px;margin-top:4px;">
        <?= h($lastMeasure["zone"]) ?>
      </div>
      <div class="metric-tag" style="color:#888;">Secteur B</div>
    </div>

    <div class="metric">
      <div class="metric-top">
        <span class="metric-label">Statut global</span>
        <i class="ti ti-shield metric-icon"></i>
      </div>
      <div class="metric-value" style="font-size:15px;margin-top:4px;">
        <span class="badge <?= badgeClass($lastMeasure["status"]) ?>">
          <i class="ti <?= statusIcon($lastMeasure["status"]) ?>"></i>
          <?= h($lastMeasure["status"]) ?>
        </span>
      </div>
    </div>
  </div>

  <!-- Grille 2 colonnes -->
  <div class="grid2">

    <!-- Mesure actuelle -->
    <div class="card">
      <div class="card-head">
        <span class="card-title">Mesure actuelle</span>
        <span class="badge badge-info">Live</span>
      </div>
      <div class="row">
        <span class="row-label"><i class="ti ti-cpu" style="font-size:14px"></i> Capteur</span>
        <span class="row-value"><?= h($lastMeasure["sensor_name"]) ?></span>
      </div>
      <div class="row">
        <span class="row-label"><i class="ti ti-activity" style="font-size:14px"></i> Valeur</span>
        <span class="row-value"><?= $lastMeasure["value_raw"] ?> raw</span>
      </div>
      <div class="row">
        <span class="row-label"><i class="ti ti-alert-triangle" style="font-size:14px"></i> Statut</span>
        <span class="badge <?= badgeClass($lastMeasure["status"]) ?>">
          <i class="ti <?= statusIcon($lastMeasure["status"]) ?>"></i>
          <?= h($lastMeasure["status"]) ?>
        </span>
      </div>
      <div class="row">
        <span class="row-label"><i class="ti ti-map-pin" style="font-size:14px"></i> Zone</span>
        <span class="row-value"><?= h($lastMeasure["zone"]) ?></span>
      </div>
      <div class="row">
        <span class="row-label"><i class="ti ti-clock" style="font-size:14px"></i> Date</span>
        <span class="row-value"><?= h($lastMeasure["created_at"]) ?></span>
      </div>
    </div>

    <!-- Zones actives -->
    <div class="card">
      <div class="card-head">
        <span class="card-title">Zones actives</span>
      </div>
      <?php foreach ($zones as $zone): ?>
      <div class="zone-item">
        <span>
          <span class="zone-dot" style="background:<?= zoneDotColor($zone["status"]) ?>;"></span>
          <?= h($zone["sector"]) ?> - <?= h($zone["name"]) ?>
        </span>
        <span class="badge <?= badgeClass($zone["status"]) ?>">
          <i class="ti <?= statusIcon($zone["status"]) ?>"></i>
          <?= h($zone["status"]) ?>
        </span>
      </div>
      <?php endforeach; ?>
    </div>

  </div>

  <!-- Historique -->
  <div class="card">
    <div class="card-head">
      <span class="card-title">Historique des mesures</span>
    </div>
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Capteur</th>
          <th>Zone</th>
          <th>Valeur</th>
          <th>Statut</th>
          <th>Niveau</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($history as $entry): ?>
        <tr>
          <td style="color:#888;"><?= h($entry["date"]) ?></td>
          <td><?= h($entry["sensor"]) ?></td>
          <td><?= h($entry["zone"]) ?></td>
          <td><?= $entry["value"] ?> raw</td>
          <td>
            <span class="badge <?= badgeClass($entry["status"]) ?>">
              <i class="ti <?= statusIcon($entry["status"]) ?>"></i>
              <?= h($entry["status"]) ?>
            </span>
          </td>
          <td>
            <div class="bar-wrap">
              <div class="bar <?= barClass($entry["status"]) ?>"
                   style="width:<?= barWidth($entry["value"]) ?>%">
              </div>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div>

<!-- ── FOOTER ── -->
<footer>
  Rover Gas Monitoring System &nbsp;·&nbsp; ISEP Project &nbsp;·&nbsp; 2026
</footer>

</body>
</html>
