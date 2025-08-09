<?php
require 'db.php';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$stmt = $conn->prepare("SELECT a.*, c.category_name FROM articles a JOIN categories c ON a.category_id=c.category_id WHERE a.title LIKE CONCAT('%',?,'%') OR a.content LIKE CONCAT('%',?,'%') ORDER BY published_date DESC");
$stmt->bind_param("ss",$q,$q);
$stmt->execute();
$res = $stmt->get_result();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Search results</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="styles.css">
</head>
<body>
<header><a href="index.php">Home</a></header>
<main>
  <h1>Search results for "<?php echo htmlspecialchars($q); ?>"</h1>
  <div class="grid">
    <?php if($res && $res->num_rows>0): while($r=$res->fetch_assoc()): ?>
      <article class="card">
        <img src="<?php echo htmlspecialchars($r['image_url']); ?>" alt="">
        <h3><a href="article.php?id=<?php echo $r['article_id']; ?>"><?php echo htmlspecialchars($r['title']); ?></a></h3>
        <p class="excerpt"><?php echo nl2br(htmlspecialchars(substr($r['content'],0,150))).'...'; ?></p>
      </article>
    <?php endwhile; else: ?>
      <p>No results found.</p>
    <?php endif; ?>
  </div>
</main>
</body>
</html>
