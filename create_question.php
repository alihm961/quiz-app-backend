<?php 
header("content-type: application/json");
include("db.php");

$data = json_decode(file_get_contents("php://input"), true);

$quiz_id = $data["quiz_id"];
$question_text = $data["question"];
$options = $data["options"];
$correct_index = $data["correct_index"];

if (empty($quiz_id) || empty($question_text) || !is_array($options) || count($options) < 2 || !isset($correct_index)) {
    echo json_encode(["status" => "error", "message" => "Missing data"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO questions (quiz_id, question) VALUES (?, ?)");
$stmt->bind_param("is", $quiz_id, $question_text);
if (!$stmt->execute()) {
    echo json_encode(["status" => "error", "message" => "Question insert failed"]);
    exit;
}

$question_id = $conn->insert_id;

for ($i = 0; $i < count($options); $i++) {
    $text = $options[$i];
    $is_correct = ($i == $correct_index) ? 1 : 0;

    $opt_stmt = $conn->prepare("INSERT INTO options (question_id, text, is_correct) VALUES (?, ?, ?)");
    $opt_stmt->bind_param("isi", $question_id, $text, $is_correct);
    $opt_stmt->execute();
}

echo json_encode(["status" => "success", "message" => "Questions and options added"]);




?>