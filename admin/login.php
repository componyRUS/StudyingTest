<?php
session_start();
require('../includes/db_connect.php');
require('../includes/functions.php');

$error = '';
$username = get_post_value('username');
$password = get_post_value('password');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (empty($username) || empty($password)) {
      $error = "Пожалуйста, заполните все поля.";
    }else{
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows === 1) {
           $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['password'])) {
              $_SESSION['admin_user'] = $admin['username'];
                header('Location: admin.php');
               exit;
             } else{
              $error = "Неверный пароль.";
            }
         } else {
           $error = "Неверное имя пользователя.";
          }
     }
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
     <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="site-header">
         <div class="container">
            <a href="../index.php" style="text-decoration: none; color:white;"><h1 class="site-title">CardCol - магазин коллекционных карточек</h1></a>
         </div>
     </header>
    <div class="container">
        <h1>Авторизация</h1>
        <?php display_error(e($error)) ?>
        <form method="post">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username" value="<?=e($username)?>"><br><br>
            <label for="password">Пароль:</label>
             <input type="password" id="password" name="password" ><br><br>
            <button type="submit">Войти</button>
        </form>
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