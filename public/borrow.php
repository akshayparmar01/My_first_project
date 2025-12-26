<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
$uid = $_SESSION['user_id'];
$err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = intval($_POST['book_id'] ?? 0);
    $stmt = $pdo->prepare("SELECT copies FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
    $b = $stmt->fetch();
    if ($b && $b['copies'] > 0) {
        // check subscription (admins bypass)
        $stmt2 = $pdo->prepare("SELECT role FROM users WHERE id = ?");
        $stmt2->execute([$uid]);
        $userRow = $stmt2->fetch();
        if (!($userRow && $userRow['role'] === 'admin')) {
            $stmt3 = $pdo->prepare("SELECT * FROM subscriptions WHERE user_id = ? AND status='active' AND end_date >= ?");
            $stmt3->execute([$uid, date('Y-m-d')]);
            $sub = $stmt3->fetch();
            if (!$sub) {
                $err = 'You need an active subscription to borrow books. Please purchase a plan.';
            }
        }
        if (empty($err)) {
            $borrow_date = date('Y-m-d');
            $due_date = date('Y-m-d', strtotime('+7 days'));
            $stmt = $pdo->prepare("INSERT INTO borrows (user_id,book_id,borrow_date,due_date) VALUES (?,?,?,?)");
            $stmt->execute([$uid,$book_id,$borrow_date,$due_date]);
            $stmt = $pdo->prepare("UPDATE books SET copies = copies - 1 WHERE id = ?");
            $stmt->execute([$book_id]);
            header('Location: borrow.php?ok=1'); exit;
        }
    } else {
        $err = 'No copies available.';
    }
}
// fetch user's borrows (both borrowed and returned)
$stmt = $pdo->prepare("SELECT br.*, bk.title, bk.cover_url FROM borrows br JOIN books bk ON br.book_id = bk.id WHERE br.user_id = ? ORDER BY br.created_at DESC");
$stmt->execute([$uid]);
$rows = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>My Borrows - Library</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body class="app">
  <aside class="sidebar">
    <h1 class="logo">AP LIBRARY</h1>
    <nav>
      <a href="dashboard.php">Dashboard</a>
      <a href="books.php">Books</a>
      <a href="borrow.php" class="active">My Borrows</a>
      <a href="subscription.php">Subscription</a>
      <a href="logout.php">Logout</a>
    </nav>
  </aside>
  <main class="main">
    <header><h2>My Borrows</h2></header>
    <?php if($err): ?><div class="error"><?=$err?></div><?php endif; ?>
    <?php if(isset($_GET['ok'])): ?><div class="info">Book borrowed.</div><?php endif; ?>
    <?php if(isset($_GET['returned'])): ?><div class="info">Book returned successfully.</div><?php endif; ?>
    <div class="borrow-list">
      <?php foreach($rows as $r): ?>
        <div class="borrow-item">
          <img src="<?=$r['cover_url']?>" alt="">
          <div>
            <h4><?=htmlspecialchars($r['title'])?></h4>
            <p>Borrowed: <?=$r['borrow_date']?> — Due: <?=$r['due_date']?></p>
            <p>Status: <strong><?=htmlspecialchars($r['status'])?></strong><?php if($r['status']=='returned' && $r['return_date']): ?> — Returned: <?=$r['return_date']?><?php endif; ?></p>
            <?php if($r['status']=='borrowed'): ?>
              <form method="post" action="return_book.php" onsubmit="return confirm('Return this book?');">
                <input type="hidden" name="borrow_id" value="<?=$r['id']?>">
                <button class="btn btn-return" type="submit">Return</button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
</body>
</html>
