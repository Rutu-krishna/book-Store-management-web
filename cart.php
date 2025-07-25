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


if(isset($_POST['update_cart'])){
   $cart_id = $_POST['cart_id'];
   $cart_quantity = $_POST['cart_quantity'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
   $message[] = 'cart quantity updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
   $message[] = 'Product deleted!';
   
    header('location:cart.php');
}

if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   $message[] = 'Product deleted!';
    header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
   <!-- Slick Carousel CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>

<!-- Slick Carousel Theme CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>

<!-- jQuery (required by Slick Carousel) -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Slick Carousel JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<style>
    body{
        background:linear-gradient(  rgb(255,255,255) ,rgb(156,133,177), rgb(255,255,255));
      }
      .shopping-cart .box-container .box {
    border-radius: 1.5rem; 
    background-color: var(--white);
    box-shadow: 5px 5px 5px rgb(106 106 117);
    padding: 2rem;
    text-align: center;
   
    position: relative;
}

      .products .box-container .box {
    border-radius: 1.5rem; 
    background-color: var(--white);
    box-shadow: 5px 5px 5px rgb(106 106 117);
    padding: 2rem;
    text-align: center;
   
    position: relative;
}
.valuecart{
   border-radius: 1.5rem; 
}

.products .box-container .box .qty {
    width: 80px;
    padding: 1.2rem 1.4rem;
    border-radius: 1.5rem;
    border: var(--border);
    margin: 1rem 0;
    font-size: 2rem;
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
   <h3  style="color:black">shopping cart</h3>
   <p> <a  href="home.php">home</a> / cart </p>
</div>

<section class="shopping-cart" >

   <h1 class="title">products added</h1>

   <div class="box-container" id="productcontainer">
      <?php
      
$db_name = "mysql:host=localhost;dbname=book_shop";
$username = "root";
$password = "";

$conn = new PDO($db_name, $username, $password);

         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){ 
               $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? and in_stock>0");
               $select_product->execute([$fetch_cart['pid']]);
               $fetch_products = $select_product->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
         <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
         
          <!-- Displaying product name, category, author, and price -->
           <div class="name"><?php echo $fetch_cart['name']; ?></div>
         <div class="category">Category: <?php echo $fetch_cart['category_name']; ?></div>
      <div class="author">Author: <?php echo $fetch_cart['author_name']; ?></div>
    
      <div class="price">Rs.<?php echo $fetch_cart['price']; ?>/-</div>
             <!-- Updating cart quantity -->
             <form action="" method="post">
            <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
           
            <input type="number" class="valuecart" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
            <input type="submit" name="update_cart" value="update" class="option-btn">
         </form>
         <div class="sub-total"> Sub Total : <span>Rs.<?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?>/-</span> </div>
      </div>
      <?php
      $grand_total += $sub_total;
         }
      }else{
         echo '<p class="empty">your cart is empty</p>';
      }
      ?>
   </div>

   <div style="margin-top: 2rem; text-align:center;">
      <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');">delete all</a>
   </div>

   <div class="cart-total">
      <p>Grand Total : <span>Rs.<?php echo $grand_total; ?>/-</span></p>
      <div class="flex">
         <a href="shop.php" class="option-btn">continue shopping</a>
         <a href="checkout.php" class="btn <?php echo ($grand_total > 0)?'':'disabled'; ?>">proceed to checkout</a>
      </div>
   </div>

</section>



<section class="products" id="suggestion">    
      <!-- Product Suggestion Items -->
     
      <h1 class="title">You Might Also Like</h1>
      <?php  
    // Retrieve distinct categories of products already present in the cart
    $conn1 = mysqli_connect('localhost','root','','book_shop') or die('connection failed');

    $select_categories = mysqli_query($conn1, "SELECT DISTINCT category_name FROM `cart` WHERE user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($select_categories) > 0){
        while($fetch_category = mysqli_fetch_assoc($select_categories)){
            $category_name = $fetch_category['category_name'];
   
    ?>
   <div class="box-container"  id="productcontainer">
    
   <?php  
            // Fetch products of the current category
          //  $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE category = '$category_name' LIMIT 5") or die('query failed');
       

          
          // Fetch products of the current category
         $select_products = mysqli_query($conn1, "SELECT * FROM `products` WHERE category = '$category_name' AND name NOT IN (SELECT name FROM `cart` WHERE user_id = '$user_id') LIMIT 5") or die('query failed');
        
        if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
       
            ?>
      
      <form action="" method="post" class="box">
            <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
            <div class="name"><?php echo $fetch_products['name']; ?></div>
            <div class="category">Category: <?php echo $fetch_products['category']; ?></div>
            <div class="author">Author: <?php echo $fetch_products['author']; ?></div>
            <div class="price">Rs.<?php echo $fetch_products['price']; ?>/-</div>
            <div class="in_stock"><span><?= $fetch_products['in_stock']; ?></span></div>
            <input type="number" min="1" name="product_quantity" value="1" class="qty">
            <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
            <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
            <input type="hidden" name="author_name" value="<?php echo $fetch_products['author']; ?>"> <!-- Add hidden input for author name -->
            <input type="hidden" name="category_name" value="<?php echo $fetch_products['category']; ?>"> <!-- Add hidden input for category name -->
            <input type="submit" value="add to cart" name="add_to_cart" class="btn">
        </form>
        <?php
            }
        }else{
            echo '<p class="empty">No products found for this category!</p>';
        }
        ?>
    </div>
 
<?php
    }
}else{
    echo '<p class="empty">No categories found!</p>';
}
?><button class="prev-btn" onclick="scrollproducts(-1)"><i class="fas fa-chevron-left"></i></button>
<button class="next-btn" onclick="scrollproducts(1)"><i class="fas fa-chevron-right"></i></button>

</section>
   



 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

<script>
   $(document).ready(function(){
      $('.slick-slider').slick({
         slidesToShow: 3, // Set the number of slides to show at a time
         slidesToScroll: 1, // Set the number of slides to scroll at a time
         prevArrow: '<button class="prev-btn">Previous</button>', // Previous arrow icon
         nextArrow: '<button class="next-btn">Next</button>', // Next arrow icon
         infinite: true, // Enable infinite loop
         responsive: [
            {
               breakpoint: 768,
               settings: {
                  slidesToShow: 2,
                  slidesToScroll: 1
               }
            },
            {
               breakpoint: 480,
               settings: {
                  slidesToShow: 1,
                  slidesToScroll: 1
               }
            }
         ]
      });
   });
</script>

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