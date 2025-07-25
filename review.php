<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit();
}

 

if(isset($_POST['send'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   
   $number = $_POST['number'];
   $review = mysqli_real_escape_string($conn, $_POST['review']);
   $starRating = isset($_POST['star_rating']) ? (int)$_POST['star_rating'] : 0;

   if (empty($name) || empty($number) || empty($review) || $starRating == 0) {
    $message[] = 'All fields are required!';
   }  
   elseif (!preg_match('/^[A-Za-z ]{2,}$/', $name)) {
      $message[] = 'Name should contain only letters!';
   }   elseif (!preg_match('/^[7-9][0-9]{9}$/', $number)) {
         $message[] = 'Invalid phone number format!';
     } elseif (!preg_match('/^[A-Za-z ]{2,}$/', $review)) {
      $message[] = 'Invalid Review!';
   } else {
    //  $selectReview = mysqli_query($conn, "SELECT * FROM `review` WHERE user_id = '$user_id'") or die('Query failed');
$selectReview = mysqli_query($conn, "SELECT * FROM `review` WHERE user_id = '$user_id' AND name = '$name' AND number = '$number'") or die('Query failed');
 
      if(mysqli_num_rows($selectReview) > 0){
        $message[] = 'You have already submitted a review!';
      } else {
         $insertReviewStmt = mysqli_prepare($conn, "INSERT INTO `review` (user_id, name, number, review, star_rating) VALUES (?,  ?, ?, ?, ?)") or die('Insert query preparation failed');
         mysqli_stmt_bind_param($insertReviewStmt, 'isiss', $user_id, $name, $number, $review, $starRating);
         mysqli_stmt_execute($insertReviewStmt);

         if (mysqli_stmt_affected_rows($insertReviewStmt) > 0) {
            $message[]='Review sent successfully!';
           // echo '<script>alert("Review sent successfully!");</script>';
            // $reviewMessage[] = 'Review sent successfully!';
         } else {
            $message[] = 'Error sending review. Please try again.';
         }

         mysqli_stmt_close($insertReviewStmt);
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
   <title>Review</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
  
  body{
        background:linear-gradient( rgb(156,133,177), rgb(255,255,255)   );
      }
      </style>
    
      </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading" style="background-image:url(images/heading.jpeg) ">
   <h3 style="color:black">Review</h3>
   <p ><a href="home.php">Home</a> / Review</p>
</div>

<section class="contact">

   <form action="" method="post">
      <h3>Tell us your review</h3>
      <h2>Your opinion matters</h2>
      <input type="text" name="name" required placeholder="Enter your name" class="box">
       
      <input type="number" name="number" required placeholder="Enter your number" class="box">
      <textarea name="review" class="box" placeholder="Enter your review" cols="30" rows="10"></textarea>
      <!-- Star Rating Input -->
      <label style="font-size:18px; text-align:left">Choose rating:</label>
      <div class="star_rating" onclick="setRating(event)">
      <i class="far fa-star" data-rating="1"></i>
   <i class="far fa-star" data-rating="2"></i>
   <i class="far fa-star" data-rating="3"></i>
   <i class="far fa-star" data-rating="4"></i>
   <i class="far fa-star" data-rating="5"></i>
   <input type="hidden" name="star_rating" id="star-rating" value="0">

   </div>
      <input type="submit" value="Send Review" name="send" class="btn">
   </form>

   <?php if (!empty($reviewMessage)) : ?>
      <p><?php echo implode("<br>", $reviewMessage); ?></p>
   <?php endif; ?>
    
</section>
<script>
function setRating(event) {
    if (event.target.matches('.far.fa-star')) {
        const selectedRating = parseInt(event.target.getAttribute('data-rating'));
        const stars = document.querySelectorAll('.far.fa-star');
        const hiddenInput = document.getElementById('star-rating');

        // Reset all stars to empty
        stars.forEach(star => star.classList.replace('fas', 'far'));

        // Fill stars up to the selected one
        for (let i = 0; i < selectedRating; i++) {
            stars[i].classList.replace('far', 'fas');
            stars[i].style.fontSize = '30px'; 
            stars[i].style.color = 'orange'; 
        }

        // Update hidden input value
        hiddenInput.value = selectedRating;
    }
}
</script>

<?php include 'footer.php'; ?>

 
<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>