<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
// basic stats
$totalBooks = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$totalCopies = $pdo->query("SELECT SUM(copies) FROM books")->fetchColumn();
$borrowed = $pdo->query("SELECT COUNT(*) FROM borrows WHERE status='borrowed'")->fetchColumn();
$returned = $pdo->query("SELECT COUNT(*) FROM borrows WHERE status='returned'")->fetchColumn();
$recentBooks = $pdo->query("SELECT * FROM books ORDER BY created_at DESC LIMIT 6")->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard - Library</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body class="app">
  <aside class="sidebar">
    <h1 class="logo">AP LIBRARY</h1>
    <nav>
      <a href="dashboard.php" class="active">Dashboard</a>
      <a href="books.php">Books</a>
      <a href="borrow.php">My Borrows</a>
      <a href="subscription.php">Subscription</a>
      <a href="logout.php">Logout</a>
    </nav>
  </aside>
  <main class="main">
    <header>
      <h2>Welcome, <?=$_SESSION['user_name']?></h2>
    </header>
    <section class="grid">
      <div class="card stat">
        <h3>Total Titles</h3>
        <p class="big"><?=$totalBooks?></p>
      </div>
      <div class="card stat">
        <h3>Total Copies</h3>
        <p class="big"><?=$totalCopies?></p>
      </div>
      <div class="card stat">
        <h3>Currently Borrowed</h3>
        <p class="big"><?=$borrowed?></p>
      </div>
      <div class="card stat">
        <h3>Returned</h3>
        <p class="big"><?=$returned?></p>
      </div>
    </section>
    <section>
      <h3>Recent additions</h3>
      <div class="book-grid">
        <?php foreach($recentBooks as $b): ?>
          <div class="book-card">
            <img src="<?=$b['cover_url']?>" alt="">
            <h4><?=htmlspecialchars($b['title'])?></h4>
            <p><?=htmlspecialchars($b['author'])?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
</body>
</html>
