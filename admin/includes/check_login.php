
<?php include "db.php"; ?>
<?php session_start(); ?>
<?php

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT user_id, username, password FROM users WHERE username = ? ";
    $stmt = mysqli_prepare($connection, $query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        die("QUERY FAILED " . mysqli_error($connection));
    }

    $db_username = "";
    $db_password = "";
    while ($row = $result->fetch_assoc()) {
        $user_id = $row["user_id"];
        $db_username = $row["username"];
        $db_password = $row["password"];
    }

    if ($username !== $db_username || $password !== $db_password) {
        echo json_encode(array("success" => false, "message" => "Invalid username or password."));
        exit;
    } else if ($username == $db_username && $password == $db_password) {
        $_SESSION['username'] = $db_username;
        $_SESSION['user_id'] = $user_id;

        echo json_encode(array("success" => true));
        exit;
    } else {
        echo json_encode(array("success" => false, "message" => "Invalid username or password."));
        exit;
    }
}
