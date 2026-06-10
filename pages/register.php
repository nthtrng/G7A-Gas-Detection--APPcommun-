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
        $error = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users_g7a WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Cet email est déjà utilisé.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users_g7a (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hash]);
            $success = "Compte créé ! Tu peux te connecter.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <h1>Créer un compte</h1>
    <?php if ($error): ?><p style="color:red"><?= $error ?></p><?php endif; ?>
    <?php if ($success): ?><p style="color:green"><?= $success ?></p><?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br><br>
        <button type="submit">S'inscrire</button>
    </form>
    <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
</body>
</html>