<?php
require 'db.php';
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role']!='admin'){
  die('Access denied. Log in as admin.');
}

// Handle create
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['action']) && $_POST['action']=='create'){
  $title=$_POST['title']; $content=$_POST['content']; $image=$_POST['image']; $cat=intval($_POST['category']);
  $stmt = $conn->prepare("INSERT INTO articles (title,content,image_url,category_id,author_id,published_date) VALUES (?,?,?,?,?,NOW())");
  $author = $_SESSION['user_id'];
  $stmt->bind_param("sssis",$title,$content,$image,$cat,$author);
  $stmt->execute();
  header('Location: admin.php'); exit;
}

// Handle delete
if(isset($_GET['delete'])){
  $id=intval($_GET['delete']);
  $stmt=$conn->prepare("DELETE FROM articles WHERE article_id=?");
  $stmt->bind_param("i",$id);
  $stmt->execute();
  header('Location: admin.php'); exit;
}

$articles = $conn->query("SELECT a.article_id,a.title,c.category_name FROM articles a LEFT JOIN categories c ON a.category_id=c.category_id ORDER BY a.published_date DESC");
$cats = $conn->query("SELECT * FROM categories");
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Admin</title><link rel="stylesheet" href="styles.css"></head><body>
<header><a href="index.php">Home</a></header>
<main>
  <h1>Admin Panel</h1>
  <h2>Create Article</h2>
  <form method="post">
    <input type="hidden" name="action" value="create">
    <label>Title<input name="title" required></label>
    <label>Image URL<input name="image" required></label>
    <label>Category<select name="category"><?php while($c=$cats->fetch_assoc()){ echo '<option value="'.$c['category_id'].'">'.htmlspecialchars($c['category_name']).'</option>'; } ?></select></label>
    <label>Content<textarea name="content" required></textarea></label>
    <button type="submit">Create</button>
  </form>

  <h2>Existing Articles</h2>
  <ul>
    <?php while($a=$articles->fetch_assoc()): ?>
      <li><?php echo htmlspecialchars($a['title']); ?> - <?php echo htmlspecialchars($a['category_name']); ?> - <a href="?delete=<?php echo $a['article_id']; ?>" onclick="return confirm('Delete?')">Delete</a></li>
    <?php endwhile; ?>
  </ul>
</main></body></html>
