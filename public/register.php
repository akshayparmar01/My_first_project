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
      <label>Password
        <div style="position:relative">
          <input type="password" name="password" id="register-password" required maxlength="8" style="padding-right:36px;">
          <span class="toggle-password" onclick="togglePassword('register-password', this)" style="position:absolute;top:50%;right:10px;transform:translateY(-50%);cursor:pointer;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path><circle cx="12" cy="12" r="3"></circle></svg>
          </span>
        </div>
      </label>
      <button type="submit" class="btn">Register</button>
    </form>
    <p>Already have account? <a href="index.php">Login</a></p>
  </div>
</body>
</html>
