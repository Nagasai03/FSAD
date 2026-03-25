<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['user_id'];
$examid = $_GET['exam_id'] ?? 0;

/* Fetch exam + subject */
$examRes = $conn->query("
    SELECT e.*, s.subject_name
    FROM exams e
    JOIN subjects s ON e.subject_id = s.id
    WHERE e.id = '$examid'
");
$exam = $examRes ? $examRes->fetch_assoc() : null;

if (!$exam) {
    echo "Invalid Exam";
    exit;
}

/* Fetch questions */
$questions = $conn->query("
    SELECT * FROM questions 
    WHERE exam_id = '$examid'
");
$total_questions = $questions->num_rows;

/* Submit exam */
if (isset($_POST['submit'])) {

    $score = 0;

    if (isset($_POST['answers'])) {
        foreach ($_POST['answers'] as $qid => $ans) {

            $qid = (int)$qid;
            $ans = (int)$ans;

            $res = $conn->query("
                SELECT correct_option 
                FROM questions 
                WHERE id = $qid
            ");

            if ($res && $row = $res->fetch_assoc()) {
                if ((int)$row['correct_option'] === $ans) {
                    $score++;
                }
            }
        }
    }

    $percentage = ($total_questions > 0)
        ? round(($score / $total_questions) * 100)
        : 0;

    /* Save result */
    $conn->query("
        INSERT INTO results (student_id, exam_id, score, percentage)
        VALUES ('$student_id', '$examid', '$score', '$percentage')
    ");

    header("Location: result.php?exam_id=$examid");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($exam['title']); ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/online_exam/assets/theme.css">
</head>
<body>

<!-- HEADER -->
<header class="app-header">
    📘 <?php echo htmlspecialchars($exam['subject_name']); ?> —
    📝 <?php echo htmlspecialchars($exam['title']); ?>
</header>

<div class="container">

    <!-- PROGRESS BAR -->
    <div class="progress-box">
        <div class="progress-bar">
            <div id="progressFill"></div>
        </div>
        <small id="progressInfo">
            0 / <?php echo $total_questions; ?> answered
        </small>
    </div>

    <!-- EXAM FORM -->
    <form method="post">

        <?php $i = 1; while ($q = $questions->fetch_assoc()) { ?>
            <div class="question-card">
                <h4>
                    Q<?php echo $i++; ?>. 
                    <?php echo htmlspecialchars($q['question']); ?>
                </h4>

                <?php for ($o = 1; $o <= 4; $o++) { ?>
                    <label class="option-card">
                        <input type="radio"
                               name="answers[<?php echo $q['id']; ?>]"
                               value="<?php echo $o; ?>"
                               required>
                        <?php echo htmlspecialchars($q['option' . $o]); ?>
                    </label>
                <?php } ?>
            </div>
        <?php } ?>

        <div class="text-center mt-20">
            <button class="btn" name="submit">
                ✅ Submit Exam
            </button>
        </div>

    </form>

</div>

<script>
const radios = document.querySelectorAll("input[type=radio]");
const fill = document.getElementById("progressFill");
const info = document.getElementById("progressInfo");
let answered = new Set();
const total = <?php echo $total_questions; ?>;

radios.forEach(radio => {
    radio.addEventListener("change", () => {
        answered.add(radio.name);
        const percent = (answered.size / total) * 100;
        fill.style.width = percent + "%";
        info.innerText = answered.size + " / " + total + " answered";
    });
});
</script>

</body>
</html>