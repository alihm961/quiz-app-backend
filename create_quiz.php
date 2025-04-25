<?php 
header("content-type: application/json");
include("db.php");

$data = json_decode(file_get_contents("php://input"), true);

$title = $data["title"];
$created_by = $data["created_by"];

if (!$title || !$created_by) {
    echo json_encode(["status" => "error", "message" => "Missing title or creator."]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO quizzes (title, created_by) VALUES (?, ?)");
$stmt->bind_param("si", $title, $created_by);

if ($stmt->execute()) {
    echo json_encode(["status" => "Success", "quiz_id" => $conn->insert_id]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to create quiz"]);
}



?>