<?php

include 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
   $cpass = password_hash($_POST['cpassword'], PASSWORD_DEFAULT);
   
  // $user_type = $_POST['user_type'];
  // Validate Name
  if (empty($name)) {
   $message[] = 'Name is required!';
} elseif (!preg_match('/^[A-Za-z ]+$/', $name)) {
   $message[] = 'Invalid name format! Only letters are allowed.';
}

// Validate Email
if (empty($email)) {
   $message[] = 'Email is required!';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
   $message[] = 'Invalid email format!';
}

   // $allowedDomains=array("gmail.com","co.in");
   // $emailParts=explode("@",$email);
   // $domail=end($emailParts);
   // if(in_array($domain,$allowedDomains)){
   //    $message[] = 'Invalid  domain';
   // }


// Validate Password
if (empty($_POST['password'])) {
   $message[] = 'Password is required!';
}else{function isStrongPassword($password) {
    // Minimum length of 8 characters
    if (strlen($password) < 8) {
        return false;
    }
    
    // Should contain at least one uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }
    
    // Should contain at least one lowercase letter
    if (!preg_match('/[a-z]/', $password)) {
        return false;
    }
    
    // Should contain at least one digit
    if (!preg_match('/[0-9]/', $password)) {
        return false;
    }
    
    // Should contain at least one special character
    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        return false;
    }
    
    return true;
}

$password =$_POST['password'] ;
if (!isStrongPassword($password)) {
   $message[] = "Password is weak. Please choose a stronger password.";
}
}

// Confirm Password
if ($_POST['password'] != $_POST['cpassword']) {
   $message[] = 'Confirm password not matched!';
}
if (empty($message)) {
   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');


  // $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){
      $message[] = 'user already exist!';
   }
    else{
         mysqli_query($conn, "INSERT INTO `users`(name, email, password) VALUES('$name', '$email', '$cpass')") or die('query failed');
       
      //echo "<script>alert('Registered successfully!');</script>"; 
      header('location:login.php'); 
      $message[] = 'registered successfully!';
         exit(); 
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

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
      <h3>register now</h3>
      <input type="text" name="name" placeholder="enter your name" required class="box">
      <input type="email" name="email" placeholder="enter your email" required class="box">
      <input type="password" name="password" placeholder="enter your password" required class="box">
      <input type="password" name="cpassword" placeholder="confirm your password" required class="box">
       
      <input type="submit" name="submit" value="REGISTER" class="btn">
      <p>already have an account? <a href="login.php">login now</a></p>
      <p>To login as a admin <a href="admin_login.php">Click Here</a></p>
   </form>

</div>

</body>
</html>