<?php
session_start();
require_once('includes/functions.php');
$action = get_post_value('action') ? get_post_value('action') : get_get_value('action');
$product_id = get_post_value('product_id')  ? get_post_value('product_id') :  get_get_value('product_id');
if($action === 'add' && is_numeric($product_id)){
        if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
        } else {
        $_SESSION['cart'][$product_id] = 1;
        }
     echo "success";
}elseif ($action === 'decrease' && is_numeric($product_id)){
    if(isset($_SESSION['cart'][$product_id])){
        $_SESSION['cart'][$product_id]--;
        if($_SESSION['cart'][$product_id] <= 0){
            unset($_SESSION['cart'][$product_id]);
        }
    }
      header('Location: cart.php');
}elseif ($action === 'increase' && is_numeric($product_id)) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
          $_SESSION['cart'][$product_id] = 1;
    }
      header('Location: cart.php');
}elseif($action === 'clear'){
  unset($_SESSION['cart']);
  header('Location: cart.php');
}elseif($action === 'update_counter'){
    $total_price = 0;
     if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
         require('db_connect.php');
      foreach ($_SESSION['cart'] as $product_id => $quantity){
          $sql = "SELECT * FROM products WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
           if($product){
               $total_price += $product['price'] * $quantity;
           }
      }
      $conn->close();
     }
    header('Content-Type: application/json');
    echo json_encode(['totalPrice' => number_format($total_price, 2)]);
     exit;
}
else{
   echo "error";
}
?>