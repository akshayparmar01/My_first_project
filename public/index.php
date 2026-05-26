<?php
require_once 'config.php';
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php'); exit;
}
$msg = '';
if (isset($_GET['registered'])) $msg = 'Registration successful. Please login.';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        if ($user['role'] === 'admin') {
            header('Location: admin_dashboard.php'); exit;
        } else {
            header('Location: dashboard.php'); exit;
        }
    } else {
        $msg = 'Login failed.';
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login - Library</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body class="auth-page">

  <div class="card auth-card">
    <h2>Login</h2>
      <a href="admin_login.php">Admin Login</a>
    <?php if($msg): ?><div class="info"><?=$msg?></div><?php endif; ?>
    <form method="post" novalidate>
      <label>Email <input type="email" name="email" required></label>
      <label>Password
        <div style="position:relative">
          <input type="password" name="password" id="login-password" required maxlength="8" style="padding-right:36px;">
          <span class="toggle-password" onclick="togglePassword('login-password', this)" style="position:absolute;top:50%;right:10px;transform:translateY(-50%);cursor:pointer;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path><circle cx="12" cy="12" r="3"></circle></svg>
          </span>
        </div>
      </label>
      <button type="submit" class="btn">Login</button>
    </form>
    <p>Don't have account? <a href="register.php">Register</a></p>
  </div>
</body>
</html>
