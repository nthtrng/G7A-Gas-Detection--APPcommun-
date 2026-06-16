<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rover Gas Monitor</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, sans-serif; background: #eef4ee; color: #1a1a1a; min-height: 100vh; display: flex; flex-direction: column; }
    .nav { background: #111318; padding: 0 2rem; display: flex; align-items: center; justify-content: space-between; height: 52px; }
    .nav-brand { display: flex; align-items: center; gap: 9px; font-size: 14px; font-weight: 500; color: #fff; text-decoration: none; }
    .nav-brand i { font-size: 17px; color: #e24b4a; }
    .nav-links { display: flex; gap: 16px; align-items: center; }
    .nav-links a { font-size: 12px; color: #888; text-decoration: none; }
    .nav-links a.cta { color: #fff; background: #1a1a2e; padding: 5px 14px; border-radius: 6px; }
    .hero { flex: 1; display: grid; grid-template-columns: 1fr 1fr; align-items: center; gap: 3rem; padding: 4rem; max-width: 1100px; margin: 0 auto; width: 100%; }
    .radar-side { display: flex; justify-content: center; }
    .sweep { animation: sweep 3s linear infinite; }
    @keyframes sweep { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    .blip { animation: blip 3s linear infinite; }
    .blip1 { animation-delay: 0s; } .blip2 { animation-delay: 1s; } .blip3 { animation-delay: 2s; }
    @keyframes blip { 0%,95%,100%{opacity:0} 50%{opacity:1} }
    .text-side { display: flex; flex-direction: column; }
    .eyebrow { font-size: 11px; color: #999; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 6px; }
    .eyebrow span { width: 5px; height: 5px; border-radius: 50%; background: #639922; display: inline-block; }
    h1 { font-size: 48px; font-weight: 500; color: #111; letter-spacing: -0.03em; line-height: 1.15; margin-bottom: 1rem; }
    h1 em { font-style: normal; color: #e24b4a; }
    .sub { font-size: 16px; color: #888; line-height: 1.7; margin-bottom: 2rem; max-width: 360px; }
    .btn-row { display: flex; gap: 8px; margin-bottom: 2rem; }
    .btn { display: inline-flex; align-items: center; gap: 6px; font-size: 14px; font-weight: 500; padding: 12px 24px; border-radius: 8px; text-decoration: none; }
    .btn-dark { background: #111318; color: #fff; }
    .btn-dark:hover { background: #1a1a2e; }
    .btn-light { background: #fff; color: #111; border: 0.5px solid rgba(0,0,0,0.12); }
    .btn-light:hover { background: #f0f0ed; }
    .chips { display: flex; gap: 8px; flex-wrap: wrap; }
    .chip { background: #fff; border: 0.5px solid rgba(0,0,0,0.08); border-radius: 99px; padding: 5px 12px; font-size: 12px; color: #888; display: flex; align-items: center; gap: 5px; }
    .chip i { font-size: 13px; color: #aaa; }
    .section { background: #fff; padding: 2.5rem 4rem; border-top: 0.5px solid rgba(0,0,0,0.07); }
    .section-title { font-size: 11px; font-weight: 500; color: #bbb; text-transform: uppercase; letter-spacing: 0.06em; text-align: center; margin-bottom: 1.5rem; }
    .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; max-width: 700px; margin: 0 auto; }
    .card { background: #eef4ee; border-radius: 10px; padding: 1rem; }
    .card-icon { font-size: 18px; color: #aaa; margin-bottom: 8px; }
    .card-title { font-size: 14px; font-weight: 500; color: #111; margin-bottom: 4px; }
    .card-desc { font-size: 12px; color: #999; line-height: 1.5; }
    footer { text-align: center; padding: 1.25rem; font-size: 12px; color: #aaa; border-top: 0.5px solid rgba(0,0,0,0.08); background: #fff; margin-top: auto; }
  </style>
</head>
<body>

<nav class="nav">
  <a href="index.php" class="nav-brand">
    <i class="ti ti-radioactive"></i>
    Rover Gas Monitor
  </a>
  <div class="nav-links">
    <a href="pages/login.php">Log in</a>
    <a href="pages/register.php" class="cta">Sign up</a>
  </div>
</nav>

<div class="hero">
  <div class="radar-side">
    <svg viewBox="0 0 240 240" width="300" height="300" xmlns="http://www.w3.org/2000/svg">
      <circle cx="120" cy="120" r="118" fill="#0f0f0f" stroke="#1a3a1a" stroke-width="0.5"/>
      <circle cx="120" cy="120" r="88" fill="none" stroke="#1a3a1a" stroke-width="0.5"/>
      <circle cx="120" cy="120" r="59" fill="none" stroke="#1a3a1a" stroke-width="0.5"/>
      <circle cx="120" cy="120" r="30" fill="none" stroke="#1a3a1a" stroke-width="0.5"/>
      <line x1="120" y1="2" x2="120" y2="238" stroke="#1a3a1a" stroke-width="0.5"/>
      <line x1="2" y1="120" x2="238" y2="120" stroke="#1a3a1a" stroke-width="0.5"/>
      <line x1="37" y1="37" x2="203" y2="203" stroke="#1a3a1a" stroke-width="0.5"/>
      <line x1="203" y1="37" x2="37" y2="203" stroke="#1a3a1a" stroke-width="0.5"/>
      <defs>
        <radialGradient id="sweepGrad" cx="0%" cy="50%" r="100%">
          <stop offset="0%" stop-color="#22c55e" stop-opacity="0.5"/>
          <stop offset="100%" stop-color="#22c55e" stop-opacity="0"/>
        </radialGradient>
      </defs>
      <g class="sweep" style="transform-origin:120px 120px">
        <path d="M120 120 L120 2 A118 118 0 0 1 203 37 Z" fill="url(#sweepGrad)"/>
        <line x1="120" y1="120" x2="120" y2="2" stroke="#22c55e" stroke-width="1.5" stroke-opacity="0.9"/>
      </g>
      <circle class="blip blip1" cx="172" cy="74" r="4" fill="#22c55e"/>
      <circle class="blip blip2" cx="80" cy="155" r="3.5" fill="#e24b4a"/>
      <circle class="blip blip3" cx="155" cy="175" r="3" fill="#22c55e"/>
      <circle cx="120" cy="120" r="3.5" fill="#22c55e"/>
    </svg>
  </div>

  <div class="text-side">
    <div class="eyebrow"><span></span> ISEP · G7A · 2026</div>
    <h1>Gas Detection<br><em>Rover</em> System</h1>
    <p class="sub">Real-time air quality monitoring inspired by Chernobyl reconnaissance missions. MQ2 sensor, live dashboard, automatic alerts.</p>
    <div class="btn-row">
      <a href="pages/login.php" class="btn btn-dark"><i class="ti ti-login" style="font-size:13px"></i> Log in</a>
      <a href="pages/register.php" class="btn btn-light">Sign up</a>
    </div>
    <div class="chips">
      <div class="chip"><i class="ti ti-cpu"></i> MQ2 sensor</div>
      <div class="chip"><i class="ti ti-activity"></i> Live monitoring</div>
      <div class="chip"><i class="ti ti-shield"></i> Danger alerts</div>
      <div class="chip"><i class="ti ti-database"></i> MariaDB</div>
    </div>
  </div>
</div>

<div class="section">
  <p class="section-title">How it works</p>
  <div class="cards">
    <div class="card">
      <div class="card-icon"><i class="ti ti-cpu"></i></div>
      <p class="card-title">MQ2 sensor</p>
      <p class="card-desc">Detects smoke, gas and flammable substances via TIVA microcontroller.</p>
    </div>
    <div class="card">
      <div class="card-icon"><i class="ti ti-arrow-right"></i></div>
      <p class="card-title">Serial to database</p>
      <p class="card-desc">Data is read from COM4 and stored automatically in MariaDB.</p>
    </div>
    <div class="card">
      <div class="card-icon"><i class="ti ti-chart-bar"></i></div>
      <p class="card-title">Live dashboard</p>
      <p class="card-desc">Visualize readings, history, and alert levels updated every 5 seconds.</p>
    </div>
  </div>
</div>

<footer>Rover Gas Monitoring System &nbsp;·&nbsp; <span style="font-size:10px; color:#bbb;">ISEP · 2026</span></footer>
</body>
</html>