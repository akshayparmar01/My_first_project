<?php
require_once 'config.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$name || !$email || !$password) $errors[] = "Bad input.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Email already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,'user')");
            $stmt->execute([$name,$email,$hash]);
            header('Location: index.php?registered=1');
            exit;
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register - Library</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body class="auth-page">
  <div class="card auth-card">
    <h2>Register</h2>
    <?php if($errors): ?><div class="error"><?=implode('<br>', $errors)?></div><?php endif; ?>
    <form method="post" novalidate>
      <label>Name <input name="name" required></label>
      <label>Email <input type="email" name="email" required></label>
      <label>Password <input type="password" name="password" required></label>
      <button type="submit" class="btn">Register</button>
    </form>
    <p>Already have account? <a href="index.php">Login</a></p>
  </div>
</body>
</html>
