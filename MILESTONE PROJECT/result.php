<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['user_id'];
$examid = $_GET['exam_id'];

/* Fetch latest result + exam + subject */
$res = $conn->query("
    SELECT 
        r.score,
        r.percentage,
        e.title AS exam_title,
        s.subject_name
    FROM results r
    JOIN exams e ON r.exam_id = e.id
    JOIN subjects s ON e.subject_id = s.id
    WHERE r.student_id = '$student_id'
      AND r.exam_id = '$examid'
    ORDER BY r.id DESC
    LIMIT 1
");

$row = ($res && $res->num_rows > 0)
    ? $res->fetch_assoc()
    : [
        'score'=>0,
        'percentage'=>0,
        'exam_title'=>'',
        'subject_name'=>''
      ];

/* Total questions */
$total = $conn->query("
    SELECT COUNT(*) as total 
    FROM questions 
    WHERE exam_id='$examid'
")->fetch_assoc()['total'];

$percentage = $row['percentage'];

/* Message */
if($percentage >= 90){
    $msg = "🌟 Outstanding! You’re a superstar!";
} elseif($percentage >= 70){
    $msg = "🎉 Great job! Keep it up!";
} elseif($percentage >= 40){
    $msg = "🙂 Good effort! Practice more!";
} else {
    $msg = "💪 Don’t worry! Try again!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Your Result</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/theme.css">
</head>
<body>

<header class="app-header">
    🎯 Exam Result
    <a href="dashboard.php">🏠 Dashboard</a>
</header>

<div class="container text-center">

    <div class="result-card">

        <h2><?php echo $msg; ?></h2>

        <!-- SUBJECT & EXAM INFO -->
        <h3>📘 Subject: <?php echo $row['subject_name']; ?></h3>
        <h3>📝 Exam: <?php echo $row['exam_title']; ?></h3>

        <h3 class="mt-20">
            Score: <?php echo $row['score']; ?> / <?php echo $total; ?>
        </h3>

        <!-- RESULT BAR -->
        <div class="result-bar big">
            <div class="result-fill" style="width: <?php echo $percentage; ?>%">
                <?php echo $percentage; ?>%
            </div>
        </div>

        <!-- BADGES -->
        <h3 class="mt-20">🏅 Your Achievements</h3>

        <div class="badge-row">

            <?php if($percentage >= 90){ ?>
                <div class="badge gold">🥇<span>Top Scorer</span></div>
            <?php } ?>

            <?php if($percentage >= 75){ ?>
                <div class="badge blue">🎯<span>High Accuracy</span></div>
            <?php } ?>

            <?php if($row['score'] == $total && $total > 0){ ?>
                <div class="badge green">🔥<span>Perfect Score</span></div>
            <?php } ?>

            <?php if($percentage < 40){ ?>
                <div class="badge purple">💪<span>Keep Trying</span></div>
            <?php } ?>

        </div>

        <div class="mt-20">
            <a href="dashboard.php" class="btn">🚀 Back to Dashboard</a>
        </div>

    </div>

</div>

</body>
</html>