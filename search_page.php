<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_cart'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
    $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];
   $author_name = $_POST['author_name']; // Add author name
   $category_name = $_POST['category_name']; // Add category name

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
    
      mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name,price, quantity, image, author_name, category_name) VALUES('$user_id', '$pid', '$product_name', '$product_price', '$product_quantity', '$product_image','$author_name', '$category_name')") or die('query failed');
      $message[] = 'product added to cart!';
   }

};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>search page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
    
  body{
        background:linear-gradient( rgb(156,133,177), rgb(255,255,255)   );
      }
        
.btn {
  
   color: white;
   padding: 2px 10px 2px 10px;
   
   border: none;
   cursor: pointer;
}
 
.products .box-container .box {
     height:auto;
 
    text-align: center;
    /* box-shadow: 10 14px 18px rgba(0, 0, 0, 0.1); */
    transition: transform 0.3s;
    margin: 1rem;
}

.products .box-container .box .qty {
    width: 80px;
    padding: 1.2rem 1.4rem;
    border-radius: 1.5rem;
    border: var(--border);
    margin: 1rem 0;
    font-size: 2rem;
}

.box img {
    max-width: 100%;
    max-height: 200px; /* Adjust the max height as needed */
    height: auto;
}
.search-form form .btn{
   margin-top: 15px;
   height:40px;

}
</style>
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading"  style="background-image:url('images/bg3 - Copy.jpg') ">
   <h3>search page</h3>
   <p> <a href="home.php">home</a> / search </p>
</div>

<section class="search-form">
   <form action="" method="post">
      <input type="text" name="search" placeholder="search products..." class="box">
      <input type="submit" name="submit" value="search" class="btn">
   </form>
</section>

<section class="products" style="padding-top: 0;">

   <div class="box-container" id="productcontainer">
   <?php
      if(isset($_POST['submit'])){
         $search_item = $_POST['search'];
          $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%$search_item%' OR category LIKE '%$search_item%' OR author LIKE '%$search_item%'") or die('query failed');

         //$select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_item}%'") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
         while($fetch_product = mysqli_fetch_assoc($select_products)){
   ?>  
   
   <form action="" method="post" class="box">
   <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="" class="image">
   <div class="name"><?php echo $fetch_product['name']; ?></div>
   <div class="category">Category: <?php echo $fetch_product['category']; ?></div>
   <div class="author">Author: <?php echo $fetch_product['author']; ?></div>
  
   <div class="price">Rs.<?php echo $fetch_product['price']; ?>/-</div>
   <input type="number" class="qty" name="product_quantity" min="1" value="1">
   <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
   <input type="hidden" name="author_name" value="<?php echo $fetch_product['author']; ?>"> <!-- Add hidden input for author name -->
         <input type="hidden" name="category_name" value="<?php echo $fetch_product['category']; ?>"> <!-- Add hidden input for category name -->
         <div class="in_stock"><span><?= $fetch_product['in_stock']; ?></span></div>
   <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
   <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
   <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
   <input type="submit" class="btn" value="add to cart" name="add_to_cart">
</form>


   <?php
            }
         }else{
            echo '<p class="empty">no result found!</p>';
         }
      }else{
         echo '<p class="empty">search something!</p>';
      }
   ?>
   </div>
  

</section>









<?php include 'footer.php'; ?>
<script>
   
   //For Reviews
     // Function to scroll reviews horizontally
    function scrollproducts() {
        const container = document.getElementById('productcontainer'); 
        const boxWidth = container.firstElementChild.offsetWidth; // Get width of each review card
        const numCards = Math.floor(container.offsetWidth / boxWidth); // Calculate number of cards visible at once
        const scrollAmount = numCards * boxWidth; // Calculate the scroll amount
  
        container.scrollBy({
           left: scrollAmount,
           behavior: 'smooth'
        });
  
       // container.scrollLeft += container.offsetWidth; // Scroll one container width at a time
        if (container.scrollLeft + container.offsetWidth >= container.scrollWidth) {
           container.scrollLeft = 0; // Reset to the beginning if reached the end
        }
     }
  
     // Set interval to scroll reviews periodically (every 5 seconds in this example)
     
     </script>
<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>