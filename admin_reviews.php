<?php

include 'config.php';

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `review` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_reviews.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>reviews</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
   <style>
        /* Additional CSS for star ratings */
        .star-rating {
            display: flex;
            color: #f1c40f; /* Set the color of the filled star */
        }

        .star-rating i {
            font-size: 16px;
            margin-right: 5px;
        }
 
    </style>
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="messages">

   <h1 class="title"> reviews </h1>

   <div class="box-container">
   <?php
    $selectReview = mysqli_query($conn, "SELECT * FROM `review`") or die('query failed');
    $number_of_reviews = mysqli_num_rows($selectReview);
       if($number_of_reviews> 0){
         while($fetch_message = mysqli_fetch_assoc($selectReview)){
      
   ?>
   <div class="box"  >
   <p> User ID : <span><?php echo $fetch_message['user_id']; ?></span> </p>
    <p> Name : <span><?php echo $fetch_message['name']; ?></span> </p>
    <p> Number : <span><?php echo $fetch_message['number']; ?></span> </p>
     
    <p> Review : <span><?php echo $fetch_message['review']; ?></span> </p>

    <!-- Display star ratings -->
    <div class="star-rating">
        <?php
        $rating = $fetch_message['star_rating'];
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                echo '<i class="fas fa-star"></i>';
            } else {
                echo '<i class="far fa-star"></i>';
            }
        }
        ?>
           
   </div><br><button   onclick="confirmDelete(<?php echo $fetch_message['id']; ?>)" class="delete-btn">Delete Review</button> 
</div>
   <?php
                }
            } else {
                echo '<p class="empty">you have no reviews!</p>';
            }
            ?>
            <!-- You can use the ID to uniquely identify reviews when deleting -->
            
         

</section> 

<!-- Add this script to handle the confirmation -->
<script>
   function confirmDelete(reviewId) {
      if (confirm('Are you sure you want to delete this review?')) {
         window.location.href = 'admin_reviews.php?delete=' + reviewId;
      }
   }
</script>
<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>