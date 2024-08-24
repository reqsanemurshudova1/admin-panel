<?php
//Bu funksiya, bir formdan POST metodu ilə göndərilən məlumatları almaq və həmin məlumatlar təyin edilməyibsə, null dəyəri qaytarmaq üçün istifadə olunur.
function post($key){
  return $_POST[$key]??null;
}

function validate($keys){
    $errors=[];
   foreach($keys as $key){
    if(!isset($_POST[$key])|| empty($_POST[$key]))// Əgər həmin sahə ya təyin edilməyibsə, ya da boşdursa, bu şərt doğru olur.
    {
        $errors[$key]="  Bu xana daxil edilməlidir";
    
    }
   }
    return $errors;//sehvleri qaytarır.
}
function view($view)
{
  header("Location: $view");
}
function route($path)
{
  return 'http://localhost/Backend/mohtesemTask2/' . $path;
}

function auth()
{

  if (isset($_SESSION['id'])) {
    return true;//login olub
  }
  return false;//login olmayib
}
//user details
function getUserDetails($connection)
{
  if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $userQuery = $connection->prepare($sql);
    $userQuery->execute([$id]);
    return $userQuery->fetch(PDO::FETCH_ASSOC);
  }
  return null;
}

//file upload
function file_upload($uploadDirectory, $file) {
  if (!file_exists($uploadDirectory)) {
      mkdir($uploadDirectory, 0777, true);
  }
  $name = $file['name'];
  $tmpname = $file['tmp_name'];
  $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];
  $fileExtension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

  if (in_array($fileExtension, $allowedExtensions)) {
      $newFileName = uniqid("", true) . "_" . time() . "." . $fileExtension;
      if (move_uploaded_file($tmpname, $uploadDirectory . '/' . $newFileName)) {
          return $newFileName;
      }
  }
  return false;
}
?>