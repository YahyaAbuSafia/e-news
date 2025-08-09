<?php
require 'db.php';

// Pagination
$limit = 6;
$page = isset($_GET['page']) ? max(1,intval($_GET['page'])) : 1;
$offset = ($page-1)*$limit;

// Fetch featured (latest 3)
$feat_sql = "SELECT a.*, c.category_name FROM articles a JOIN categories c ON a.category_id=c.category_id ORDER BY published_date DESC LIMIT 3";
$feat_res = $conn->query($feat_sql);

// Fetch latest with pagination
$sql = "SELECT a.*, c.category_name FROM articles a JOIN categories c ON a.category_id=c.category_id ORDER BY published_date DESC LIMIT $limit OFFSET $offset";
$res = $conn->query($sql);

// Count total
$total_res = $conn->query("SELECT COUNT(*) as cnt FROM articles");
$total_row = $total_res->fetch_assoc();
$total = intval($total_row['cnt']);
$pages = ceil($total / $limit);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Global News Network</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
  <div class="logo">Global News Network</div>
  <nav>
    <a href="index.php">Home</a>
    <a href="categories.php">Categories</a>
    <a href="about.php">About Us</a>
    <a href="contact.php">Contact</a>
  </nav>
  <form class="search" action="search.php" method="get">
    <input name="q" placeholder="Search articles...">
    <button type="submit">Search</button>
  </form>
  <div class="auth">
    <a href="login.php">Login</a> | <a href="register.php">Signup</a>
  </div>
</header>

<main>
  <section class="breaking">
    <h2>Breaking News</h2>
    <?php if($feat_res && $feat_res->num_rows>0): ?>
      <div class="breaking-list">
        <?php while($f=$feat_res->fetch_assoc()): ?>
          <article>
            <img src="<?php echo htmlspecialchars($f['image_url']); ?>" alt="">
            <h3><a href="article.php?id=<?php echo $f['article_id']; ?>"><?php echo htmlspecialchars($f['title']); ?></a></h3>
            <p class="meta"><?php echo htmlspecialchars($f['category_name']); ?> • <?php echo $f['published_date']; ?></p>
          </article>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p>No breaking news yet.</p>
    <?php endif; ?>
  </section>

  <section class="latest">
    <h2>Latest News</h2>
    <div class="grid">
      <?php if($res): while($row=$res->fetch_assoc()): ?>
        <article class="card">
          <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="">
          <h3><a href="article.php?id=<?php echo $row['article_id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h3>
          <p class="excerpt"><?php echo nl2br(htmlspecialchars(substr($row['content'],0,150))).'...'; ?></p>
          <p class="meta"><?php echo htmlspecialchars($row['category_name']); ?> • <?php echo $row['published_date']; ?></p>
        </article>
      <?php endwhile; else: ?>
        <p>No articles found.</p>
      <?php endif; ?>
    </div>

    <div class="pagination">
      <?php for($i=1;$i<=$pages;$i++): ?>
        <a class="<?php echo $i==$page ? 'active':''; ?>" href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
      <?php endfor; ?>
    </div>
  </section>
</main>

<aside>
  <section class="trending">
    <h3>Trending</h3>
    <?php
      $tres = $conn->query("SELECT article_id, title FROM articles ORDER BY published_date DESC LIMIT 5");
      if($tres): while($t=$tres->fetch_assoc()): ?>
        <p><a href="article.php?id=<?php echo $t['article_id']; ?>"><?php echo htmlspecialchars($t['title']); ?></a></p>
    <?php endwhile; endif; ?>
  </section>
</aside>

<footer>
  <p>© <?php echo date('Y'); ?> Global News Network</p>
</footer>
</body>
</html>
