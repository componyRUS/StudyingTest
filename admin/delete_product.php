<?php
session_start();
require('../includes/db_connect.php');
require('../includes/functions.php');

if(!isset($_SESSION['admin_user'])){
    header('Location: login.php');
    exit;
}

$id = get_get_value('id');

if(!is_numeric($id) || empty($id)) {
    header('Location: admin.php');
    exit;
}

$sql = "DELETE FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if($stmt->execute()) {
     header('Location: admin.php');
        exit;
} else {
    echo "Ошибка удаления товара.";
}

$conn->close();
?>