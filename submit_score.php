<?php 
header("content-type: application/json");
include("db.php");

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $data["user_id"];
$quiz_id = $data["quiz_id"];
$score = $data["score"];

if (!$user_id || !$quiz_id || !isset($score)) {
    echo json_encode(["status" => "error", "message" => "Missing data"]);
    exit;
}


$stmt = $conn->prepare("INSERT INTO scores (user_id, quiz_id, score) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $user_id, $quiz_id, $score);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Score saved"]);

} else {
    echo json_encode(["status" => "error", "message" => "Failed to save score"]);
}


?>