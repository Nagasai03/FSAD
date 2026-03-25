<?php
include '../db.php';
if(isset($_POST['add'])){
    $t=$_POST['title']; $d=$_POST['description']; $dur=$_POST['duration'];
    $conn->query("INSERT INTO exams(title,description,duration) VALUES('$t','$d','$dur')");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Exam</title>
    <link rel="stylesheet" href="../assets/theme.css">
</head>
<body>
<header>Online Exam System</header>
<div class="container">
<h2>Add Exam</h2>
<form method="post">
Title: <input type="text" name="title" required><br>
Description: <textarea name="description"></textarea><br>
Duration (minutes): <input type="number" name="duration" required><br>
<input type="submit" name="add" value="Add Exam">
</form>
</div>
</body>
</html>