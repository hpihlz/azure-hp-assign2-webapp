<?php require __DIR__.'/inc/db.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>All issues</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;margin:0;padding:2rem;background:#0f172a;color:#e2e8f0}
    .wrap{max-width:900px;margin:0 auto}
    a.btn{padding:.7rem 1rem;border-radius:10px;border:1px solid #334155;background:#1e293b;color:#e2e8f0;text-decoration:none}
    .item{background:#111827;border:1px solid #1f2937;border-radius:16px;padding:1rem;margin-top:1rem}
    .meta{opacity:.8;font-size:.9rem;margin-bottom:.5rem}
    img{max-width:100%}
  </style>
</head>
<body>
  <div class="wrap">
    <a class="btn" href="index.html">← Back</a>
    <h2>All issues</h2>
    <?php
      $stmt = $pdo->query("SELECT id, created_at, name, email, description, 
        CASE WHEN screenshot IS NULL THEN NULL ELSE screenshot_mime END AS screenshot_mime
        FROM issues ORDER BY id DESC");
      while ($row = $stmt->fetch()) {
        echo '<div class="item">';
        echo '<div class="meta">#'.$row['id'].' &middot; '.htmlspecialchars($row['created_at']).' &middot; '.htmlspecialchars($row['name']).' &lt;'.htmlspecialchars($row['email']).'&gt;</div>';
        echo '<div>'.nl2br(htmlspecialchars($row['description'])).'</div>';
        if ($row['screenshot_mime']) {
          echo '<div style="margin-top:.5rem"><img src="api/submit_issue.php?id='.$row['id'].'&download=inline" alt="screenshot"></div>';
        }
        echo '</div>';
      }
    ?>
  </div>
</body>
</html>
