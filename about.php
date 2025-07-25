<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];
 
   // Fetch reviews from the database
$selectReview = mysqli_query($conn, "SELECT * FROM `review`") or die('query failed');
$reviews = mysqli_fetch_all($selectReview, MYSQLI_ASSOC);

if(!isset($user_id)){
   header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
  body{
        background:linear-gradient( rgb(156,133,177), rgb(255,255,255)   );
      }
      :root{
         --main-color:#443;
    --border-radius:95% 4% 97% 5%/4% 94% 3% 95%;
    --border-radius-hover:4% 95% 6% 95%/95% 4% 92% 5%;
    --border:.2rem solid var(--main-color);
    --border-hover:.2rem dashed var(--main-color);
      }
      .about .flex{
    display:flex;
    align-items: center;
    flex-wrap:wrap;
    gap:1.5rem;
}
      
.about .flex .image{
    flex:1 1 42rem;
}

.about .flex .image img{
    width: 100%;
    animation:aboutImage 4s linear infinite;

}

@keyframes aboutImage {
    0%, 100%{
        transform:scale(.9);
        border-radius: var(--border-radius-hover);

    }
    50%{
        transform:scale(.8);
        border-radius: var(--border-radius);
        
    }

}
      </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading" style="background-image:url('images/heading.jpeg') ">
   <h3   >about us</h3>
   <p   > <a    href="home.php">home</a> / about </p>
</div>

<section class="about">

   <div class="flex">

      <div class="image">
         <img src="images\book1.jpg" alt="">
      </div>

      <div class="content">
         <h3>Welcome to Bookly - Your Literary Haven!</h3>
         <p>At Bookly, we believe in the transformative power of literature. Our passion for books and the written word drives us to provide you with an exceptional online bookstore experience. Whether you're a devoted bibliophile, a casual reader, or someone exploring the world of books for the first time, we've curated a diverse collection that caters to every taste and preference.</p>
        <!-- <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Impedit quos enim minima ipsa dicta officia corporis ratione saepe sed adipisci?</p>-->
         <a href="contact.php" class="btn">contact us</a>
      </div>

   </div>

</section>
   <!--
<section class="reviews">
      <h1 class="title">client's reviews</h1>
      <div class="box-container">
         <?php
         $selectReview = mysqli_query($conn, "SELECT * FROM `review`") or die('query failed');
         $number_of_reviews = mysqli_num_rows($selectReview);
         if ($number_of_reviews > 0) {
            while ($fetch_message = mysqli_fetch_assoc($selectReview)) {
         ?>
               <div class="box" style="height: 320px; width:300px;">
                  <p> User ID : <span><?php echo $fetch_message['user_id']; ?></span> </p> 
                  <p> Name : <span><?php echo $fetch_message['name']; ?></span> </p>
                  <p> Number : <span><?php echo $fetch_message['number']; ?></span> </p>
                  <p> Email : <span><?php echo $fetch_message['email']; ?></span> </p>
                  <p> Review : <span><?php echo $fetch_message['review']; ?></span> </p>

                    
                  <div class="stars">
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
                  </div>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">No reviews available!</p>';
         }
         ?>
      </div>
   </section>-->


<!--<section class="authors">

   <h1 class="title">greate authors</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/author-1.jpg" alt="">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <h3>john deo</h3>
      </div>

      <div class="box">
         <img src="images/author-2.jpg" alt="">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <h3>john deo</h3>
      </div>

      <div class="box">
         <img src="images/author-3.jpg" alt="">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <h3>john deo</h3>
      </div>

      
      

      <div class="box">
         <img src="images/author-6.jpg" alt="">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <h3>john deo</h3>
      </div>

   </div>

</section>-->







<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>