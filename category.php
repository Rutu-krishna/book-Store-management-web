<?php

$db_name = "mysql:host=localhost;dbname=book_shop";
$username = "root";
$password = "";

$conn = new PDO($db_name, $username, $password);
@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);


   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }

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
   <title>category</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
<style>
    body{
        background:linear-gradient(180deg, rgb(255,255,255), rgb(156,133,177));
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

.products .box-container .box {
         border-radius: 1.5rem;
         background-color: var(--white);
         box-shadow: 5px 5px 5px rgb(148, 148, 152);
        
         padding: 2rem;
         text-align: center;
         border: var(--border);
         position: relative;
      }
      
      .products .box-container .box .qty {
         width: 80px;
         padding: 1.2rem 1.4rem;
         border-radius: 1.5rem;
       
         margin: 1rem 0;
         font-size: 2rem;
      }

.box {
         height:auto;
         padding:10px;
         text-align: center;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         transition: transform 0.3s;
         margin: 1rem;
         border-radius: 15px;
      }

      .box img {
         max-width: 200px;
         max-height: 200px;
         height: auto;
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="products">

   <h1 class="title">products categories</h1>

   <div class="box-container">

   <?php
   
$db_name = "mysql:host=localhost;dbname=book_shop";
$username = "root";
$password = "";

$conn = new PDO($db_name, $username, $password);
      $category_name = $_GET['category'];
      // $select_products = $conn->prepare("SELECT * FROM `products` WHERE category = ?");
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE '%{$category_name}%' OR category LIKE '%{$category_name}%' ");
      $select_products->execute();
      // $select_products->execute([$category_name]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST">
      <div class="price">₹<span><?= $fetch_products['price']; ?></span>/-</div>
      <div class="in_stock"><span><?= $fetch_products['in_stock']; ?></span></div>
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="">
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="" width="306" height="255">
      <div class="name"><?= $fetch_products['name']; ?><?= ($fetch_products['in_stock'] > 1)?'':'(Out of Stock)'; ?></div>
      </a>
      
      <div class="category">Category: <?php echo $fetch_products['category']; ?></div>
      <div class="author">Author: <?php echo $fetch_products['author']; ?></div>
      <div class="in_stock"><span><?= $fetch_products['in_stock']; ?></span></div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="product_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="product_price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="product_image" value="<?= $fetch_products['image']; ?>">

      <input type="hidden" name="author_name" value="<?= $fetch_products['author']; ?>">
      <input type="hidden" name="category_name" value="<?= $fetch_products['category']; ?>">

      <input type="number" min="0" max="<?= $fetch_products['in_stock']; ?>" value="1" name="product_quantity" class="qty">
     
      <input type="submit" value="add to cart"  class="btn <?= ($fetch_products['in_stock'] > 1)?'':'disabled'; ?> " name="add_to_cart">
   </form>
   <?php
   
         }
      }else{
         echo '<p class="empty">No Products Available!</p>';
      }
   ?>

   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>