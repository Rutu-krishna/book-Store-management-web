<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}
$message = [];
if(isset($_POST['send'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $number = $_POST['number'];
   $msg = mysqli_real_escape_string($conn, $_POST['message']);

   //$select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');
   if (empty($name) || empty($email) || empty($number) || empty($msg)) {
      $message[] = 'All fields are required!';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $message[] = 'Invalid email format!';
  } elseif (!preg_match('/^[A-Za-z ]{2,}$/', $name)) {
   $message[] = 'Name should contain only letters!';
}   elseif (!preg_match('/^[7-9][0-9]{9}$/', $number)) {
      $message[] = 'Invalid phone number format!';
  } elseif (!preg_match('/^[A-Za-z ]{2,}$/', $msg)) {
   $message[] = 'Invalid Message!';
}
  
  else {
      $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

   if(mysqli_num_rows($select_message) > 0){
      $message[] = 'message sent already!';
   }else{
      mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
      //echo '<script>alert("Message sent successfully!");</script>';
      $message[] = 'message sent successfully!';
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
   <title>contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
  body{
        background:linear-gradient( rgb(156,133,177), rgb(255,255,255)   );
      }
     form{
         border-radius: 5.5rem;
      }
      </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading"style="background-image:url(images/heading.jpeg)  ">
   <h3 style="color:black">contact us</h3>
   <p > <a   href="home.php">home</a> / contact </p>
</div>

<section class="contact">

   <form action="" method="post"   >
      <h3>say something!</h3>
      <input type="text" name="name" required placeholder="enter your name" class="box">
      <input type="email" name="email" required placeholder="enter your email" class="box">
      <input type="number" name="number" required placeholder="enter your number" class="box">
      <textarea name="message" class="box" placeholder="enter your message" id="" cols="30" rows="10"></textarea>
      <input type="submit" value="send message" name="send" class="btn">
   </form>
   
    
</section>
  
<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>