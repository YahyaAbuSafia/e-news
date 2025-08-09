<?php
require 'db.php';
$cats = $conn->query("SELECT * FROM categories");
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Categories</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="styles.css">
</head>
<body>
<header><a href="index.php">Home</a></header>
<main>
  <h1>Categories</h1>
  <ul class="cat-list">
  <?php while($c=$cats->fetch_assoc()): ?>
    <li><a href="category.php?id=<?php echo $c['category_id']; ?>"><?php echo htmlspecialchars($c['category_name']); ?></a></li>
  <?php endwhile; ?>
  </ul>
</main>
</body>
</html>
