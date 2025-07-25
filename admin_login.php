<?php

include 'config.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['login'])){
   $username = mysqli_real_escape_string($conn, $_POST['username']);
   $password = $_POST['password'];

   $result = mysqli_query($conn, "SELECT * FROM `admin` WHERE `username` = '$username' LIMIT 1");

   if ($result && mysqli_num_rows($result) > 0) {
      $admin = mysqli_fetch_assoc($result);

      // Verify password (plaintext comparison)
      if ($password == $admin['password']) {
         $_SESSION['admin_id'] = $admin['id'];
         $_SESSION['username'] = $admin['username']; 
         header('Location: admin_page.php');
         exit();
      } else {
         $message[]= "Invalid password";
      }
   } else {
      $message[]= "Invalid username or password";
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style> 
      label {
         font-size: 20px;
         text-align: left;
      }
   </style>
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
   
<div class="form-container">
   <form action="" method="post">
      <h3>Admin login </h3>
      <label for="username">Username:</label>
      <input type="username" name="username" placeholder="Enter your username" required class="box">
      <label for="password">Password:</label>
      <input type="password" name="password" placeholder="Enter your password" required class="box">
      <input type="submit" name="login" value="Login now" class="btn">
      <p>Are you a user <a href="login.php">Click here</a></p>
   </form>
</div>

</body>
</html>
