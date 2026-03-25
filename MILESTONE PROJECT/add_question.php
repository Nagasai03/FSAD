<?php
include '../db.php';
$exam_id=$_GET['exam_id'];
if(isset($_POST['add'])){
    $q=$_POST['question']; $o1=$_POST['option1']; $o2=$_POST['option2'];
    $o3=$_POST['option3']; $o4=$_POST['option4']; $c=$_POST['correct'];
    $conn->query("INSERT INTO questions(exam_id,question_text,option1,option2,option3,option4,correct_option) VALUES('$exam_id','$q','$o1','$o2','$o3','$o4','$c')");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Question</title>
    <link rel="stylesheet" href="../assets/theme.css">
</head>
<body>
<header>Online Exam System</header>
<div class="container">
<h2>Add Question</h2>
<form method="post">
Question: <input type="text" name="question" required><br>
Option 1: <input type="text" name="option1" required><br>
Option 2: <input type="text" name="option2" required><br>
Option 3: <input type="text" name="option3" required><br>
Option 4: <input type="text" name="option4" required><br>
Correct Option (1-4): <input type="number" name="correct" min="1" max="4" required><br>
<input type="submit" name="add" value="Add Question">
</form>
</div>
</body>
</html>