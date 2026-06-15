<?php
session_start();
require_once '../dbconnect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users_g7a WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "This email is already in use.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users_g7a (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hash]);
            $success = "Account created! You can now log in.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign up</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, sans-serif; background: #eef4ee; color: #1a1a1a; min-height: 100vh; display: flex; flex-direction: column; }
    .navbar { background: #1a1a2e; color: #fff; padding: 0 2rem; display: flex; align-items: center; height: 56px; }
    .nav-brand { display: flex; align-items: center; gap: 10px; font-size: 15px; font-weight: 500; color: #fff; text-decoration: none; }
    .nav-brand i { font-size: 20px; color: #e24b4a; }
    .wrap { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem; }
    .card { background: #fff; border: 0.5px solid rgba(0,0,0,0.1); border-radius: 12px; padding: 2rem; width: 100%; max-width: 380px; }
    .card-title { font-size: 17px; font-weight: 500; margin-bottom: 4px; }
    .card-sub { font-size: 13px; color: #999; margin-bottom: 1.5rem; }
    .field { margin-bottom: 1rem; }
    .field label { display: block; font-size: 12px; color: #888; margin-bottom: 6px; }
    .field input { width: 100%; padding: 9px 12px; border: 0.5px solid rgba(0,0,0,0.15); border-radius: 8px; font-size: 13px; outline: none; background: #fafafa; }
    .field input:focus { border-color: #1a1a2e; background: #fff; }
    .btn { width: 100%; padding: 10px; background: #1a1a2e; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; margin-top: 0.5rem; }
    .btn:hover { background: #2a2a4e; }
    .error { font-size: 12px; color: #a32d2d; background: #fcebeb; border-radius: 6px; padding: 8px 12px; margin-bottom: 1rem; }
    .success { font-size: 12px; color: #3b6d11; background: #eaf3de; border-radius: 6px; padding: 8px 12px; margin-bottom: 1rem; }
    .link { text-align: center; margin-top: 1.25rem; font-size: 12px; color: #999; }
    .link a { color: #1a1a2e; text-decoration: none; font-weight: 500; }
    footer { text-align: center; padding: 1.5rem; font-size: 12px; color: #aaa; border-top: 0.5px solid rgba(0,0,0,0.08); background: #fff; }
  </style>
</head>
<body>

<nav class="navbar">
  <a href="#" class="nav-brand">
    <i class="ti ti-radioactive"></i>
    Rover Gas Monitor
  </a>
</nav>

<div class="wrap">
  <div class="card">
    <p class="card-title">Create an account</p>
    <p class="card-sub">Access the dashboard</p>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="success"><?= htmlspecialchars($success) ?> <a href="login.php" style="color:#3b6d11; font-weight:500;">Log in</a></div>
    <?php endif; ?>

    <form method="POST">
      <div class="field">
        <label>Username</label>
        <input type="text" name="username" placeholder="e.g. rover_admin" required>
      </div>
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" placeholder="your@email.com" required>
      </div>
      <div class="field">
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn">Sign up</button>
    </form>

    <p class="link">Already have an account? <a href="login.php">Log in</a></p>
  </div>
</div>

<footer>Rover Gas Monitoring System &nbsp;·&nbsp; <span style="font-size:10px; color:#bbb;">ISEP · 2026</span></footer>
</body>
</html>