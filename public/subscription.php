<?php
require 'config.php';

$sub = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE user_id = ? ORDER BY end_date DESC LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    $sub = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Subscription Plans</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, #0e1f47, #15306b, #1e3a8a);
            background-size: 300% 300%;
            animation: gradientShift 6s ease infinite;
            color: white;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            max-width: 1150px;
            margin: 60px auto;
            text-align: center;
        }

        h1 {
            font-size: 42px;
            margin-bottom: 10px;
            font-weight: 700;
            text-shadow: 0 0 10px rgba(255,255,255,0.4);
        }

        .plans {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            flex-wrap: wrap;
            gap: 30px;
        }

        .plan-card {
            width: 310px;
            padding: 30px;
            border-radius: 22px;
            background: rgba(255,255,255,0.08);
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.18);
            transition: 0.4s ease;
            position: relative;
            transform-style: preserve-3d;
        }

        .plan-card:hover {
            transform: translateY(-15px) scale(1.03);
            box-shadow: 0 15px 40px rgba(0,0,0,0.6);
        }

        .plan-card h2 {
            font-size: 26px;
            margin-bottom: 10px;
            color: #fff;
        }

        .price {
            font-size: 38px;
            font-weight: 700;
            margin: 15px 0;
            text-shadow: 0 0 8px rgba(0,0,0,0.6);
        }

        .feature {
            font-size: 15px;
            margin: 8px 0;
            opacity: 0.85;
        }

        .btn {
            display: inline-block;
            margin-top: 18px;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 12px;
            text-decoration: none;
            background: linear-gradient(145deg, #3b82f6, #1e40af);
            color: white;
            font-weight: 600;
            box-shadow: 0 6px 20px rgba(0,0,0,0.4);
            transition: 0.3s ease-in-out;
        }

        .btn:hover {
            background: linear-gradient(145deg, #60a5fa, #1e3a8a);
            transform: translateY(-3px);
        }

        .badge {
            position: absolute;
            top: -12px;
            right: -12px;
            background: #f97316;
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            box-shadow: 0 0 12px rgba(0,0,0,0.5);
        }

        .badge-best {
            background: #22c55e;
        }

        .active-sub {
            background: rgba(34,197,94,0.6);
            padding: 15px;
            border-radius: 12px;
            margin-top: 20px;
            font-size: 16px;
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255,255,255,0.2);
            display: inline-block;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>⚡ Premium Access Plans</h1>

    <?php if ($sub && strtotime($sub['end_date']) > time()): ?>
        <div class="active-sub">
            <b>ACTIVE PLAN:</b> <?= ucfirst($sub['plan_type']) ?>  
            <br>
            <b>Expires:</b> <?= $sub['end_date'] ?>
        </div>
    <?php endif; ?>

    <div class="plans">

        <!-- WEEKLY -->
        <div class="plan-card">
            <span class="badge">NEW</span>
            <h2>Weekly</h2>
            <div class="price">₹29</div>
            <div class="feature">✔ 7-Day Full Access</div>
            <div class="feature">✔ All Premium Features</div>
            <div class="feature">✔ Fast Support</div>
            <a class="btn" href="subscribe_process.php?plan=weekly">Select Weekly</a>
        </div>

        <!-- MONTHLY -->
        <div class="plan-card">
            <h2>Monthly</h2>
            <div class="price">₹99</div>
            <div class="feature">✔ 30-Day Unlimited Access</div>
            <div class="feature">✔ No Restrictions</div>
            <div class="feature">✔ Priority Support</div>
            <a class="btn" href="subscribe_process.php?plan=monthly">Select Monthly</a>
        </div>

        <!-- YEARLY -->
        <div class="plan-card">
            <span class="badge badge-best">BEST VALUE</span>
            <h2>Yearly</h2>
            <div class="price">₹499</div>
            <div class="feature">✔ 1-YEAR Full Access</div>
            <div class="feature">✔ Save ₹689 Total</div>
            <div class="feature">✔ VIP Support</div>
            <a class="btn" href="subscribe_process.php?plan=yearly">Select Yearly</a>
        </div>

    </div>
</div>

</body>
</html>
