<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

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
    // echo '<script>alert("product added to cart!!");</script>';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
       
       body{
        background:linear-gradient( rgb(156,133,177), rgb(255,255,255)  );
      }

   .products .box-container .box {
    border-radius: 1.5rem;
    box-shadow: 5px 5px 5px rgb(106 106 117);
    background-color: var(--white);
  
    padding: 2rem;
    text-align: center;
    border: var(--border);
    position: relative;

}
.products .box-container .box .qty {
    width: 80px;
    padding: 1.2rem 1.4rem;
    border-radius: 1.5rem;
    border: var(--border);
    margin: 1rem 0;
    font-size: 2rem;
}
   .box {
     height:auto;
    
    text-align: center;
    
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
    margin: 1rem;
}

.box img {
    max-width: 100%;
    max-height: 200px; /* Adjust the max height as needed */
    height: auto;
}

.prev-btn,
.next-btn {
    position: absolute;
    
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    padding: 10px;
    font-size: 24px;
    color: #555;
}

.prev-btn {
    left: 0;
}

.next-btn {
    right: 0;
}

.in_stock {
   position: absolute;
   top:1rem; right:1rem;
   border-radius: .5rem;
   height: 4.5rem;
   line-height: 4.3rem;
   width: 5rem;
   border:var(--border);
   color:var(--black);
   font-size: 2rem;
   background-color: var(--white);
}
</style>
</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading" style="background-image:url(images/heading.jpeg) ">
   <h3 style="color:black">our shop</h3>
   <p > <a   href="home.php">home</a> / shop </p>
</div>

<section class="products">

   <h1 class="title">latest products</h1>

   <div class="box-container" id="productcontainer">

      <?php  
           $select_products = mysqli_query($conn, "SELECT p.*, p.category AS category_name, p.author AS author_name FROM `products` p") or die('query failed');

         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
      <div class="name"><?php echo $fetch_products['name']; ?></div>
      <div class="category">Category: <?php echo $fetch_products['category_name']; ?></div>
      <div class="author">Author: <?php echo $fetch_products['author_name']; ?></div>
      <div class="price">Rs.<?php echo $fetch_products['price']; ?>/-</div>

      <div class="in_stock"><span><?= $fetch_products['in_stock']; ?></span></div>
      
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="number" min="0" max="<?= $fetch_products['in_stock']; ?>" value="1" name="product_quantity" class="qty">
      <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
      <input type="hidden" name="author_name" value="<?php echo $fetch_products['author_name']; ?>"> <!-- Add hidden input for author name -->
         <input type="hidden" name="category_name" value="<?php echo $fetch_products['category_name']; ?>"> <!-- Add hidden input for category name -->
         <input type="submit" value="add to cart" class="btn <?= ($fetch_products['in_stock'] > 1)?'':'disabled'; ?> " name="add_to_cart">
     </form>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?> <button class="prev-btn" onclick="scrollproducts(-1)"><i class="fas fa-chevron-left"></i></button>
      <button class="next-btn" onclick="scrollproducts(1)"><i class="fas fa-chevron-right"></i></button>
    
   </div>

</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
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
     
</body>
</html>