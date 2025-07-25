<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};
if(isset($_POST['add_info'])){
    $authorName = mysqli_real_escape_string($conn, $_POST['Author']);
    $categoryName = mysqli_real_escape_string($conn, $_POST['Category']);
    $categoryImage = $_FILES['Categoryimage']['name'];
    $categoryImageSize = $_FILES['Categoryimage']['size'];
    $categoryImageTmpName = $_FILES['Categoryimage']['tmp_name'];
    $categoryImageFolder = 'uploaded_img/'.$categoryImage;
 
    // Check if category name already exists
    $selectCategoryQuery = mysqli_query($conn, "SELECT name FROM `categories` WHERE category_name = '$categoryName'");
    
    if(mysqli_num_rows($selectCategoryQuery) > 0){
       $message[] = 'Category name already added';
    }else{
       // Insert category information into the 'categories' table
       $insertCategoryQuery = mysqli_query($conn, "INSERT INTO `categories` (category_name, image) VALUES ('$categoryName', '$categoryImage')");
 
       if($insertCategoryQuery){
          // Check if author name already exists
          $selectAuthorQuery = mysqli_query($conn, "SELECT name FROM `authors` WHERE authorname = '$authorName'");
          
          if(mysqli_num_rows($selectAuthorQuery) > 0){
             $message[] = 'Author name already added';
          }else{
             // Insert author information into the 'authors' table
             $insertAuthorQuery = mysqli_query($conn, "INSERT INTO `authors` (authorname) VALUES ('$authorName')");
 
             if($insertAuthorQuery){
                // Move uploaded category image to the folder
                move_uploaded_file($categoryImageTmpName, $categoryImageFolder);
                $message[] = 'Author and Category information added successfully!';
             }else{
                $message[] = 'Author information could not be added!';
             }
          }
       }else{
          $message[] = 'Category information could not be added!';
       }
    }
 }
 

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
}

if(isset($_POST['update_product'])){

   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];

   mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price' WHERE id = '$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'image file size is too large';
      }else{
         mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
      }
   }

   header('location:admin_products.php');

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
   
<style> label{
font-size:20px;
}</style>
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- product CRUD section starts  -->

<section class="add-products">

   <h1 class="title">Product Info</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Add Author & Category</h3>
      <label >Author Name</label> 
      <input type="text" name="Author" class="box" placeholder="enter Author name" required>
      <label> Category Name</label> 
       <input type="text" name="Category" class="box" placeholder="enter Category name" required>
       <label> Category Image</label> 
      <input type="file" name="Categoryimage" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="submit" value="add info" name="add_info" class="btn">
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
         <div class="name"><?php echo $fetch_products['name']; ?></div>
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
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="enter product name">
      <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="enter product price">
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update_product" class="btn">
      <input type="reset" value="cancel" id="close-update" class="option-btn">
   </form>
   <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>







<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>