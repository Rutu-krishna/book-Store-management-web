<?php 

 include 'config.php';
 
 session_start();
 
 $admin_id = $_SESSION['admin_id'];
 
 if (!isset($admin_id)) {
     header('location:login.php');
     exit; // Add an exit statement after header redirect
 }
 
 if (isset($_POST['add_product'])) {
 
     $category = mysqli_real_escape_string($conn, $_POST['category']);
     $author = mysqli_real_escape_string($conn, $_POST['author']);
     $name = mysqli_real_escape_string($conn, $_POST['name']);
     $in_stock = $_POST['in_stock'];
     $in_stock = filter_var($in_stock, FILTER_SANITIZE_STRING);
     $price = $_POST['price'];
     $image = $_FILES['image']['name'];
     $image_size = $_FILES['image']['size'];
     $image_tmp_name = $_FILES['image']['tmp_name'];
     $image_folder = 'uploaded_img/' . $image;
     if (!preg_match('/^[A-Za-z ]{2,}$/', $name)) {
      $message[] = 'Product Name should contain only letters!';
      // return false;
   } 
   elseif(!preg_match('/^[A-Za-z ]{2,}$/', $author)) {
      $message[] = 'Author Name should contain only letters!';
      // return false;
   } 
   else{
 
     $add_product_query = mysqli_query($conn, "INSERT INTO `products`(category, author,name,in_stock, price, image) VALUES('$category', '$author','$name',$in_stock, '$price', '$image')") or die('query failed');
 
     if ($add_product_query) {
         if ($image_size > 2000000) {
             $message[] = 'image size is too large';
         } else {
             move_uploaded_file($image_tmp_name, $image_folder);
             $message[] = 'product added successfully!';
         }
     } else {
         $message[] = 'product could not be added!';
     }
 }
}
 
 if (isset($_GET['delete'])) {
     $delete_id = $_GET['delete'];
     $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
     $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
     unlink('uploaded_img/' . $fetch_delete_image['image']);
     mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
     header('location:admin_products.php');
     exit; // Add an exit statement after header redirect
 }
 
 if (isset($_POST['update_product'])) {
 
     $update_p_id = $_POST['update_p_id'];
     $update_name = $_POST['update_name'];
     $update_price = $_POST['update_price'];
     $update_qty = $_POST['update_quantity'];
 
     mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price', in_stock=' $update_qty' WHERE id = '$update_p_id'") or die('query failed');
 
     $update_image = $_FILES['update_image']['name'];
     $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
     $update_image_size = $_FILES['update_image']['size'];
     $update_folder = 'uploaded_img/' . $update_image;
     $update_old_image = $_POST['update_old_image'];
 
     if (!empty($update_image)) {
         if ($update_image_size > 2000000) {
             $message[] = 'image file size is too large';
         } else {
             mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
 
             move_uploaded_file($update_image_tmp_name, $update_folder);
             unlink('uploaded_img/' . $update_old_image);
             $message = 'Product updated successfully!';
         }
     } else {
         $message = 'Product updated successfully!';
     }
 
 
     header('location:admin_products.php');
     exit; // Add an exit statement after header redirect
 } 
 

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
   <style>
   .edit-product-form {
      margin:1px;
      padding:1px;
      height: 400px; /* Set your desired height in pixels */
      overflow-y: auto; /* Add scrollbar if content exceeds the height */
   }

   /* Add other necessary styles for the form elements */
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
.box img:hover {
   transform:scale(1.2);
}

.box-container{
  
}

.show-products .box-container .box .in_stock {
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
   
<?php include 'admin_header.php'; ?>

<!-- product CRUD section starts  -->

<section class="add-products">

   <h1 class="title">shop products</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Add product</h3>
      <select name="category" class="box" required>
      <option value="" disabled selected>Select Category</option>
      <option value="Psychology">Psychology</option>
      <option value="Science">Science</option>
      <option value="Fiction">Fiction</option>
      <option value="Novel">Novel</option>
      <!-- Add more categories as needed -->
   </select>
   <input type="text" name="author" class="box" placeholder="enter author name" required>
  
      <input type="text" name="name" class="box" placeholder="enter product name" required>
      <input type="number" min="0" name="in_stock" class="box" placeholder="Enter Quantity" required>
      <input type="number" min="0" name="price" class="box" placeholder="enter product price" required>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="submit" value="add product" name="add_product" class="btn">
   </form>

</section>

<!-- product CRUD section ends -->

<!-- show products  -->

<section class="show-products">

   <div class="box-container">

      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <div class="box">
         <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="category">Category: <?php echo $fetch_products['category']; ?></div>
         <div class="author">Author: <?php echo $fetch_products['author']; ?></div>
         
       
         
         <div class="name"><?php echo $fetch_products['name']; ?></div>

         <div class="in_stck"><span><?= $fetch_products['in_stock']; ?></span></div>
         
         <div class="price">Rs.<?php echo $fetch_products['price']; ?>/-</div>
         
         <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form">
<div class="box-container">
   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
      <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
      <select name="update_category" class="box" required>
         <option value="Psychology" <?php echo ($fetch_update['category'] == 'Psychology') ? 'selected' : ''; ?>>Psychology</option>
         <option value="Science" <?php echo ($fetch_update['category'] == 'Science') ? 'selected' : ''; ?>>Science</option>
         <option value="Fiction" <?php echo ($fetch_update['category'] == 'Fiction') ? 'selected' : ''; ?>>Fiction</option>
         <option value="Novel" <?php echo ($fetch_update['category'] == 'Novel') ? 'selected' : ''; ?>>Novel</option>
         
   </select>
   <input type="text" name="update_author" value="<?php echo $fetch_update['author']; ?>" class="box" placeholder="enter author name" required>
  
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="enter product name">
      <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="enter product price">
      <input type="number" name="update_quantity" value="<?php echo $fetch_update['in_stock']; ?>" min="0" class="box" required placeholder="enter product quantity">
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update_product" class="btn">
      <input type="reset" value="cancel" id="close-update" class="option-btn">
   </form>
   <!-- Add this code where you want to display the message -->
<?php if(!empty($message)): ?>
    <div class="message"><?php echo $message; ?></div>
<?php endif; ?>

   <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>
   </div>

</section>







<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>
<style>
   
   .in_stck {
      
  margin-left:85px;
  margin-right:85px;
  padding-top:8px;
  padding-bottom:8px;
   font-size: 2rem;
 border:1px solid black;
}
</style>
</body>
</html>