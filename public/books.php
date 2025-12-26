<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
$books = $pdo->query("SELECT * FROM books ORDER BY title")->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Books - Library</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body class="app">
  <aside class="sidebar">
    <h1 class="logo">AP LIBRARY</h1>
    <nav>
      <a href="dashboard.php">Dashboard</a>
      <a href="books.php" class="active">Books</a>
      <a href="borrow.php">My Borrows</a>
      <a href="subscription.php">Subscription</a>
      <a href="logout.php">Logout</a>
    </nav>
  </aside>
  <main class="main">
    <header><h2>Books</h2></header>
    <div class="book-grid large">
      <?php foreach($books as $b): ?>
        <div class="book-card">
          <a href="book_details.php?id=<?=$b['id']?>"><img src="<?=$b['cover_url']?>" alt=""></a>
          <h4><?=htmlspecialchars($b['title'])?></h4>
          <p><?=htmlspecialchars($b['author'])?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
</body>
</html>
