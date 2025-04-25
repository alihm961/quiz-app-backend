<?php 

header("content-type: application/json");
include("db.php");

$data = json_decode(file_get_contents("php://input"), true);

$email = $data["email"];
$password = $data["password"];

if (!$email || !$password) {
    echo json_encode(["status" => "error", "message" => "Email and password required."]);
    exit;
}

$stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "User not found."]);
    exit;
}

$user = $result->fetch_assoc();

if (password_verify($password, $user["password"])) {
    echo json_encode([
        "status" => "Success",
        "user" => ["id" => $user["id"], "email" => $email, "is_admin" => $user["is_admin"]
        ]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Incorrect password"]);
}


?>