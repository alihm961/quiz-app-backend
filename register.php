<?php 

header("content-type: application/json");
include("db.php");

$data = json_decode(file_get_contents("php://input"), true);

$email = $data["email"];
$password = $data["password"];

if(!$email || !$password ) {
    echo json_encode(["status" => "error", "message" => "Email and password required"]);
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "User already exists."]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $hashed);

if ($stmt->execute()) {
    echo json_encode(["status" => "Success", "message" => "User registered."]);
} else {
    echo json_encode(["status" => "error", "message" => "Registration failed."]);
}


?>