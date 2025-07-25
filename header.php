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
  <style>
    .flex {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
      text-decoration: none;
      color: #333; /* Change the color as needed */
      position: relative;
      transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
    }

    .logo:hover {
      color: #ff5733; /* Change the hover color as needed  */
      transform: scale(1.2);  
    }

     

    .navbar a {
      margin: 0 15px;
      text-decoration: none;
      color: #333; /* Change the color as needed */
      transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
    }

    .navbar a:hover {
      color: #ff5733; /* Change the hover color as needed */
      transform: scale(1.2);  
    }
    
  </style>
<header class="header"  >

   <div class="header-1" style="background-color:">
      <div class="flex">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <p> new <a href="login.php">login</a> | <a href="register.php">register</a> </p>
      </div>
   </div>
<!-- tyle="background-color:#efd0f2;"-->
   <div class="header-2" >
      <div class="flex">
         <a href="home.php" class="logo">Bookly</a>

         <nav class="navbar">
            <a href="home.php">HOME</a>
            <a href="about.php">ABOUT</a>
            <a href="shop.php">SHOP</a>
            <a href="contact.php">CONTACT</a>
            <a href="orders.php">ORDERS</a>
            <a href="review.php">REVIEW</a>
         </nav>

         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
            <?php
            $conn=mysqli_connect('localhost','root','','book_shop');
               $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
               $cart_rows_number = mysqli_num_rows($select_cart_number); 
            ?>
            <a href="cart.php"> <i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_rows_number; ?>)</span> </a>
         </div>

         <div class="user-box">
            <p>username : <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
            <a href="logout.php" class="delete-btn">logout</a>
         </div>
      </div>
   </div>

</header>