<?php
require 'db.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare("SELECT a.*, c.category_name, u.username FROM articles a LEFT JOIN categories c ON a.category_id=c.category_id LEFT JOIN users u ON a.author_id=u.user_id WHERE a.article_id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$res = $stmt->get_result();
$article = $res->fetch_assoc();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?php echo htmlspecialchars($article['title'] ?? 'Article'); ?></title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<header><a href="index.php">← Back to Home</a></header>
<main>
  <?php if($article): ?>
    <article class="single">
      <h1><?php echo htmlspecialchars($article['title']); ?></h1>
      <p class="meta"><?php echo htmlspecialchars($article['category_name']); ?> • <?php echo $article['published_date']; ?> • by <?php echo htmlspecialchars($article['username'] ?? 'Guest'); ?></p>
      <img src="<?php echo htmlspecialchars($article['image_url']); ?>" alt="">
      <div class="content"><?php echo nl2br(htmlspecialchars($article['content'])); ?></div>
    </article>

    <section class="comments">
      <h3>Comments</h3>
      <?php
        $cstmt = $conn->prepare("SELECT cm.comment_text, u.username, cm.timestamp FROM comments cm LEFT JOIN users u ON cm.user_id=u.user_id WHERE cm.article_id=? ORDER BY cm.timestamp DESC");
        $cstmt->bind_param("i",$id);
        $cstmt->execute();
        $cres = $cstmt->get_result();
        if($cres->num_rows>0){
          while($c=$cres->fetch_assoc()){
            echo '<div class="comment"><strong>'.htmlspecialchars($c['username'] ?? 'Guest').'</strong> <small>'.$c['timestamp'].'</small><p>'.nl2br(htmlspecialchars($c['comment_text'])).'</p></div>';
          }
        } else { echo '<p>No comments yet.</p>'; }
      ?>
    </section>

  <?php else: ?>
    <p>Article not found.</p>
  <?php endif; ?>
</main>
</body>
</html>
