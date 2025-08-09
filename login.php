<?php
require 'db.php';
$errors=[];
if($_SERVER['REQUEST_METHOD']=='POST'){
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $stmt = $conn->prepare("SELECT user_id, password, role FROM users WHERE email=? LIMIT 1");
  $stmt->bind_param("s",$email);
  $stmt->execute();
  $res = $stmt->get_result();
  if($res->num_rows==1){
    $u = $res->fetch_assoc();
    if(password_verify($password, $u['password'])){
      session_start();
      $_SESSION['user_id']=$u['user_id'];
      $_SESSION['role']=$u['role'];
      header('Location: index.php'); exit;
    } else $errors[]='Invalid credentials.';
  } else $errors[]='Invalid credentials.';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Login</title><link rel="stylesheet" href="styles.css"></head><body>
<header><a href="index.php">Home</a></header>
<main>
  <h1>Login</h1>
  <?php foreach($errors as $e) echo '<p class="error">'.htmlspecialchars($e).'</p>'; ?>
  <form method="post">
    <label>Email<input name="email" type="email" required></label>
    <label>Password<input name="password" type="password" required></label>
    <button type="submit">Login</button>
  </form>
</main></body></html>
