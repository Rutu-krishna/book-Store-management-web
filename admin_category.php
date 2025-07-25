<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

if (isset($_POST['add_info'])) {
    $categoryName = mysqli_real_escape_string($conn, $_POST['Category']);
    $categoryImage = $_FILES['Categoryimage']['name'];
    $categoryImageSize = $_FILES['Categoryimage']['size'];
    $categoryImageTmpName = $_FILES['Categoryimage']['tmp_name'];
    $categoryImageFolder = 'uploaded_img/' . $categoryImage;

    // Check if category name already exists
    $selectCategoryQuery = mysqli_query($conn, "SELECT category_name FROM `categories` WHERE category_name = '$categoryName'");

    if (mysqli_num_rows($selectCategoryQuery) > 0) {
        echo '<script>alert("Category name already added");</script>';
    } else {
        // Insert category information into the 'categories' table
        $insertCategoryQuery = mysqli_query($conn, "INSERT INTO `categories` (category_name, image) VALUES ('$categoryName', '$categoryImage')");

        if ($insertCategoryQuery) {
            // Move uploaded category image to the folder
            move_uploaded_file($categoryImageTmpName, $categoryImageFolder);
            echo '<script>alert("Category information added successfully!");</script>';
        } else {
            echo '<script>alert("Category information could not be added!");</script>';
        }
    }
}

if (isset($_POST['update_category'])) {
    $update_category_id = $_POST['update_category_id'];
    $update_category_name = mysqli_real_escape_string($conn, $_POST['update_category_name']);
    $update_category_image = $_FILES['update_category_image']['name'];
    $update_category_image_tmp_name = $_FILES['update_category_image']['tmp_name'];
    $update_category_image_size = $_FILES['update_category_image']['size'];
    $update_category_image_folder = 'uploaded_img/' . $update_category_image;
    $update_old_category_image = $_POST['update_old_image'];
     // Check if a new image is selected
     if (!empty($update_category_image)) {
        // Update with new image
        mysqli_query($conn, "UPDATE `categories` SET category_name = '$update_category_name', image = '$update_category_image' WHERE ID = '$update_category_id'") or die('query failed');

        // Move uploaded image to the folder
        if ($update_category_image_size <= 2000000) {
            move_uploaded_file($update_category_image_tmp_name, $update_category_image_folder);
            unlink('uploaded_img/' . $update_old_category_image); // Delete the old image
        } else {
            echo '<script>alert("Image file size is too large");</script>';
        }
    } else {
        // Update without changing the image
        mysqli_query($conn, "UPDATE `categories` SET category_name = '$update_category_name' WHERE ID = '$update_category_id'") or die('query failed');
    }

    header('location:admin_category.php');
}


if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_image_query = mysqli_query($conn, "SELECT image FROM `categories` WHERE ID = '$delete_id'") or die('query failed');
    $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
    unlink('uploaded_img/' . $fetch_delete_image['image']);
    mysqli_query($conn, "DELETE FROM `categories` WHERE ID = '$delete_id'") or die('query failed');
    header('location:admin_category.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom admin CSS file link -->
    <link rel="stylesheet" href="css/admin_style.css">

    <style>
        label {
            font-size: 20px;
        }

        body {
            background-color: #DBEDF9;
        } 
    .image-container {
        max-height: 300px; /* Adjust the maximum height as needed */
        overflow: hidden;
        margin-bottom: 10px; /* Add margin to separate the image from other inputs */
    }

    .category-image {
        max-width: 100%;
        height: auto;
    }
    /* Add these styles to your existing CSS file */
.box {
    /* Your existing styles for the box */
    text-align: center; /* Align the text in the center */
}

.image-container {
    max-width: 100%; /* Ensure the image does not exceed the container width */
    max-height: 200px; /* Set a maximum height for the image */
    overflow: hidden; /* Hide any overflow to maintain the aspect ratio */
    margin-bottom: 10px; /* Add some spacing between the image and the text */
}

.image-container img {
    width: 100%; /* Make the image fill the container */
    height: auto; /* Maintain the aspect ratio */
}

</style> 
</head>
<body>

<?php include 'admin_header.php'; ?>

<!-- Product CRUD section starts -->
<div class="one">
    <section class="add-products">

        <h1 class="title">Product Info</h1>

        <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="update_category_id" value="<?php echo $fetch_update['ID']; ?>">
                <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
                <div class="image-container">
                    <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="" class="category-image">
                </div>
                <label>Category Name</label>
                <input type="text" name="update_category_name" value="<?php echo $fetch_update['category_name']; ?>" class="box" required placeholder="Enter category name">
                <label>Category Image</label>
                <input type="file" class="box" name="update_category_image" accept="image/jpg, image/jpeg, image/png">
                <input type="submit" value="Update" name="update_category" class="btn">
                <input type="reset" value="Cancel" id="close-update" class="option-btn" onclick="window.location.href='admin_category.php';">
              </form>

    </section>

    <!-- Product CRUD section ends -->

    <!-- Show categories -->

    <section class="show-products">

        <div class="box-container">

            <?php
            $select_categories = mysqli_query($conn, "SELECT * FROM `categories`") or die('query failed');
            if (mysqli_num_rows($select_categories) > 0) {
                while ($fetch_category = mysqli_fetch_assoc($select_categories)) {
                    ?>
                    <div class="box">
                        <img src="uploaded_img/<?php echo $fetch_category['image']; ?>" alt="">
                        <div class="name"><?php echo $fetch_category['category_name']; ?></div>
                        <a href="<?php echo $_SERVER['PHP_SELF'] . '?update=' . $fetch_category['ID']; ?>" class="option-btn">Update</a>
                         <a href="<?php echo $_SERVER['PHP_SELF'] . '?delete=' . $fetch_category['ID']; ?>" class="delete-btn" onclick="return confirm('Delete this category?');">Delete</a>

                    </div>
                    <?php
                }
            } else {
                echo '<p class="empty">No category added yet!</p>';
            }
            ?>
        </div>

    </section>

    <section class="edit-product-form">
        <?php
        if (isset($_GET['update'])) {
            $update_id = $_GET['update'];
            $update_query = mysqli_query($conn, "SELECT * FROM `categories` WHERE ID = '$update_id'") or die('query failed');
            if (mysqli_num_rows($update_query) > 0) {
                while ($fetch_update = mysqli_fetch_assoc($update_query)) {
                    ?>
                  <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="update_category_id" value="<?php echo $fetch_update['ID']; ?>">
    <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
    <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
    <label>Category Name</label>
    <input type="text" name="update_category_name" value="<?php echo $fetch_update['category_name']; ?>" class="box" required placeholder="Enter category name">
    <input type="file" class="box" name="update_category_image" accept="image/jpg, image/jpeg, image/png">
    <input type="submit" value="Update" name="update_category" class="btn">
    <input type="reset" value="Cancel" id="close-update" class="option-btn">
</form>
                    <?php
                }
            }
        } else {
            echo '<script>
            document.querySelector(".edit-product-form").style.display = "none";
            </script>';
        }
        ?>

    </section>
</div>

<!-- Custom admin JS file link 
<script src="js/admin_script.js"></script> -->

</body>
</html>
