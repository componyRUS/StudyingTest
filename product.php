<?php
require_once('includes/db_connect.php');
require_once('includes/functions.php');
$id = get_get_value('id');
if (!is_numeric($id) || empty($id)) {
    header('Location: index.php');
    exit;
}
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result || $result->num_rows === 0) {
   header('Location: index.php');
    exit;
}
$product = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?=e($product['name'])?></title>
     <link rel="stylesheet" href="css/style.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
</head>
<body>
 <header class="site-header">
       <div class="container">
        <a href="index.php" style="text-decoration: none; color:white;"><h1 class="site-title">CardCol - магазин коллекционных карточек</h1></a>
           <?php display_cart_link() ?>
       </div>
   </header>
    <div class="container content-wrapper">
        <div class="product">
            <h1><?=e($product['name'])?></h1>
               <div class="product-slider">
                 <?php if ($product['image']): ?>
                      <img src="<?=e($product['image']) ?>" alt="<?=e($product['name']) ?>">
                    <?php endif; ?>
                      <?php if ($product['image2']): ?>
                            <img src="<?=e($product['image2']) ?>" alt="<?=e($product['name']) ?>">
                        <?php endif; ?>
                          <?php if ($product['image3']): ?>
                               <img src="<?=e($product['image3']) ?>" alt="<?=e($product['name']) ?>">
                          <?php endif; ?>
                </div>
            <p><?=e($product['description'])?></p>
            <p>Цена: <?=e($product['price'])?> руб.</p>
            <button class="add-to-cart" data-product-id="<?=e($product['id'])?>">В корзину</button>
             <p><a href="index.php">Назад</a></p>
        </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
   <script>
       $(document).ready(function(){
          $('.product-slider').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                adaptiveHeight: true
          });
      });
   </script>
</body>
</html>
<?php $conn->close(); ?>