<?php 
header("content-type: application/json");
include("db.php");

$quiz_id = $_GET["quiz_id"];

if (!$quiz_id) {
    echo json_encode(["status" => "error", "message" => "Missing quiz_id"]);
    exit;
}

$q_stmt = $conn->prepare("SELECT id, question FROM questions WHERE quiz_id = ?");
$q_stmt->bind_param("i", $quiz_id);
$q_stmt->execute();
$questions_result = $q_stmt->get_result();

$questions = [];

while ($q = $questions_result->fetch_assoc()) {
    $question_id = $q["id"];

    $o_stmt = $conn->prepare("SELECT id, text FROM options WHERE question_id = ?");
    $o_stmt->bind_param("i", $question_id);
    $o_stmt->execute();
    $options_result = $o_stmt->get_result();

    $options = [];
    while ($o = $options_result->fetch_assoc()) {
        $options = $o;
    }

    $q["options"] = $options;
    $questions[] = $q;
}

echo json_encode($questions);

?>