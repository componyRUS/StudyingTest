<?php
$host = "localhost";
$username = "root";
$password = "password";
$database = "shopping_cart";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>
