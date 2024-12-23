<?php
session_start();
require_once('includes/functions.php');
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
require_once('includes/db_connect.php');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>CardCol - магазин коллекционных карточек</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header class="site-header">
       <div class="container">
         <a href="index.php" style="text-decoration: none; color:white;"><h1 class="site-title">CardCol - магазин коллекционных карточек</h1></a>
           <?php display_cart_link() ?>
       </div>
   </header>
    <div class="container">
        <h1>Корзина</h1>
         <?php if(!empty($cart)): ?>
              <table>
                  <thead>
                      <tr>
                          <th>Товар</th>
                          <th>Количество</th>
                            <th>Цена</th>
                            <th>Сумма</th>
                           <th>Действия</th>
                      </tr>
                   </thead>
                   <tbody>
             <?php
                $total = 0;
                  foreach ($cart as $product_id => $quantity):
                    $sql = "SELECT * FROM products WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $product_id);
                   $stmt->execute();
                    $result = $stmt->get_result();
                    $product = $result->fetch_assoc();
                    if ($product):
                        $sum = $product['price'] * $quantity;
                        $total += $sum;
                        ?>
                       <tr>
                           <td><?=e($product['name'])?></td>
                          <td><?=e($quantity)?></td>
                          <td><?=e($product['price'])?></td>
                            <td><?=e($sum)?></td>
                             <td>
                                 <a href="cart_process.php?action=decrease&product_id=<?=e($product_id)?>">Удалить</a>
                                 <a href="cart_process.php?action=increase&product_id=<?=e($product_id)?>">Добавить</a>
                            </td>
                        </tr>
                  <?php
                      endif;
                endforeach;
              ?>
                </tbody>
                <tfoot>
                     <tr>
                         <td colspan="4" align="right"><strong>Итого:</strong></td>
                         <td><strong><?=e($total)?></strong></td>
                    </tr>
                </tfoot>
              </table>
                <p>
                  <a href="#" class="buy-button">Купить</a>
                </p>
         <p><a href="cart_process.php?action=clear" class="clear-cart">Очистить корзину</a></p>
            <?php else: ?>
           <p>Корзина пуста.</p>
         <?php endif; ?>
        <p><a href="index.php">Продолжить покупки</a></p>
    </div>
    <footer class="site-footer">
      <div class="container">
          <div class="footer-content">
          <a href="admin/login.php" class="admin-button">Администрирование</a>
         <div class="social-links">
               <a href="#" target="_blank">Вконтакте</a>
        </div>
         </div>
      </div>
    </footer>
     <script src="js/scripts.js"></script>
</body>
</html>
<?php $conn->close(); ?>