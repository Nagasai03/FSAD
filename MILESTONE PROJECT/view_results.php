<?php
session_start();
include '../db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

$res = $conn->query("
    SELECT r.score, r.percentage, r.date_taken,
           u.name AS student,
           e.title AS exam
    FROM results r
    JOIN users u ON r.student_id = u.id
    JOIN exams e ON r.examid = e.id
    ORDER BY r.percentage DESC, r.date_taken DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Results</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/theme.css">
</head>
<body>

<header class="app-header">
    📊 Admin – Exam Results
    <a href="../dashboard.php">🏠 Dashboard</a>
</header>

<div class="container">

    <h2>🏆 Student Results</h2>

    <div class="card mt-20">
        <table class="result-table">
            <tr>
                <th>#</th>
                <th>Student</th>
                <th>Exam</th>
                <th>Score</th>
                <th>Percentage</th>
                <th>Date</th>
            </tr>

            <?php $i=1; while($row = $res->fetch_assoc()){ ?>
            <tr class="<?php echo ($row['percentage'] >= 90) ? 'topper' : ''; ?>">
                <td><?php echo $i++; ?></td>
                <td><?php echo $row['student']; ?></td>
                <td><?php echo $row['exam']; ?></td>
                <td><?php echo $row['score']; ?></td>
                <td><?php echo $row['percentage']; ?>%</td>
                <td><?php echo $row['date_taken']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>

</div>

</body>
</html>