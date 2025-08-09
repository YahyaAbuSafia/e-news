<?php
require 'db.php';
$errors = [];
if($_SERVER['REQUEST_METHOD']=='POST'){
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  if(!$username||!$email||!$password) $errors[]='All fields required.';
  if(empty($errors)){
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username,email,password,role) VALUES (?,?,?, 'user')");
    $stmt->bind_param("sss",$username,$email,$hash);
    if($stmt->execute()){
      header('Location: login.php'); exit;
    } else {
      $errors[]='Could not create account.';
    }
  }
}
?>
<!doctype html>
<html lang="en"><head><meta charset="utf-8"><title>Register</title><link rel="stylesheet" href="styles.css"></head><body>
<header><a href="index.php">Home</a></header>
<main>
  <h1>Register</h1>
  <?php foreach($errors as $e) echo '<p class="error">'.htmlspecialchars($e).'</p>'; ?>
  <form method="post">
    <label>Username<input name="username" required></label>
    <label>Email<input name="email" type="email" required></label>
    <label>Password<input name="password" type="password" required></label>
    <button type="submit">Register</button>
  </form>
</main></body></html>
