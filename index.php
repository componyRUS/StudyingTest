<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
require_once('includes/db_connect.php');
require_once('includes/functions.php');
$sql = "SELECT * FROM products ORDER BY added_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>CardCol - магазин коллекционных карточек</title>
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
        <h1>Товары</h1>
        <div class="products">
            <?php if ($result && $result->num_rows > 0):
              while ($row = $result->fetch_assoc()):
                ?>
                    <div class="product">
                        <h3><?=e($row['name']) ?></h3>
                       <div class="product-slider">
                         <?php if ($row['image']): ?>
                            <img src="<?=e($row['image']) ?>" alt="<?=e($row['name']) ?>">
                        <?php endif; ?>
                            <?php if ($row['image2']): ?>
                                <img src="<?=e($row['image2']) ?>" alt="<?=e($row['name']) ?>">
                           <?php endif; ?>
                            <?php if ($row['image3']): ?>
                                <img src="<?=e($row['image3']) ?>" alt="<?=e($row['name']) ?>">
                            <?php endif; ?>
                        </div>
                        <p><?=e($row['description']) ?></p>
                        <p>Цена: <?=e($row['price']) ?> руб.</p>
                         <button class="add-to-cart" data-product-id="<?=e($row['id']) ?>">В корзину</button>
                          <p><a href="product.php?id=<?=e($row['id']) ?>">Подробнее</a></p>
                    </div>
                <?php
                 endwhile;
            else:
               echo "<p>Нет товаров для отображения.</p>";
            endif;
            ?>
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