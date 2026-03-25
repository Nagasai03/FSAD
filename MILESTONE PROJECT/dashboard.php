<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/online_exam/assets/theme.css">
</head>
<body>

<header class="app-header">
    👋 Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
    <a href="logout.php">🚪 Logout</a>
</header>

<div class="container">

<?php if ($_SESSION['role'] === 'admin') { ?>

    <h2>🛠 Admin Panel</h2>
    <div class="exam-grid">
        <a class="exam-card btn" href="admin/add_exam.php">➕ Add Exam</a>
        <a class="exam-card btn" href="admin/view_results.php">📊 View Results</a>
    </div>

<?php } else { ?>

    <!-- SUBJECT LIST -->
    <h2>📚 Choose a Subject</h2>

    <div class="exam-grid">
        <?php
        $subjects = $conn->query("SELECT * FROM subjects");
        while ($sub = $subjects->fetch_assoc()) {
        ?>
            <div class="exam-card">
                <h3><?php echo htmlspecialchars($sub['subject_name']); ?></h3>
                <a class="btn"
                   href="dashboard.php?subject_id=<?php echo $sub['id']; ?>">
                    View Exams
                </a>
            </div>
        <?php } ?>
    </div>

    <!-- EXAMS UNDER SELECTED SUBJECT -->
    <?php if (isset($_GET['subject_id'])) {
        $sid = (int)$_GET['subject_id'];

        $exams = $conn->query("
            SELECT * FROM exams 
            WHERE subject_id = '$sid'
        ");
    ?>
        <h2 class="mt-20">📝 Available Exams</h2>

        <div class="exam-grid">
            <?php
            if ($exams->num_rows === 0) {
                echo "<p>No exams found for this subject.</p>";
            }

            while ($exam = $exams->fetch_assoc()) {
            ?>
                <div class="exam-card">
                    <h4><?php echo htmlspecialchars($exam['title']); ?></h4>
                    <p>⏱ <?php echo $exam['duration']; ?> mins</p>
                    <p>🎯 <?php echo $exam['total_marks']; ?> marks</p>

                    <!-- THIS LINK IS THE MOST IMPORTANT -->
                    <a class="btn"
                       href="exam.php?exam_id=<?php echo $exam['id']; ?>">
                        Start Exam
                    </a>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

<?php } ?>

</div>
</body>
</html>