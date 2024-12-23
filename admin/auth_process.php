<?php
session_start();
require_once('../includes/db_connect.php');
require_once('../includes/functions.php');
$username = get_post_value('username');
$password = get_post_value('password');

if ($username && $password) {
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if($user && password_verify($password, $user['password'])){
        $_SESSION['admin'] = true;
        header('Location: admin.php');
        exit;
    }
}
header('Location: auth.php?error=auth');

$conn->close();
?>