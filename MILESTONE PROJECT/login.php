<?php
session_start();
include 'db.php';

$error = "";

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");

    if($result && $result->num_rows > 0){
        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "❌ Oops! Wrong password";
        }
    } else {
        $error = "😕 No account found";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/login.css">
</head>
<body>

<div class="login-wrapper">
  <div class="login-card">
    <h1>🎓 Welcome Back!</h1>
    <p>Let’s start learning 🚀</p>

    <?php if($error){ ?>
      <div class="error"><?php echo $error; ?></div>
    <?php } ?>

    <form method="post">
      <div class="input-group">
        <label>📧 Email</label>
        <input type="email" name="email" required>
      </div>

      <div class="input-group">
        <label>🔒 Password</label>
        <input type="password" name="password" required>
      </div>

      <button name="login">✨ Login</button>
    </form>

    <p class="small-text">
      New student? <a href="register.php">Register 📘</a>
    </p>
  </div>
</div>

</body>
</html>