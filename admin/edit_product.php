<?php
session_start();
require('../includes/db_connect.php');
require('../includes/functions.php');

if(!isset($_SESSION['admin_user'])){
    header('Location: login.php');
    exit;
}
$id = get_get_value('id');
if(!is_numeric($id) || empty($id)){
  header('Location: admin.php');
  exit;
}
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
if (!$product) {
    header('Location: admin.php');
    exit;
}
$name = get_post_value('name');
$description = get_post_value('description');
$price = get_post_value('price');
$image = $product['image'];
$image2 = $product['image2'];
$image3 = $product['image3'];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($name) || empty($description) || empty($price)) {
      $error = 'Все поля обязательны для заполнения.';
    } else if (!is_numeric($price)) {
        $error = "Цена должна быть числом";
    }else {
       $targetDir = "../uploads/";
       $image = handleFileUpload($_FILES['image'], $targetDir);
           $image = $image ? $image : $product['image'];
       $image2 = handleFileUpload($_FILES['image2'], $targetDir);
            $image2 = $image2 ? $image2 : $product['image2'];
       $image3 = handleFileUpload($_FILES['image3'], $targetDir);
        $image3 = $image3 ? $image3 : $product['image3'];
        if($image === false || $image2 === false || $image3 === false) {
              $error = 'Ошибка при загрузке файла.';
        }else{
            $sql = "UPDATE products SET name = ?, description = ?, price = ?, image = ?, image2 = ?, image3 = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdsssi", $name, $description, $price, $image, $image2, $image3, $id);
            if ($stmt->execute()) {
              header('Location: admin.php');
                exit;
            } else {
               $error = 'Ошибка при обновлении товара.';
            }
        }
    }
}
function handleFileUpload($file, $targetDir){
         if ($file['error'] == UPLOAD_ERR_NO_FILE) {
            return null; // No file was uploaded, return null
        }
        if ($file['error'] === UPLOAD_ERR_OK) {
           $fileName = basename($file["name"]);
             $fileTmpName = $file["tmp_name"];
            $fileSize = $file["size"];
              $fileError = $file["error"];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = array('jpg', 'jpeg', 'png', 'gif');
            if (in_array($fileExt, $allowed)) {
               if($fileSize < 500000){
                  $fileNameNew = uniqid('',true).".".$fileExt;
                   $targetFile = $targetDir.$fileNameNew;
                if(move_uploaded_file($fileTmpName, $targetFile)){
                    $watermarkText = "CardCol";
                   $watermarkedFilePath = addWatermark($targetFile, $watermarkText);
                    if($watermarkedFilePath)
                        return  "uploads/".$fileNameNew;
                   else
                        return false;
                 }else{
                 return false;
                 }
             }else{
              return false;
          }
        }else{
         return false;
        }
        }else{
             return false;
        }
}
function addWatermark($imagePath, $watermarkText){
    $imageInfo = getimagesize($imagePath);
    $imageType = $imageInfo[2];

    switch($imageType){
        case IMAGETYPE_JPEG:
           $image = imagecreatefromjpeg($imagePath);
            break;
        case IMAGETYPE_PNG:
              $image = imagecreatefrompng($imagePath);
           break;
        case IMAGETYPE_GIF:
              $image = imagecreatefromgif($imagePath);
           break;
           default:
               return false;
    }

    $textColor = imagecolorallocate($image, 255, 255, 255); // Белый цвет для текста
    $fontSize = 20; // Размер шрифта
    $textAngle = -20;
   $textBoundingBox = imagettfbbox($fontSize, $textAngle, __DIR__.'/../includes/arial.ttf', $watermarkText);
     $textWidth = abs($textBoundingBox[4] - $textBoundingBox[0]);
     $textHeight = abs($textBoundingBox[5] - $textBoundingBox[1]);
     $x = (imagesx($image) - $textWidth) / 2;
     $y = (imagesy($image) - $textHeight) / 2;

    imagettftext($image, $fontSize, $textAngle, $x, $y, $textColor, __DIR__.'/../includes/arial.ttf', $watermarkText);

    switch($imageType){
        case IMAGETYPE_JPEG:
            imagejpeg($image, $imagePath, 90);
            break;
        case IMAGETYPE_PNG:
             imagepng($image, $imagePath, 9);
           break;
        case IMAGETYPE_GIF:
             imagegif($image, $imagePath);
           break;
    }

     imagedestroy($image);

    return true;

}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать товар</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <header class="site-header">
       <div class="container">
         <a href="index.php" style="text-decoration: none; color:white;"><h1 class="site-title">CardCol - магазин коллекционных карточек</h1></a>
           <?php display_cart_link() ?>
       </div>
   </header>
  <div class="container">
      <h1>Редактировать товар</h1>
      <p><a href="admin.php">Назад</a></p>
     <?php display_error($error)?>
      <form method="post" enctype="multipart/form-data">
          <label for="name">Название:</label>
          <input type="text" id="name" name="name" value="<?=e($name)?>">
          <br><br>
           <label for="description">Описание:</label>
          <textarea id="description" name="description" style="min-height: 150px;"><?=e($description)?></textarea>
            <br><br>
            <label for="price">Цена:</label>
          <input type="text" id="price" name="price" value="<?=e($price)?>">
           <br><br>
           <label for="image">Основное изображение:</label>
          <input type="file" id="image" name="image">
           <br><br>
            <label for="image2">Дополнительное изображение 1:</label>
            <input type="file" id="image2" name="image2">
              <br><br>
           <label for="image3">Дополнительное изображение 2:</label>
            <input type="file" id="image3" name="image3">
            <br><br>
          <button type="submit">Сохранить изменения</button>
      </form>
  </div>
      <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
            <a href="login.php" class="admin-button">Администрирование</a>
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