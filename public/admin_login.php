<?php
session_start();

// Admin credentials
$admin_email = "abhip8426@gmail.com";
$admin_pass  = "0131";

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $pass  = $_POST['password'];

    if ($email === $admin_email && $pass === $admin_pass) {

        $_SESSION['admin_logged_in'] = true;

        header("Location: admin_dashboard.php");
        exit;

    } else {
        $message = "Invalid credentials! You are not admin.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>

    <style>

        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

        body {
            margin:0;
            padding:0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .admin-box {
            width: 380px;
            padding: 35px;
            background: rgba(255,255,255,0.16);
            backdrop-filter: blur(12px);
            border-radius: 18px;
            box-shadow:0 20px 40px rgba(0,0,0,0.18);
            color:white;
            animation: fadeIn 0.7s ease;
        }

        @keyframes fadeIn {
            from { opacity:0; transform: translateY(20px); }
            to { opacity:1; transform: translateY(0px); }
        }

        h2 {
            text-align:center;
            margin-bottom:25px;
            font-weight:600;
        }

        label {
            font-size:14px;
            display:block;
            margin-bottom:6px;
            opacity:0.85;
        }

        input {
            width:100%;
            padding:12px 14px;
            border-radius:10px;
            border: none;
            outline:none;
            margin-bottom:18px;
            background: rgba(255,255,255,0.25);
            color:white;
            font-size:14px;
        }

        input::placeholder {
            color: rgba(255,255,255,0.7);
        }

        .btn {
            width:100%;
            padding:12px;
            background:#00c6ff;
            background: linear-gradient(135deg,#00c6ff,#0072ff);
            border:none;
            color:white;
            font-weight:600;
            font-size:16px;
            border-radius:10px;
            cursor:pointer;
            transition:0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow:0 5px 18px rgba(0,0,0,0.25);
        }

        .error {
            margin-top:15px;
            padding:12px;
            background: rgba(255,0,0,0.15);
            color:#ffb9b9;
            border-left:4px solid #ff4c4c;
            border-radius:8px;
            text-align:center;
        }

    </style>
</head>

<body>

<div class="admin-box">

    <h2>🔐 Admin Login</h2>

    <form method="POST">

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter admin email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter admin password" required>

        <button type="submit" class="btn">Login</button>
    </form>

    <?php if ($message): ?>
        <div class="error"><?= $message ?></div>
    <?php endif; ?>

</div>

</body>
</html>
