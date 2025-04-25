<?php 
header("content-type: application/json");
include("db.php");

$result = $conn->query("SELECT * FROM quizzes");

$quizzes = [];

while ($row = $result->fetch_assoc()) {
    $quizzes[] = $row;
}

echo json_encode($quizzes);






?>