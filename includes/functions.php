<?php
// Функция для безопасного получения значений из $_POST или $_GET
function get_post_value($name) {
  return isset($_POST[$name]) ? trim($_POST[$name]) : '';
}

function get_get_value($name) {
  return isset($_GET[$name]) ? trim($_GET[$name]) : '';
}
// Функция для вывода сообщений об ошибках
function display_error($error){
    if($error) echo "<div class='error'>$error</div>";
}
// Функция для безопасного вывода HTML
function e( $str ) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
// Функция для отображения корзины
function display_cart_link(){
  echo "<div class='cart-info'>";
       echo "<a href='cart.php' class='cart-link'>Корзина</a>";
   echo "</div>";
}
?>