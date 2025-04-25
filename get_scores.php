<?php 
header("content-type: application/json");
include("db.php");

$result = $conn->query("
SELECT users.email, quizzes.title, scores.score FROM scores
JOIN users ON scores.user_id = users.id
JOIN quizzes ON scores.quiz_id = quizzes.id

");

$scores = [];

while ($row = $result->fetch_assoc()) {
    $scores[] = $row;
}

echo json_encode($scores);


?>