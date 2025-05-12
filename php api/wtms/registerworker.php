<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email     = $_POST['email'];
    $password  = sha1($_POST['password']);
    $phone     = $_POST['phone'];
    $address   = $_POST['address'];

    if (!$full_name || !$email || !$password || !$phone || !$address) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    $check = $conn->prepare("SELECT * FROM workers WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO workers (full_name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $full_name, $email, $password, $phone, $address);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Registered successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Registration failed']);
    }
}
?>
