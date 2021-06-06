<?php
include "db.php";
include "functions.php";

if (isset($_POST['user_id'])) {
    header('Content-Type: application/json');

    $user_id = $_POST['user_id'];
    $password = $_POST['password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $query = "SELECT username, password FROM users WHERE user_id = ? ";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;

    while ($rows = $result->fetch_assoc()) {
        $current_password = $rows['password'];
    }

    if ($current_password == $password) {
        $query = "UPDATE users SET password= ? WHERE user_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("si", $new_password, $user_id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo json_encode(array('success' => false, 'message' => 'Invalid Password'));
        exit;
    }

    echo json_encode(array('success' => true));
}
