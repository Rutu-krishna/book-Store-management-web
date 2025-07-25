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
      /*body{
         background-image:url('images/1.jpg');
      }
    body{background-color: #C8DCFF ; }  #B7D7FF*/
    body{
        background:linear-gradient(180deg,rgb(156,133,177), rgb(255,255,255), rgb(156,133,177));
      }
  .flex{background-color: ; }
  .navbar{
   margin: 5px; padding: 5px;
    
  }
.navbar a {
    padding: 5px;
    margin: 5px;
    text-decoration: none;
    color: #fff; 
    border-radius: 5px;
}
.logo{  background-color: ;font-style:bold;}
.navbar a:hover {margin: 5px; padding: 5px;
   
    background-color:#AAA1C8; /* Add your desired hover background color */
}
   </style>
<header class="header">

   <div class="flex">

      <a href="admin_page.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <a href="admin_page.php">HOME</a>
         
         
         <a href="admin_products.php">PRODUCTS</a>
         <a href="admin_orders.php">ORDER</a>
         <a href="admin_users.php">USERS</a>
         <a href="admin_contacts.php">MESSAGES</a>
         <a href="admin_reviews.php">REVIEWS</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="account-box">
      <p>Your details are <span> </span></p>
          <p>Username : <span><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?></span></p>

         <a href="logout.php" class="delete-btn">logout</a>
       
      </div>

   </div>

</header>