<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $borrow_id = intval($_POST['borrow_id'] ?? 0);
    // get borrow row
    $stmt = $pdo->prepare("SELECT * FROM borrows WHERE id = ? AND user_id = ?");
    $stmt->execute([$borrow_id, $_SESSION['user_id']]);
    $b = $stmt->fetch();
    if ($b && $b['status'] === 'borrowed') {
        // set status returned and return_date, increment book copies
        $pdo->prepare("UPDATE borrows SET status='returned', return_date=? WHERE id = ?")->execute([date('Y-m-d'), $borrow_id]);
        $pdo->prepare("UPDATE books SET copies = copies + 1 WHERE id = ?")->execute([$b['book_id']]);
        header('Location: borrow.php?returned=1');
        exit;
    }
}
header('Location: borrow.php');
exit;
?>