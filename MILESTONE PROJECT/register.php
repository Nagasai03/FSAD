<?php
include 'db.php';
if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (name,email,password) VALUES ('$name','$email','$password')";
    if($conn->query($sql)) echo "Registered successfully. <a href='login.php'>Login here</a>";
    else echo "Error: " . $conn->error;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header>Online Exam System</header>
<div class="container">
<h2>Register</h2>
<form method="post">
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" name="register" value="Register">
</form>
<p>Already have an account? <a href="login.php">Login</a></p>
</div>
</body>
</html>