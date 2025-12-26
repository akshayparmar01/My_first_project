<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['plan'])) {
    die("Invalid plan selected.");
}

$plan = strtolower(trim($_GET['plan']));
$user_id = $_SESSION['user_id'];

$valid_plans = ['weekly', 'monthly', 'yearly'];
if (!in_array($plan, $valid_plans)) {
    die("Invalid plan type.");
}

// PLAN RANK
$rank = [
    "weekly" => 1,
    "monthly" => 2,
    "yearly" => 3
];

// Fetch active subscription
$stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE user_id = ? ORDER BY end_date DESC LIMIT 1");
$stmt->execute([$user_id]);
$current = $stmt->fetch();

if ($current && strtotime($current['end_date']) > time()) {

    $current_plan = strtolower($current['plan_type']);

    if ($rank[$current_plan] > $rank[$plan]) {
        die("<h2 style='color:red;text-align:center;margin-top:50px;'>❌ You already have a higher plan active!</h2>");
    }

    if ($rank[$current_plan] == $rank[$plan]) {
        die("<h2 style='color:red;text-align:center;margin-top:50px;'>❌ You already purchased this plan!</h2>");
    }
}

// Calculate dates
$start = date("Y-m-d");

if ($plan == "weekly") {
    $end = date("Y-m-d", strtotime("+7 days"));
} elseif ($plan == "monthly") {
    $end = date("Y-m-d", strtotime("+30 days"));
} else {
    $end = date("Y-m-d", strtotime("+365 days"));
}

// Insert new subscription
$stmt = $pdo->prepare("
    INSERT INTO subscriptions (user_id, plan_type, start_date, end_date, status) 
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([$user_id, $plan, $start, $end, 'active']);

header("Location: subscription.php?success=1");
exit;

?>
