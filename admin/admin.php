<?php
session_start();
require('../includes/db_connect.php');
require('../includes/functions.php');

if(!isset($_SESSION['admin_user'])){
    header('Location: login.php');
    exit;
}
$sql = "SELECT * FROM products ORDER BY added_date DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ панель</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
          <a href="admin.php" style="text-decoration: none; color:white;"><h1 class="site-title">CardCol - магазин коллекционных карточек</h1></a>
            <?php display_cart_link() ?>
        </div>
    </header>
    <div class="container">
        <h1>Админ панель</h1>
        <p><a href="add_product.php">Добавить товар</a> | <a href="logout.php">Выход</a></p>
         <?php if ($result && $result->num_rows > 0): ?>
         <div class="products">
              <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product">
                     <h3><?=e($row['name']) ?></h3>
                     <?php if ($row['image']): ?>
                            <img src="../<?=e($row['image']) ?>" alt="<?=e($row['name']) ?>" style="max-height: 150px;">
                     <?php endif; ?>
                     <p><?=e($row['description']) ?></p>
                     <p>Цена: <?=e($row['price']) ?> руб.</p>
                     <p><a href="edit_product.php?id=<?=e($row['id']) ?>">Редактировать</a></p>
                     <p><a href="delete_product.php?id=<?=e($row['id']) ?>">Удалить</a></p>
                 </div>
              <?php endwhile; ?>
         </div>
           <?php else: ?>
              <p>Нет товаров для отображения.</p>
        <?php endif; ?>
    </div>
     <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
           <div class="social-links">
                <a href="#" target="_blank">Вконтакте</a>
            </div>
            </div>
        </div>
    </footer>
    <script src="../js/scripts.js"></script>
</body>
</html>
<?php $conn->close(); ?>