<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

// Set the session variable for username if it exists
if(isset($_SESSION['username'])) {
   $username = $_SESSION['username'];
} else {
   header('location:login.php');
   exit(); // Ensure script execution stops after redirecting
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin panel</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
<style>
    
    body{
        background:linear-gradient(180deg, rgb(255,255,255), rgb(156,133,177));
      }
   </style>
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- admin dashboard section starts  -->

<section class="dashboard">

   <h1 class="title">Dashboard</h1>

   <div class="box-container">
    
      <div class="box"   >
         
         <?php
            $total_pendings = 0;
            $select_pending = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
            if(mysqli_num_rows($select_pending) > 0){
               while($fetch_pendings = mysqli_fetch_assoc($select_pending)){
                  $total_price = $fetch_pendings['total_price'];
                  $total_pendings += $total_price;
               };
            };
         ?>
         <h3>Rs.<?php echo $total_pendings; ?>/-</h3>
         <p>Total Pendings</p>
      </div> 

      <div class="box">
         <?php
            $total_completed = 0;
            $select_completed = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'completed'") or die('query failed');
            if(mysqli_num_rows($select_completed) > 0){
               while($fetch_completed = mysqli_fetch_assoc($select_completed)){
                  $total_price = $fetch_completed['total_price'];
                  $total_completed += $total_price;
               };
            };
         ?>
         <h3>Rs.<?php echo $total_completed; ?>/-</h3>
         <p>Completed Payments</p>
      </div>

      <div class="box">
         <?php 
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
         ?>
         <h3><?php echo $number_of_orders; ?></h3>
         <p>Order Placed</p>
      </div>

      
 
      <div class="box">
         <?php 
            $select_account = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
            $number_of_account = mysqli_num_rows($select_account);
         ?>
         <h3><?php echo $number_of_account; ?></h3>
         <p>Total Users</p>
      </div>

      <div class="box">
         <?php 
           $select_unique_categories = mysqli_query($conn, "SELECT category, COUNT(*) AS count FROM products GROUP BY category") or die('query failed');
          $number_of_unique_categories = mysqli_num_rows($select_unique_categories);
        ?>
           <h3><?php echo $number_of_unique_categories;  ?></h3>
    <p>Book Categories</p>
      </div>


      <div class="box">
         <?php 
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            $number_of_products = mysqli_num_rows($select_products);
         ?>
         <h3><?php echo $number_of_products; ?></h3>
         <p>Products Added</p>
      </div>


      <div class="box">
         <?php 
            $select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
            $number_of_messages = mysqli_num_rows($select_messages);
         ?>
         <h3><?php echo $number_of_messages; ?></h3>
         <p>New Messages</p>
      </div>

      <div class="box">
         <?php 
            $selectReview = mysqli_query($conn, "SELECT * FROM `review`") or die('query failed');
            $number_of_messages = mysqli_num_rows($selectReview);
         ?>
         <h3><?php echo $number_of_messages; ?></h3>
         <p>New Review</p>
      </div>

   </div>

</section>

<!-- admin dashboard section ends -->









<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>