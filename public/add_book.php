<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: index.php'); exit; }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');
    $publisher = trim($_POST['publisher'] ?? '');
    $year = intval($_POST['year'] ?? 0);
    $copies = max(1,intval($_POST['copies'] ?? 1));
    $cover = trim($_POST['cover_url'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    if (!$title || !$author) $errors[] = 'Title and author required.';
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO books (title,author,isbn,publisher,year,copies,cover_url,description) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$title,$author,$isbn,$publisher,$year,$copies,$cover,$desc]);
        header('Location: books.php?added=1'); exit;
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add Book - Library</title>
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
      <a href="add_book.php" class="active">Add Book</a>
      <a href="logout.php">Logout</a>
    </nav>
  </aside>
  <main class="main">
    <header><h2>Add Book</h2></header>
    <?php if($errors): ?><div class="error"><?=implode('<br>',$errors)?></div><?php endif; ?>
    <form method="post" class="form">
      <label>Title <input name="title" required></label>
      <label>Author <input name="author" required></label>
      <label>ISBN <input name="isbn"></label>
      <label>Publisher <input name="publisher"></label>
      <label>Year <input name="year" type="number"></label>
      <label>Copies <input name="copies" type="number" value="1"></label>
      <label>Cover URL <input name="cover_url"></label>
      <label>Description <textarea name="description"></textarea></label>
      <button class="btn" type="submit">Add Book</button>
    </form>
  </main>
</body>
</html>
