<?php
// admin_dashboard.php
// NOTE: config.php must start session and create $pdo
require '../public/config.php';

// Only admin access
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

/* ---------------------------------------------------
   FETCH BORROWS: latest subscription per user (subquery)
---------------------------------------------------- */
$borrows = $pdo->query("
    SELECT b.id, b.borrow_date, b.return_date,
           u.name AS user_name, u.email,
           bk.title AS book_title, bk.cover_url,
           (
               SELECT s.plan_type 
               FROM subscriptions s 
               WHERE s.user_id = u.id 
               ORDER BY s.id DESC LIMIT 1
           ) AS plan_type,
           (
               SELECT s.end_date 
               FROM subscriptions s 
               WHERE s.user_id = u.id 
               ORDER BY s.id DESC LIMIT 1
           ) AS plan_expiry
    FROM borrows b
    JOIN users u ON b.user_id = u.id
    JOIN books bk ON b.book_id = bk.id
    GROUP BY b.id
    ORDER BY b.borrow_date DESC
")->fetchAll(PDO::FETCH_ASSOC);

/* ---------------------------
   IMAGE FALLBACK FUNCTION
---------------------------- */
function getBookImage($file)
{
    // Try uploads (user uploaded covers)
    $uploads = __DIR__ . '/../public/uploads/' . $file;
    if ($file && file_exists($uploads) && is_file($uploads)) {
        return '../public/uploads/' . rawurlencode($file);
    }

    // Try images folder
    $images = __DIR__ . '/../public/images/' . $file;
    if ($file && file_exists($images) && is_file($images)) {
        return '../public/images/' . rawurlencode($file);
    }

    // If URL (cover_url stored as remote link), return it directly
    if ($file && (strpos($file, 'http://') === 0 || strpos($file, 'https://') === 0)) {
        return $file;
    }

    // Default fallback (make sure this file exists)
    return '../public/default_book.png';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Admin Dashboard — Library</title>

<style>
/* ---------- Modern Admin CSS (compact & responsive) ---------- */
:root{
  --bg: #eef6ff;
  --card: #ffffff;
  --accent: #1A73E8;
  --muted: #6b7280;
  --glass: rgba(255,255,255,0.65);
  --success: #16a34a;
  --danger: #ef4444;
  --shadow: 0 12px 30px rgba(16,24,40,0.08);
}

*{box-sizing:border-box}
body{
  margin:0;
  font-family: "Inter", "Poppins", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
  background: linear-gradient(180deg, #f6fbff 0%, #eef6ff 100%);
  color:#111827;
  -webkit-font-smoothing:antialiased;
  -moz-osx-font-smoothing:grayscale;
}

/* top bar */
.top-bar{
  max-width:1200px;margin:22px auto;padding:18px 20px;border-radius:12px;
  background:var(--glass);backdrop-filter: blur(6px);box-shadow:var(--shadow);
  display:flex;align-items:center;justify-content:space-between;
}
.top-bar h2{margin:0;font-size:18px;display:flex;gap:10px;align-items:center}
.logout-btn{
  background:var(--danger);color:white;padding:10px 14px;border-radius:10px;text-decoration:none;font-weight:600;
  transition:transform .14s ease, background .14s ease;
}
.logout-btn:hover{transform:translateY(-3px);background:#d93636}

/* page wrapper */
.container{max-width:1200px;margin:12px auto;padding:0 18px}

/* heading */
.page-title{ text-align:center;margin:18px 0 12px;font-size:22px;font-weight:700;color:#0f172a }

/* table card */
.table-card{
  background:var(--card);border-radius:12px;overflow:hidden;box-shadow:var(--shadow);
  margin-top:8px;
}

/* responsive table */
table{width:100%;border-collapse:collapse}
thead th{
  background: linear-gradient(90deg,var(--accent),#1565c0);
  color:#fff;padding:14px 12px;text-align:left;font-size:13px;font-weight:700;
}
tbody td{padding:12px;border-bottom:1px solid #f3f6fb;font-size:14px;color:#0f172a}
tbody tr:hover{background:#fbfdff}

.book-cell{display:flex;align-items:center;gap:12px}
.book-thumb{
  width:56px;height:78px;border-radius:8px;object-fit:cover;border:1px solid #e6eefc;background:#f8fbff;
  box-shadow:0 6px 14px rgba(16,24,40,0.04)
}

/* small label */
.small{display:inline-block;font-size:12px;color:var(--muted)}

/* status */
.status-active{color:var(--success);font-weight:700}
.status-expired{color:var(--danger);font-weight:700}

/* mobile stacking */
@media (max-width:920px){
  thead{display:none}
  table, tbody, tr, td{display:block;width:100%}
  tr{margin-bottom:12px;border-radius:10px;background:var(--card);box-shadow:0 8px 20px rgba(16,24,40,0.04);overflow:hidden}
  td{padding:10px 14px;border-bottom:none}
  td:before{content:attr(data-label);display:block;font-size:12px;color:var(--muted);margin-bottom:6px}
  .book-cell{margin-bottom:6px}
}
</style>
</head>
<body>

<div class="top-bar">
  <h2>📊 Admin Dashboard</h2>
  <a class="logout-btn" href="logout.php">Logout</a>
</div>

<div class="container">
  <div class="page-title">Borrowed Books & User Plans</div>

  <div class="table-card">
    <table role="table" aria-label="Borrowed books">
      <thead>
        <tr>
          <th>User</th>
          <th>Email</th>
          <th>Book</th>
          <th>Borrow Date</th>
          <th>Return Date</th>
          <th>Subscription</th>
          <th>Expiry</th>
        </tr>
      </thead>
      <tbody>
      <?php if (count($borrows) === 0): ?>
        <tr><td colspan="7" style="padding:24px;text-align:center;color:var(--muted)">No borrow records found.</td></tr>
      <?php endif; ?>

      <?php foreach ($borrows as $row): 
          // prepare safe display values
          $userName = htmlspecialchars($row['user_name'] ?? 'Unknown');
          $email = htmlspecialchars($row['email'] ?? '');
          $title = htmlspecialchars($row['book_title'] ?? 'Unknown Title');
          $borrowDate = htmlspecialchars($row['borrow_date'] ?? '-');
          $returnDate = htmlspecialchars($row['return_date'] ?? '-');
          $plan = $row['plan_type'] ? htmlspecialchars($row['plan_type']) : null;
          $expiry = $row['plan_expiry'] ?? null;
          $imgPath = getBookImage($row['cover_url'] ?? '');
      ?>
        <tr>
          <td data-label="User"><?= $userName ?></td>
          <td data-label="Email"><span class="small"><?= $email ?></span></td>
          <td data-label="Book">
            <div class="book-cell">
              <img class="book-thumb" src="<?= $imgPath ?>" alt="<?= $title ?>" onerror="this.onerror=null;this.src='../public/default_book.png'">
              <div>
                <div style="font-weight:700;color:#0f172a"><?= $title ?></div>
                <div class="small">ID: <?= (int)$row['id'] ?></div>
              </div>
            </div>
          </td>
          <td data-label="Borrow Date"><?= $borrowDate ?></td>
          <td data-label="Return Date"><?= $returnDate ?></td>
          <td data-label="Subscription"><?= $plan ? ucfirst($plan) : '<span class="small">No Plan</span>' ?></td>
          <td data-label="Expiry">
            <?php if ($expiry): ?>
                <?php if (strtotime($expiry) > time()): ?>
                    <span class="status-active"><?= htmlspecialchars($expiry) ?></span>
                <?php else: ?>
                    <span class="status-expired"><?= htmlspecialchars($expiry) ?></span>
                <?php endif; ?>
            <?php else: ?>
                <span class="small">No subscription</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
