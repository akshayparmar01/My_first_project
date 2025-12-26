<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$b = $stmt->fetch();
if (!$b) { echo 'Book not found'; exit; }
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?=htmlspecialchars($b['title'])?> - Library</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body class="app">
  <aside class="sidebar">
    <h1 class="logo">Library</h1>
    <nav>
      <a href="dashboard.php">Dashboard</a>
      <a href="books.php">Books</a>
      <a href="borrow.php">My Borrows</a>
      <a href="subscription.php">Subscription</a>
      <a href="logout.php">Logout</a>
    </nav>
  </aside>
  <main class="main">
    <div class="book-detail">
      <img src="<?=$b['cover_url']?>" alt="">
      <div class="meta">
        <h2><?=htmlspecialchars($b['title'])?></h2>
        <p><strong>Author:</strong> <?=htmlspecialchars($b['author'])?></p>
        <p><strong>Publisher:</strong> <?=htmlspecialchars($b['publisher'])?> (<?=$b['year']?>)</p>
        <p><?=nl2br(htmlspecialchars($b['description']))?></p>
        <p><strong>Copies:</strong> <?=$b['copies']?></p>
        <form method="post" action="borrow.php">
          <input type="hidden" name="book_id" value="<?=$b['id']?>">
          <button class="btn">Borrow</button>
        </form>
      </div>
    </div>
  </main>
</body>
</html>
