<?php
require 'db.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$limit = 6;
$page = isset($_GET['page']) ? max(1,intval($_GET['page'])) : 1;
$offset = ($page-1)*$limit;

$stmt = $conn->prepare("SELECT a.*, c.category_name FROM articles a JOIN categories c ON a.category_id=c.category_id WHERE a.category_id=? ORDER BY published_date DESC LIMIT ? OFFSET ?");
$stmt->bind_param("iii",$id,$limit,$offset);
$stmt->execute();
$res = $stmt->get_result();

$total_stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM articles WHERE category_id=?");
$total_stmt->bind_param("i",$id);
$total_stmt->execute();
$total = $total_stmt->get_result()->fetch_assoc()['cnt'];
$pages = ceil($total/$limit);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Category</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="styles.css">
</head>
<body>
<header><a href="index.php">Home</a></header>
<main>
  <h1>Category</h1>
  <div class="grid">
  <?php while($row=$res->fetch_assoc()): ?>
    <article class="card">
      <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="">
      <h3><a href="article.php?id=<?php echo $row['article_id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h3>
      <p class="excerpt"><?php echo nl2br(htmlspecialchars(substr($row['content'],0,150))).'...'; ?></p>
      <p class="meta"><?php echo htmlspecialchars($row['category_name']); ?> • <?php echo $row['published_date']; ?></p>
    </article>
  <?php endwhile; ?>
  </div>

  <div class="pagination">
    <?php for($i=1;$i<=$pages;$i++): ?>
      <a class="<?php echo $i==$page ? 'active':''; ?>" href="category.php?id=<?php echo $id; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
  </div>
</main>
</body>
</html>
